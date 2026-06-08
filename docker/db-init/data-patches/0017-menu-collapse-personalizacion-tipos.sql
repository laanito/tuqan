-- 0017-menu-collapse-personalizacion-tipos.sql
-- Stage 8.9 nitpick fixes for menu hierarchy under Personalizacion (id 1400):
-- * Reparent the actionable "ver"/"listado" items for Tipos* / Clientes / Criterios directly to 1400
--   instead of the intermediate section headers (85=Tipos Acc, 86=Clientes, 87=Tipos area, etc.).
--   This collapses the unwanted 4th level nesting (Administracion > Personalizacion > [section] > item)
--   so the items appear as direct children of Personalizacion (3rd level) and "should be collapsed".
-- * Specifically addresses "all tipos are in a 4th level" and "Clientes -> Clientes".
-- * Also guards against Criterios ver row having its padre set to Clientes section (86) by data error ("criterios points to clientes").
-- Idempotent; only affects the known personalizacion catalog items.

-- Reparent the known actionable ver items (from data: 41 tipomejora, 42 clientes, 43 tiposareas, 44 tiposamb, 58 tiposimp, 64 tipodocumento)
UPDATE menu_nuevo SET padre = 1400
WHERE id IN (41,42,43,44,58,64);

-- Catch Criterios ver (and any other) that might be wrongly under old sections (e.g. 86 Clientes)
UPDATE menu_nuevo SET padre = 1400
WHERE (accion LIKE '%criterios%listado%' OR accion LIKE '%criterios%ver%')
   AND padre != 1400;

-- Also any other personalizacion catalog ver that still points to the old section ids (84-92 range for safety)
UPDATE menu_nuevo SET padre = 1400
WHERE padre IN (84,85,86,87,88,90,92)
  AND (accion LIKE '%listado%' OR accion LIKE '%ver%')
  AND id NOT IN (84,85,86,87,88,90,92);  -- don't move the section headers themselves

-- Record the patch (guard)
INSERT INTO data_patches (filename, applied_at)
VALUES ('0017-menu-collapse-personalizacion-tipos.sql', NOW())
ON CONFLICT (filename) DO NOTHING;

COMMENT ON TABLE menu_nuevo IS 'Menu hierarchy; 0017 collapsed personalizacion catalog items to direct under 1400.';