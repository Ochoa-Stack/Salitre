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
    $page = max(1, (int)($_GET['page'] ?? 1));
    $offset = ($page - 1) * PAGE_SIZE;

    $stmt_count = $pdo->query('SELECT COUNT(*) FROM contacto');
    $total_records = (int)$stmt_count->fetchColumn();
    $total_pages = ceil($total_records / PAGE_SIZE);
    
    $stmt_nl = $pdo->query('SELECT COUNT(*) FROM contacto WHERE leido = 0');
    $no_leidos = (int)$stmt_nl->fetchColumn();

    $stmt = $pdo->prepare(
        'SELECT id, nombre, email, mensaje, leido, creado_en
         FROM contacto
         ORDER BY leido ASC, creado_en DESC
         LIMIT :limit OFFSET :offset'
    );
    $stmt->bindValue(':limit', PAGE_SIZE, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $mensajes = $stmt->fetchAll();
} catch (Throwable $e) {
    error_log('Admin contacto/listar: ' . $e->getMessage());
}

$flash = (isset($_GET['success']) && $_GET['success'] === 'marked_read')
    ? ['type' => 'success', 'text' => 'Mensaje marcado como leído']
    : null;

// Configuramos el título de la página y los estilos adicionales, luego incluimos el header y sidebar del admin
$page_title = 'Mensajes - Panel Salitre';
$extra_css  = ['assets/css/admin/crud.css'];
require __DIR__ . '/listar.view.php';
