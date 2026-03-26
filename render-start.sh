#!/usr/bin/env bash
set -e

echo "Starting Pixel Post application..."

# Ensure all storage directories exist with correct permissions
mkdir -p /var/www/html/storage/framework/{views,cache/data,sessions} /var/www/html/storage/logs /var/www/html/storage/app/public /var/www/html/bootstrap/cache
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database || true
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database || true

# Generate app key if missing
if [ -z "${APP_KEY:-}" ]; then
  echo "Generating APP_KEY..."
  php artisan key:generate --force || true
fi

# Create storage symlink for public file access
echo "Creating storage symlink..."
php artisan storage:link || true

# Run migrations
echo "Running database migrations..."
php artisan migrate --force || true

# Cache configs for performance
echo "Caching configurations..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo "Starting Apache..."
# Start Apache (exec to keep PID 1)
exec apache2-foreground
