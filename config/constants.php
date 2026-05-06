<?php
/* Definimos las rutas absolutas y URLs bases de direccionamiento */
define('BASE_URL', 'http://localhost/salitre/');

define('BASE_PATH', dirname(__DIR__) . '/');
define('CONFIG_PATH', BASE_PATH . 'config/');
define('INCLUDES_CLIENT_PATH', BASE_PATH . 'client/includes/');
define('INCLUDES_ADMIN_PATH', BASE_PATH . 'admin/includes/');

define('MONEDA', 'USD');

/* Definimos las constantes del negocio para cálculos de reserva */
define('SITE_NAME', 'Salitre');
define('SITE_TAGLINE', 'Sal de la oficina. No del trabajo.');
define('IVA', 0.16);              /* Lo usamos en 'client/carrito/agregar.php' */
define('LIMPIEZA_FEE', 25.00);    /* Lo usamos en 'client/carrito/agregar.php' */
define('UPLOAD_PATH', __DIR__ . '/../assets/img/client/espacios/');
?>
