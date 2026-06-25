-- 0027-indicadores-table-and-seed.sql
-- Stage 9.9: Indicadores basic slice (first vertical for legacy 72).
-- Core `indicadores` table for KPI definitions, targets, tolerances, responsibilities and frequencies.
-- Follows exact pattern. Charts, calculations, metas, objetivos, and dashboard deferred.
-- Related: metas_indicadores, objetivos_indicadores, graficaIndicadores etc. remain legacy for now.

CREATE TABLE IF NOT EXISTS indicadores (
    id SERIAL PRIMARY KEY,
    definicion VARCHAR(255),
    valor_inicial INTEGER,
    tecnica VARCHAR(64),
    variables_control VARCHAR(128),
    activo BOOLEAN,
    frecuencia_seg INTEGER,
    frecuencia_ana INTEGER,
    genera_objetivo BOOLEAN,
    nombre VARCHAR(128),
    responsable_analisis VARCHAR(128),
    responsable_seguimiento VARCHAR(128),
    valor_tolerable DOUBLE PRECISION,
    valor_tolerable2 INTEGER,
    valor_objetivo INTEGER
);

INSERT INTO indicadores (definicion, valor_inicial, tecnica, variables_control, activo, frecuencia_seg, frecuencia_ana, genera_objetivo, nombre, responsable_analisis, responsable_seguimiento, valor_tolerable, valor_tolerable2, valor_objetivo)
VALUES
    ('Tasa de defectos de producción', 5, 'Porcentaje', 'Unidades producidas, defectos detectados', true, 1, 2, true, 'Tasa Defectos', 'Jefe Calidad', 'Supervisor Línea', 3.5, 4, 2),
    ('Consumo energético por unidad', 120, 'kWh/unidad', 'Energía total, unidades producidas', true, 2, 1, false, 'Consumo Energía', 'Responsable Energía', 'Jefe Mantenimiento', 100.0, 110, 90),
    ('Índice de satisfacción cliente', 75, 'Encuesta 1-100', 'Encuestas realizadas, puntuación media', false, 3, 3, true, 'Satisfacción Cliente', 'Jefe Comercial', 'Gerente', 80.0, 85, 85)
ON CONFLICT DO NOTHING;

INSERT INTO data_patches (filename, applied_at)
VALUES ('0027-indicadores-table-and-seed.sql', NOW())
ON CONFLICT (filename) DO NOTHING;
