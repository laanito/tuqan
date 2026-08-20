-- 0051-areas-table-and-seed.sql
-- Documentación (and Horario optional FK) expect legacy table `areas(id, nombre, activo)`.
-- Full schema dump has it; the modern data-patches / minimal path never created it,
-- so /admin/documentacion/editar/* crashed with relation "areas" does not exist.
-- Seed ids 1..3 match documentos.area demo values (1, 2) from patch 0024.

CREATE TABLE IF NOT EXISTS areas (
    id SERIAL PRIMARY KEY,
    nombre CHARACTER VARYING(64),
    activo BOOLEAN DEFAULT true
);

INSERT INTO areas (id, nombre, activo)
VALUES
    (1, 'Calidad', true),
    (2, 'Producción', true),
    (3, 'Medio Ambiente', true)
ON CONFLICT (id) DO NOTHING;

SELECT setval(
    pg_get_serial_sequence('areas', 'id'),
    GREATEST((SELECT COALESCE(MAX(id), 1) FROM areas), 1),
    true
);

INSERT INTO data_patches (filename, applied_at)
VALUES ('0051-areas-table-and-seed.sql', NOW())
ON CONFLICT (filename) DO NOTHING;
