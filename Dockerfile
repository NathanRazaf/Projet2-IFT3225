FROM php:8.2-apache

# Install any PHP extensions you need
RUN docker-php-ext-install pdo pdo_mysql

# Copy your application code
COPY . /var/www/html/

# Set permissions
RUN chown -R www-data:www-data /var/www/html/