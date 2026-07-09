-- 0035-documentacion-workflows.sql
-- Stage 9.24: Enrich sample documentos with workflow data for demo.

UPDATE documentos SET revisado_por = 1, fecha_revision = '2025-01-10' WHERE id = 1;
UPDATE documentos SET revisado_por = 1, aprobado_por = 1, fecha_revision = '2025-01-15', fecha_aprobacion = '2025-01-20' WHERE id = 2;

INSERT INTO data_patches (filename, applied_at)
VALUES ('0035-documentacion-workflows.sql', NOW())
ON CONFLICT (filename) DO NOTHING;
