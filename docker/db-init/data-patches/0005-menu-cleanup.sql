-- ============================================================================
-- 0005-menu-cleanup.sql
-- Aesthetic and ordering fixes for the full legacy menu now that we have
-- the real data volume loaded.
--
-- Problems addressed:
--   - Inconsistent upper/lower case in labels (especially top level)
--   - Duplicate "Inicio" and "Administracion" top-level entries
--   - Wrong ordering (Inicio should be first, Administracion + Cerrar Sesion last)
-- ============================================================================

-- 1. Standardize casing for the main top-level labels (Spanish)
--    We target the most important ones by their known legacy ids.

UPDATE menu_idiomas_nuevo SET valor = 'Inicio'           WHERE menu = 65  AND idioma_id = 1;
UPDATE menu_idiomas_nuevo SET valor = 'Administración'   WHERE menu = 74  AND idioma_id = 1;
UPDATE menu_idiomas_nuevo SET valor = 'Cerrar Sesión'    WHERE menu = 75  AND idioma_id = 1;

-- Also fix some other common top-level ones that were shouting
UPDATE menu_idiomas_nuevo SET valor = 'Documentación'    WHERE menu = 66  AND idioma_id = 1;
UPDATE menu_idiomas_nuevo SET valor = 'Procesos'         WHERE menu = 76  AND idioma_id = 1;
UPDATE menu_idiomas_nuevo SET valor = 'Proveedores'      WHERE menu = 67  AND idioma_id = 1;
UPDATE menu_idiomas_nuevo SET valor = 'Equipos'          WHERE menu = 70  AND idioma_id = 1;
UPDATE menu_idiomas_nuevo SET valor = 'Acciones de Mejora' WHERE menu = 68 AND idioma_id = 1;
UPDATE menu_idiomas_nuevo SET valor = 'Formación'        WHERE menu = 69  AND idioma_id = 1;
UPDATE menu_idiomas_nuevo SET valor = 'Auditorías'       WHERE menu = 71  AND idioma_id = 1;
UPDATE menu_idiomas_nuevo SET valor = 'Indicadores'      WHERE menu = 72  AND idioma_id = 1;
UPDATE menu_idiomas_nuevo SET valor = 'Aspectos Ambientales' WHERE menu = 73 AND idioma_id = 1;

-- 2. Fix ordering via the orden column on menu_nuevo (top level items)
--    Lower number = appears first in the navbar.

-- Reinforce the keepers (in case of partial previous application)
UPDATE menu_nuevo SET orden = 5, activo = true WHERE id = 65;   -- real Inicio
UPDATE menu_nuevo SET orden = 90, activo = true WHERE id = 74;  -- real Administración
UPDATE menu_nuevo SET orden = 100, activo = true WHERE id = 75; -- Cerrar Sesión

-- Inicio first
UPDATE menu_nuevo SET orden = 5   WHERE id = 65;

-- Normal business areas in the middle (spread them reasonably)
UPDATE menu_nuevo SET orden = 10  WHERE id = 66;   -- Documentación
UPDATE menu_nuevo SET orden = 15  WHERE id = 76;   -- Procesos
UPDATE menu_nuevo SET orden = 20  WHERE id = 67;   -- Proveedores
UPDATE menu_nuevo SET orden = 25  WHERE id = 70;   -- Equipos
UPDATE menu_nuevo SET orden = 30  WHERE id = 68;   -- Mejora
UPDATE menu_nuevo SET orden = 35  WHERE id = 69;   -- Formación
UPDATE menu_nuevo SET orden = 40  WHERE id = 71;   -- Auditorías
UPDATE menu_nuevo SET orden = 45  WHERE id = 72;   -- Indicadores
UPDATE menu_nuevo SET orden = 50  WHERE id = 73;   -- Aspectos Ambientales

-- Administración near the end (but before logout)
UPDATE menu_nuevo SET orden = 90  WHERE id = 74;

-- Cerrar Sesión last
UPDATE menu_nuevo SET orden = 100 WHERE id = 75;

-- 3. Deactivate obvious duplicate top-level "Inicio" / "Administracion" entries
--    (if any other rows besides 65/74 are surfacing as duplicate top-level labels)

-- Example: if there is another row with accion = 'inicio' or similar that is top-level
-- and not id 65, we can deactivate it. Adjust the ids below after running the diagnostic query.
-- UPDATE menu_nuevo SET activo = false WHERE id IN (XXX, YYY) AND (padre = 0 OR padre IS NULL);

-- 3. Deactivate duplicate top-level entries from the old curated patch
--    These conflict with the real legacy ones now that the full menu is loaded.

-- Extra "Inicio" (the old curated one with accion='/main/')
UPDATE menu_nuevo SET activo = false WHERE id = 1;

-- Extra "Administración" (the old curated one with accion='administracion:menu')
UPDATE menu_nuevo SET activo = false WHERE id = 30;

-- Note: We keep the real legacy versions:
--   id 65 = Inicio (real, now first)
--   id 74 = Administración (real, now near the end)
--   id 75 = Cerrar Sesión (real, last)

-- If you still see other unwanted duplicates after this, run the diagnostic below
-- and share the output so we can target more ids.

-- Diagnostic query:
-- SELECT m.id, m.orden, mi.valor as label_es, m.accion, m.activo
-- FROM menu_nuevo m
-- JOIN menu_idiomas_nuevo mi ON mi.menu = m.id AND mi.idioma_id=1
-- WHERE (m.padre = 0 OR m.padre IS NULL) AND m.activo = true
-- ORDER BY m.orden;
