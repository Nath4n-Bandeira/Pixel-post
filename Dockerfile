# ============================
# 1) NODE BUILDER – Build do Vite/Tailwind
# ============================
FROM node:18 AS node-builder

WORKDIR /build

# Instalar dependências de build (necessário para Tailwind v4 com oxide)
RUN apt-get update && apt-get install -y --no-install-recommends \
    build-essential python3 pkg-config \
    && rm -rf /var/lib/apt/lists/*

# Copiar configs essenciais
COPY package*.json ./
COPY tailwind.config.js vite.config.js ./

# Criar postcss.config.js se não existir
RUN if [ ! -f postcss.config.js ]; then \
    echo "module.exports = { plugins: { tailwindcss: {}, autoprefixer: {} } }" > postcss.config.js; \
    fi

# Ajustar variáveis de ambiente para linking nativo
ENV LD_LIBRARY_PATH=/usr/local/lib
ENV LDFLAGS="-Wl,--rpath,/usr/local/lib"

# Instalar dependências
RUN npm ci

# Copiar assets e recursos
COPY resources ./resources
COPY public ./public

# Build do Vite
RUN npm run build

# ============================
# 2) COMPOSER BUILDER – Instalar dependências PHP
# ============================
FROM composer:2 AS composer-builder

WORKDIR /build

# Copiar composer files
COPY composer.json composer.lock* ./

# Instalar dependências sem dev
RUN composer install --no-interaction --no-dev --optimize-autoloader --no-scripts

# ============================
# 3) PHP RUNNER – Laravel em Produção
# ============================
FROM php:8.2-apache

# Atualizar pacotes e instalar extensões necessárias do Laravel
RUN apt-get update && apt-get install -y --no-install-recommends \
    zip unzip git libpng-dev libonig-dev libxml2-dev libzip-dev curl \
    && docker-php-ext-install pdo pdo_mysql gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Configurar PHP para produção
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Aumentar limites para produção
RUN echo "memory_limit = 256M" >> "$PHP_INI_DIR/php.ini" && \
    echo "max_execution_time = 60" >> "$PHP_INI_DIR/php.ini" && \
    echo "post_max_size = 50M" >> "$PHP_INI_DIR/php.ini" && \
    echo "upload_max_filesize = 50M" >> "$PHP_INI_DIR/php.ini"

WORKDIR /var/www/html

# Configurar Apache
RUN a2enmod rewrite headers
RUN echo "<Directory /var/www/html/public>" > /etc/apache2/sites-available/000-default.conf && \
    echo "    Options -MultiViews" >> /etc/apache2/sites-available/000-default.conf && \
    echo "    RewriteEngine On" >> /etc/apache2/sites-available/000-default.conf && \
    echo "    RewriteCond %{REQUEST_FILENAME} !-f" >> /etc/apache2/sites-available/000-default.conf && \
    echo "    RewriteRule ^ index.php [QSA,L]" >> /etc/apache2/sites-available/000-default.conf && \
    echo "    <IfModule mod_fcgid.c>" >> /etc/apache2/sites-available/000-default.conf && \
    echo "        SetHandler application/x-httpd-php" >> /etc/apache2/sites-available/000-default.conf && \
    echo "    </IfModule>" >> /etc/apache2/sites-available/000-default.conf && \
    echo "</Directory>" >> /etc/apache2/sites-available/000-default.conf && \
    echo "DocumentRoot /var/www/html/public" >> /etc/apache2/sites-available/000-default.conf

# Copiar todos os arquivos do Laravel
COPY --chown=www-data:www-data . .

# Copiar vendor do composer-builder
COPY --from=composer-builder --chown=www-data:www-data /build/vendor ./vendor

# Copiar build gerado pelo Node
COPY --from=node-builder --chown=www-data:www-data /build/public/build ./public/build

# Criar diretórios necessários
RUN mkdir -p storage/logs bootstrap/cache && \
    chown -R www-data:www-data storage bootstrap/cache public && \
    chmod -R 755 storage bootstrap/cache public && \
    chmod -R 775 storage bootstrap/cache

# Gerar chave da aplicação durante o build
RUN php artisan key:generate --force 2>/dev/null || true

# Limpeza de cache
RUN php artisan config:cache --force 2>/dev/null || true && \
    php artisan route:cache --force 2>/dev/null || true && \
    php artisan view:cache --force 2>/dev/null || true

# Copiar script de entrypoint
COPY --chown=www-data:www-data entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 80

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=40s --retries=3 \
    CMD curl -f http://localhost/ || exit 1

ENTRYPOINT ["/entrypoint.sh"]
CMD ["apache2-foreground"]