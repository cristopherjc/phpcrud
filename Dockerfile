FROM php:8.2-apache

# Instala PDO MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Habilita mod_rewrite
RUN a2enmod rewrite

# Copia tu proyecto al contenedor
COPY . /var/www/html/

# Permisos correctos
RUN chown -R www-data:www-data /var/www/html

# Mantener Apache corriendo en primer plano
CMD ["apache2-foreground"]
