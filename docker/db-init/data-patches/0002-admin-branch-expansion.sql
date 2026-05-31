-- ============================================================================
-- 0002-admin-branch-expansion.sql
-- Expands the Administración section with more real legacy items needed
-- for the first modernization vertical slice (user management + profiles).
-- Safe for repeated application (ON CONFLICT).
-- ============================================================================

-- Additional items under our synthetic "Administración" (id 30 in the curated patch)
-- These mirror real legacy actions under the old "administracion" (74) branch.

-- Perfiles children (we had the parent)
INSERT INTO menu_nuevo (id, padre, orden, accion, permisos, activo) VALUES
(320, 301, 10, 'administracion:perfiles:nuevo', '{t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t}', true),
(321, 301, 20, 'administracion:perfiles:editar', '{t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t}', true),
(322, 301, 30, 'administracion:perfiles:borrar', '{t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t}', true)
ON CONFLICT (id) DO NOTHING;

-- More under Usuarios (already had 300 parent + 310-312 children)
INSERT INTO menu_nuevo (id, padre, orden, accion, permisos, activo) VALUES
(313, 300, 40, 'administracion:usuarios:excel:ver', '{t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t}', true),
(314, 300, 50, 'administracion:usuarios:baja:general', '{t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t}', true)
ON CONFLICT (id) DO NOTHING;

-- Mensajes children (parent 302)
INSERT INTO menu_nuevo (id, padre, orden, accion, permisos, activo) VALUES
(330, 302, 10, 'administracion:mensajes:nuevo', '{t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t}', true),
(331, 302, 20, 'administracion:mensajes:ver', '{t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t}', true)
ON CONFLICT (id) DO NOTHING;

-- Labels (idioma 1 = Spanish) — extend the existing ones
INSERT INTO menu_idiomas_nuevo (menu, idioma_id, valor) VALUES
(320,1,'Nuevo Perfil'),
(321,1,'Editar Perfil'),
(322,1,'Borrar Perfil'),
(313,1,'Exportar Usuarios'),
(314,1,'Dar de Baja'),
(330,1,'Nuevo Mensaje'),
(331,1,'Ver Mensaje')
ON CONFLICT (menu, idioma_id) DO NOTHING;

-- Also add Catalan labels (idioma 2) for completeness
INSERT INTO menu_idiomas_nuevo (menu, idioma_id, valor) VALUES
(320,2,'Nou Perfil'),
(321,2,'Editar Perfil'),
(322,2,'Esborrar Perfil'),
(313,2,'Exportar Usuaris'),
(314,2,'Donar de Baixa'),
(330,2,'Nou Missatge'),
(331,2,'Veure Missatge')
ON CONFLICT (menu, idioma_id) DO NOTHING;
