# Use PHP 8.2 with Apache
FROM php:8.2-apache

# Enable mod_rewrite
RUN a2enmod rewrite

# Install PDO and MySQL extension
RUN docker-php-ext-install pdo pdo_mysql

# Copy your app folder into Apache's web root
COPY ./AmitieCafe/ /var/www/html/

# Set pos.php as the default home page
RUN echo "DirectoryIndex pos.php" >> /etc/apache2/apache2.conf

# Set permissions (optional)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

EXPOSE 80
