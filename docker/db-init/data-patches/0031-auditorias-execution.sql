-- 0031-auditorias-execution.sql
-- Stage 9.19: Auditorías execution first slice (the actual audits, linked to programa_auditoria).
-- Basic fields for list + form. Full execution (plan, horario, findings, informes, Mejora links) deferred.
-- Follows pattern from other 9.x verticals.

CREATE TABLE IF NOT EXISTS auditorias (
    id SERIAL PRIMARY KEY,
    programa INTEGER,
    nombre VARCHAR(255),
    fecha DATE,
    estado INTEGER DEFAULT 0,
    descripcion TEXT,
    observaciones TEXT,
    activo BOOLEAN DEFAULT true,
    requisitos TEXT,
    alcance TEXT,
    interna BOOLEAN,
    fecha_realiza DATE,
    lugar_informe VARCHAR(255),
    fecha_informe DATE
);

INSERT INTO auditorias (programa, nombre, fecha, estado, descripcion, activo, interna)
VALUES
    (1, 'Auditoría Interna 2025-01', '2025-01-15', 1, 'Auditoría de procesos de calidad', true, true),
    (2, 'Auditoría Proveedores Q1', '2025-02-10', 0, 'Revisión de homologación de proveedores', true, false),
    (1, 'Auditoría Seguimiento', '2025-03-05', 2, 'Seguimiento a acciones correctivas', false, true)
ON CONFLICT DO NOTHING;

INSERT INTO data_patches (filename, applied_at)
VALUES ('0031-auditorias-execution.sql', NOW())
ON CONFLICT (filename) DO NOTHING;
