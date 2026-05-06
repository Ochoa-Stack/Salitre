<?php
/* 'client/carrito/confirmacion.php' muestra la página de confirmación y derivados de reserva después de que el cliente ha completado el proceso de reserva */

session_start();

/* Mandamos a llamar a la base de datos y las constantes - requerido obligatoriamente */
require_once "../../config/database.php";
require_once "../../config/constants.php";

/* Validamos la sesión de cliente */
if (!isset($_SESSION["cliente_id"])) {
    header("Location: " . BASE_URL . "client/auth/login.php");
    exit;
}

/* Obtenemos el ID de reserva de la sesión */
$reserva_id = $_SESSION["reserva_confirmacion_id"] ?? null;

if (!$reserva_id) {
    header("Location: " . BASE_URL . "client/espacios/index.php");
    exit;
}

/* Obtenemos los datos de la reserva */
$db = conectarDB();
$stmt = $db->prepare("
    SELECT r.*, e.nombre as espacio_nombre, e.slug as espacio_slug
    FROM reservas r
    JOIN espacios e ON r.espacio_id = e.id
    WHERE r.id = ? AND r.cliente_id = ?
");
$stmt->execute([$reserva_id, $_SESSION["cliente_id"]]);
$reserva = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$reserva) {
    header("Location: " . BASE_URL . "client/espacios/index.php");
    exit;
}

/* Limpiamos el ID de confirmación */
unset($_SESSION["reserva_confirmacion_id"]);

$page_title = "Reserva Confirmada — Hotel Salitre";
$extra_stylesheets = ["assets/css/client/carrito.css"];

/* Incluye los datos del encabezado y la navegación */
require_once "../includes/header.php";
require_once "../includes/nav.php";
require __DIR__ . '/confirmacion.view.php';
?>
