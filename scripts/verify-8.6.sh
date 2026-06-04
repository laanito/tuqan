#!/bin/bash
#
# verify-8.6.sh
# Non-interactive checks for the major 8.6 functional changes (POST modules, Sedes rename, new Personalizacion tables, etc.).
# Run inside the app container after init-db.sh:
#   docker compose exec app ./scripts/verify-8.6.sh
#
# This is intentionally simple and focused on the things that are easy to assert automatically.
# Full confidence still requires the human browser flows described in STAGE-CHECKLISTS.md.

set -euo pipefail

echo "=== Stage 8.6 / 8.7 Verification (non-interactive) ==="
echo ""

echo "1. Syntax check on key files..."
php -l Pages/Sedes/Listado.php Pages/Sedes/Formulario.php \
    Pages/Perfiles/Formulario.php Pages/Usuarios/Formulario.php \
    Pages/Clientes/Listado.php Pages/Clientes/Formulario.php \
    Pages/Criterios/Listado.php Pages/Criterios/Formulario.php \
    Pages/TiposMejora/Listado.php Pages/TiposMejora/Formulario.php \
    Pages/TiposAreas/Listado.php Pages/TiposAreas/Formulario.php \
    Pages/TipoDocumento/Listado.php Pages/TipoDocumento/Formulario.php \
    Pages/Permisos/Formulario.php Pages/Menus/Listado.php \
    index.php > /dev/null
echo "   PASS: No syntax errors in the main 8.6/8.7 files."

echo ""
echo "2. DB state checks (tables from 0012/0013/0014/0015 + menu updates)..."
export PGPASSWORD="${DB_PASS:-secret}"
psql -h "${DB_HOST:-db}" -U qnova -d qnova -v ON_ERROR_STOP=1 -c "
-- Tables (8.6 + 8.7)
SELECT tablename FROM pg_tables 
WHERE schemaname='public' AND tablename IN ('sedes','clientes','criterios','tiposmejora','empresas','tipoaccionesmejora','tiposareas','tipodocumento')
ORDER BY tablename;

-- Sedes rename evidence
SELECT COUNT(*) AS sedes_rows FROM sedes;
SELECT id, accion FROM menu_nuevo WHERE accion LIKE '%sedes%' ORDER BY id;

-- Spanish label for Sedes
SELECT valor FROM menu_idiomas_nuevo 
WHERE menu = (SELECT id FROM menu_nuevo WHERE accion LIKE '%sedes%' ORDER BY id LIMIT 1)
  AND idioma_id = 1;

-- New Personalizacion modules (8.6 + 8.7)
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
SELECT 'tipodocumento', COUNT(*) FROM tipodocumento;

-- Patch tracking (up to 0015)
SELECT filename FROM data_patches 
WHERE filename LIKE '001%' 
ORDER BY filename;
" 2>&1 | cat

echo ""
echo "3. (Class load smoke skipped in this script because it is fragile from different CWDs; the php -l above already gives us syntax confidence. Full route exercising requires a real session and is covered in the browser + DB-assert part of the playbook.)"

echo ""
echo "=== 8.6/8.7 non-interactive verification finished ==="
echo "For the real confidence on the POST behavior, flashes, matrix, editing, etc., follow the full playbook in .agents/STAGE-CHECKLISTS.md (the browser + DB-assert-after-submit part)."
