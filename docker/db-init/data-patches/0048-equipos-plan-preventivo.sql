-- 0048-equipos-plan-preventivo.sql
-- Stage 9.38: Ensure demo equipos have clear intervals + a past preventivo
-- so Plan "next due" calculation is visible after init.

-- EQ-001: every 90 days; seed a preventivo ~90 days before "today" if missing
UPDATE equipos
SET mantenimiento_cada = 90, dias = true
WHERE id = 1 AND EXISTS (SELECT 1 FROM equipos WHERE id = 1);

UPDATE equipos
SET mantenimiento_cada = 6, dias = false
WHERE id = 2 AND EXISTS (SELECT 1 FROM equipos WHERE id = 2);

INSERT INTO mantenimientos (equipo, tipo, fecha_prevista, fecha_realiza, comentarios, motivos)
SELECT 1, 'preventivo',
       (CURRENT_DATE - INTERVAL '90 days')::date,
       (CURRENT_DATE - INTERVAL '90 days')::date,
       'Plan demo: preventivo base para cálculo de próximo',
       'Seed 9.38'
WHERE EXISTS (SELECT 1 FROM equipos WHERE id = 1)
  AND NOT EXISTS (
    SELECT 1 FROM mantenimientos
    WHERE equipo = 1 AND comentarios = 'Plan demo: preventivo base para cálculo de próximo'
  );

INSERT INTO data_patches (filename, applied_at)
VALUES ('0048-equipos-plan-preventivo.sql', NOW())
ON CONFLICT (filename) DO NOTHING;
