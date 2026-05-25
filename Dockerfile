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