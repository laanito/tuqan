# Docker Environment Specification — Tuqan

**Purpose:** Complete, copy-paste ready definition of the 100% Docker dev environment. Future agents will use the exact content below to create the files via write/search_replace after plan approval.

**Rule:** All commands in this file are to be executed from the host using only `docker` and `docker compose`. No bare `php`, `composer`, `psql`.

## Files to Create (Stage 1)

1. `Dockerfile` (root)
2. `docker-compose.yml` (root)
3. `docker-compose.override.yml` (optional, gitignored example)
4. `.env.docker` (template, committed)
5. `.env` (local only, gitignored)
6. `docker/nginx/tuqan.conf`
7. `docker/php/php.ini`
8. `docker/php/xdebug.ini`
9. `docker/postgres/init/README.md` (how to manage dumps)

## Full Dockerfile (Recommended Starting Point)

```dockerfile
# syntax=docker/dockerfile:1.7
FROM php:8.3-fpm-bookworm AS base

ENV DEBIAN_FRONTEND=noninteractive

# System packages + PHP extensions required by legacy Tuqan (gd for graphs/PDF, gettext, pgsql, zip for excel, etc.)
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libpq-dev \
        libgd-dev \
        libzip-dev \
        libicu-dev \
        gettext \
        locales \
        unzip \
        git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_pgsql \
        pgsql \
        gd \
        zip \
        gettext \
        intl \
        bcmath \
        opcache \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Composer v2
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Locale for gettext (matches original es_ES expectations)
RUN sed -i '/es_ES.UTF-8/s/^# //g' /etc/locale.gen && locale-gen

WORKDIR /var/www/html

# -------------------------
# Dev image (with Xdebug + permissive settings)
# -------------------------
FROM base AS dev

RUN pecl install xdebug-3.3.2 \
    && docker-php-ext-enable xdebug

# Default dev php.ini overrides (also mounted)
COPY docker/php/php.ini /usr/local/etc/php/conf.d/99-tuqan.ini
COPY docker/php/xdebug.ini /usr/local/etc/php/conf.d/99-xdebug.ini

# Non-root for bind mount friendliness on macOS (adjust uid if needed)
RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

USER www-data

# -------------------------
# Prod image (lean, no xdebug)
# -------------------------
FROM base AS prod

COPY docker/php/php.ini /usr/local/etc/php/conf.d/99-tuqan.ini

RUN usermod -u 1000 www-data && groupmod -g 1000 www-data
USER www-data

# Default target for compose (override with --target prod later)
FROM dev
```

## docker-compose.yml (Core)

See the skeleton in MIGRATION-PLAN.md. Use that as base. Add healthchecks, restart policies, and logging in the real file.

Recommended ports (avoid clashing with host postgres):
- Web: 8080 → 80
- DB direct (optional, for external tools): 54321 → 5432

## docker/nginx/tuqan.conf

```nginx
server {
    listen 80;
    server_name localhost;
    root /var/www/html;
    index index.php index.html;

    client_max_body_size 20M;  # matches original upload needs

    location / {
        try_files $uri $uri/ /index.php?$args;
    }

    location ~ \.php$ {
        fastcgi_pass app:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_read_timeout 300;
    }

    # Legacy static paths (images, css, javascript, userfiles)
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|ttf|woff)$ {
        expires 30d;
        access_log off;
    }

    location /userfiles/ {
        alias /var/www/html/userfiles/;
    }
}
```

## docker/php/php.ini (Dev)

```ini
; Tuqan legacy-friendly + modern dev settings
memory_limit = 512M
upload_max_filesize = 16M
post_max_size = 20M
max_execution_time = 300

; Error reporting (strict in dev)
error_reporting = E_ALL
display_errors = On
display_startup_errors = On
log_errors = On
error_log = /var/log/php_errors.log

; Timezone (adjust to your needs)
date.timezone = Europe/Madrid

; OPcache (dev: validate timestamps)
opcache.enable=1
opcache.validate_timestamps=1
opcache.revalidate_freq=0

; Session (original uses $_SESSION heavily)
session.save_handler = files
session.save_path = /tmp

; Legacy charset notes (many parts still expect LATIN1/ISO-8859-15)
; Do not force UTF-8 globally yet — handle per connection in migration
```

## docker/php/xdebug.ini

```ini
zend_extension=xdebug.so
xdebug.mode=develop,debug,coverage
xdebug.start_with_request=yes
xdebug.client_host=host.docker.internal   # macOS Docker Desktop magic
xdebug.client_port=9003
xdebug.log=/tmp/xdebug.log
xdebug.discover_client_host=0
```

## .env.docker (Committed Template)

```
# Tuqan Docker environment
DB_NAME=qnova
DB_USER=qnova
DB_PASS=change_me_in_real_use
BASE_PATH=
```

## .gitignore Additions (Stage 1)

```
.env
.env.local
docker-compose.override.yml
*.log
/vendor/
```

## Common Docker Workflows (Copy-Paste)

```bash
# Fresh start (rebuild everything)
docker compose --env-file .env.docker down -v
docker compose --env-file .env.docker build --no-cache
docker compose --env-file .env.docker up -d

# Quick shell into PHP (for composer, phpunit, artisan-like tasks later)
docker compose exec app bash

# DB console
docker compose exec db psql -U qnova -d qnova

# Run composer inside (always)
docker compose exec app composer install

# View PHP + extensions
docker compose exec app php -v
docker compose exec app php -m | grep -E 'pdo|gd|gettext'

# Tail logs
docker compose logs -f --tail=100 app
```

## DB Initialization Strategy

- For dev: mount `scripts/qnovaintegraldumpvacio.sql` (or a stripped version) into `/docker-entrypoint-initdb.d/`.
- For speed: create a minimal "seed" SQL with only schema + 2-3 empresas/usuarios for login testing.
- Never commit real production dumps with real data.
- Always take volume snapshots before destructive migrations: `docker run --rm -v tuqan_tuqan_pgdata:/data alpine tar czf /backup/pg_$(date +%s).tgz /data`

## Troubleshooting (Common Gotchas from Audit)

- Permission denied on bind mounts → run `docker compose exec app chown -R www-data:www-data /var/www/html` or align uids.
- Gettext not finding .mo → ensure Locale/es_ES/ is present and locale-gen ran in Dockerfile.
- Old paths `/var/www/html/qnova` → either fix in Config or set nginx alias + DOCUMENT_ROOT properly.
- Mixed encoding errors → will be addressed in Stage 3.

**This file is the single source for the Docker environment.** Any deviation must be documented here with rationale.