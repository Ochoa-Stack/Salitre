<?php
/* Definimos las variables para la página principal de Salitre */
declare(strict_types=1);
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once dirname(__DIR__) . '/config/constants.php';
require_once dirname(__DIR__) . '/config/database.php';

$espacios = [];
try {
    $pdo  = conectarDB();
    $stmt = $pdo->prepare(
        '(SELECT id, nombre, slug, tipo, precio_noche, foto_principal FROM espacios WHERE activo = 1 ORDER BY precio_noche ASC LIMIT 1)
         UNION
         (SELECT id, nombre, slug, tipo, precio_noche, foto_principal FROM espacios WHERE activo = 1 ORDER BY precio_noche DESC LIMIT 1)
         ORDER BY precio_noche ASC'
    );
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (is_array($rows)) { $espacios = $rows; }
} catch (Throwable $e) {
    error_log('Index espacios: ' . $e->getMessage());
}

$page_title        = 'Salitre';
$extra_stylesheets = ['assets/css/client/index.css'];


require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/nav.php';

$base = BASE_URL;

require __DIR__ . '/index.view.php';
