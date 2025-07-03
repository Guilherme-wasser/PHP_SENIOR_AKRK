# ───────────────────────── Base ─────────────────────────
FROM php:8.3-fpm-alpine

# Pacotes de build (removidos depois) e runtime
RUN set -ex \
  && apk add --no-cache --virtual .build-deps \
       icu-dev oniguruma-dev libpng-dev freetype-dev libjpeg-turbo-dev \
       zlib-dev libzip-dev libxml2-dev libxslt-dev \
       sqlite-dev pkgconf \
       git curl zip unzip bash make g++ gcc autoconf \
  && apk add --no-cache \
       icu-libs oniguruma libpng freetype libjpeg-turbo \
       zlib libzip libxml2 libxslt sqlite-libs \
  \
  # ─── Extensões nativas ───
  && docker-php-ext-configure intl \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install -j"$(nproc)" \
       bcmath pdo_mysql pdo_sqlite zip intl gd xsl \
  \
  # ─── Redis (PECL) ───
  && pecl install redis \
  && docker-php-ext-enable redis \
  \
  # ─── Limpeza ───
  && apk del .build-deps

# ───────────────────────── Composer ─────────────────────
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer


COPY ./start-entrypoint.sh /usr/local/bin/start-container
COPY ./wait-for.sh        /usr/local/bin/wait-for.sh

RUN chmod +x /usr/local/bin/start-container /usr/local/bin/wait-for.sh

# ───────────────────────── App ──────────────────────────
WORKDIR /var/www/html

# 1) Instala as dependências PHP sem scripts
COPY composer.json composer.lock ./
RUN composer install \
      --no-interaction \
      --prefer-dist \
      --optimize-autoloader \
      --no-dev \
      --no-scripts     

# 2) Agora copia todo o código da aplicação
COPY . .

# 3) (Opcional) Gera autoload otimizado outra vez – agora com o código presente
RUN composer dump-autoload --optimize --apcu

# Permissões — ajuste UID/GID se necessário
RUN chown -R www-data:www-data /var/www/html

EXPOSE 9000
CMD ["php-fpm"]
