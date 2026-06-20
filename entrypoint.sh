#!/bin/sh

echo "Running database migrations..."
php artisan migrate --force

echo "Starting Apache web server..."
exec apache2-foreground
