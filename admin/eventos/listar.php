<?php
/* 'admin/eventos/listar.php' es la página para listar, editar y gestionar los eventos desde el panel de administración */
declare(strict_types=1);
require_once dirname(__DIR__) . "/includes/auth_check.php";
require_once dirname(__DIR__, 2) . "/config/database.php";
require_once dirname(__DIR__, 2) . "/config/constants.php";

// Definimos el título de la página y cargamos los datos desde la misma base de datos
$page_title = "Agenda — Panel Salitre";
$extra_css = [
    "assets/css/admin/dashboard.css",
    "assets/css/admin/crud.css"
];

$eventos = [];
try {
    $pdo = conectarDB();
    $stmt = $pdo->prepare("SELECT * FROM eventos ORDER BY fecha_evento DESC");
    $stmt->execute();
    $eventos = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Error listando eventos: " . $e->getMessage());
}

require __DIR__ . '/listar.view.php';
