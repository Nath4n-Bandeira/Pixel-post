#!/usr/bin/env bash
set -e

echo "🚀 Starting Pixel Post application..."

# Ensure storage directories with correct permissions
echo "📁 Setting up directories..."
mkdir -p /var/www/html/storage/framework/{views,cache/data,sessions} /var/www/html/storage/{logs,app/public} /var/www/html/bootstrap/cache
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database || true
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database || true

# Ensure .env file exists
if [ ! -f /var/www/html/.env ]; then
  echo "📝 Creating .env from .env.example..."
  cp /var/www/html/.env.example /var/www/html/.env
fi

# Generate APP_KEY only if not already set
if [ -z "${APP_KEY}" ]; then
  echo "🔑 Generating APP_KEY..."
  php /var/www/html/artisan key:generate --force || echo "⚠️  Failed to generate APP_KEY"
else
  echo "✓ APP_KEY already set"
fi

# Create storage symlink for public file access
echo "🔗 Creating storage symlink..."
php /var/www/html/artisan storage:link --force || true

# Run database migrations
echo "🗄️  Running database migrations..."
php /var/www/html/artisan migrate --force 2>&1 || echo "⚠️  Warning: Migration completed with warnings"

# Cache configurations for performance
echo "⚡ Caching configurations..."
php /var/www/html/artisan config:cache || true
php /var/www/html/artisan route:cache || true
php /var/www/html/artisan view:cache || true

echo "✓ Application ready!"
echo "🌐 Starting Apache..."

# Start Apache (exec to keep PID 1)
exec apache2-foreground
