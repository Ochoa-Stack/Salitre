<?php
/* 'client/auth/login.php' es la página de inicio de sesión para los clientes */
session_start();
require_once dirname(__DIR__) . "/../config/database.php";
require_once dirname(__DIR__) . "/../config/constants.php";

// Si ya hay sesión, redirige según el parámetro 'redirect' o a la página principal
if (isset($_SESSION["cliente_id"])) {
    $redirect = $_GET["redirect"] ?? "home";
    if ($redirect === "carrito") {
        header("Location: " . BASE_URL . "client/carrito/index.php");
    } else {
        header("Location: " . BASE_URL . "client/index.php");
    }
    exit;
}

$page_title = "Iniciar Sesión — Salitre";
$extra_stylesheets = ["assets/css/client/auth.css"];

require_once dirname(__DIR__) . "/includes/header.php";
require_once dirname(__DIR__) . "/includes/nav.php";
$base = BASE_URL;
require __DIR__ . '/login.view.php';
?>
