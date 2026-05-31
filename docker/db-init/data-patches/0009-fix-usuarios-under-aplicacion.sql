-- ============================================================================
-- 0009-fix-usuarios-under-aplicacion.sql
-- Revert the incorrect re-parenting of Usuarios (id 32) so it sits again
-- under "Aplicacion" (id 82), which itself is under the real "Administración" (74).
--
-- This restores the original legacy hierarchy that the user confirmed is correct.
-- ============================================================================

-- Put Usuarios back under Aplicacion
UPDATE menu_nuevo 
SET padre = 82,
    orden = 10          -- reasonable position inside Aplicacion
WHERE id = 32;

-- Ensure Aplicacion remains the first child under Administración
-- (already set to orden 1 by 0008, but we reinforce it here)
UPDATE menu_nuevo 
SET orden = 1 
WHERE id = 82;
