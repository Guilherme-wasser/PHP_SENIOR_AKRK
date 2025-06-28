# ---------- Stage 1: dependências ----------
    FROM composer:2.8 AS vendor
    WORKDIR /app
    
    # Copia apenas os arquivos de dependência
    COPY ../../lavarel/composer.json ../../lavarel/composer.lock ./
    RUN composer install --no-dev --prefer-dist --no-scripts --no-progress --no-interaction
    
    # ---------- Stage 2: aplicação ----------
    FROM php:8.3-fpm-alpine
    
    # Extensões que o Laravel precisa
    RUN apk add --no-cache \
          libpng-dev libjpeg-turbo-dev freetype-dev icu-dev oniguruma-dev \
        && docker-php-ext-install pdo_mysql bcmath intl gd \
        && apk del libpng-dev libjpeg-turbo-dev freetype-dev
    
    # Composer (copiado da imagem acima)
    COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
    
    # Copia o vendor já pronto
    COPY --from=vendor /app/vendor /var/www/html/vendor
    
    # Copia código da aplicação
    WORKDIR /var/www/html
    COPY ../../lavarel .
    
    # Permissões (ajuste se preferir a user id distinta)
    RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
    
    # Entrypoint customizado
    COPY start-container.sh /usr/local/bin/start-container
    RUN chmod +x /usr/local/bin/start-container
    
    EXPOSE 9000
    CMD ["start-container", "app"]
    