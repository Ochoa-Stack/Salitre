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
