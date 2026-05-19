FROM php:8.2-fpm

# Instalamos Nginx y extensiones
RUN apt-get update && apt-get install -y nginx \
    && docker-php-ext-install pdo pdo_mysql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Copiamos el código PRIMERO
WORKDIR /var/www/html
COPY . /var/www/html/

# Configuración de Nginx CORRECTA
RUN echo 'server { \
    listen 8080; \
    server_name localhost; \
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
    \
    location ~ /\.ht { \
        deny all; \
    } \
}' > /etc/nginx/sites-available/default \
    && ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default \
    && rm -f /etc/nginx/sites-enabled/default

# Permisos CORRECTOS (después de copiar)
RUN chown -R www-data:www-data /var/www/html \
    && find /var/www/html -type d -exec chmod 755 {} \; \
    && find /var/www/html -type f -exec chmod 644 {} \; \
    && chmod -R 777 /var/www/html/assets/img/client/espacios

# Inicio
CMD bash -c "sed -i \"s/listen 8080/listen \${PORT}/g\" /etc/nginx/sites-available/default && php-fpm8.2 -D && nginx -g 'daemon off;'"

EXPOSE ${PORT}
