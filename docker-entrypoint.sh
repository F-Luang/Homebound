#!/bin/bash
set -e

echo "Running migrations..."
php artisan migrate --force

echo "Linking storage..."
php artisan storage:link || true

echo "Optimizing..."
php artisan optimize

echo "Starting Apache..."
exec "$@"