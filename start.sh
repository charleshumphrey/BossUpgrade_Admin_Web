#!/bin/bash
set -e

echo "🔥 Decoding Firebase credentials from environment..."

# Save Firebase credentials (you probably already do this)
echo "$FIREBASE_CREDENTIALS" > /tmp/firebase_credentials.json

# 🔁 Clear old Laravel caches
echo "🧹 Clearing Laravel caches..."
php artisan config:clear
php artisan cache:clear || true
php artisan route:clear
php artisan view:clear

# ✅ Rebuild caches (now using correct CACHE_DRIVER=file)
echo "⚙️ Rebuilding Laravel caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# ✅ Start Laravel via supervisord
echo "🚀 Starting supervisord..."
exec /usr/bin/supervisord -c /etc/supervisord.conf
