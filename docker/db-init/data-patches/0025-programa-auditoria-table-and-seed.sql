-- 0025-programa-auditoria-table-and-seed.sql
-- Stage 9.6: Auditorías basic slice (first vertical for Auditorías legacy 71).
-- programa_auditoria is the core for "Programa" / "Auditoria anual" list (nombre + activo + vigente + revision).
-- Follows exact pattern from prior vertical patches (0020-0024).
-- Full auditorias execution, plan, horario, findings, informes and cross-links deferred.

CREATE TABLE IF NOT EXISTS programa_auditoria (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    activo BOOLEAN,
    vigente BOOLEAN,
    revision VARCHAR(16)
);

INSERT INTO programa_auditoria (nombre, activo, vigente, revision)
VALUES
    ('Programa de Auditoría 2025', true, true, 'Rev 01'),
    ('Programa de Auditoría 2024', true, false, 'Rev 00'),
    ('Programa Auditoría Interna Calidad', true, true, 'R2')
ON CONFLICT DO NOTHING;

INSERT INTO data_patches (filename, applied_at)
VALUES ('0025-programa-auditoria-table-and-seed.sql', NOW())
ON CONFLICT (filename) DO NOTHING;
