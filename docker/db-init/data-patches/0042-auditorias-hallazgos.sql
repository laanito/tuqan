-- 0042-auditorias-hallazgos.sql
-- Stage 9.32: Hallazgos de auditoría (modern first slice; no legacy table in clean schema)
-- Linked to auditorias ejecución; optional accion_mejora.

CREATE TABLE IF NOT EXISTS hallazgos_auditoria (
    id SERIAL PRIMARY KEY,
    auditoria INTEGER,
    fecha DATE,
    descripcion TEXT NOT NULL,
    tipo VARCHAR(32) DEFAULT 'observacion',
    gravedad VARCHAR(32) DEFAULT 'menor',
    cerrado BOOLEAN DEFAULT false,
    accion_mejora INTEGER,
    activo BOOLEAN DEFAULT true,
    observaciones TEXT
);

INSERT INTO hallazgos_auditoria (auditoria, fecha, descripcion, tipo, gravedad, cerrado, accion_mejora, activo)
SELECT 1, '2025-01-20', 'Falta evidencia de formación en el área de producción', 'no_conformidad', 'mayor', false, 1, true
WHERE NOT EXISTS (SELECT 1 FROM hallazgos_auditoria WHERE descripcion LIKE 'Falta evidencia de formación%');

INSERT INTO hallazgos_auditoria (auditoria, fecha, descripcion, tipo, gravedad, cerrado, activo)
SELECT 1, '2025-01-20', 'Mejora recomendada en el control de registros', 'oportunidad', 'menor', false, true
WHERE NOT EXISTS (SELECT 1 FROM hallazgos_auditoria WHERE descripcion LIKE 'Mejora recomendada en el control%');

INSERT INTO hallazgos_auditoria (auditoria, fecha, descripcion, tipo, gravedad, cerrado, accion_mejora, activo)
SELECT 2, '2025-02-12', 'Proveedor sin evaluación actualizada en expediente', 'no_conformidad', 'menor', false, 2, true
WHERE NOT EXISTS (SELECT 1 FROM hallazgos_auditoria WHERE descripcion LIKE 'Proveedor sin evaluación%');

INSERT INTO data_patches (filename, applied_at)
VALUES ('0042-auditorias-hallazgos.sql', NOW())
ON CONFLICT (filename) DO NOTHING;
