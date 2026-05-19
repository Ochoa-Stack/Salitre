<?php
declare(strict_types=1);

// Preparamos el entorno para las pruebas sin ejecutar la lógica web

// Traemos las herramientas de desarrollo
require_once dirname(__DIR__) . '/vendor/autoload.php';

// Cargamos las rutas y constantes base
require_once dirname(__DIR__) . '/config/constants.php';

// Dejamos lista la conexión a la base de datos
require_once dirname(__DIR__) . '/config/database.php';

// Simulamos el entorno del servidor para evitar alertas
if (!isset($_SERVER['HTTP_HOST'])) {
    $_SERVER['HTTP_HOST'] = 'localhost';
}

if (!isset($_SERVER['REQUEST_METHOD'])) {
    $_SERVER['REQUEST_METHOD'] = 'GET';
}
