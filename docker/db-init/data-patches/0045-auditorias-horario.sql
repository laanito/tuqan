-- 0045-auditorias-horario.sql
-- Stage 9.35: Horario de auditoría (legacy columns + modern auditoria FK)

CREATE TABLE IF NOT EXISTS horario_auditoria (
    id SERIAL PRIMARY KEY,
    auditoria INTEGER,
    horainicio TIMESTAMP WITHOUT TIME ZONE,
    horafin TIMESTAMP WITHOUT TIME ZONE,
    requisito VARCHAR(32),
    auditor VARCHAR(32),
    area INTEGER
);

-- If table already existed without auditoria (full schema), add column
ALTER TABLE horario_auditoria ADD COLUMN IF NOT EXISTS auditoria INTEGER;

INSERT INTO horario_auditoria (auditoria, horainicio, horafin, requisito, auditor, area)
SELECT 1, '2025-01-15 09:00:00', '2025-01-15 11:00:00', '4.1 Contexto', 'Auditor A', 1
WHERE NOT EXISTS (
    SELECT 1 FROM horario_auditoria WHERE requisito = '4.1 Contexto' AND auditoria = 1
);

INSERT INTO horario_auditoria (auditoria, horainicio, horafin, requisito, auditor, area)
SELECT 1, '2025-01-15 11:30:00', '2025-01-15 13:00:00', '7.5 Documentación', 'Auditor A', 1
WHERE NOT EXISTS (
    SELECT 1 FROM horario_auditoria WHERE requisito = '7.5 Documentación' AND auditoria = 1
);

INSERT INTO horario_auditoria (auditoria, horainicio, horafin, requisito, auditor, area)
SELECT 2, '2025-02-10 10:00:00', '2025-02-10 12:00:00', '8.4 Proveedores', 'Auditor B', NULL
WHERE NOT EXISTS (
    SELECT 1 FROM horario_auditoria WHERE requisito = '8.4 Proveedores' AND auditoria = 2
);

INSERT INTO data_patches (filename, applied_at)
VALUES ('0045-auditorias-horario.sql', NOW())
ON CONFLICT (filename) DO NOTHING;
