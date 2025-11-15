FROM php:8.2-apache

# Instalar extensiones necesarias de PHP
RUN docker-php-ext-install pdo pdo_mysql

# Habilitar mod_rewrite si lo necesitas
RUN a2enmod rewrite

# Copiar tu proyecto
COPY . /var/www/html/

# Dar permisos correctos
RUN chown -R www-data:www-data /var/www/html
