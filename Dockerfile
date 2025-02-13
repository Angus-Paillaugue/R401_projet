# Use official PHP image with Apache
FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy composer files first (improves build caching)
COPY composer.json composer.lock ./

# Install dependencies inside the container
RUN composer install --no-dev --no-scripts --optimize-autoloader

# Copy the rest of the app (EXCLUDE migrate.sql)
COPY . /var/www/html

# Change Apache document root to /var/www/html/app/vue
RUN sed -i 's|/var/www/html|/var/www/html/app|' /etc/apache2/sites-available/000-default.conf
RUN sed -i 's|/var/www/html|/var/www/html/app|' /etc/apache2/apache2.conf

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
