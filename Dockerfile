FROM php:8.2-fpm

# Instalar extensiones y Nginx
RUN apt-get update && apt-get install -y \
    nginx \
    && docker-php-ext-install pdo pdo_mysql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Configurar Nginx
RUN echo 'server { \
        listen 8080; \
        root /var/www/html; \
        index index.php index.html; \
        location / { \
            try_files $uri $uri/ /index.php?$query_string; \
        } \
        location ~ \.php$ { \
            fastcgi_pass 127.0.0.1:9000; \
            fastcgi_index index.php; \
            fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name; \
            include fastcgi_params; \
        } \
    }' > /etc/nginx/sites-available/default

# Configurar permisos
WORKDIR /var/www/html
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && mkdir -p /var/www/html/assets/img/client/espacios \
    && chmod -R 777 /var/www/html/assets/img/client/espacios

# Copiar código
COPY . /var/www/html/

# Script de inicio para Nginx + PHP-FPM
RUN echo '#!/bin/bash' > /entrypoint.sh && \
    echo 'service php8.2-fpm start' >> /entrypoint.sh && \
    echo 'nginx -g "daemon off;"' >> /entrypoint.sh && \
    chmod +x /entrypoint.sh

EXPOSE 8080
ENTRYPOINT ["/entrypoint.sh"]
