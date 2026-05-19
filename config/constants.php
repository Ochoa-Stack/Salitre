<?php
declare(strict_types=1);
/* Definimos las rutas absolutas y URLs bases de direccionamiento */

/* Usamos getenv() para permitir override mediante variables de entorno del servidor.
   Si no existe la variable de entorno, caemos al valor por defecto para XAMPP local.
   En producción: SetEnv SALITRE_BASE_URL https://tusitio.com/ en el .htaccess o VirtualHost */
   
define('BASE_URL',   (string) (getenv('SALITRE_BASE_URL') ?: '/'));

define('BASE_PATH',            dirname(__DIR__) . '/');
define('CONFIG_PATH',          BASE_PATH . 'config/');
define('INCLUDES_CLIENT_PATH', BASE_PATH . 'client/includes/');
define('INCLUDES_ADMIN_PATH',  BASE_PATH . 'admin/includes/');

define('MONEDA',    'USD');

/* Definimos las constantes del negocio para cálculos de reserva */
define('SITE_NAME',    'Salitre');
define('SITE_TAGLINE', 'Sal de la oficina. No del trabajo.');
define('IVA',          0.16);             /* Lo usamos en 'client/carrito/agregar.php' */
define('LIMPIEZA_FEE', 25.00);            /* Lo usamos en 'client/carrito/agregar.php' */
define('UPLOAD_PATH',  __DIR__ . '/../assets/img/client/espacios/');
define('PAGE_SIZE',    20);               /* Items por página en admin */
?>
