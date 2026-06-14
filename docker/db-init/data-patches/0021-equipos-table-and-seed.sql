-- 0021-equipos-table-and-seed.sql
-- Stage 9.3: Equipos main table + seed (first slice of the module under Aplicacion).
-- Follows exact pattern from 0020-proveedores and 0013-clientes.
-- All columns from legacy schema included for completeness (some maintenance fields
-- will be exercised in later legs for revisiones/calendario/planes).

CREATE TABLE IF NOT EXISTS equipos (
    id SERIAL PRIMARY KEY,
    numero VARCHAR(10) NOT NULL,
    descripcion VARCHAR(255) NOT NULL,
    numero_serie VARCHAR(20) NOT NULL,
    modelo VARCHAR(255) NOT NULL,
    fabricante VARCHAR(255) NOT NULL,
    ubicacion VARCHAR(255) NOT NULL,
    fuera_uso BOOLEAN,
    causa TEXT,
    fecha_fuera DATE,
    ver_interna BOOLEAN NOT NULL,
    mantenimiento_cada SMALLINT NOT NULL,
    dias BOOLEAN NOT NULL,
    activo BOOLEAN
);

INSERT INTO equipos (
    numero, descripcion, numero_serie, modelo, fabricante, ubicacion,
    fuera_uso, causa, fecha_fuera, ver_interna, mantenimiento_cada, dias, activo
)
VALUES 
    ('EQ-001', 'Compresor de aire principal', 'SN-1001A', 'CA-500', 'Atlas Copco', 'Nave A - Zona 1', false, NULL, NULL, false, 90, true, true),
    ('EQ-002', 'Prensa hidráulica', 'SN-2044B', 'PH-250', 'Bosch Rexroth', 'Nave B - Línea 3', false, NULL, NULL, true, 180, false, true),
    ('EQ-003', 'Banda transportadora', 'SN-3199C', 'BT-1200', 'Interroll', 'Almacén Central', true, 'Fin de vida útil programado', '2025-12-31', false, 30, true, false)
ON CONFLICT DO NOTHING;

INSERT INTO data_patches (filename, applied_at)
VALUES ('0021-equipos-table-and-seed.sql', NOW())
ON CONFLICT (filename) DO NOTHING;