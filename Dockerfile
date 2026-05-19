FROM php:8.2-apache

# Instalar extensiones necesarias
RUN docker-php-ext-install pdo pdo_mysql

# Configurar puerto dinámico para Railway
ENV PORT=8080
ENV APACHE_DOCUMENT_ROOT=/var/www/html

# Deshabilitar MPMs conflictivos y habilitar solo mpm_prefork
RUN a2dismod mpm_event && \
    a2dismod mpm_worker && \
    a2enmod mpm_prefork && \
    a2enmod rewrite

# Configurar Apache para usar el puerto de Railway
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's!Listen 80!Listen ${PORT}!g' /etc/apache2/ports.conf && \
    sed -ri -e 's!<VirtualHost \*:80!<VirtualHost *:${PORT}>!g' /etc/apache2/sites-available/*.conf

# Configurar permisos
WORKDIR /var/www/html
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html && \
    mkdir -p /var/www/html/assets/img/client/espacios && \
    chmod -R 777 /var/www/html/assets/img/client/espacios

# Copiar código fuente
COPY . /var/www/html/

# Exponer el puerto
EXPOSE ${PORT}

# Iniciar Apache
CMD ["apache2-foreground"]
