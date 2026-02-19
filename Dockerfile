# ============================
# 1) NODE BUILDER – Build do Vite/Tailwind
# ============================
FROM node:18 AS node-builder

WORKDIR /app

# Copiar configs essenciais
COPY package*.json ./
COPY tailwind.config.js vite.config.js ./

# Criar postcss.config.js se não existir
RUN if [ ! -f postcss.config.js ]; then \
    echo "module.exports = { plugins: { tailwindcss: {}, autoprefixer: {} } }" > postcss.config.js; \
    fi

# Instalar dependências
RUN npm install

# Copiar assets e recursos
COPY resources ./resources
COPY public ./public

# Build do Vite
RUN npm run build

# ============================
# 2) PHP RUNNER – Laravel + Nginx
# ============================
FROM php:8.2-fpm

# Instalar extensões necessárias do Laravel
RUN apt-get update && apt-get install -y \
    zip unzip git libpng-dev libonig-dev libxml2-dev libzip-dev \
    nginx \
    && docker-php-ext-install pdo pdo_mysql gd zip

WORKDIR /var/www/html

# Copiar arquivos do Laravel
COPY . .

# Copiar build gerado pelo Node
COPY --from=node-builder /app/public/build ./public/build
COPY --from=node-builder /app/resources/css ./resources/css
COPY --from=node-builder /app/resources/js ./resources/js

# Instalar composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN composer install --no-dev --optimize-autoloader

# Ajustar permissões
RUN chown -R www-data:www-data storage bootstrap/cache public
RUN chmod -R 755 storage bootstrap/cache public

# Configurar nginx
COPY nginx.conf /etc/nginx/nginx.conf

EXPOSE 80

# Script de entrada para iniciar PHP-FPM e Nginx
RUN echo '#!/bin/sh\n\
php-fpm -D\n\
nginx -g "daemon off;"' > /start.sh && chmod +x /start.sh

CMD ["/start.sh"]