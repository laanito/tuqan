-- ============================================================================
-- 0007-menu-reparent-usuarios.sql
-- During full menu consolidation, move the real "Usuarios" item
-- (id 32) to be a direct child of the real "Administración" (id 74).
--
-- Previously it was under "Aplicacion" (82), which made the user management
-- submenu less visible/directly associated with Administración in the sidebar.
--
-- This is a display/UX adjustment for the modern menu while we modernize
-- the user management module.
-- ============================================================================

-- Make Usuarios a direct child of Administración (74)
UPDATE menu_nuevo 
SET padre = 74,
    orden = 40   -- Reasonable position among other admin functions
WHERE id = 32;

-- Note: If in the future we restore a deeper hierarchy or the real "Aplicacion"
-- container becomes useful again, this can be reverted or adjusted.
