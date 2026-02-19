# ------------------------------
# Build dos assets com Node
# ------------------------------
FROM node:18 AS node-builder

WORKDIR /app

COPY package*.json ./
RUN npm install

COPY . .
RUN npm run build


# ------------------------------
# Build do Laravel / PHP
# ------------------------------
FROM php:8.2-fpm AS php-builder

# Instala dependências do sistema
RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libxml2-dev zip unzip curl git \
    && docker-php-ext-install pdo pdo_mysql mbstring gd

# Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer*.json ./
RUN composer install --no-dev --no-interaction --optimize-autoloader

COPY . .

# Copia assets que foram construídos no node-builder
COPY --from=node-builder /app/public/build public/build

# Gera APP_KEY
RUN cp .env.example .env && php artisan key:generate

# Otimizações de produção
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache


# ------------------------------
# Container final (Nginx + PHP-FPM)
# ------------------------------
FROM nginx:stable

COPY --from=php-builder /app /var/www/html

# Copia configuração do nginx
COPY docker/nginx.conf /etc/nginx/conf.d/default.conf

# Permissões
RUN chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
