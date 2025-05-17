#!/bin/bash
set -e

echo "🔥 Decoding Firebase credentials from environment..."

# Optional: Save decoded credentials to a file if needed
if [ ! -z "$FIREBASE_CREDENTIALS_BASE64" ]; then
    echo "$FIREBASE_CREDENTIALS_BASE64" | base64 -d > /tmp/firebase_credentials.json
    export GOOGLE_APPLICATION_CREDENTIALS=/tmp/firebase_credentials.json
    echo "✅ Firebase credentials decoded and stored at /tmp/firebase_credentials.json"
else
    echo "⚠️ No FIREBASE_CREDENTIALS_BASE64 provided!"
fi

echo "🧹 Clearing Laravel caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo "⚙️ Caching Laravel configs..."
php artisan route:cache
php artisan view:cache

echo "🚀 Starting supervisord..."
exec /usr/bin/supervisord -c /etc/supervisord.conf
