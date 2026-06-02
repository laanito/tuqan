-- 0012-rename-empresas-to-sedes.sql
-- Stage 8.6 nitpick correction + prep for POST work.
--
-- Background: In Stage 8.5 the "Hospitales" menu entry was turned into "Empresas"
-- (per user request at the time) and a minimal `empresas` table + demo data was added (0011).
-- User later clarified: the correct business term is "Sedes" (branches / sites / locations).
--
-- This patch:
--   * Renames the table `empresas` → `sedes` (idempotent).
--   * Updates all menu_nuevo.accion values containing "empresas" → "sedes" (modern + legacy paths).
--   * Updates Spanish labels in menu_idiomas_nuevo for the affected menu rows to "Sedes".
--   * Records itself in data_patches.
--
-- Safe to re-apply. Historical 0011 remains for audit trail (it created the original `empresas` table).
-- Legacy `hospitales` table (if ever present in a full restore) is left untouched.
--
-- After this patch the modern Sedes module (renamed from Empresas) + any future POST work
-- will target the `sedes` table.

DO $$
BEGIN
    -- 1. Rename table if the old name still exists (from 0011). Safe IF.
    IF EXISTS (
        SELECT 1 FROM information_schema.tables
        WHERE table_schema = 'public' AND table_name = 'empresas'
    ) THEN
        ALTER TABLE empresas RENAME TO sedes;
        RAISE NOTICE 'Renamed table empresas -> sedes';
    ELSE
        RAISE NOTICE 'Table sedes already exists or empresas was not present; skipping rename.';
    END IF;

    -- 2. Update menu actions (idempotent replace). Covers parent + children (nuevo, editar, ver, etc.)
    UPDATE menu_nuevo
    SET accion = replace(accion, 'empresas', 'sedes')
    WHERE accion LIKE '%empresas%';

    RAISE NOTICE 'Updated menu_nuevo.accion for sedes (was empresas)';

    -- 3. Update labels in menu_idiomas_nuevo (columns are: menu, idioma_id, valor)
    --    Main entry (id 108 from 0010/0012 context) and its children (1401/1402 added for actions)
    UPDATE menu_idiomas_nuevo SET valor = 'Sedes'     WHERE menu = 108 AND idioma_id = 1;
    UPDATE menu_idiomas_nuevo SET valor = 'Sedes'     WHERE menu = 1401 AND idioma_id = 1;
    UPDATE menu_idiomas_nuevo SET valor = 'Sedes'     WHERE menu = 1402 AND idioma_id = 1;

    -- Fix any "Empresa/Empresas" remnants in labels under these menus (for all idiomas)
    UPDATE menu_idiomas_nuevo
    SET valor = replace(replace(valor, 'Empresas', 'Sedes'), 'Empresa', 'Sede')
    WHERE menu IN (108, 1401, 1402);

    RAISE NOTICE 'Updated menu labels to Sedes where appropriate';

    -- 4. Record patch (idempotent) -- column is "filename"
    INSERT INTO data_patches (filename, applied_at)
    VALUES ('0012-rename-empresas-to-sedes.sql', NOW())
    ON CONFLICT (filename) DO NOTHING;

    RAISE NOTICE '0012-rename-empresas-to-sedes.sql applied (or was already recorded).';
END $$;

-- Optional: add a comment on the table for future humans
COMMENT ON TABLE sedes IS 'Sedes (branches/locations). Renamed from empresas (ex-hospitales) in 0012. Minimal columns for now: id, nombre, activo. Extended in later stages as needed for the business domain.';