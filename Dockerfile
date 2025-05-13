# Use the official PHP 8.2 FPM image
FROM php:8.2-fpm

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    git \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# Set working directory
WORKDIR /var/www

# Copy composer files early to speed up rebuilds
COPY composer.json composer.lock ./

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP dependencies without scripts (artisan not available yet)
RUN composer install --no-dev --no-scripts --optimize-autoloader

# Copy the rest of the application
COPY . .

# Run composer scripts now that artisan is available
RUN composer dump-autoload --optimize && \
    php artisan package:discover --ansi || true

# Fix permissions if needed
RUN chown -R www-data:www-data /var/www && \
    chmod -R 775 storage bootstrap/cache

# Expose PHP-FPM port
EXPOSE 9000

# Start PHP-FPM server
CMD ["php-fpm"]
