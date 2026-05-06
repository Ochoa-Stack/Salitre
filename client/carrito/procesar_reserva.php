<?php
/* 'client/carrito/procesar_reserva.php' es el endpoint que procesa la solicitud de reserva cuando el cliente confirma su carrito. */
session_start();
require_once dirname(__DIR__) . "/../config/database.php";
require_once dirname(__DIR__) . "/../config/constants.php";

// Verificamos que la sesión se encuentre activa
if (!isset($_SESSION["cliente_id"])) {
    header("Location: " . BASE_URL . "client/auth/login.php?redirect=carrito");
    exit;
}

// Verificamos que el carrito contenga los datos necesarios
if (!isset($_SESSION["carrito"]) || empty($_SESSION["carrito"])) {
    header("Location: " . BASE_URL . "client/espacios/index.php");
    exit;
}

// Validamos que la solicitud sea 'POST'
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: " . BASE_URL . "client/carrito/index.php");
    exit;
}

// Obtenemos los datos del carrito desde la sesión
$cliente_id = (int)$_SESSION["cliente_id"];
$espacio_id = (int)$_SESSION["carrito"]["espacio_id"];
$fecha_entrada = $_SESSION["carrito"]["fecha_entrada"];
$fecha_salida = $_SESSION["carrito"]["fecha_salida"];
$noches = (int)$_SESSION["carrito"]["noches"];
$precio_total = (float)$_SESSION["carrito"]["total"];

// Hacemos el respectivo 'INSERT' en reservas con prepared statement
try {
    $pdo = conectarDB();
    $stmt = $pdo->prepare(
        "INSERT INTO reservas (cliente_id, espacio_id, fecha_entrada, fecha_salida, noches, precio_total, estado) 
         VALUES (?, ?, ?, ?, ?, ?, 'pendiente')"
    );
    $stmt->execute([
        $cliente_id,
        $espacio_id,
        $fecha_entrada,
        $fecha_salida,
        $noches,
        $precio_total
    ]);

    // Obtenemos el ID o folio de la reserva recién creada para mostrar en la confirmación
    $reserva_id = $pdo->lastInsertId();

    // Limpiamos el carrito y establecemos el ID de confirmación en sesión
    unset($_SESSION["carrito"]);
    $_SESSION["reserva_confirmacion_id"] = $reserva_id;

    // Redirigimos a la página de confirmación
    header("Location: " . BASE_URL . "client/carrito/confirmacion.php");
    exit;

} catch (PDOException $e) {
    // Si hay error de base de datos, volvemos al carrito con un flag de error
    error_log("Error al insertar reserva: " . $e->getMessage());
    header("Location: " . BASE_URL . "client/carrito/index.php?error=db");
    exit;
}
