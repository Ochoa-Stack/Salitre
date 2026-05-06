<?php
/* Definimos la página principal de espacios - 'client/espacios/index.php' */
session_start();
require_once dirname(__DIR__) . "/../config/database.php";
require_once dirname(__DIR__) . "/../config/constants.php";

$pdo = conectarDB();
$stmt = $pdo->prepare("SELECT * FROM espacios WHERE activo = 1 ORDER BY precio_noche ASC");
$stmt->execute();
$espacios = $stmt->fetchAll();

$page_title = "Espacios — Salitre";
$extra_stylesheets = ["assets/css/client/espacios.css"];
$extra_scripts = ["assets/js/client/espacios.js"];

require_once dirname(__DIR__) . "/includes/header.php";
require_once dirname(__DIR__) . "/includes/nav.php";
$base = BASE_URL;
require __DIR__ . '/index.view.php';
?>
