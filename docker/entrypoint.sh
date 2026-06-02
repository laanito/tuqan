#!/bin/sh
#
# tuqan-entrypoint.sh
#
# Docker entrypoint for Tuqan.
# Runs before the main CMD (php-fpm).
#
# Responsibilities:
# - Ensure .mo files are freshly compiled from .po sources (so dev edits to
#   translations take effect immediately, and prod images are always current).
# - Then exec the original command so that signals, pid 1, etc. are handled
#   correctly by php-fpm.
#
# The script is copied to /usr/local/bin/tuqan-entrypoint.sh and made executable
# during the Docker build. Both the dev and prod stages inherit the ENTRYPOINT.

set -e

# Best-effort: do not prevent container from starting if compilation has a hiccup
# (e.g. msgfmt not in PATH in some exotic image). The || true keeps us resilient.
if [ -x /usr/local/bin/compile-locales.sh ]; then
  /usr/local/bin/compile-locales.sh || true
fi

# Hand over to the real command (usually "php-fpm")
exec "$@"
