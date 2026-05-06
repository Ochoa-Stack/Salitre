<?php
/* 'admin/eventos/crear.php' es la página para crear un nuevo evento desde el panel de administración */
declare(strict_types=1);
require_once dirname(__DIR__) . "/includes/auth_check.php";
require_once dirname(__DIR__, 2) . "/config/database.php";
require_once dirname(__DIR__, 2) . "/config/constants.php";

// Inicializamos variables para el formulario y un array para errores
$errores = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titulo = trim($_POST["titulo"] ?? "");
    $descripcion = trim($_POST["descripcion"] ?? "");
    $fecha_evento = $_POST["fecha_evento"] ?? "";
    $hora_inicio = !empty($_POST["hora_inicio"]) ? $_POST["hora_inicio"] : null;
    $hora_fin = !empty($_POST["hora_fin"]) ? $_POST["hora_fin"] : null;
    $cupo = !empty($_POST["cupo"]) ? (int)$_POST["cupo"] : null;
    $activo = isset($_POST["activo"]) ? 1 : 0;

    // Realizamos las respectivas validaciones
    if (empty($titulo)) $errores[] = "El título es obligatorio.";
    if (empty($fecha_evento)) $errores[] = "La fecha es obligatoria.";
    
    if (empty($errores)) {
        try {
            $pdo = conectarDB();
            $stmt = $pdo->prepare(
                "INSERT INTO eventos (titulo, descripcion, fecha_evento, hora_inicio, hora_fin, cupo, activo) 
                 VALUES (?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt->execute([$titulo, $descripcion, $fecha_evento, $hora_inicio, $hora_fin, $cupo, $activo]);
            header("Location: listar.php?success=created");
            exit;
        } catch (PDOException $e) {
            $errores[] = "Error de persistencia: " . $e->getMessage();
        }
    }
}

// Definimos el título de la página y luego incluimos el header y sidebar comunes
$page_title = "Eventos - Panel Salitre";
require __DIR__ . '/crear.view.php';
