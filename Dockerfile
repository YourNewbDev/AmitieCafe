# Use PHP 8.2 with Apache
FROM php:8.2-apache

# Enable mod_rewrite if needed
RUN a2enmod rewrite

# Copy all files into Apache's default web root
COPY . /var/www/html/

# (Optional) Set pos.php as the default page if no index.php exists
RUN echo "DirectoryIndex pos.php" >> /etc/apache2/apache2.conf

# Set permissions (not strictly necessary but good practice)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Expose port 80
EXPOSE 80
