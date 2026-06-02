# syntax=docker/dockerfile:1.7
FROM php:8.3-fpm-bookworm AS base

ENV DEBIAN_FRONTEND=noninteractive

# System packages + PHP extensions required by legacy Tuqan
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
        postgresql-client \
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

# Locale for gettext (Spanish + English scaffolding for Stage 8.3)
RUN sed -i '/es_ES.UTF-8/s/^# //g' /etc/locale.gen \
    && sed -i '/en_US.UTF-8/s/^# //g' /etc/locale.gen \
    && locale-gen es_ES.UTF-8 en_US.UTF-8 \
    && update-locale LANG=es_ES.UTF-8

# Provide sensible defaults (Spanish is the current runtime default)
ENV LANG=es_ES.UTF-8
ENV LC_ALL=es_ES.UTF-8
ENV LC_MESSAGES=es_ES.UTF-8

WORKDIR /var/www/html

# Install a small entrypoint that ensures .mo files are always up-to-date
# from .po sources on every container start (very useful for dev + translations).
# We place this in the base stage so both dev and prod images inherit the
# ENTRYPOINT + the guarantee that locales are fresh.
COPY docker/entrypoint.sh /usr/local/bin/tuqan-entrypoint.sh
COPY scripts/compile-locales.sh /usr/local/bin/compile-locales.sh
RUN chmod +x /usr/local/bin/tuqan-entrypoint.sh /usr/local/bin/compile-locales.sh

ENTRYPOINT ["/usr/local/bin/tuqan-entrypoint.sh"]
CMD ["php-fpm"]

# -------------------------
# Dev image (with Xdebug)
# -------------------------
FROM base AS dev

RUN pecl install xdebug-3.3.2 \
    && docker-php-ext-enable xdebug

# Default dev php.ini overrides (also mounted)
COPY docker/php/php.ini /usr/local/etc/php/conf.d/99-tuqan.ini
COPY docker/php/xdebug.ini /usr/local/etc/php/conf.d/99-xdebug.ini

# Non-root for bind mount friendliness on macOS
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