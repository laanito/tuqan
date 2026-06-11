-- 0019-criterios-ambientales.sql
-- Stage 9.1 hygiene: close the open "NOTE FOR LATER" from 0018 + 8.9.
-- The actionable Criterios row *should* have been reparented to direct under
-- Personalizacion (1400) by 0017. The old no-accion section header (row 84)
-- was deleted by 0018. In the current minimal data + patch sequence the row
-- may be missing after the deletes, so this patch both fixes an existing one
-- (UPDATE labels) *and* inserts a fresh direct actionable child if none exists.
--
-- Idempotent. Records itself in data_patches.

DO $$
DECLARE
    v_pers_id INTEGER := 1400;
    v_crit_id INTEGER;
    v_new_id  INTEGER;
BEGIN
    -- 1. Try to locate an existing actionable Criterios entry under 1400
    SELECT m.id INTO v_crit_id
    FROM menu_nuevo m
    LEFT JOIN menu_idiomas_nuevo mi ON mi.menu = m.id AND mi.idioma_id = 1
    WHERE m.padre = v_pers_id
      AND (
           m.accion LIKE '%criterios%listado%'
        OR m.accion LIKE '%criterios%ver%'
        OR COALESCE(mi.valor, '') ILIKE '%criterio%'
      )
    ORDER BY m.id
    LIMIT 1;

    IF v_crit_id IS NOT NULL THEN
        -- Update labels on the existing row
        UPDATE menu_idiomas_nuevo
        SET valor = 'Criterios Ambientales'
        WHERE menu = v_crit_id AND idioma_id = 1;

        INSERT INTO menu_idiomas_nuevo (menu, idioma_id, valor)
        SELECT v_crit_id, i.id, 'Environmental Criteria'
        FROM idiomas i
        WHERE i.id <> 1
          AND NOT EXISTS (SELECT 1 FROM menu_idiomas_nuevo WHERE menu = v_crit_id AND idioma_id = i.id)
        ON CONFLICT (menu, idioma_id) DO UPDATE SET valor = EXCLUDED.valor;

        UPDATE menu_nuevo
        SET accion = 'administracion:criterios:listado:ver'
        WHERE id = v_crit_id AND (accion IS NULL OR accion = '');

        RAISE NOTICE '0019: Updated existing Criterios row % under 1400 to label "Criterios Ambientales".', v_crit_id;
    ELSE
        -- 2. No matching row — restore/insert as a direct actionable child (as the NOTE intended)
        SELECT COALESCE(MAX(id), 0) + 50 INTO v_new_id FROM menu_nuevo;  -- safe gap after explicit legacy ids

        INSERT INTO menu_nuevo (id, padre, orden, accion, permisos, activo)
        VALUES (v_new_id, v_pers_id, 45, 'administracion:criterios:listado:ver',
                '{t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t}', true)
        ON CONFLICT (id) DO NOTHING;

        -- Spanish label
        INSERT INTO menu_idiomas_nuevo (menu, idioma_id, valor)
        VALUES (v_new_id, 1, 'Criterios Ambientales')
        ON CONFLICT (menu, idioma_id) DO UPDATE SET valor = EXCLUDED.valor;

        -- English
        INSERT INTO menu_idiomas_nuevo (menu, idioma_id, valor)
        SELECT v_new_id, i.id, 'Environmental Criteria'
        FROM idiomas i
        WHERE i.id <> 1
          AND NOT EXISTS (SELECT 1 FROM menu_idiomas_nuevo WHERE menu = v_new_id AND idioma_id = i.id)
        ON CONFLICT (menu, idioma_id) DO UPDATE SET valor = EXCLUDED.valor;

        RAISE NOTICE '0019: Inserted missing Criterios Ambientales row % as direct child of Personalizacion (1400).', v_new_id;
    END IF;

    -- Always record (guard in init-db.sh)
    INSERT INTO data_patches (filename, applied_at)
    VALUES ('0019-criterios-ambientales.sql', NOW())
    ON CONFLICT (filename) DO NOTHING;
END $$;

COMMENT ON TABLE menu_nuevo IS 'Menu hierarchy; 0019 finalized Criterios Ambientales (label + action) under Personalizacion.';