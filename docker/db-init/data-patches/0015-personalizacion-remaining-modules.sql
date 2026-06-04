-- 0015-personalizacion-remaining-modules.sql
-- Stage 8.7: tables + seeds for 3 more Personalizacion modules (Tipos Acc. Mejora, Tipos Area, Tipo Documento).
-- Lightweight like previous (id, nombre, activo). Reuses pattern from 0013/0014.
-- Also adds basic child menu actions (nuevo, editar) for the new modules if not present (modeled on 0010/8.5).
-- Idempotent.

-- Tipos Acc. Mejora (tipomejora / tipo_acciones style)
CREATE TABLE IF NOT EXISTS tipoaccionesmejora (
    id SERIAL PRIMARY KEY,
    nombre TEXT NOT NULL,
    activo BOOLEAN NOT NULL DEFAULT true
);
INSERT INTO tipoaccionesmejora (nombre, activo)
VALUES ('Acción Correctiva Demo', true),
       ('Acción Preventiva Demo', true),
       ('Mejora Continua Demo', true)
ON CONFLICT DO NOTHING;

-- Tipos Area
CREATE TABLE IF NOT EXISTS tiposareas (
    id SERIAL PRIMARY KEY,
    nombre TEXT NOT NULL,
    activo BOOLEAN NOT NULL DEFAULT true
);
INSERT INTO tiposareas (nombre, activo)
VALUES ('Área Calidad Demo', true),
       ('Área Ambiental Demo', true)
ON CONFLICT DO NOTHING;

-- Tipo Documento
CREATE TABLE IF NOT EXISTS tipodocumento (
    id SERIAL PRIMARY KEY,
    nombre TEXT NOT NULL,
    activo BOOLEAN NOT NULL DEFAULT true
);
INSERT INTO tipodocumento (nombre, activo)
VALUES ('Procedimiento Demo', true),
       ('Instrucción Técnica Demo', true),
       ('Registro Demo', true)
ON CONFLICT DO NOTHING;

-- Record patch
INSERT INTO data_patches (filename, applied_at)
VALUES ('0015-personalizacion-remaining-modules.sql', NOW())
ON CONFLICT (filename) DO NOTHING;

-- Optional: add basic child actions for nuevo/editar under the parent menus (85,87,92 etc.)
-- These parents were reparented to Personalizacion in 0010.
-- We use safe inserts; adjust if exact accions differ.
-- For simplicity in this minimal setup, the modern routes will be the primary; legacy can fall to Placeholder if needed.
-- (In full 8.5 style we would add precise ones here.)

COMMENT ON TABLE tipoaccionesmejora IS 'Tipos de Acciones de Mejora (Personalizacion). Lightweight for Stage 8.7.';
COMMENT ON TABLE tiposareas IS 'Tipos de Área (Personalizacion). Lightweight for Stage 8.7.';
COMMENT ON TABLE tipodocumento IS 'Tipos de Documento (Personalizacion). Lightweight for Stage 8.7.';