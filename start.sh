#!/bin/bash
set -e

echo "🔥 Decoding Firebase credentials from environment"

# CLEAR ALL CACHES BEFORE CREATING THEM
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo "⚙️ Caching Laravel configs..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "🚀 Starting supervisord..."
exec /usr/bin/supervisord -c /etc/supervisord.conf
