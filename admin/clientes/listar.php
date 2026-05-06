<?php
/* 'admin/clientes/listar.php' muestra todo el listados de los clientes registrados dentro del sistema */
declare(strict_types=1);
require_once '../includes/auth_check.php';

require_once dirname(__DIR__, 2) . '/config/constants.php';
require_once dirname(__DIR__, 2) . '/config/database.php';

// Hacemos la consulta para obtener el listado de clientes junto con el conteo de reservas asociadas a cada cliente
$clientes = [];
try {
    $pdo  = conectarDB();
    $stmt = $pdo->prepare(
        'SELECT c.*, COUNT(r.id) as total_reservas 
         FROM clientes c 
         LEFT JOIN reservas r ON c.id = r.cliente_id 
         GROUP BY c.id 
         ORDER BY c.creado_en DESC'
    );
    $stmt->execute();
    $clientes = $stmt->fetchAll();
} catch (Throwable $e) {
    error_log('Admin clientes/listar: ' . $e->getMessage());
}

// Configuramos el título de la página y los estilos extra para esta sección
$page_title = 'Clientes - Panel Salitre';
$extra_css  = ['assets/css/admin/crud.css'];
require __DIR__ . '/listar.view.php';
