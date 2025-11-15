FROM php:8.2-apache

# Instalar extensiones necesarias
RUN docker-php-ext-install pdo pdo_mysql

# Habilitar mod_rewrite si lo necesitas
RUN a2enmod rewrite

# Copiar tu proyecto
COPY . /var/www/html/

# Permisos
RUN chown -R www-data:www-data /var/www/html

# Comando de arranque
CMD ["apache2-foreground"]
