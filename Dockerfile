# --- STAGE 1: Build Assets ---
FROM node:20-alpine AS node_builder
WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY . .
RUN npm run build

# --- STAGE 2: PHP Dependencies ---
FROM composer:2 AS composer_builder
WORKDIR /app

COPY --from=node_builder /app /app
RUN composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction

# --- STAGE 3: Runtime Laravel ---
FROM php:8.2-fpm-alpine

# Instalación de extensiones necesarias
RUN set -eux; \
    apk update; \
    apk add --no-cache --virtual .build-deps $PHPIZE_DEPS icu-dev sqlite-dev oniguruma-dev libzip-dev; \
    apk add --no-cache icu sqlite-libs git unzip shadow; \
    docker-php-ext-configure intl; \
    docker-php-ext-install -j"$(nproc)" pdo_sqlite bcmath intl mbstring; \
    docker-php-ext-enable opcache; \
    apk del .build-deps

# ------------------------------------------------------
# 🔥 CREAR USUARIO www-data COMPATIBLE COMO EN DEBIAN
# ------------------------------------------------------
RUN usermod -u 33 www-data && groupmod -g 33 www-data

WORKDIR /var/www/html

COPY --from=composer_builder /app /var/www/html

# ------------------------------------------------------
# 🔥 PERMISOS CORRECTOS ANTES DE CAMBIAR A www-data
# ------------------------------------------------------
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# ------------------------------------------------------
# ⚠️ NO HACER artisan migrate/key:generate EN EL DOCKERFILE
# Laravel necesita DB accesible → debes hacerlo al arrancar.
# ------------------------------------------------------

USER www-data

EXPOSE 9000

CMD ["php-fpm"]
