-- ============================================================================
-- MINIMAL VIABLE SCHEMA — Bare Minimum Working App
--
-- This file contains the absolute smallest set of tables required to:
--   1. Perform company login (qnova_acl + qnova_bbdd)
--   2. Perform user login (usuarios + perfiles)
--   3. Render the main page / document tree without fatal errors
--      (idiomas + menu_nuevo + menu_idiomas_nuevo)
--
-- Everything else (documents, aspects, risks, questionnaires, etc.)
-- has been deliberately left out and will be added incrementally
-- as we modernize specific features.
--
-- All tables use CREATE TABLE IF NOT EXISTS for safe repeated application.
-- ============================================================================

-- 1. Idiomas (required very early by many parts of the app)
CREATE TABLE IF NOT EXISTS idiomas (
    id     SERIAL PRIMARY KEY,
    nombre CHARACTER VARYING(64) NOT NULL
);

-- 2. Central "etc" tables for company selection (used by LoginEmpresa)
CREATE TABLE IF NOT EXISTS qnova_acl (
    id         SERIAL PRIMARY KEY,
    login_name CHARACTER VARYING(64),
    login_pass CHARACTER VARYING(64)
);

CREATE TABLE IF NOT EXISTS qnova_bbdd (
    id          SERIAL PRIMARY KEY,
    nombre_bbdd CHARACTER VARYING(64),
    login_bbdd  CHARACTER VARYING(64),
    pass_bbdd   CHARACTER VARYING(64),
    empresa     CHARACTER VARYING(128)
);

-- 3. Perfiles / Roles (used by Auth)
CREATE TABLE IF NOT EXISTS perfiles (
    id     SERIAL PRIMARY KEY,
    nombre CHARACTER VARYING(64),
    activo BOOLEAN DEFAULT TRUE
);

-- 4. Usuarios (the actual company users)
CREATE TABLE IF NOT EXISTS usuarios (
    id      SERIAL PRIMARY KEY,
    login   CHARACTER VARYING(64) NOT NULL,
    pass    CHARACTER VARYING(64),
    perfil  INTEGER,
    area    INTEGER,
    activo  BOOLEAN DEFAULT TRUE,
    nombre  CHARACTER VARYING(128)
);

-- 5. Menu system (required for MainPage to not crash)
CREATE TABLE IF NOT EXISTS menu_nuevo (
    id       SERIAL PRIMARY KEY,
    padre    INTEGER,
    orden    INTEGER,
    accion   CHARACTER VARYING(256),
    permisos TEXT,
    activo   BOOLEAN DEFAULT TRUE
);

CREATE TABLE IF NOT EXISTS menu_idiomas_nuevo (
    menu      INTEGER,
    idioma_id INTEGER,
    valor     CHARACTER VARYING(128),
    PRIMARY KEY (menu, idioma_id)
);

-- Basic indexes for the menu (common in the original schema)
CREATE INDEX IF NOT EXISTS idx_menu_nuevo_padre ON menu_nuevo (padre);
CREATE INDEX IF NOT EXISTS idx_menu_idiomas_nuevo_menu ON menu_idiomas_nuevo (menu);

-- ============================================================================
-- Incremental data migration tracking (Stage 8.3+)
-- Allows adding new reference data (menus, etc.) over time without re-running
-- the entire seed. Patches are applied exactly once in lexical order.
-- ============================================================================
CREATE TABLE IF NOT EXISTS data_patches (
    filename   TEXT PRIMARY KEY,
    applied_at TIMESTAMP WITH TIME ZONE DEFAULT now()
);
