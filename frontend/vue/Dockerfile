# Use uma imagem Node.js leve
FROM node:20-alpine

# Cria o diretório de trabalho
WORKDIR /app

# Copia package.json e package-lock.json para instalar dependências
COPY package*.json ./

# Instala dependências
RUN npm install

# Copia o resto do código
COPY . .

# Expõe a porta padrão do Vite
EXPOSE 5173

# Define o comando padrão para dev
CMD ["npm", "run", "dev"]
