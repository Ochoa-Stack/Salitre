<?php
/* 'client/carrito/agregar.php' maneja la lógica de agregar un espacio al carrito, se asegura de validar los datos, calcular el total correctamente y manejar la sesión del cliente. */
session_start();
require_once dirname(__DIR__) . "/../config/database.php";
require_once dirname(__DIR__) . "/../config/constants.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: " . BASE_URL . "client/espacios/index.php");
    exit;
}

// Verificamos que 'usuario' este logueado, si no, redirige al login y guarda el estado del carrito pendiente
if (!isset($_SESSION["cliente_id"])) {
    $_SESSION["redirect_after_login"] = "carrito";
    $_SESSION["carrito_pendiente"] = $_POST;
    header("Location: " . BASE_URL . "client/auth/login.php?redirect=carrito");
    exit;
}

// Validamos y sanitizamos los datos recibidos del formulario
$espacio_id = filter_var($_POST["espacio_id"] ?? 0, FILTER_VALIDATE_INT);
$fecha_entrada = filter_var($_POST["fecha_entrada"] ?? "", FILTER_SANITIZE_SPECIAL_CHARS);
$fecha_salida = filter_var($_POST["fecha_salida"] ?? "", FILTER_SANITIZE_SPECIAL_CHARS);

if (!$espacio_id || !$fecha_entrada || !$fecha_salida) {
    header("Location: " . BASE_URL . "client/espacios/index.php?error=invalid_data");
    exit;
}

// Hacemos el cálculo de las noches transcurridas entre las fechas
try {
    $entrada = new DateTime($fecha_entrada);
    $salida = new DateTime($fecha_salida);
    $diff = $entrada->diff($salida);
    // Verificar si la salida es antes de la entrada (invert date diff)
    if ($diff->invert || $diff->days < 1) {
        // Redirigir al detalle con error si las fechas son ilogicas
        header("Location: " . BASE_URL . "client/espacios/index.php?error=invalid_dates");
        exit;
    }
    $noches = $diff->days;
} catch (Exception $e) {
    header("Location: " . BASE_URL . "client/espacios/index.php?error=invalid_date_format");
    exit;
}

// Hacemos el cálculo de precio
$pdo = conectarDB();
$stmt = $pdo->prepare("SELECT precio_noche FROM espacios WHERE id = ? AND activo = 1");
$stmt->execute([$espacio_id]);
$espacio = $stmt->fetch();

if (!$espacio) {
    header("Location: " . BASE_URL . "client/espacios/index.php?error=space_not_found");
    exit;
}

// Calculamos el total basándose en los datos 100% de backend
$subtotal = $espacio["precio_noche"] * $noches;
$iva = $subtotal * IVA;
$total = $subtotal + LIMPIEZA_FEE + $iva;

// Guardamos en la variable 'SESSION'
$_SESSION["carrito"] = [
    "espacio_id" => (int)$espacio_id,
    "fecha_entrada" => $fecha_entrada,
    "fecha_salida" => $fecha_salida,
    "noches" => $noches,
    "subtotal" => $subtotal,
    "iva" => $iva,
    "limpieza" => LIMPIEZA_FEE,
    "total" => $total
];

header("Location: " . BASE_URL . "client/carrito/index.php");
exit;
