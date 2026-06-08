-- 0018-menu-remove-redundant-personalizacion-sections.sql
-- Follow-up to 0017: now that the actionable items (with real 'accion') have been
-- reparented directly under Personalizacion (1400), the old intermediate section
-- headers (empty 'accion', just labels like "Clientes", "Tipos Acc. Mejora") are
-- now redundant 3rd-level duplicates.
--
-- They cause:
--   - duplicated keys/names in the sidebar (two "Clientes", two "Tipos Acc. Mejora", etc.)
--   - showing the old parent sections instead of (or alongside) the actual action items
--   - unwanted nesting/4th level appearance for the tipos group
--
-- Solution: delete these now-childless redundant sections.
-- This leaves only the real action items (the ones with full 'accion' like
-- administracion:xxx:listado:ver) directly under Personalizacion.
--
-- Idempotent.

-- First clean up any labels for these sections
DELETE FROM menu_idiomas_nuevo WHERE menu IN (84,85,86,87,88,90,92);

-- Then remove the redundant section rows themselves
-- (these were the old group parents: Clientes(86), Criterios(84), Tipos Acc(85),
--  Tipos area(87), T.Amb(88), Tipos Imp(90), Tipo doc(92))
DELETE FROM menu_nuevo WHERE id IN (84,85,86,87,88,90,92);

-- NOTE FOR LATER (recorded during 8.9 menu cleanup):
-- Row 84 was the "Criterios" container/section under Personalizacion (1400) with
-- no 'accion'. According to user recall, the real module should be
-- "Criterios Ambientales" and it should have had a proper action (likely
-- something like administracion:criterios:listado:ver or similar).
-- If it was not present in the legacy data we imported, it will surface when
-- the full dump is processed. When restoring, it should be placed as a direct
-- actionable child of Personalizacion (padre=1400), not as another nested section.
-- Current state after this patch: no Criterios entry remains under Personalizacion.
-- See also the modern Criterios page at /admin/criterios and the legacy route
-- /administracion/criterios/listado/ver that was added in earlier stages.

-- Record
INSERT INTO data_patches (filename, applied_at)
VALUES ('0018-menu-remove-redundant-personalizacion-sections.sql', NOW())
ON CONFLICT (filename) DO NOTHING;