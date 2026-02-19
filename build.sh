#!/usr/bin/env bash
set -o errexit

composer install --no-dev
php artisan migrate --force
php artisan config:cache
