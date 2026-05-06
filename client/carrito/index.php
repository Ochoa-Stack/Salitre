<?php
/* 'client/carrito/index.php' muestra el resumen de la reserva antes de que el cliente confirme su solicitud. */
session_start();
require_once dirname(__DIR__) . "/../config/database.php";
require_once dirname(__DIR__) . "/../config/constants.php";

// Validamos que el cliente esté logueado, si no, redirige al login y guarda el estado del carrito pendiente
if (!isset($_SESSION["cliente_id"])) {
    header("Location: " . BASE_URL . "client/auth/login.php?redirect=carrito");
    exit;
}

// Validamos que haya un carrito en sesión, si no, redirige a la lista de espacios
if (!isset($_SESSION["carrito"]) || empty($_SESSION["carrito"])) {
    header("Location: " . BASE_URL . "client/espacios/index.php");
    exit;
}

$pdo = conectarDB();
$stmt = $pdo->prepare("SELECT * FROM espacios WHERE id = ? AND activo = 1");
$stmt->execute([$_SESSION["carrito"]["espacio_id"]]);
$espacio = $stmt->fetch();

if (!$espacio) {
    unset($_SESSION["carrito"]);
    header("Location: " . BASE_URL . "client/espacios/index.php");
    exit;
}

$page_title = "Mi Reserva — Hotel Salitre";
$extra_stylesheets = ["assets/css/client/carrito.css"];
$extra_scripts = ["assets/js/client/carrito.js"]; // Para validar boton confirm

require_once dirname(__DIR__) . "/includes/header.php";
require_once dirname(__DIR__) . "/includes/nav.php";
$base = BASE_URL;
$cart = $_SESSION["carrito"];

$amenidades = json_decode((string)$espacio['amenidades'], true) ?? [];
require __DIR__ . '/index.view.php';
?>
