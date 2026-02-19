# ============================
# 1) NODE BUILDER – Build do Vite/Tailwind
# ============================
FROM node:18 AS node-builder

WORKDIR /app

# Copiar configs essenciais
COPY package*.json ./
COPY tailwind.config.js postcss.config.js vite.config.js ./

# Instalar dependências
RUN npm install

# Copiar apenas os assets
COPY resources ./resources

# Build do Vite
RUN npm run build



# ============================
# 2) PHP RUNNER – Laravel + Nginx
# ============================
FROM php:8.2-fpm

# Instalar extensões necessárias do Laravel
RUN apt-get update && apt-get install -y \
    zip unzip git libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo pdo_mysql gd zip

WORKDIR /var/www/html

# Copiar arquivos do Laravel
COPY . .

# Copiar build gerado pelo Node para public/
COPY --from=node-builder /app/resources ./resources
COPY --from=node-builder /app/public/build ./public/build

# Instalar composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN composer install --no-dev --optimize-autoloader

# Ajustar permissões do Laravel teste
RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]
