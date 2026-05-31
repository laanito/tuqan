#!/bin/bash
set -e

# Bare-minimum database initialization for Tuqan (Docker development)
# This script should be run from inside the app container:
#   docker compose exec app ./scripts/init-db.sh

echo "=== Tuqan Bare Minimum DB Initialization ==="
echo ""

DB_HOST=${DB_HOST:-db}
DB_PORT=${DB_PORT:-5432}
DB_NAME=${DB_NAME:-qnova}
DB_USER=${DB_USER:-qnova}
DB_PASS=${DB_PASS:-secret}

export PGPASSWORD="$DB_PASS"

echo "Waiting for PostgreSQL to be ready..."
until pg_isready -h "$DB_HOST" -p "$DB_PORT" -U "$DB_USER" -d "$DB_NAME" >/dev/null 2>&1; do
    sleep 1
done
echo "PostgreSQL is ready."

echo ""
echo "Checking current table count in public schema..."
TABLE_COUNT=$(psql -h "$DB_HOST" -p "$DB_PORT" -U "$DB_USER" -d "$DB_NAME" -tAc "
    SELECT COUNT(*) FROM information_schema.tables 
    WHERE table_schema = 'public';
" 2>/dev/null || echo 0)

if [ "$TABLE_COUNT" -gt 0 ]; then
    echo "Database already contains $TABLE_COUNT tables."
    echo "Initialization skipped (use 'docker compose down -v' for a fresh start)."
    exit 0
fi

echo "Applying minimal schema (docker/db-init/00-minimal-schema.sql)..."
echo "(Bare minimum tables needed for login + main navigation — see docker/db-init/README.md)"

psql -h "$DB_HOST" -p "$DB_PORT" -U "$DB_USER" -d "$DB_NAME" \
    -v ON_ERROR_STOP=1 \
    -f /var/www/html/docker/db-init/00-minimal-schema.sql

echo "Minimal schema applied."

echo ""
echo "Applying minimal seed data (docker/db-init/01-minimal-seed.sql)..."
psql -h "$DB_HOST" -p "$DB_PORT" -U "$DB_USER" -d "$DB_NAME" \
    -v ON_ERROR_STOP=1 \
    -f /var/www/html/docker/db-init/01-minimal-seed.sql

echo "Minimal seed applied."

echo ""
echo "Applying incremental data patches (docker/db-init/data-patches/*.sql)..."
echo "(0004 contains the full real legacy menu for renderer verification)"
PATCH_DIR="/var/www/html/docker/db-init/data-patches"
if [ -d "$PATCH_DIR" ]; then
    for patch in $(ls "$PATCH_DIR"/*.sql 2>/dev/null | sort); do
        filename=$(basename "$patch")
        # Check if already applied
        already=$(psql -h "$DB_HOST" -p "$DB_PORT" -U "$DB_USER" -d "$DB_NAME" -tAc \
            "SELECT 1 FROM data_patches WHERE filename = '$filename';" 2>/dev/null || echo "")
        if [ "$already" = "1" ]; then
            echo "  - $filename (already applied)"
        else
            echo "  - Applying $filename"
            psql -h "$DB_HOST" -p "$DB_PORT" -U "$DB_USER" -d "$DB_NAME" \
                -v ON_ERROR_STOP=1 -f "$patch"
            psql -h "$DB_HOST" -p "$DB_PORT" -U "$DB_USER" -d "$DB_NAME" \
                -c "INSERT INTO data_patches (filename) VALUES ('$filename') ON CONFLICT DO NOTHING;"
        fi
    done
else
    echo "  (no data-patches directory yet)"
fi
echo "Data patches applied."

echo ""
echo "=== Initialization complete ==="
echo "You can now log in with:"
echo "  Company login: demo / admin"
echo "  User login:    admin / admin"
echo ""
echo "This uses only verified minimal schema + seed."
echo "More data will be added incrementally as we modernize specific features."