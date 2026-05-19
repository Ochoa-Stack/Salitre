FROM php:8.2-fpm

# Instalamos Nginx y extensiones PHP
RUN apt-get update && apt-get install -y nginx \
    && docker-php-ext-install pdo pdo_mysql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Configuración de Nginx optimizada
RUN echo 'server { \
    listen 8080; \
    root /var/www/html; \
    index index.php index.html index.htm; \
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
}' > /etc/nginx/sites-available/default

# Configuramos permisos CORRECTAMENTE
WORKDIR /var/www/html
COPY . /var/www/html/

# Permisos recursivos DESPUÉS de copiar
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && find /var/www/html -type f -name "*.php" -exec chmod 644 {} \; \
    && find /var/www/html -type d -exec chmod 755 {} \; \
    && mkdir -p /var/www/html/assets/img/client/espacios \
    && chown -R www-data:www-data /var/www/html/assets/img/client/espacios \
    && chmod -R 777 /var/www/html/assets/img/client/espacios

# Comando de inicio
CMD bash -c "sed -i \"s/listen 8080/listen \${PORT}/g\" /etc/nginx/sites-available/default && php-fpm8.2 -D && nginx -g 'daemon off;'"

EXPOSE ${PORT}
