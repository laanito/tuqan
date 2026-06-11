-- 0020-proveedores-table-and-seed.sql
-- Stage 9.2: Proveedores main table (first slice of the module under Aplicacion).
-- Follows exact pattern from 0013-clientes and similar catalog patches.

CREATE TABLE IF NOT EXISTS proveedores (
    id SERIAL PRIMARY KEY,
    nombre TEXT NOT NULL,
    telefono VARCHAR(64),
    activo BOOLEAN NOT NULL DEFAULT true
);

INSERT INTO proveedores (nombre, telefono, activo)
VALUES 
    ('Proveedor Demo Principal', '555-1234', true),
    ('Proveedor Secundario S.A.', '555-5678', true),
    ('Suministros Ambientales', '555-9012', false)
ON CONFLICT DO NOTHING;

INSERT INTO data_patches (filename, applied_at)
VALUES ('0020-proveedores-table-and-seed.sql', NOW())
ON CONFLICT (filename) DO NOTHING;