-- 0023-plan-formacion-table-and-seed.sql
-- Stage 9.5: Planes de Formación (first basic slice of Formación module).
-- plan_formacion is catalog-like (nombre + activo present) with additional flags.
-- Follows exact pattern from prior vertical patches (0020-0022).

CREATE TABLE IF NOT EXISTS plan_formacion (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(128) NOT NULL,
    vigente BOOLEAN,
    descripcion TEXT,
    activo BOOLEAN,
    calidad BOOLEAN,
    medioambiente BOOLEAN
);

INSERT INTO plan_formacion (nombre, vigente, descripcion, activo, calidad, medioambiente)
VALUES
    ('Plan de Formación 2025 - Calidad', true, 'Formación obligatoria en normas ISO 9001 para todo el personal de producción y calidad.', true, true, false),
    ('Plan de Formación Ambiental 2025', true, 'Capacitación en gestión ambiental, residuos y aspectos significativos para áreas operativas.', true, false, true),
    ('Plan de Inducción Seguridad y Salud', false, 'Módulos básicos de prevención de riesgos laborales. Actualización prevista tras revisión de riesgos.', true, true, true)
ON CONFLICT DO NOTHING;

INSERT INTO data_patches (filename, applied_at)
VALUES ('0023-plan-formacion-table-and-seed.sql', NOW())
ON CONFLICT (filename) DO NOTHING;