-- 0040-formacion-cross-links.sql
-- Stage 9.30: Ensure Formación demo FKs for Planes ↔ Cursos ↔ Inscripciones
-- Idempotent; existing 0032/0034 seeds already link most rows.

UPDATE cursos SET plan = 1 WHERE id = 1 AND (plan IS NULL OR plan = 0);
UPDATE cursos SET plan = 1 WHERE id = 2 AND (plan IS NULL OR plan = 0);
UPDATE cursos SET plan = 2 WHERE id = 3 AND (plan IS NULL OR plan = 0);

UPDATE alumnos SET curso = 1 WHERE id = 1 AND (curso IS NULL OR curso = 0);
UPDATE alumnos SET curso = 2 WHERE id = 2 AND (curso IS NULL OR curso = 0);
UPDATE alumnos SET curso = 3 WHERE id = 3 AND (curso IS NULL OR curso = 0);

INSERT INTO data_patches (filename, applied_at)
VALUES ('0040-formacion-cross-links.sql', NOW())
ON CONFLICT (filename) DO NOTHING;
