#!/usr/bin/env bash
echo "Running composer"
composer global require hirak/prestissimo
composer install --no-dev --working-dir=/var/www/html

echo "generating application key..."
php artisan key:generate --show

echo "copying .env.example to .env"
cp .env.example .env

echo "Running migrations..."
php artisan migrate --force

echo "Running seeders..."
php artisan db:seed