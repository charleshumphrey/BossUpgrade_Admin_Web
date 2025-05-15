# ────── Stage 1: Node build ──────
FROM node:18-alpine AS node-builder

WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY . .
RUN npm run build

# ────── Stage 2: PHP-FPM and Laravel ──────
FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev zip git unzip curl nginx supervisor \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

WORKDIR /var/www

# Install composer dependencies
COPY composer.json composer.lock ./
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer && \
    composer install --no-dev --no-scripts --optimize-autoloader

# Copy all files and built assets
COPY . .
COPY --from=node-builder /app/public/build ./public/build

RUN composer dump-autoload --optimize && \
    php artisan package:discover || true

# Set Laravel permissions
RUN chown -R www-data:www-data /var/www && \
    chmod -R 775 storage bootstrap/cache

# Copy configs
COPY render/nginx.conf /etc/nginx/sites-available/default
COPY render/supervisord.conf /etc/supervisord.conf

# Copy and prepare start script
COPY start.sh /var/www/start.sh
RUN chmod +x /var/www/start.sh

EXPOSE 80

CMD ["/var/www/start.sh"]
