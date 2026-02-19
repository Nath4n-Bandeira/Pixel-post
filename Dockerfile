# ------------------------------------------------------------
# Etapa 1: Build dos assets com Node
# ------------------------------------------------------------
FROM node:18 AS node-builder

WORKDIR /app

COPY package*.json ./
RUN npm install

COPY resources ./resources
COPY vite.config.js ./

RUN npm run build


# ------------------------------------------------------------
# Etapa 2: Build do Laravel
# ------------------------------------------------------------
FROM php:8.2-fpm AS php-builder

RUN apt-get update && apt-get install -y \
    zip unzip git curl libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring gd

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --optimize-autoloader

# Copia TODO o projeto agora
COPY . .

# Copia build do Node para o Laravel
COPY --from=node-builder /app/public/build public/build

RUN cp .env.example .env
RUN php artisan key:generate
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache


# ------------------------------------------------------------
# Container final: NGINX + PHP-FPM
# ------------------------------------------------------------
FROM nginx:stable

COPY --from=php-builder /var/www/html /var/www/html

COPY docker/nginx.conf /etc/nginx/conf.d/default.conf

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
