<?php
/* 'client/auth/perfil.php' es la página de perfil del cliente, muestra toda su información */

session_start();
require_once "../../config/database.php";
require_once "../../config/constants.php";

/* Se valida la sesión de cliente antes de mostrar información personal, si no hay sesión va a 'login.php' */
if (!isset($_SESSION["cliente_id"])) {
    header("Location: " . BASE_URL . "client/auth/login.php");
    exit;
}

$cliente_id = $_SESSION["cliente_id"];
$db = conectarDB();

/* Obtenemos los datos del cliente con 'prepared statement' */
$stmt = $db->prepare("SELECT * FROM clientes WHERE id = ?");
$stmt->execute([$cliente_id]);
$cliente = $stmt->fetch(PDO::FETCH_ASSOC);

/* Obtenemos las reservas del cliente con 'JOIN' a espacios */
$stmt = $db->prepare("
    SELECT r.*, e.nombre as espacio_nombre, e.slug as espacio_slug
    FROM reservas r
    JOIN espacios e ON r.espacio_id = e.id
    WHERE r.cliente_id = ?
    ORDER BY r.creado_en DESC
    LIMIT 10
");
$stmt->execute([$cliente_id]);
$reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);

$page_title = "Mi Perfil — " . SITE_NAME;
$extra_stylesheets = ["assets/css/client/auth.css"];

require_once "../includes/header.php";
require_once "../includes/nav.php";
require __DIR__ . '/perfil.view.php';
?>
