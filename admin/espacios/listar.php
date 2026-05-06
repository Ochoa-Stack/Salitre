<?php
/* 'admin/espacios/listar.php' es la página para listar todos los espacios registrados desde el panel de administración */
declare(strict_types=1);
require_once '../includes/auth_check.php';
require_once dirname(__DIR__, 2) . '/config/constants.php';
require_once dirname(__DIR__, 2) . '/config/database.php';

// Obtenemos el listado de espacios desde la base de datos
$espacios = [];
try {
    $pdo   = conectarDB();
    $stmt  = $pdo->prepare(
        'SELECT id, nombre, slug, tipo, precio_noche, capacidad, activo FROM espacios ORDER BY id ASC'
    );
    $stmt->execute();
    $espacios = $stmt->fetchAll();
} catch (Throwable $e) {
    error_log('Admin espacios/listar: ' . $e->getMessage());
}

// Hacemos mapeo para mensajes flash usando query param 'msg'
$msg = $_GET['msg'] ?? '';
$flash_map = [
    'creado'      => ['type' => 'success', 'text' => 'Espacio creado correctamente.'],
    'editado'     => ['type' => 'success', 'text' => 'Espacio actualizado correctamente.'],
    'desactivado' => ['type' => 'info',    'text' => 'Espacio desactivado (soft delete).'],
];
$flash = isset($flash_map[$msg]) ? $flash_map[$msg] : null;

// Definimos el titulo de la página y los estilos adicionales, luego incluimos el header y sidebar comunes
$page_title = 'Espacios - Panel Salitre';
$extra_css  = ['assets/css/admin/crud.css'];
require __DIR__ . '/listar.view.php';
