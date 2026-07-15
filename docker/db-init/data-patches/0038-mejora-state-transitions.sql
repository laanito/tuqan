-- 0038-mejora-state-transitions.sql
-- Stage 9.28: Ensure rich demo states for full state machine (auto transitions, quick actions)
-- Idempotent: safe to re-apply.

-- Ensure variety after clean init (Pendiente remains, Verificada, Cerrada)
UPDATE acciones_mejora SET 
    usuario_verifica = 1, 
    fecha_verifica = '2025-01-15'
WHERE id = 1 AND (usuario_verifica IS NULL OR cerrada IS NOT true);

UPDATE acciones_mejora SET 
    usuario_verifica = 1, 
    fecha_verifica = '2025-02-01', 
    usuario_cerrado = 1, 
    fecha_cierre = '2025-02-10', 
    cerrada = true 
WHERE id = 3;

-- Make sure at least one clear Pendiente exists (id=2 or create if needed)
UPDATE acciones_mejora SET 
    usuario_verifica = NULL, 
    fecha_verifica = NULL,
    usuario_cerrado = NULL,
    fecha_cierre = NULL,
    cerrada = false
WHERE id = 2;

-- Optional: seed an extra row for testing quick actions if table small (idempotent via description unique-ish)
INSERT INTO acciones_mejora (tipo, cliente, fecha, descripcion, area, cerrada, usuario_detectado)
SELECT 1, NULL, '2025-03-01', 'Acción de prueba para transiciones rápidas (Pendiente)', 'Calidad', false, 1
WHERE NOT EXISTS (
    SELECT 1 FROM acciones_mejora WHERE descripcion LIKE '%transiciones rápidas%'
);

-- If the insert happened, we can leave it Pendiente for demo of Verificar button.

INSERT INTO data_patches (filename, applied_at)
VALUES ('0038-mejora-state-transitions.sql', NOW())
ON CONFLICT (filename) DO NOTHING;