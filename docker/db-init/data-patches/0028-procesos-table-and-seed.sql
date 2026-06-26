-- 0028-procesos-table-and-seed.sql
-- Stage 9.10: Procesos basic slice (core `procesos` catalog for legacy 76).
-- Basic list + form over nombre, codigo, revision, padre, activo.
-- Árbol de Procesos (tree rendering + padre hierarchy UI), contenido_procesos (entradas/salidas/flujograma/indicadores[] etc.), flujogramas, per-process indicators, Ficha, Matriz, workflows (revisar/aprobar/baja) all deferred.

CREATE TABLE IF NOT EXISTS procesos (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(64),
    revision VARCHAR(16),
    padre INTEGER,
    codigo VARCHAR(32),
    activo BOOLEAN DEFAULT TRUE
);

INSERT INTO procesos (nombre, revision, padre, codigo, activo)
VALUES
    ('Proceso de Diseño y Desarrollo', '01', 0, 'PROC-DES', true),
    ('Revisión y Verificación', '01', 1, 'PROC-REV', true),
    ('Proceso de Gestión de Calidad', '02', 0, 'PROC-CAL', true),
    ('Entrega y Soporte al Cliente', '03', 0, 'PROC-ENT', false)
ON CONFLICT DO NOTHING;

INSERT INTO data_patches (filename, applied_at)
VALUES ('0028-procesos-table-and-seed.sql', NOW())
ON CONFLICT (filename) DO NOTHING;
