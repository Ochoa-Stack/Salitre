<?php
/* 'client/auth/registro.php' es la página de registro de nuevos clientes */
session_start();
require_once dirname(__DIR__) . "/../config/database.php";
require_once dirname(__DIR__) . "/../config/constants.php";

// Si ya hay sesión, 'redirect' a home
if (isset($_SESSION["cliente_id"])) {
    header("Location: " . BASE_URL . "client/index.php");
    exit;
}

$page_title = "Crear Cuenta — Salitre";
$extra_stylesheets = ["assets/css/client/auth.css"];

require_once dirname(__DIR__) . "/includes/header.php";
require_once dirname(__DIR__) . "/includes/nav.php";
$base = BASE_URL;
require __DIR__ . '/registro.view.php';
?>
