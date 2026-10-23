#!/usr/bin/env bash
# Exit on error
set -e

echo "--- Installing Composer Dependencies ---"
composer install --no-dev --optimize-autoloader --no-interaction

echo "--- Installing NPM & Compiling Assets ---"
npm install
npm run build

echo "--- Preparing Laravel Application ---"
php artisan config:clear
php artisan route:clear
php artisan view:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "--- Build Completed Successfully! ---"
