FROM php:8.2-apache

RUN a2enmod rewrite

RUN docker-php-ext-install pdo pdo_mysql

COPY . /var/www/html/

RUN echo "DirectoryIndex pos.php" >> /etc/apache2/apache2.conf

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

EXPOSE 80
