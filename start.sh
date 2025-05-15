#!/bin/bash
set -e

echo "🔥 Setting Firebase credentials permissions"
if [ -f "/etc/secrets/firebase_credentials.json" ]; then
    chmod 644 /etc/secrets/firebase_credentials.json
    chown www-data:www-data /etc/secrets/firebase_credentials.json
else
    echo "⚠️  /etc/secrets/firebase_credentials.json not found at runtime"
fi

echo "⚙️ Caching Laravel configs..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "🚀 Starting supervisord..."
exec /usr/bin/supervisord -c /etc/supervisord.conf
