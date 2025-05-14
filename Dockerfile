# Start from PHP-FPM base image
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

# Copy the rest of the app
COPY . .

# Run composer scripts now that artisan exists
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

# Start both Nginx and PHP-FPM using supervisord
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
