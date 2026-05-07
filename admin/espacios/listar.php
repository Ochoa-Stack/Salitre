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
    $page = max(1, (int)($_GET['page'] ?? 1));
    $offset = ($page - 1) * PAGE_SIZE;

    $stmt_count = $pdo->query('SELECT COUNT(*) FROM espacios');
    $total_records = (int)$stmt_count->fetchColumn();
    $total_pages = ceil($total_records / PAGE_SIZE);

    $stmt  = $pdo->prepare(
        'SELECT id, nombre, slug, tipo, precio_noche, capacidad, activo FROM espacios ORDER BY id ASC LIMIT :limit OFFSET :offset'
    );
    $stmt->bindValue(':limit', PAGE_SIZE, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
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

// Mapeo de tipos para mostrar etiquetas legibles en la vista
$tipo_labels = ['estudio' => 'Estudio', 'loft' => 'Loft', 'suite' => 'Suite', 'villa' => 'Villa'];

// Definimos el titulo de la página y los estilos adicionales, luego incluimos el header y sidebar comunes
$page_title = 'Espacios - Panel Salitre';
$extra_css  = ['assets/css/admin/crud.css'];
require __DIR__ . '/listar.view.php';
