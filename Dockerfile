# ────── Stage 1: Node build ──────
FROM node:18-alpine AS node-builder

WORKDIR /app

# Install Node dependencies
COPY package*.json ./
RUN npm ci

# Copy source files for Vite build
COPY . .

# Run Vite production build
RUN npm run build


# ────── Stage 2: PHP-FPM and Laravel ──────
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev zip git unzip curl nginx supervisor \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Set the working directory
WORKDIR /var/www

# Install Composer
COPY composer.json composer.lock ./
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer && \
    composer install --no-dev --no-scripts --optimize-autoloader

# Copy app source (excluding node_modules etc. from .dockerignore)
COPY . .

# Copy built Vite assets from node build stage
COPY --from=node-builder /app/public/build ./public/build

# Run post-install Composer actions
RUN composer dump-autoload --optimize && \
    php artisan package:discover || true

# Fix permissions
RUN chown -R www-data:www-data /var/www && \
    chmod -R 775 storage bootstrap/cache

# Copy Nginx and Supervisor configs
COPY render/nginx.conf /etc/nginx/sites-available/default
COPY render/supervisord.conf /etc/supervisord.conf

# Expose HTTP port
EXPOSE 80

# Set correct permissions for Firebase credentials
RUN chmod 644 /etc/secrets/firebase_credentials.json && \
    chown www-data:www-data /etc/secrets/firebase_credentials.json

# Start services
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
