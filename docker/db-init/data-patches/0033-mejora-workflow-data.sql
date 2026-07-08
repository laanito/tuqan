-- 0033-mejora-workflow-data.sql
-- Stage 9.21: Enrich existing acciones_mejora seeds with workflow fields (users, auditoria links)
-- for demo of deeper Mejora workflow. Idempotent updates.

UPDATE acciones_mejora SET 
    usuario_detectado = 1,
    usuario_cerrado = 1,
    auditoria = 1
WHERE id = 1;

UPDATE acciones_mejora SET 
    usuario_detectado = 1,
    usuario_implantacion = 1,
    auditoria = 2
WHERE id = 2;

UPDATE acciones_mejora SET 
    usuario_detectado = 1,
    usuario_verifica = 1,
    usuario_cerrado = 1,
    auditoria = 3
WHERE id = 3;

INSERT INTO data_patches (filename, applied_at)
VALUES ('0033-mejora-workflow-data.sql', NOW())
ON CONFLICT (filename) DO NOTHING;
