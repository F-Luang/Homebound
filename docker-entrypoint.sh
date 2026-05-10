#!/bin/bash
set -e
echo "Creating storage directories..."
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache
echo "Setting permissions..."
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache
echo "Running migrations..."
php artisan migrate --force
echo "Seeding if needed..."
php artisan tinker --execute="if(\App\Models\User::count() === 0) { \Artisan::call('db:seed', ['--force' => true]); echo 'Seeded!'; } else { echo 'Already seeded, skipping.'; }"
echo "Linking storage..."
php artisan storage:link || true
echo "Clearing config cache..."
php artisan config:clear
php artisan cache:clear
echo "Optimizing..."
php artisan optimize
echo "Starting Apache..."
exec "$@"
 