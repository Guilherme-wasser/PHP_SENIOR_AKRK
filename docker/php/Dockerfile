# docker/php/Dockerfile

# ─────────── Stage 1 – Install vendor packages ───────────
FROM composer:2.8 AS vendor
WORKDIR /app

# Copia composer.json e composer.lock da pasta laravel (RELATIVO ao context '..')
COPY ./laravel/composer.json ./laravel/composer.lock ./
RUN composer install --no-dev --prefer-dist --no-scripts --no-progress --no-interaction


# ─────────── Stage 2 – Final PHP-FPM image ───────────
FROM php:8.3-fpm-alpine

# Extensões PHP requeridas pelo Laravel
RUN apk add --no-cache \
      libpng-dev libjpeg-turbo-dev freetype-dev icu-dev oniguruma-dev \
  && docker-php-ext-install pdo_mysql bcmath intl gd \
  && apk del libpng-dev libjpeg-turbo-dev freetype-dev

# Composer global
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Dependências já instaladas na stage 1
COPY --from=vendor /app/vendor /var/www/html/vendor

# Copia todo o código Laravel
WORKDIR /var/www/html
COPY ./laravel .

# Permissões para storage & cache
RUN chown -R www-data:www-data storage bootstrap/cache

# Entrypoint que decide entre php-fpm ou queue
COPY ./docker/start-entrypoint.sh /usr/local/bin/start-container
RUN chmod +x /usr/local/bin/start-container

EXPOSE 9000
CMD ["start-container", "app"]
