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
php artisan event:cache
php artisan filament:optimize
php artisan icons:cache

echo "Linking storage..."
php artisan storage:link

echo "Starting queue worker..."
# Run queue worker in background
php artisan queue:work --daemon --tries=3 --timeout=300 &

echo "Starting PHP-FPM..."
php-fpm -y /app/php-fpm.conf -c /app/php.ini -D

echo "Configuring Nginx..."
sed -i "s/PORT_PLACEHOLDER/$PORT/g" /app/nginx.conf

echo "Starting Nginx..."
nginx -c /app/nginx.conf
