<?php
/* Permite visualizar el listado de reservas en el panel de administración con toda la información disponible */
declare(strict_types=1);
require_once '../includes/auth_check.php';

require_once dirname(__DIR__, 2) . '/config/constants.php';
require_once dirname(__DIR__, 2) . '/config/database.php';
// Obtenemos el listado completo de reservas, con JOINs para traer datos relacionados de cliente y espacio
$reservas = [];
try {
    $pdo  = conectarDB();
    $stmt = $pdo->prepare(
        'SELECT
           r.id,
           c.nombre  AS cliente,
           e.nombre  AS espacio,
           r.fecha_entrada,
           r.fecha_salida,
           r.noches,
           r.precio_total,
           r.estado
         FROM reservas r
         JOIN clientes  c ON r.cliente_id  = c.id
         JOIN espacios  e ON r.espacio_id  = e.id
         ORDER BY r.creado_en DESC'
    );
    $stmt->execute();
    $reservas = $stmt->fetchAll();
} catch (Throwable $e) {
    error_log('Admin reservas/listar: ' . $e->getMessage());
}

$estados_validos = ['pendiente', 'confirmada', 'cancelada', 'completada'];
// Definimos el título de la página, cargamos el header y sidebar
$page_title = 'Reservas - Panel Salitre';
$extra_css  = ['assets/css/admin/crud.css'];
require __DIR__ . '/listar.view.php';
