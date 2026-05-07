<?php
/* 'admin/espacios/eliminar.php' es la página para desactivar un espacio desde el panel */
declare(strict_types=1);
require_once '../includes/auth_check.php';
require_once dirname(__DIR__, 2) . '/config/constants.php';
require_once dirname(__DIR__, 2) . '/config/database.php';

// Sólo aceptamos 'POST' para protección básica contra activación por enlace 'GET'
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . 'admin/espacios/listar.php');
    exit();
}

    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        http_response_code(403);
        die('CSRF token validation failed');
    }
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));


$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    header('Location: ' . BASE_URL . 'admin/espacios/listar.php');
    exit();
}

try {
    $pdo  = conectarDB();
    // En lugar de eliminar físicamente el registro, hacemos un "soft delete" marcándolo como inactivo
    $stmt = $pdo->prepare('UPDATE espacios SET activo = 0 WHERE id = :id');
    $stmt->execute([':id' => $id]);
} catch (Throwable $e) {
    error_log('Admin espacios/eliminar: ' . $e->getMessage());
}

header('Location: ' . BASE_URL . 'admin/espacios/listar.php?msg=desactivado');
exit();
