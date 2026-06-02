#!/bin/bash
#
# compile-locales.sh
#
# Recompiles gettext .po catalog files into .mo binary files.
# Only recompiles when the .po is newer than the corresponding .mo (or .mo is missing).
# This script is intended to be run on every container start via the Docker
# entrypoint so that .po changes (especially during development) are automatically
# reflected without requiring a manual msgfmt step or full image rebuild.
#
# Usage: ./scripts/compile-locales.sh   (or called from entrypoint)
#
# The textdomain used by the application is "qnova", therefore we always emit
# qnova.mo inside each LC_MESSAGES directory.

set -euo pipefail

echo "[locale] Checking for .po -> .mo compilation needs..."

shopt -s nullglob
po_files=(Locale/*/LC_MESSAGES/*.po)

if [ ${#po_files[@]} -eq 0 ]; then
  echo "[locale] No .po files found under Locale/*/LC_MESSAGES/"
  exit 0
fi

compiled=0
for po_file in "${po_files[@]}"; do
  mo_dir="$(dirname "$po_file")"
  # Always target qnova.mo because that is the domain name used by bindtextdomain/textdomain in the app
  mo_file="$mo_dir/qnova.mo"

  if [ ! -f "$mo_file" ] || [ "$po_file" -nt "$mo_file" ]; then
    echo "[locale] Compiling $po_file -> $mo_file"
    mkdir -p "$mo_dir"
    msgfmt --output-file="$mo_file" "$po_file"
    compiled=$((compiled + 1))
  fi
done

if [ "$compiled" -gt 0 ]; then
  echo "[locale] Compiled $compiled catalog(s)."
else
  echo "[locale] All .mo files are up to date."
fi
