# Base image
FROM php:7.4-apache

# Instalación de extensiones necesarias para MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Habilitación de módulos de Apache
RUN a2enmod rewrite

# Configuración de PHP
COPY php.ini /usr/local/etc/php/

# Configuración del directorio src
RUN mkdir -p /var/www/html/src && \
    chmod -R 755 /var/www/html/src && \
    chown -R www-data:www-data /var/www/html/src

# Copia del archivo index.php inicial
COPY ./src/index.php /var/www/html/index.php
