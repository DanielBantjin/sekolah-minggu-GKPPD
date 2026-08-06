#!/usr/bin/env bash

set -e

composer install --no-dev --optimize-autoloader

npm install

npm run build

php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true