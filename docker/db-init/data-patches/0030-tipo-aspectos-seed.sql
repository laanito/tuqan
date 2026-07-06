-- 0030-tipo-aspectos-seed.sql
-- Supporting data for Aspectos tipo_aspecto FK (used in list/form labels and selects).
-- Minimal seed to support 9.18 polish without breaking clean-room runs.
-- Values chosen to match existing aspectos seed (tipo_aspecto 1 and 2).

CREATE TABLE IF NOT EXISTS tipo_aspectos (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(64),
    activo BOOLEAN DEFAULT true
);

INSERT INTO tipo_aspectos (id, nombre, activo) VALUES
    (1, 'Aspecto normal', true),
    (2, 'Aspecto significativo', true)
ON CONFLICT (id) DO NOTHING;

INSERT INTO data_patches (filename, applied_at)
VALUES ('0030-tipo-aspectos-seed.sql', NOW())
ON CONFLICT (filename) DO NOTHING;
