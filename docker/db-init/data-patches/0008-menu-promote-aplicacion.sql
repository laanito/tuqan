-- ============================================================================
-- 0008-menu-promote-aplicacion.sql
-- Make "Aplicacion" (id 82) the first item directly under the real
-- "Administración" (id 74) in the modern sidebar.
--
-- Context:
-- - The legacy structure has "Usuarios" correctly nested under "Aplicacion".
-- - We want "Aplicacion" to appear as the very first submenu under Administración
--   for better visibility in the current UI.
-- ============================================================================

-- Promote Aplicacion to be the first child of Administración
UPDATE menu_nuevo 
SET orden = 1
WHERE id = 82;

-- Note: All other children of 74 keep their original relative ordering.
-- If you want even stricter control (e.g. force other items to start at orden 10+),
-- we can add more statements later.
