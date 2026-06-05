-- 0016-personalizacion-last-two-plus-tipocursos.sql
-- Stage 8.8: tables + seeds for the last 2 Personalizacion modules from the original 7
-- (T. Amb. Aplicable / tiposamb + Tipos Imp. Amb. / tiposimp) plus Tipo Cursos (tipocursos)
-- to keep PR size similar to 8.6/8.7 while finishing the Aplicacion/Personalizacion focus.
-- Lightweight (id, nombre, activo) exactly like 0013/0014/0015.
-- Idempotent (CREATE IF NOT EXISTS + INSERT ... ON CONFLICT DO NOTHING).
-- No menu_nuevo changes needed: the leaf entries (administracion:tiposamb:listado:ver etc.)
-- pre-exist from 0004 full legacy menu + 0010 restructure.

-- Tipos Amb. Aplicable (T. Amb. Aplicacion)
CREATE TABLE IF NOT EXISTS tiposamb (
    id SERIAL PRIMARY KEY,
    nombre TEXT NOT NULL,
    activo BOOLEAN NOT NULL DEFAULT true
);
INSERT INTO tiposamb (nombre, activo)
VALUES ('Ruido Demo', true),
       ('Residuos Sólidos Demo', true),
       ('Vertidos Demo', true),
       ('Emisiones Demo', true)
ON CONFLICT DO NOTHING;

-- Tipos Imp. Amb.
CREATE TABLE IF NOT EXISTS tiposimp (
    id SERIAL PRIMARY KEY,
    nombre TEXT NOT NULL,
    activo BOOLEAN NOT NULL DEFAULT true
);
INSERT INTO tiposimp (nombre, activo)
VALUES ('Impacto Ambiental Alto Demo', true),
       ('Impacto Medio Demo', true),
       ('Impacto Controlado Demo', true)
ON CONFLICT DO NOTHING;

-- Tipo Cursos (under Formacion area in menu; catalog like other tipos)
CREATE TABLE IF NOT EXISTS tipocursos (
    id SERIAL PRIMARY KEY,
    nombre TEXT NOT NULL,
    activo BOOLEAN NOT NULL DEFAULT true
);
INSERT INTO tipocursos (nombre, activo)
VALUES ('Curso Calidad Demo', true),
       ('Curso Ambiental Demo', true),
       ('Curso Seguridad y Salud Demo', true),
       ('Curso Gestión Demo', true)
ON CONFLICT DO NOTHING;

-- Record patch (guard inside patch + also in init-db.sh)
INSERT INTO data_patches (filename, applied_at)
VALUES ('0016-personalizacion-last-two-plus-tipocursos.sql', NOW())
ON CONFLICT (filename) DO NOTHING;

COMMENT ON TABLE tiposamb IS 'Tipos Amb. Aplicable (Personalizacion, last of original 7). Lightweight for Stage 8.8.';
COMMENT ON TABLE tiposimp IS 'Tipos Imp. Amb. (Personalizacion, last of original 7). Lightweight for Stage 8.8.';
COMMENT ON TABLE tipocursos IS 'Tipo Cursos (catalog under Formacion). Lightweight for Stage 8.8.';

-- Note: modern routes will be added for /admin/tiposamb , /admin/tiposimp , /admin/tipo-cursos
-- plus legacy /administracion/... paths so menu clicks (resolveLegacyAction) land on real pages.
-- Placeholder routes for the 2 will be replaced in index.php.
