-- ============================================================================
-- Stage 8.5 Menu Restructure (Aplicacion + Personalizacion) — CORRECTED
--
-- Purpose:
--   - Rename Hospitales → Empresas under Aplicacion (and update accion)
--   - Move "Permisos" (id 107) into Aplicacion
--   - Remove Mensajes + Tareas from inside Aplicacion (reparent to Administracion)
--   - Create new "Personalizacion" submenu under Administracion (74)
--   - Move the 7 specified items under the new Personalizacion parent
--   - Ensure all affected entries have proper labels in menu_idiomas_nuevo
--
-- Idempotent: safe to re-run on any state. Uses existence checks.
--
-- IMPORTANT (PostgreSQL SERIAL gotcha):
--   The 0004-full-legacy-menu.sql patch inserts ~100 rows using explicit IDs.
--   This leaves the menu_nuevo_id_seq sequence pointing at a low number.
--   Any later INSERT that relies on the default SERIAL value will collide.
--   Solution used here: always compute a safe ID via MAX(id) + gap for new rows
--   in this patch (and any future menu-altering patches).
-- ============================================================================

DO $$
DECLARE
    v_personalizacion_id INTEGER;
    v_aplicacion_id      INTEGER := 82;
    v_admin_id           INTEGER := 74;
    v_hospitales_id      INTEGER := 108;
    v_permisos_id        INTEGER := 107;
    v_mensajes_id        INTEGER := 35;
    v_tareas_id          INTEGER := 36;
    v_clientes_id        INTEGER := 86;
    v_criterios_id       INTEGER := 84;
    v_tipos_mejora_id    INTEGER := 85;
    v_tipos_area_id      INTEGER := 87;
    v_t_amb_id           INTEGER := 88;
    v_tipos_imp_id       INTEGER := 90;
    v_tipo_doc_id        INTEGER := 92;
BEGIN
    -- Strong guard: if a row with label 'Personalizacion' already exists as direct child of Administracion, skip.
    SELECT m.id INTO v_personalizacion_id
    FROM menu_nuevo m
    JOIN menu_idiomas_nuevo mi ON mi.menu = m.id AND mi.idioma_id = 1
    WHERE m.padre = v_admin_id
      AND mi.valor = 'Personalizacion'
    LIMIT 1;

    IF v_personalizacion_id IS NOT NULL THEN
        RAISE NOTICE 'Patch 0010 already applied (Personalizacion % exists under Administracion). Skipping.', v_personalizacion_id;
        RETURN;
    END IF;

    -- 1. Rename Hospitales → Empresas + modern accion (idempotent)
    UPDATE menu_nuevo SET accion = 'administracion:empresas:listado:ver' WHERE id = v_hospitales_id;
    UPDATE menu_idiomas_nuevo SET valor = 'Empresas' WHERE menu = v_hospitales_id AND idioma_id = 1;

    INSERT INTO menu_idiomas_nuevo (menu, idioma_id, valor)
    SELECT v_hospitales_id, i.id, 'Companies'
    FROM idiomas i
    WHERE i.id <> 1
      AND NOT EXISTS (SELECT 1 FROM menu_idiomas_nuevo WHERE menu = v_hospitales_id AND idioma_id = i.id)
    ON CONFLICT DO NOTHING;

    -- 2. Move Permisos into Aplicacion (safe even if already there)
    UPDATE menu_nuevo SET padre = v_aplicacion_id, orden = 47 WHERE id = v_permisos_id;

    -- 3. Reparent Mensajes and Tareas out of Aplicacion to direct under Administracion
    UPDATE menu_nuevo SET padre = v_admin_id, orden = 80 WHERE id = v_mensajes_id;
    UPDATE menu_nuevo SET padre = v_admin_id, orden = 81 WHERE id = v_tareas_id;

    -- 4. Create the new Personalizacion parent.
    -- We MUST allocate the ID explicitly (using MAX+gap) because the 0004 full-legacy-menu
    -- patch inserted hundreds of rows with explicit IDs. This leaves the menu_nuevo_id_seq
    -- sequence pointing at a low value, so a plain SERIAL INSERT would collide (e.g. id=1).
    SELECT COALESCE(MAX(id), 0) + 1000 INTO v_personalizacion_id FROM menu_nuevo;

    INSERT INTO menu_nuevo (id, padre, orden, accion, permisos, activo)
    VALUES (v_personalizacion_id, v_admin_id, 70, '', '{t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t}', true);

    -- Labels (castellano primary + English best-effort)
    INSERT INTO menu_idiomas_nuevo (menu, idioma_id, valor)
    VALUES (v_personalizacion_id, 1, 'Personalizacion')
    ON CONFLICT (menu, idioma_id) DO UPDATE SET valor = EXCLUDED.valor;

    INSERT INTO menu_idiomas_nuevo (menu, idioma_id, valor)
    SELECT v_personalizacion_id, i.id, 'Customization'
    FROM idiomas i
    WHERE i.id <> 1
      AND NOT EXISTS (SELECT 1 FROM menu_idiomas_nuevo WHERE menu = v_personalizacion_id AND idioma_id = i.id)
    ON CONFLICT DO NOTHING;

    -- 5. Reparent the exact 7 requested items under the freshly allocated Personalizacion
    UPDATE menu_nuevo SET padre = v_personalizacion_id, orden = 10 WHERE id = v_clientes_id;
    UPDATE menu_nuevo SET padre = v_personalizacion_id, orden = 20 WHERE id = v_criterios_id;
    UPDATE menu_nuevo SET padre = v_personalizacion_id, orden = 30 WHERE id = v_tipos_mejora_id;
    UPDATE menu_nuevo SET padre = v_personalizacion_id, orden = 40 WHERE id = v_tipos_area_id;
    UPDATE menu_nuevo SET padre = v_personalizacion_id, orden = 50 WHERE id = v_t_amb_id;
    UPDATE menu_nuevo SET padre = v_personalizacion_id, orden = 60 WHERE id = v_tipos_imp_id;
    UPDATE menu_nuevo SET padre = v_personalizacion_id, orden = 70 WHERE id = v_tipo_doc_id;

    -- 6. Add representative child actions under Empresas so the menu is ready for forms.
    -- Use the same safe high-ID technique (the sequence is not reliable after 0004's explicit IDs).
    INSERT INTO menu_nuevo (id, padre, orden, accion, permisos, activo) VALUES
      (v_personalizacion_id + 1, v_hospitales_id, 10, 'administracion:empresas:nuevo',  '{t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t}', true),
      (v_personalizacion_id + 2, v_hospitales_id, 20, 'administracion:empresas:editar', '{t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t}', true)
    ON CONFLICT DO NOTHING;

    RAISE NOTICE 'Patch 0010 completed successfully. New Personalizacion id=% under Administracion. Empresas, Permisos, Personalizacion items all in final positions.', v_personalizacion_id;
END $$;

-- data_patches row is inserted automatically by scripts/init-db.sh after successful apply.