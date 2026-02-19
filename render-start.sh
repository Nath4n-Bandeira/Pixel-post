#!/usr/bin/env bash
set -e

# If using sqlite, ensure the DB file exists and is writable
if [ "${DB_CONNECTION:-sqlite}" = "sqlite" ]; then
  DB_FILE=/var/www/html/database/database.sqlite
  mkdir -p "$(dirname "$DB_FILE")"
  if [ ! -f "$DB_FILE" ]; then
    touch "$DB_FILE"
  fi
  chown www-data:www-data "$DB_FILE" || true
  chmod 664 "$DB_FILE" || true
  # Import any local SQL dumps into sqlite
  for sql in /var/www/html/database/*.sql; do
    if [ -f "$sql" ]; then
      echo "Importing $sql into $DB_FILE"
      sqlite3 "$DB_FILE" < "$sql" || echo "Warning: failed to import $sql"
    fi
  done
  # Use file-based sessions for SQLite to avoid 419 CSRF errors
  export SESSION_DRIVER=file
  echo "Using file-based sessions for SQLite environment"
fi

# Ensure all storage directories exist with correct permissions
mkdir -p /var/www/html/storage/framework/{views,cache/data,sessions} /var/www/html/storage/logs /var/www/html/storage/app/public /var/www/html/bootstrap/cache
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database || true
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database || true

# Generate app key if missing
if [ -z "${APP_KEY:-}" ]; then
  php artisan key:generate --force || true
fi

# Create storage symlink for public file access
php artisan storage:link || true

# Run migrations
php artisan migrate --force || true

# Cache configs for performance
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Start Apache (exec to keep PID 1)
exec apache2-foreground
