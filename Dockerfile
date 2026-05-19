FROM php:8.2-apache

# 1. Instalar extensiones necesarias
RUN docker-php-ext-install pdo pdo_mysql

# 2. LIMPIEZA AGRESIVA DE MPMs (El truco para evitar el crash)
# Borramos los archivos de configuración de los MPMs que NO queremos
RUN rm -f /etc/apache2/mods-enabled/mpm_event.* \
    && rm -f /etc/apache2/mods-enabled/mpm_worker.* \
    && rm -f /etc/apache2/mods-available/mpm_event.* \
    && rm -f /etc/apache2/mods-available/mpm_worker.*

# Aseguramos que solo exista y esté activo el prefork (necesario para PHP)
RUN a2enmod mpm_prefork rewrite

# 3. Configurar puerto dinámico para Railway
ENV PORT=8080
ENV APACHE_DOCUMENT_ROOT=/var/www/html

# Actualizar el DocumentRoot en la config de Apache
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf

# Cambiar el puerto de escucha de 80 a la variable de entorno ${PORT}
RUN sed -ri -e 's!Listen 80!Listen ${PORT}!g' /etc/apache2/ports.conf
RUN sed -ri -e 's!<VirtualHost \*:80!<VirtualHost *:${PORT}>!g' /etc/apache2/sites-available/*.conf

# 4. Permisos para uploads
WORKDIR /var/www/html
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && mkdir -p /var/www/html/assets/img/client/espacios \
    && chmod -R 777 /var/www/html/assets/img/client/espacios

# 5. Copiar código
COPY . /var/www/html/

# 6. Exponer puerto y arrancar
EXPOSE ${PORT}
CMD ["apache2-foreground"]
