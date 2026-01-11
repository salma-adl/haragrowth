#!/bin/bash
set -e

echo "Running migrations..."
php artisan migrate --force

echo "Running seeders..."
php artisan db:seed --force

echo "Caching config..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Linking storage..."
php artisan storage:link

echo "Starting queue worker..."
# Run queue worker in background
php artisan queue:work --daemon --tries=3 --timeout=300 &

echo "Starting server..."
php artisan serve --host=0.0.0.0 --port=$PORT
