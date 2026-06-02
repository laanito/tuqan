-- 0014-more-personalizacion-modules.sql
-- Stage 8.6 continuation: two more fully implemented modules under Personalizacion.
-- Criterios and Tipos de Acciones de Mejora (lightweight master data tables).
-- Using simple names for our minimal env; in a full legacy restore these may map
-- to criterios_homologacion / tipo_acciones etc. (we keep them separate for now).

CREATE TABLE IF NOT EXISTS criterios (
    id SERIAL PRIMARY KEY,
    nombre TEXT NOT NULL,
    activo BOOLEAN NOT NULL DEFAULT true
);

INSERT INTO criterios (nombre, activo)
VALUES ('Criterio de Calidad Demo', true),
       ('Criterio Ambiental Demo', true)
ON CONFLICT DO NOTHING;

CREATE TABLE IF NOT EXISTS tiposmejora (
    id SERIAL PRIMARY KEY,
    nombre TEXT NOT NULL,
    activo BOOLEAN NOT NULL DEFAULT true
);

INSERT INTO tiposmejora (nombre, activo)
VALUES ('Acción Correctiva', true),
       ('Acción Preventiva', true),
       ('Mejora Continua', true)
ON CONFLICT DO NOTHING;

INSERT INTO data_patches (filename, applied_at)
VALUES ('0014-more-personalizacion-modules.sql', NOW())
ON CONFLICT (filename) DO NOTHING;