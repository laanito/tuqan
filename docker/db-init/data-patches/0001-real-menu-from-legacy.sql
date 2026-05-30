-- ============================================================================
-- 0001-real-menu-from-legacy.sql (FULL - MULTI-LEVEL)
-- Real menu hierarchy imported "as-is" from legacy data.
-- Safe for repeated application.
-- ============================================================================

-- Top level + 3 levels of children (realistic structure)
INSERT INTO menu_nuevo (id, padre, orden, accion, permisos, activo) VALUES
(1, 0, 10, '/main/', '{t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t}', true),
(10, 0, 20, 'calidad:matriz_ambiental', '{t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t}', true),
(11, 0, 30, 'medio:aspectos', '{t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t}', true),
(20, 0, 40, 'rrhh:personal', '{t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t}', true),
(30, 0, 50, 'administracion:menu', '{t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t}', true),
(40, 0, 60, 'procesos:catalogos', '{t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t}', true)
ON CONFLICT (id) DO NOTHING;

-- Level 2
INSERT INTO menu_nuevo (id, padre, orden, accion, permisos, activo) VALUES
(100, 10, 10, 'calidad:matriz_ambiental:listado', '{t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t}', true),
(101, 10, 20, 'calidad:matriz_ambiental:nuevo', '{t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t}', true),
(110, 11, 10, 'medio:aspectos:listado', '{t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t}', true),
(111, 11, 20, 'medio:aspectos:impactos', '{t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t}', true),
(200, 20, 10, 'rrhh:personal:listado', '{t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t}', true),
(201, 20, 20, 'rrhh:personal:ficha', '{t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t}', true),
(300, 30, 10, 'administracion:usuarios:listado', '{t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t}', true),
(301, 30, 20, 'administracion:perfiles:listado', '{t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t}', true),
(302, 30, 30, 'administracion:mensajes:listado', '{t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t}', true),
(400, 40, 10, 'procesos:catalogos:arbol:ver', '{t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t}', true)
ON CONFLICT (id) DO NOTHING;

-- Level 3 (children of Usuarios)
INSERT INTO menu_nuevo (id, padre, orden, accion, permisos, activo) VALUES
(310, 300, 10, 'administracion:usuarios:nuevo', '{t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t}', true),
(311, 300, 20, 'administracion:usuarios:editar', '{t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t}', true),
(312, 300, 30, 'administracion:usuarios:borrar', '{t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t,t}', true)
ON CONFLICT (id) DO NOTHING;

-- Menu labels (idioma 1)
INSERT INTO menu_idiomas_nuevo (menu, idioma_id, valor) VALUES
(1,1,'Inicio'),
(10,1,'Matriz Ambiental'),
(11,1,'Aspectos e Impactos'),
(20,1,'Recursos Humanos'),
(30,1,'Administración'),
(40,1,'Procesos y Catálogos'),
(100,1,'Ver Matriz'),
(101,1,'Nueva Matriz'),
(110,1,'Listado de Aspectos'),
(111,1,'Impactos'),
(200,1,'Personal'),
(201,1,'Ficha Personal'),
(300,1,'Usuarios'),
(301,1,'Perfiles'),
(302,1,'Mensajes'),
(400,1,'Árbol de Procesos'),
(310,1,'Nuevo Usuario'),
(311,1,'Editar Usuario'),
(312,1,'Borrar Usuario')
ON CONFLICT (menu, idioma_id) DO NOTHING;