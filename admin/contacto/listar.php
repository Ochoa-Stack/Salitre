<?php
/* 'admin/contacto/listar.php' es la página para listar todos los mensajes de contacto desde el panel de administración */
declare(strict_types=1);
require_once '../includes/auth_check.php';
require_once dirname(__DIR__, 2) . '/config/constants.php';
require_once dirname(__DIR__, 2) . '/config/database.php';

// Declaramos un array vacío para almacenar los mensajes que se obtendrán de la base de datos
$mensajes = [];
try {
    $pdo  = conectarDB();
    $stmt = $pdo->prepare(
        'SELECT id, nombre, email, mensaje, leido, creado_en
         FROM contacto
         ORDER BY leido ASC, creado_en DESC'
    );
    $stmt->execute();
    $mensajes = $stmt->fetchAll();
} catch (Throwable $e) {
    error_log('Admin contacto/listar: ' . $e->getMessage());
}

$no_leidos = count(array_filter($mensajes, fn($m) => !(bool) $m['leido']));

$flash = (isset($_GET['success']) && $_GET['success'] === 'marked_read')
    ? ['type' => 'success', 'text' => 'Mensaje marcado como leído']
    : null;

// Configuramos el título de la página y los estilos adicionales, luego incluimos el header y sidebar del admin
$page_title = 'Mensajes - Panel Salitre';
$extra_css  = ['assets/css/admin/crud.css'];
require __DIR__ . '/listar.view.php';
