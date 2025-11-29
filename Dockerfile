# 1. Imagen base PHP con Apache
FROM php:8.2-apache

# 2. Instalar PDO MySQL y otras extensiones útiles
RUN docker-php-ext-install pdo pdo_mysql mysqli

# 3. Habilitar mod_rewrite (si usas rutas “amigables” en Apache)
RUN a2enmod rewrite

# 4. Copiar tu código al contenedor
COPY . /var/www/html/

# 5. Dar permisos a Apache (opcional según Railway)
RUN chown -R www-data:www-data /var/www/html

# 6. Puerto que escucha Apache
EXPOSE 80

# 7. Comando por defecto
CMD ["apache2-foreground"]
