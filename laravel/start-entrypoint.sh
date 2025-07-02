#!/usr/bin/env sh
set -e

role="${1:-app}"

# Espera banco
wait-for.sh "$DB_HOST" "$DB_PORT"

# .env (primeiro uso do volume)
[ -f .env ] || cp .env.example .env

# Garante que o autoloader existe (bind-mount deve trazer vendor/)
if [ ! -f vendor/autoload.php ]; then
  echo "❌  vendor/ não encontrado no volume. Rode 'composer install' no host."
  exit 1
fi

# ⚡️ NOVO: Garante permissões corretas em cada start
chown -R www-data:www-data storage bootstrap/cache
chmod -R ug+rwX storage bootstrap/cache

# Só a app faz migrations/seed
if [ "$role" = "app" ]; then
  php artisan key:generate --force
  php artisan migrate --force --seed
  [ ! -L public/storage ] && php artisan storage:link
fi

if [ "$role" = "queue" ]; then
  echo "🎧  Queue worker online…"
  exec php artisan queue:work --tries=3 --timeout=90
fi

echo "🚀  PHP-FPM online…"
exec php-fpm
