#!/usr/bin/env sh
set -e

role="${1:-app}"   # default é app

# 1ª vez? cria .env e key
if [ ! -f ".env" ]; then
  cp .env.example .env
  php artisan key:generate
fi

# Ajusta permissões (caso esteja rodando no host com UID≠82)
chown -R www-data:www-data storage bootstrap/cache

# Executa migrações (idempotente)
php artisan migrate --force

if [ "$role" = "queue" ]; then
  echo "Starting queue worker..."
  exec php artisan queue:work --max-jobs=3 --tries=2
fi

# role = app
echo "Starting php-fpm..."
exec php-fpm
