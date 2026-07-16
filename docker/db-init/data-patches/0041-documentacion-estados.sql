-- 0041-documentacion-estados.sql
-- Stage 9.31: Mixed demo estados for filter/badges (legacy codes)
-- 1=En vigor, 2=Borrador, 3=Pend. revisión, 4=Revisado, 5=Pend. aprobación, 6=Histórico

UPDATE documentos SET estado = 2 WHERE id = 1;  -- Borrador
UPDATE documentos SET estado = 3 WHERE id = 2;  -- Pend. revisión
UPDATE documentos SET estado = 1 WHERE id = 3;  -- En vigor

INSERT INTO data_patches (filename, applied_at)
VALUES ('0041-documentacion-estados.sql', NOW())
ON CONFLICT (filename) DO NOTHING;
