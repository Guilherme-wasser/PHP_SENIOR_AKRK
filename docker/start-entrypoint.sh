#!/usr/bin/env sh
set -e

###############################################################################
# 1) Qual contêiner sou?  (app  ou  queue)
###############################################################################
role="${1:-app}"      # padrão = app

###############################################################################
# 2) Espera o MySQL ficar de pé
###############################################################################
wait-for.sh "$DB_HOST" "$DB_PORT"   # usa as variáveis do .env

###############################################################################
# 3) Somente **o serviço “app”** faz setup de ambiente e banco
###############################################################################
if [ "$role" = "app" ]; then
  # cria .env na primeira execução e gera APP_KEY
  [ -f .env ] || cp .env.example .env
  php artisan key:generate --force

  # migrações + seed (idempotente)
  php artisan migrate --force --seed
fi

###############################################################################
# 4) Dispara o worker ou o PHP-FPM
###############################################################################
if [ "$role" = "queue" ]; then
  echo "🎧  Queue worker online…"
  exec php artisan queue:work --tries=3 --timeout=90
fi

echo "🚀  PHP-FPM online…"
exec php-fpm                     # mantém o contêiner vivo
