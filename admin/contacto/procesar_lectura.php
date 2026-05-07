<?php
/* 'admin/contacto/procesar_lectura.php' se encarga de marcar un mensaje de contacto como leído desde el panel de administración */
declare(strict_types=1);

require_once dirname(__DIR__) . "/includes/auth_check.php";
require_once dirname(__DIR__, 2) . "/config/database.php";

// Sólo aceptamos 'POST' para protección contra activación por enlace 'GET'
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: listar.php");
    exit;
}

    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        http_response_code(403);
        die('CSRF token validation failed');
    }
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));


$id = filter_var($_POST["id"] ?? 0, FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: listar.php");
    exit;
}

try {
    $pdo = conectarDB();
    $stmt = $pdo->prepare("UPDATE contacto SET leido = 1 WHERE id = ?");
    $stmt->execute([$id]);
} catch (PDOException $e) {
    error_log("Error al procesar lectura de contacto: " . $e->getMessage());
}

header("Location: listar.php?success=marked_read");
exit;
