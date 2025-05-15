#!/bin/bash
set -e

echo "🔥 Preparing Firebase credentials"

if [ -f "/etc/secrets/firebase_credentials.json" ]; then
    echo "⚠️ Copying firebase_credentials.json to writable /tmp directory"
    cp /etc/secrets/firebase_credentials.json /tmp/firebase_credentials.json
    chmod 600 /tmp/firebase_credentials.json
else
    echo "⚠️  /etc/secrets/firebase_credentials.json not found at runtime"
fi

echo "Checking /tmp directory..."
ls -ld /tmp

echo "⚙️ Caching Laravel configs..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "🚀 Starting supervisord..."
exec /usr/bin/supervisord -c /etc/supervisord.conf
