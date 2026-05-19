FROM php:8.2-apache

# Instalar extensiones
RUN docker-php-ext-install pdo pdo_mysql

# Configurar variables de entorno
ENV PORT=8080
ENV APACHE_DOCUMENT_ROOT=/var/www/html

# Habilitar solo mpm_prefork y rewrite (desactivar otros MPMs)
RUN a2dismod mpm_event mpm_worker && \
    a2enmod mpm_prefork rewrite && \
    # Configurar el puerto en Apache
    echo "Listen ${PORT}" > /etc/apache2/ports.conf && \
    # Configurar VirtualHost
    echo "<VirtualHost *:${PORT}>" > /etc/apache2/sites-available/000-default.conf && \
    echo "    DocumentRoot ${APACHE_DOCUMENT_ROOT}" >> /etc/apache2/sites-available/000-default.conf && \
    echo "    <Directory ${APACHE_DOCUMENT_ROOT}>" >> /etc/apache2/sites-available/000-default.conf && \
    echo "        Options Indexes FollowSymLinks" >> /etc/apache2/sites-available/000-default.conf && \
    echo "        AllowOverride All" >> /etc/apache2/sites-available/000-default.conf && \
    echo "        Require all granted" >> /etc/apache2/sites-available/000-default.conf && \
    echo "    </Directory>" >> /etc/apache2/sites-available/000-default.conf && \
    echo "</VirtualHost>" >> /etc/apache2/sites-available/000-default.conf

# Configurar permisos
WORKDIR /var/www/html
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html && \
    mkdir -p /var/www/html/assets/img/client/espacios && \
    chmod -R 777 /var/www/html/assets/img/client/espacios

# Copiar código
COPY . /var/www/html/

# Crear script de entrada personalizado
RUN echo '#!/bin/bash' > /entrypoint.sh && \
    echo 'export PORT=${PORT:-8080}' >> /entrypoint.sh && \
    echo 'echo "Starting Apache on port $PORT"' >> /entrypoint.sh && \
    echo 'sed -i "s/Listen .*/Listen $PORT/g" /etc/apache2/ports.conf' >> /entrypoint.sh && \
    echo 'sed -i "s/VirtualHost \*:.*/VirtualHost *:$PORT/g" /etc/apache2/sites-available/000-default.conf' >> /entrypoint.sh && \
    echo 'apache2-foreground' >> /entrypoint.sh && \
    chmod +x /entrypoint.sh

EXPOSE ${PORT}

ENTRYPOINT ["/entrypoint.sh"]
