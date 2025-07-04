# CNAB Generator — Desafio Técnico

## ⚡ Requisitos do Sistema

Para rodar este projeto do zero, você precisa de:

✅ **Sistema operacional:**  
- Linux (Ubuntu 20.04+ recomendado)

✅ **Dependências do Sistema:**  
- [Docker Engine](https://docs.docker.com/engine/install/) >= 20.x  
- [Docker Compose](https://docs.docker.com/compose/install/) >= 2.x  
- [Node.js](https://nodejs.org/) >= 18.x (apenas para rodar `npm install`)  
- [npm](https://www.npmjs.com/) >= 9.x  
- [Composer](https://getcomposer.org/download/) >= 2.x  
- **Git** (para clonar o repositório)

## ⚡ Passo a passo para rodar localmente

```bash
# 1) Clone o repositório
git clone https://github.com/Guilherme-wasser/PHP_SENIOR_AKRK.git
cd PHP_SENIOR_AKRK

# 2) Instale dependências do backend Laravel
cd backend/laravel
composer install

# 3) Instale dependências do frontend Vue 3
cd ../../frontend/vue
npm install

# 4) Suba todos os containers em segundo plano
cd ../../backend/docker

Antes de subir os containers, ajuste os caminhos dos volumes no docker-compose.yml:

Execute pwd no terminal para copiar o caminho absoluto do seu projeto.

Atualize os volumes de cada serviço com esse caminho.

Exemplo:

volumes:
  - /SEU/CAMINHO/ABSOLUTO/backend/laravel:/var/www/html
  - /SEU/CAMINHO/ABSOLUTO/frontend/vue:/app



docker compose up -d

# 5) Rode as migrações e seeds (já popula tudo!)
docker compose exec app php artisan migrate:fresh --seed
docker compose exec app php artisan permission:cache-reset

Frontend (Vue): http://localhost:5173

API Laravel: http://localhost/api

'email' =>'test@example.com'
'name'     => 'Test User'
'password' => 'password'
'role'     => 'user'
'from' => 'database/seeders/DatabaseSeeder.php'

'email' => 'admin@demo.com'
'name'  => 'Admin',
'password' => 'secret'
'role'     => 'admin',
'from' =>  'database/seeders/AdminUserSeeder.php'

            