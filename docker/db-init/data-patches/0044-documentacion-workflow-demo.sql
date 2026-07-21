-- 0044-documentacion-workflow-demo.sql
-- Stage 9.34: Demo estados so list shows Enviar/Revisar/Aprobar buttons
-- 1=En vigor, 2=Borrador, 3=Pend. revisión, 4=Revisado, 5=Pend. aprobación

UPDATE documentos SET estado = 2, revisado_por = NULL, fecha_revision = NULL,
    aprobado_por = NULL, fecha_aprobacion = NULL WHERE id = 1;

UPDATE documentos SET estado = 3, revisado_por = NULL, fecha_revision = NULL,
    aprobado_por = NULL, fecha_aprobacion = NULL WHERE id = 2;

UPDATE documentos SET estado = 4, revisado_por = 1, fecha_revision = '2025-06-01',
    aprobado_por = NULL, fecha_aprobacion = NULL WHERE id = 3;

INSERT INTO data_patches (filename, applied_at)
VALUES ('0044-documentacion-workflow-demo.sql', NOW())
ON CONFLICT (filename) DO NOTHING;
