<?php
declare(strict_types=1);
/* 'client/carrito/pago.php' controlador para la pasarela de pago simulada */

session_start();
require_once "../../config/database.php";
require_once "../../config/constants.php";
require_once dirname(__DIR__) . "/../config/csrf.php";
$csrf_token = generarTokenCSRF();

// Verificamos que el cliente tenga sesión activa
if (!isset($_SESSION["cliente_id"])) {
    header("Location: " . BASE_URL . "client/auth/login.php?redirect=carrito");
    exit;
}

// Validamos que exista un carrito con información completa antes de proceder
if (!isset($_SESSION["carrito"]) || empty($_SESSION["carrito"])) {
    header("Location: " . BASE_URL . "client/espacios/index.php");
    exit;
}

$carrito = $_SESSION["carrito"];
$cliente_id = $_SESSION["cliente_id"];

// Definimos los metadatos de la página
$page_title = "Pago de Reserva — " . SITE_NAME;
$extra_stylesheets = ["assets/css/client/auth.css", "assets/css/client/pago.css"];

// Incluimos la cabecera y navegación del cliente
require_once "../includes/header.php";
require_once "../includes/nav.php";

// Cargamos la vista de la pasarela
require __DIR__ . '/pago.view.php';
?>
