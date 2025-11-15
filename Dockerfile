FROM php:8.2-apache

# Instala PDO MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Habilita mod_rewrite (opcional)
RUN a2enmod rewrite

# Copia tu proyecto dentro del contenedor
COPY . /var/www/html/

# Permisos correctos
RUN chown -R www-data:www-data /var/www/html
