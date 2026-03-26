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
COPY ./public ./public
COPY artisan ./
# Ensure storage and cache directories exist for later chown/permissions
RUN mkdir -p public storage bootstrap/cache database || true
# Create sqlite file for environments using sqlite
RUN touch database/database.sqlite && chmod 664 database/database.sqlite || true
RUN composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction

# 2) Node stage: build front-end assets
FROM node:20 as node_builder
WORKDIR /app
COPY package.json package-lock.json* ./
COPY vite.config.js tailwind.config.js ./
COPY resources ./resources
RUN npm ci
RUN npm run build

# 3) Final image: PHP + Apache
FROM php:8.4-apache
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git zlib1g-dev libpng-dev libonig-dev libjpeg-dev libxml2-dev libsqlite3-dev sqlite3 libpq-dev postgresql-client && \
    docker-php-ext-install pdo pdo_pgsql pdo_sqlite mbstring exif pcntl bcmath gd zip && \
    docker-php-ext-enable pdo_pgsql && \
    rm -rf /var/lib/apt/lists/*

# Enable Apache modules
RUN a2enmod rewrite headers env

# Set working directory
WORKDIR /var/www/html

# Copy all application code from composer stage
COPY --from=composer_builder /app /var/www/html

# Copy built assets from node stage
COPY --from=node_builder /app/public/js /var/www/html/public/js
COPY --from=node_builder /app/public/css /var/www/html/public/css

# Copy configuration and environment files
COPY .env.example /var/www/html/.env
COPY render-start.sh /usr/local/bin/render-start.sh
RUN chmod +x /usr/local/bin/render-start.sh

# Ensure necessary runtime directories exist and have correct permissions
RUN mkdir -p /var/www/html/storage/framework/{views,cache/data,sessions} && \
    mkdir -p /var/www/html/storage/{logs,app/public} && \
    mkdir -p /var/www/html/bootstrap/cache && \
    chown -R www-data:www-data /var/www/html && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

# Configure Apache
RUN sed -ri 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf && \
    echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Set proper permissions for www-data
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
ENTRYPOINT ["/usr/local/bin/render-start.sh"]
