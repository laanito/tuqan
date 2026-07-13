-- 0037-mejora-states.sql
-- Stage 9.27: Sample data for basic state machine demo (Pendiente, Verificada, Cerrada)

UPDATE acciones_mejora SET usuario_verifica = 1, fecha_verifica = '2025-01-15' WHERE id = 1;  -- Verificada
UPDATE acciones_mejora SET usuario_verifica = 1, fecha_verifica = '2025-02-01', usuario_cerrado = 1, fecha_cierre = '2025-02-10', cerrada = true WHERE id = 3;  -- Cerrada

INSERT INTO data_patches (filename, applied_at)
VALUES ('0037-mejora-states.sql', NOW())
ON CONFLICT (filename) DO NOTHING;
