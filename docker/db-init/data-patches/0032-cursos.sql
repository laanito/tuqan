-- 0032-cursos.sql
-- Stage 9.20: Formación subs first slice (Cursos under plan_formacion).
-- Basic fields for list + form. Full features (material, hoja_firmas, ficha integration, estado workflows) deferred.
-- Links to existing plan_formacion.

CREATE TABLE IF NOT EXISTS cursos (
    id SERIAL PRIMARY KEY,
    tipo INTEGER,
    objetivos TEXT,
    contenido TEXT,
    num_horas INTEGER,
    material_necesario TEXT,
    material_suministrado TEXT,
    activo BOOLEAN DEFAULT true,
    plan INTEGER,
    fecha_prevista DATE,
    lugar VARCHAR(64),
    fecha_realizacion DATE,
    estado INTEGER DEFAULT 0,
    nombre VARCHAR(128),
    responsable INTEGER,
    observaciones TEXT,
    calidad BOOLEAN,
    medioambiente BOOLEAN,
    hoja_firmas INTEGER
);

INSERT INTO cursos (nombre, plan, num_horas, fecha_prevista, activo, tipo, estado, calidad, medioambiente)
VALUES
    ('Curso de Calidad ISO 9001', 1, 16, '2025-09-15', true, 1, 0, true, false),
    ('Formación en Medio Ambiente', 1, 8, '2025-10-01', true, 2, 1, false, true),
    ('Seguridad y Salud en el Trabajo', 2, 12, '2025-11-20', false, 1, 0, true, true)
ON CONFLICT DO NOTHING;

INSERT INTO data_patches (filename, applied_at)
VALUES ('0032-cursos.sql', NOW())
ON CONFLICT (filename) DO NOTHING;
