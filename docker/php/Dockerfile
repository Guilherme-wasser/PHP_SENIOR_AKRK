# Usa imagem base oficial
FROM php:8.3-fpm-alpine

# Instala extensões necessárias para Laravel + CNAB
RUN docker-php-ext-install pdo_mysql bcmath

# Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Define diretório de trabalho
WORKDIR /var/www/html

# Copia arquivos do projeto (opcional, se quiser buildar tudo)
# COPY . .

# Garante permissões corretas (opcional)
# RUN chown -R www-data:www-data /var/www/html

# Expõe porta interna (não é obrigatório pro FPM)
EXPOSE 9000
