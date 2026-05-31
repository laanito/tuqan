-- ============================================================================
-- 0006-synthetic-user-admin-cleanup.sql
-- Now that the full real legacy menu is loaded, remove the old synthetic
-- "Administración / Usuarios / Perfiles / Mensajes" placeholder branch
-- that was created in 0001 + 0002.
--
-- These items were causing:
--   - Duplicate top-level "Administración"
--   - User creation ("Nuevo Usuario" etc.) appearing under the wrong
--     "Inicio" / broken hierarchy
--   - Mixed casing and duplicated functionality
--
-- We keep the real legacy items under the real "Administración" (id 74 and descendants).
-- ============================================================================

-- Deactivate the entire old curated admin subtree
UPDATE menu_nuevo SET activo = false 
WHERE id IN (
    30,   -- old top-level Administración
    300,  -- Usuarios (synthetic)
    301,  -- Perfiles (synthetic)
    302,  -- Mensajes (synthetic)
    310,311,312,313,314,  -- user children
    320,321,322,          -- perfiles children
    330,331               -- mensajes children
);

-- Also deactivate any other children that might have been added later under these
UPDATE menu_nuevo SET activo = false 
WHERE padre IN (300, 301, 302);

-- Note: The real equivalents live under the legacy "administracion" (id 74)
-- and its children (e.g. id 82 "Aplicacion" → id 32 "Usuarios", etc.).
-- Those remain active and are the ones we will use going forward for the
-- user management module.

-- If after this you want to completely remove the old synthetic items later,
-- we can DELETE them (instead of just deactivating), but deactivating is safer
-- for now during verification.
