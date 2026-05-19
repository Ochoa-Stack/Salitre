FROM php:8.2-fpm

# Instalamos Nginx y extensiones PHP
RUN apt-get update && apt-get install -y nginx \
    && docker-php-ext-install pdo pdo_mysql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Copiamos código
WORKDIR /var/www/html
COPY . .

# Configuración de Nginx (puerto 8080 por defecto)
RUN echo 'server { \
    listen 8080; \
    server_name _; \
    root /var/www/html; \
    index index.php index.html; \
    \
    location / { \
        try_files $uri $uri/ /index.php?$query_string; \
    } \
    \
    location ~ \.php$ { \
        fastcgi_pass 127.0.0.1:9000; \
        fastcgi_index index.php; \
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name; \
        include fastcgi_params; \
    } \
}' > /etc/nginx/sites-available/default \
    && rm -f /etc/nginx/sites-enabled/default \
    && ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

# Permisos correctos (aplicados DESPUÉS de copiar)
RUN chown -R www-data:www-data /var/www/html \
    && find /var/www/html -type d -exec chmod 755 {} \; \
    && find /var/www/html -type f -exec chmod 644 {} \; \
    && chmod -R 777 /var/www/html/assets/img/client/espacios

# Inicio de servicios
CMD php-fpm -D && nginx -g 'daemon off;'

EXPOSE 8080
