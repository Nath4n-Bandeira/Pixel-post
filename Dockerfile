# Multi-stage Dockerfile for Laravel app on Render

# 1) Composer stage: install PHP dependencies
FROM composer:2 as composer_builder
WORKDIR /app
COPY composer.json composer.lock* ./
COPY ./app ./app
COPY ./bootstrap ./bootstrap
COPY ./config ./config
COPY ./database ./database
COPY ./resources ./resources
COPY ./routes ./routes
COPY artisan ./
RUN composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction

# 2) Node stage: build front-end assets
FROM node:18 as node_builder
WORKDIR /app
COPY package.json package-lock.json* ./
COPY vite.config.js tailwind.config.js ./
COPY resources ./resources
RUN npm ci
RUN npm run build

# 3) Final image: PHP + Apache
FROM php:8.2-apache
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git zlib1g-dev libpng-dev libonig-dev libjpeg-dev libxml2-dev && \
    docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd && \
    rm -rf /var/lib/apt/lists/*

# Enable Apache rewrite module
RUN a2enmod rewrite

# Copy application code from composer stage
WORKDIR /var/www/html
COPY --from=composer_builder /app /var/www/html

# Copy built assets from node stage
COPY --from=node_builder /app/public /var/www/html/public

# Ensure correct permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache || true

# Serve from public directory
RUN sed -ri 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

EXPOSE 80
CMD ["apache2-foreground"]
