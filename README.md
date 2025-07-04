# 1) Clone o repositório
git clone https://github.com/Guilherme-wasser/PHP_SENIOR_AKRK.git
cd seu-projeto

# 2) Instale dependências do backend Laravel
cd backend/laravel
composer install

# 3) Instale dependências do frontend Vue 3
cd ../../frontend/vue
npm install

# 4) Suba todos os containers em segundo plano
cd ../../backend/docker
docker compose up -d

# 5) Rode as migrações e seeds (já popula tudo!)
docker compose exec app php artisan migrate:fresh --seed

