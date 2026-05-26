-- ============================================================================
-- MINIMAL SEED DATA — Bare Minimum Working App
--
-- Goal: Smallest possible dataset that allows a full login flow
-- (company selection → user login) and the main page to render without
-- fatal errors.
--
-- This seed is designed to be applied on top of 00-minimal-schema.sql.
-- ============================================================================

-- 1. Idiomas (required very early by many parts of the app and by MainPage)
INSERT INTO idiomas (id, nombre) VALUES (1, 'castellano')
ON CONFLICT (id) DO NOTHING;

-- 2. Central "etc" tables for company selection (LoginEmpresa flow)
INSERT INTO qnova_acl (id, login_name, login_pass)
VALUES (1, 'demo', '21232f297a57a5a743894a0e4a801fc3')  -- md5('admin')
ON CONFLICT (id) DO NOTHING;

INSERT INTO qnova_bbdd (id, nombre_bbdd, login_bbdd, pass_bbdd, empresa)
VALUES (1, 'qnova', 'qnova', '5ebe2294ecd0e0f08eab7690d2a6ee69', 'Demo Company')  -- md5('secret')
ON CONFLICT (id) DO NOTHING;

-- 3. Perfiles / Roles (required by Auth::getRoleById)
INSERT INTO perfiles (id, nombre, activo) VALUES
(0, 'Administrador', 't'),
(1, 'Usuario', 't')
ON CONFLICT (id) DO NOTHING;

-- 4. Usuarios (in the company database)
INSERT INTO usuarios (id, login, pass, perfil, activo, nombre)
VALUES (1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 0, 't', 'Administrador Demo')
ON CONFLICT (id) DO NOTHING;

-- 5. Minimal menu so MainPage does not crash
INSERT INTO menu_nuevo (id, padre, orden, accion, permisos, activo) VALUES
(1, 0, 10, '/main/', '{t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t}', 't')
ON CONFLICT (id) DO NOTHING;

INSERT INTO menu_idiomas_nuevo (menu, idioma_id, valor) VALUES
(1, 1, 'Inicio')
ON CONFLICT (menu, idioma_id) DO NOTHING;