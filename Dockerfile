FROM php:8.2-apache

# Enable mod_rewrite (optional but useful)
RUN a2enmod rewrite

# Set Apache root directory to the AmitieCafe folder
COPY ./AmitieCafe /var/www/html/

# Use pos.php as the homepage
RUN echo "DirectoryIndex pos.php" >> /etc/apache2/apache2.conf

# Set permissions (optional)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

EXPOSE 80
