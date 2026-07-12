#!/bin/bash
#
# verify-8.6.sh
# Non-interactive checks for the major 8.6/8.7/8.8/8.9 functional changes (POST modules, Sedes rename, new Personalizacion tables, catalog base extraction, etc.).
# Run inside the app container after init-db.sh:
#   docker compose exec app ./scripts/verify-8.6.sh
#
# This is intentionally simple and focused on the things that are easy to assert automatically.
# Full confidence still requires the human browser flows described in STAGE-CHECKLISTS.md.

set -euo pipefail

echo "=== Stage 8.6 / 8.7 / 8.8 / 9.1 / 9.2 / 9.3 / 9.4 / 9.5 / 9.6 / 9.7 / 9.8 / 9.9 / 9.10 / 9.11 / 9.12 / 9.13 / 9.14 / 9.15 / 9.16 / 9.17 / 9.18 / 9.19 / 9.20 / 9.21 / 9.22 / 9.23 / 9.24 / 9.25 Verification (non-interactive) ==="
echo ""

echo "1. Syntax check on key files..."
php -l Pages/Catalog/CatalogListado.php Pages/Catalog/CatalogFormulario.php Pages/Catalog/CatalogTree.php \
    Pages/Sedes/Listado.php Pages/Sedes/Formulario.php \
    Pages/Perfiles/Formulario.php Pages/Usuarios/Formulario.php \
    Pages/Clientes/Listado.php Pages/Clientes/Formulario.php \
    Pages/Criterios/Listado.php Pages/Criterios/Formulario.php \
    Pages/TiposMejora/Listado.php Pages/TiposMejora/Formulario.php \
    Pages/TiposAreas/Listado.php Pages/TiposAreas/Formulario.php \
    Pages/TipoDocumento/Listado.php Pages/TipoDocumento/Formulario.php \
    Pages/Permisos/Formulario.php Pages/Menus/Listado.php \
    Pages/TiposAmb/Listado.php Pages/TiposAmb/Formulario.php \
    Pages/TiposImp/Listado.php Pages/TiposImp/Formulario.php \
    Pages/TipoCursos/Listado.php Pages/TipoCursos/Formulario.php \
    Pages/Proveedores/Listado.php Pages/Proveedores/Formulario.php \
    Pages/Equipos/Listado.php Pages/Equipos/Formulario.php \
    Pages/Mejora/Listado.php Pages/Mejora/Formulario.php \
    Pages/Formacion/Listado.php Pages/Formacion/Formulario.php \
    Pages/Documentacion/Listado.php Pages/Documentacion/Formulario.php \
    Pages/Documentacion/Arbol.php \
    Pages/Auditorias/Listado.php Pages/Auditorias/Formulario.php \
    Pages/Aspectos/Listado.php Pages/Aspectos/Formulario.php \
    Pages/Aspectos/Matriz.php \
    Pages/Indicadores/Listado.php Pages/Indicadores/Formulario.php \
    Pages/Procesos/Listado.php Pages/Procesos/Formulario.php \
    Pages/Procesos/Arbol.php \
    index.php > /dev/null
echo "   PASS: No syntax errors in the main 8.6/8.7/8.8/8.9 files."

echo ""
echo "2. DB state checks (tables from 0012/0013/0014/0015 + menu updates)..."
export PGPASSWORD="${DB_PASS:-secret}"
psql -h "${DB_HOST:-db}" -U qnova -d qnova -v ON_ERROR_STOP=1 -c "
-- Tables (8.6 + 8.7 + 8.8 + 9.2 + 9.3 + 9.4 + 9.5 + 9.6 + 9.7 + 9.9)
SELECT tablename FROM pg_tables 
WHERE schemaname='public' AND tablename IN ('sedes','clientes','criterios','tiposmejora','empresas','tipoaccionesmejora','tiposareas','tipodocumento','tiposamb','tiposimp','tipocursos','proveedores','equipos','acciones_mejora','plan_formacion','documentos','programa_auditoria','aspectos','indicadores','procesos','contenido_procesos','auditorias','cursos','alumnos')
ORDER BY tablename;

-- Sedes rename evidence
SELECT COUNT(*) AS sedes_rows FROM sedes;
SELECT id, accion FROM menu_nuevo WHERE accion LIKE '%sedes%' ORDER BY id;

-- Spanish label for Sedes
SELECT valor FROM menu_idiomas_nuevo 
WHERE menu = (SELECT id FROM menu_nuevo WHERE accion LIKE '%sedes%' ORDER BY id LIMIT 1)
  AND idioma_id = 1;

-- New Personalizacion modules (8.6 + 8.7 + 8.8)
SELECT 'clientes' AS t, COUNT(*) FROM clientes
UNION ALL
SELECT 'criterios', COUNT(*) FROM criterios
UNION ALL
SELECT 'tiposmejora', COUNT(*) FROM tiposmejora
UNION ALL
SELECT 'tipoaccionesmejora', COUNT(*) FROM tipoaccionesmejora
UNION ALL
SELECT 'tiposareas', COUNT(*) FROM tiposareas
UNION ALL
SELECT 'tipodocumento', COUNT(*) FROM tipodocumento
UNION ALL
SELECT 'tiposamb', COUNT(*) FROM tiposamb
UNION ALL
SELECT 'tiposimp', COUNT(*) FROM tiposimp
UNION ALL
SELECT 'tipocursos', COUNT(*) FROM tipocursos;

-- Patch tracking (up to 0029 for 9.11)
SELECT filename FROM data_patches 
WHERE filename LIKE '00%' 
ORDER BY filename;

-- 9.2 Proveedores evidence
SELECT COUNT(*) AS proveedores_rows FROM proveedores;

-- 9.3 Equipos evidence
SELECT COUNT(*) AS equipos_rows FROM equipos;

-- 9.4 Acciones de Mejora evidence
SELECT COUNT(*) AS acciones_mejora_rows FROM acciones_mejora;

-- 9.21 Mejora deeper workflow evidence (sample workflow fields)
SELECT COUNT(*) AS mejora_with_detectado FROM acciones_mejora WHERE usuario_detectado IS NOT NULL;

-- 9.25 Mejora more workflow evidence
SELECT COUNT(*) AS mejora_with_verifica FROM acciones_mejora WHERE usuario_verifica IS NOT NULL;

-- 9.5 Formación (Planes) + Documentación evidence
SELECT COUNT(*) AS plan_formacion_rows FROM plan_formacion;
SELECT COUNT(*) AS documentos_rows FROM documentos;

-- 9.23 Documentación perfiles evidence
SELECT COUNT(*) AS documentos_with_perfiles FROM documentos WHERE perfil_ver IS NOT NULL;

-- 9.24 Documentación workflows evidence
SELECT COUNT(*) AS documentos_with_workflows FROM documentos WHERE revisado_por IS NOT NULL;

-- 9.20 Formación Cursos evidence
SELECT COUNT(*) AS cursos_rows FROM cursos;

-- 9.22 Formación Inscripciones evidence
SELECT COUNT(*) AS alumnos_rows FROM alumnos;

-- 9.6 Auditorías (Programa) evidence
SELECT COUNT(*) AS programa_auditoria_rows FROM programa_auditoria;

-- 9.19 Auditorías Ejecución evidence
SELECT COUNT(*) AS auditorias_rows FROM auditorias;

-- 9.7 Aspectos Ambientales evidence
SELECT COUNT(*) AS aspectos_rows FROM aspectos;

-- 9.9 Indicadores evidence
SELECT COUNT(*) AS indicadores_rows FROM indicadores;

-- 9.10 Procesos evidence
SELECT COUNT(*) AS procesos_rows FROM procesos;

-- 9.11 Procesos Árbol + contenido evidence
SELECT COUNT(*) AS contenido_procesos_rows FROM contenido_procesos;

-- Menu structure invariants for Personalizacion (added in 8.9 after 0017/0018 exposed gaps)
-- These would have caught wrong padres (4th-level nesting), redundant empty sections,
-- duplicate labels, and missing actions before user report.
SELECT 'personalizacion_direct_children' as check, COUNT(*) 
FROM menu_nuevo WHERE padre = 1400;
SELECT id, orden, accion, COALESCE((SELECT valor FROM menu_idiomas_nuevo WHERE menu=m.id AND idioma_id=1), accion) as nombre 
FROM menu_nuevo m WHERE padre = 1400 ORDER BY orden;
-- Flag any empty sections at this level (should be 0 after cleanup)
SELECT 'orphan_sections_under_personalizacion' as check, COUNT(*) 
FROM menu_nuevo WHERE padre = 1400 AND (accion IS NULL OR accion = '');

-- 9.1 specific: the Criterios Ambientales entry exists as direct child with label + accion
SELECT 'criterios_ambientales_under_personalizacion' as check, COUNT(*) 
FROM menu_nuevo m
LEFT JOIN menu_idiomas_nuevo mi ON mi.menu = m.id AND mi.idioma_id = 1
WHERE m.padre = 1400
  AND mi.valor = 'Criterios Ambientales'
  AND m.accion IS NOT NULL AND m.accion <> '';
" 2>&1 | cat

echo ""
echo "3. (Class load smoke skipped in this script because it is fragile from different CWDs; the php -l above already gives us syntax confidence. Full route exercising requires a real session and is covered in the browser + DB-assert part of the playbook.)"

echo ""
echo "=== 8.6/8.7/8.8/9.1/9.2/9.3/9.4/9.5/9.6/9.7/9.8/9.9/9.10/9.11/9.12/9.13/9.14/9.15/9.16/9.17/9.18/9.19/9.20/9.21/9.22/9.23/9.24/9.25 non-interactive verification finished ==="
echo "For the real confidence on the POST behavior, flashes, matrix, editing, etc., follow the full playbook in .agents/STAGE-CHECKLISTS.md (the browser + DB-assert-after-submit part)."
