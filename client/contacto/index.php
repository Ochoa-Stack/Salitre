<?php
/* 'client/contacto/index.php' será la página de Contacto */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once dirname(__DIR__, 2) . "/config/database.php";
require_once dirname(__DIR__, 2) . "/config/constants.php";

$page_title = "Contacto — " . SITE_NAME;
$extra_stylesheets = ["assets/css/client/index.css"];    // Reutilizamos estilos de contacto de index

require_once dirname(__DIR__) . "/includes/header.php";
require_once dirname(__DIR__) . "/includes/nav.php";
$base = BASE_URL;
require __DIR__ . '/index.view.php';
?>
