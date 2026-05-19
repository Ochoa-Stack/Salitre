# Dockerfile optimizado para producción (< 100MB extras, dependencias mínimas)
FROM php:8.2-apache

# Habilitamos mod_rewrite para URLs amigables o control de acceso
RUN a2enmod rewrite

# Instalamos extensiones necesarias de PHP y limpiamos caché de apt
RUN apt-get update && apt-get install -y \
    libmariadb-dev \
    && docker-php-ext-install pdo_mysql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Copiamos el código fuente de la aplicación (excluyendo reglas del .dockerignore)
COPY . /var/www/html/

# Configuramos los permisos adecuados para que Apache pueda leer y escribir en uploads
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

EXPOSE 80
CMD ["apache2-foreground"]
