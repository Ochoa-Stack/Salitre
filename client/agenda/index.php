<?php
/* 'client/agenda/index.php' es la página de agenda de experiencias */
session_start();
require_once dirname(__DIR__) . "/../config/database.php";
require_once dirname(__DIR__) . "/../config/constants.php";

$eventos = [];
try {
    $pdo = conectarDB();
    $stmt = $pdo->prepare(
        "SELECT * FROM eventos WHERE activo = 1 AND fecha_evento >= CURDATE() 
         ORDER BY fecha_evento ASC, hora_inicio ASC"
    );
    $stmt->execute();
    $eventos = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Error Agenda: " . $e->getMessage());
}

$page_title = "Agenda — Salitre";
$extra_stylesheets = ["assets/css/client/agenda.css"];

require_once dirname(__DIR__) . "/includes/header.php";
require_once dirname(__DIR__) . "/includes/nav.php";
$base = BASE_URL;

require __DIR__ . '/index.view.php';
?>
