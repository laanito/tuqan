-- 0046-equipos-calendario.sql
-- Stage 9.36: Demo mantenimientos with dates in the current calendar year
-- so /admin/equipos/calendario shows markers after init-db.
-- No schema change (reuses mantenimientos from 0043 / clean schema).

INSERT INTO mantenimientos (equipo, tipo, fecha_prevista, fecha_realiza, comentarios, motivos)
SELECT 1, 'preventivo',
       (date_trunc('year', CURRENT_DATE) + INTERVAL '1 month' + INTERVAL '14 days')::date,
       (date_trunc('year', CURRENT_DATE) + INTERVAL '1 month' + INTERVAL '16 days')::date,
       'Calendario demo: preventivo febrero',
       'Seed 9.36'
WHERE EXISTS (SELECT 1 FROM equipos WHERE id = 1)
  AND NOT EXISTS (
    SELECT 1 FROM mantenimientos WHERE comentarios = 'Calendario demo: preventivo febrero'
  );

INSERT INTO mantenimientos (equipo, tipo, fecha_prevista, fecha_realiza, comentarios, motivos)
SELECT 1, 'revision',
       (date_trunc('year', CURRENT_DATE) + INTERVAL '6 months' + INTERVAL '9 days')::date,
       (date_trunc('year', CURRENT_DATE) + INTERVAL '6 months' + INTERVAL '10 days')::date,
       'Calendario demo: revisión julio',
       'Seed 9.36'
WHERE EXISTS (SELECT 1 FROM equipos WHERE id = 1)
  AND NOT EXISTS (
    SELECT 1 FROM mantenimientos WHERE comentarios = 'Calendario demo: revisión julio'
  );

INSERT INTO mantenimientos (equipo, tipo, fecha_prevista, fecha_realiza, comentarios, motivos)
SELECT 2, 'correctivo',
       NULL,
       (date_trunc('year', CURRENT_DATE) + INTERVAL '3 months' + INTERVAL '4 days')::date,
       'Calendario demo: correctivo abril',
       'Avería puntual (seed 9.36)'
WHERE EXISTS (SELECT 1 FROM equipos WHERE id = 2)
  AND NOT EXISTS (
    SELECT 1 FROM mantenimientos WHERE comentarios = 'Calendario demo: correctivo abril'
  );

INSERT INTO mantenimientos (equipo, tipo, fecha_prevista, fecha_realiza, comentarios, motivos)
SELECT 2, 'preventivo',
       (date_trunc('year', CURRENT_DATE) + INTERVAL '9 months' + INTERVAL '19 days')::date,
       (date_trunc('year', CURRENT_DATE) + INTERVAL '9 months' + INTERVAL '19 days')::date,
       'Calendario demo: preventivo octubre (prevista)',
       'Seed 9.36 (fecha_realiza = prevista; columna NOT NULL en legacy)'
WHERE EXISTS (SELECT 1 FROM equipos WHERE id = 2)
  AND NOT EXISTS (
    SELECT 1 FROM mantenimientos WHERE comentarios = 'Calendario demo: preventivo octubre (prevista)'
  );

INSERT INTO data_patches (filename, applied_at)
VALUES ('0046-equipos-calendario.sql', NOW())
ON CONFLICT (filename) DO NOTHING;
