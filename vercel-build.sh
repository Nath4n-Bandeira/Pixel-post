#!/usr/bin/env bash
set -e

# Install PHP dependencies
if command -v composer >/dev/null 2>&1; then
  composer install --no-dev --prefer-dist --optimize-autoloader
else
  echo "composer not found in PATH"
  exit 1
fi

# Install node dependencies and build assets
if command -v npm >/dev/null 2>&1; then
  npm ci
  npm run build
else
  echo "npm not found in PATH"
  exit 1
fi

# Generate app key and cache configs
php artisan key:generate --force || true
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo "Vercel build script completed."
