-- 0013-clientes-table-and-seed.sql
-- Stage 8.6: First fully implemented module under Personalizacion (Clientes).
-- Adds a minimal clientes table (modeled after perfiles/sedes) + demo rows.
-- Menu entry already exists under Personalizacion (from 0010 patch) and currently
-- pointed to Placeholder. This + the Pages/Clientes + routes makes it real.

CREATE TABLE IF NOT EXISTS clientes (
    id SERIAL PRIMARY KEY,
    nombre TEXT NOT NULL,
    activo BOOLEAN NOT NULL DEFAULT true
);

-- Demo data (idempotent)
INSERT INTO clientes (nombre, activo)
VALUES ('Cliente Corporativo Demo', true),
       ('Cliente PYME Demo', true)
ON CONFLICT DO NOTHING;

-- Record (the init script also inserts, but we do it here too for direct runs)
INSERT INTO data_patches (filename, applied_at)
VALUES ('0013-clientes-table-and-seed.sql', NOW())
ON CONFLICT (filename) DO NOTHING;