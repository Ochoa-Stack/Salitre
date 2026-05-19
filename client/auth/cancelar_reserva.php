<?php
declare(strict_types=1);
/* 'client/auth/cancelar_reserva.php' procesa la cancelación de una reserva por parte del cliente */

session_start();
require_once dirname(__DIR__) . "/../config/database.php";
require_once dirname(__DIR__) . "/../config/constants.php";

/* Validar sesión activa */
if (!isset($_SESSION["cliente_id"])) {
    header("Location: " . BASE_URL . "client/auth/login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: " . BASE_URL . "client/auth/perfil.php");
    exit;
}

/* Validar CSRF */
if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    http_response_code(403);
    die('CSRF token validation failed');
}

$cliente_id = (int)$_SESSION["cliente_id"];
$reserva_id = (int)($_POST["reserva_id"] ?? 0);

if ($reserva_id <= 0) {
    header("Location: " . BASE_URL . "client/auth/perfil.php?error=invalid_reserva");
    exit;
}

try {
    $db = conectarDB();
    
    /* Verificamos que la reserva pertenezca al cliente activo y que su estado
       permita la cancelación (solo 'pendiente' o 'confirmada') */
    $stmt = $db->prepare("SELECT id FROM reservas WHERE id = ? AND cliente_id = ? AND (estado = 'pendiente' OR estado = 'confirmada')");
    $stmt->execute([$reserva_id, $cliente_id]);
    $reserva = $stmt->fetch();
    
    if (!$reserva) {
        header("Location: " . BASE_URL . "client/auth/perfil.php?error=not_allowed");
        exit;
    }
    
    /* Ejecutar cancelación */
    $stmt = $db->prepare("UPDATE reservas SET estado = 'cancelada' WHERE id = ?");
    $stmt->execute([$reserva_id]);
    
    header("Location: " . BASE_URL . "client/auth/perfil.php?success=reserva_cancelada");
    exit;
    
} catch (PDOException $e) {
    error_log("Error cancelando reserva ID $reserva_id: " . $e->getMessage());
    header("Location: " . BASE_URL . "client/auth/perfil.php?error=db_error");
    exit;
}
