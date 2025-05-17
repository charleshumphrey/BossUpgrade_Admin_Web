# ────── Stage 1: Node build ──────
FROM node:18-alpine AS node-builder

WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY . .
RUN npm run build


# ────── Stage 2: PHP-FPM and Laravel ──────
FROM php:8.2-fpm

# Install necessary PHP extensions and system packages
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev zip git unzip curl nginx supervisor \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Set working directory
WORKDIR /var/www

# Copy Laravel application
COPY . .

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copy Node build assets
COPY --from=node-builder /app/public/build ./public/build

# Laravel optimizations
RUN composer dump-autoload --optimize && \
    php artisan package:discover || true

# Set permissions
RUN chown -R www-data:www-data /var/www && \
    chmod -R 775 storage bootstrap/cache

# Copy configs
COPY render/nginx.conf /etc/nginx/sites-available/default
COPY render/supervisord.conf /etc/supervisord.conf

# Copy start script
COPY start.sh /var/www/start.sh
RUN chmod +x /var/www/start.sh

# Expose HTTP port
EXPOSE 80

# Start the application via supervisord
CMD ["/var/www/start.sh"]
