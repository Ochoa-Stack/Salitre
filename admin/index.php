<?php
/* 'admin/index.php' es el dashboard principal del panel de administración de Salitre */
declare(strict_types=1);
require_once 'includes/auth_check.php';

require_once dirname(__DIR__) . '/config/constants.php';
require_once dirname(__DIR__) . '/config/database.php';
// Definimos variables para las estadísticas, con valores por defecto en caso de error
$stat_reservas_pendientes = 0;
$stat_espacios_activos    = 0;
$stat_clientes            = 0;

try {
    $pdo = conectarDB();

    // Vamos a obtener las estadísticas principales de reservas pendientes
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM reservas WHERE estado = :estado');
    $stmt->execute([':estado' => 'pendiente']);
    $stat_reservas_pendientes = (int) $stmt->fetchColumn();
    // Vamos a obtener las estadísticas principales de espacios activos
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM espacios WHERE activo = 1');
    $stmt->execute();
    $stat_espacios_activos = (int) $stmt->fetchColumn();
    // Vamos a obtener las estadísticas principales de clientes registrados
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM clientes');
    $stmt->execute();
    $stat_clientes = (int) $stmt->fetchColumn();

} catch (Throwable $e) {
    error_log('Admin dashboard stats: ' . $e->getMessage());
}
// Definimos el título de la página, cargamos el header y sidebar
$page_title = 'Inicio - Panel Salitre';
require __DIR__ . '/index.view.php';
