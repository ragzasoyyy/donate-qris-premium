FROM php:8.1-apache

# Install extensions
RUN docker-php-ext-install pdo pdo_mysql curl

# Copy website files to Apache root
COPY . /var/www/html/

# Set folder permissions
RUN chown -R www-data:www-data /var/www/html/

EXPOSE 80
