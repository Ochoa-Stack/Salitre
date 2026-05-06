<?php
/* 'admin/eventos/editar.php' es la página para editar un evento existente desde el panel de administración */
declare(strict_types=1);
require_once dirname(__DIR__) . "/includes/auth_check.php";
require_once dirname(__DIR__, 2) . "/config/database.php";
require_once dirname(__DIR__, 2) . "/config/constants.php";

// Inicializamos variables para el formulario y un array para errores
$errores = [];
// Validar y obtener ID del evento a editar
$id = filter_var($_GET["id"] ?? 0, FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: listar.php");
    exit;
}

try {
    $pdo = conectarDB();

    // Cargamos los datos actuales del evento, obtenemos los datos del evento original
    $stmt = $pdo->prepare("SELECT * FROM eventos WHERE id = ?");
    $stmt->execute([$id]);
    $evento = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$evento) {
        header("Location: listar.php");
        exit;
    }
} catch (PDOException $e) {
    error_log("Error cargando evento: " . $e->getMessage());
    header("Location: listar.php");
    exit;
}
// Si se envió el formulario, se procesan los datos del mismo
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titulo = trim($_POST["titulo"] ?? "");
    $descripcion = trim($_POST["descripcion"] ?? "");
    $fecha_evento = $_POST["fecha_evento"] ?? "";
    $hora_inicio = !empty($_POST["hora_inicio"]) ? $_POST["hora_inicio"] : null;
    $hora_fin = !empty($_POST["hora_fin"]) ? $_POST["hora_fin"] : null;
    $cupo = !empty($_POST["cupo"]) ? (int)$_POST["cupo"] : null;
    $activo = isset($_POST["activo"]) ? 1 : 0;
    
    if (empty($titulo)) $errores[] = "El título es obligatorio.";
    if (empty($fecha_evento)) $errores[] = "La fecha es obligatoria.";
    
    if (empty($errores)) {
        try {
            $stmt = $pdo->prepare(
                "UPDATE eventos SET titulo=?, descripcion=?, fecha_evento=?, hora_inicio=?, hora_fin=?, cupo=?, activo=? 
                 WHERE id=?"
            );
            $stmt->execute([$titulo, $descripcion, $fecha_evento, $hora_inicio, $hora_fin, $cupo, $activo, $id]);
            header("Location: listar.php?success=updated");
            exit;
        } catch (PDOException $e) {
            $errores[] = "Error guardando evento: " . $e->getMessage();
        }
    }
}

// Definimos el título de la página y luego incluimos el header y sidebar comunes
$page_title = "Editar Evento — Panel Salitre";
$extra_css = ['assets/css/admin/crud.css'];
require __DIR__ . '/editar.view.php';
