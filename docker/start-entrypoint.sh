#!/usr/bin/env sh
set -e

role="${1:-app}"   # default=app

# ─── Espera o MySQL ─────────────────────────────────────────
wait-for.sh "$DB_HOST" "$DB_PORT"  # usa variáveis do .env

# primeira vez: cria .env e key
[ -f .env ] || cp .env.example .env
php artisan key:generate --force

# migrações (idempotente)
php artisan migrate --force

if [ "$role" = "queue" ]; then
  echo "🎧  Queue worker online…"
  exec php artisan queue:work --max-jobs=3 --tries=2
fi

echo "🚀  PHP-FPM online…"
exec php-fpm   # <- mantém o contêiner vivo
