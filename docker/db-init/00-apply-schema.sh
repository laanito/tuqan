#!/bin/bash
set -e

# This script is currently DISABLED for the bare-minimum working app phase.
# DB initialization is handled exclusively from the app container
# via scripts/init-db.sh (which uses only verified minimal SQL files).
#
# This file is kept for reference / future use. It does nothing harmful.

echo "DB-side automatic schema init is disabled in this phase."
echo "Please run: docker compose exec app ./scripts/init-db.sh"
exit 0