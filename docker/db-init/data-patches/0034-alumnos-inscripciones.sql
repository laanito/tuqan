-- 0034-alumnos-inscripciones.sql
-- Stage 9.22: Formación more subs - Inscripciones (alumnos table) basic slice.
-- Links to cursos (from 9.20) and usuarios.

CREATE TABLE IF NOT EXISTS alumnos (
    id SERIAL PRIMARY KEY,
    usuario INTEGER,
    curso INTEGER,
    inscrito BOOLEAN DEFAULT false,
    verificado BOOLEAN DEFAULT false
);

INSERT INTO alumnos (usuario, curso, inscrito, verificado)
VALUES
    (1, 1, true, true),
    (1, 2, true, false),
    (1, 3, false, false)
ON CONFLICT DO NOTHING;

INSERT INTO data_patches (filename, applied_at)
VALUES ('0034-alumnos-inscripciones.sql', NOW())
ON CONFLICT (filename) DO NOTHING;
