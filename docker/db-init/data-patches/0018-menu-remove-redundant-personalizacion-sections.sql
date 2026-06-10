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

-- NOTE (closed in Stage 9.1):
-- The original row 84 "Criterios" (no accion) section header was deleted here.
-- 0017 had already reparented the actionable Criterios item (with proper accion)
-- directly under Personalizacion (1400). 0019 (Stage 9.1) updated the label on that
-- actionable row to the desired "Criterios Ambientales" (with English) and ensured
-- a non-empty accion. This closes the open thread from the 8.9 menu work and retrospective.
-- See 0019-criterios-ambientales.sql and the 9.1 playbook in STAGE-CHECKLISTS.md.
-- The modern page (/admin/criterios + legacy route) already existed since 8.6.

-- Record
INSERT INTO data_patches (filename, applied_at)
VALUES ('0018-menu-remove-redundant-personalizacion-sections.sql', NOW())
ON CONFLICT (filename) DO NOTHING;