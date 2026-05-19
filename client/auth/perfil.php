<?php
declare(strict_types=1);
/* 'client/auth/perfil.php' es la página de perfil del cliente, muestra toda su información */

session_start();
require_once "../../config/database.php";
require_once "../../config/constants.php";
require_once dirname(__DIR__) . "/../config/csrf.php";
$csrf_token = generarTokenCSRF();

/* Se valida la sesión de cliente antes de mostrar información personal, si no hay sesión va a 'login.php' */
if (!isset($_SESSION["cliente_id"])) {
    header("Location: " . BASE_URL . "client/auth/login.php");
    exit;
}

$cliente_id = $_SESSION["cliente_id"];
$db = conectarDB();
$success_msg = "";
$error_msg = "";

/* Procesar actualización de perfil */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action']) && $_POST['action'] === 'update_profile') {
    // Validar CSRF
    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        http_response_code(403);
        die('CSRF token validation failed');
    }
    
    $nombre = trim($_POST["nombre"] ?? "");
    $telefono = trim($_POST["telefono"] ?? "");
    $new_password = $_POST["password"] ?? "";
    
    if (empty($nombre)) {
        $error_msg = "El nombre no puede estar vacío.";
    } else {
        // Validar teléfono
        if (!empty($telefono)) {
            if (!preg_match('/^[0-9\s\+\-\(\)]{1,15}$/', $telefono)) {
                $error_msg = "Formato de teléfono inválido.";
            }
        } else {
            $telefono = null;
        }
        
        if (empty($error_msg)) {
            try {
                if (!empty($new_password)) {
                    if (strlen($new_password) < 8) {
                        $error_msg = "La nueva contraseña debe tener al menos 8 caracteres.";
                    } else {
                        $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
                        $stmt = $db->prepare("UPDATE clientes SET nombre = ?, telefono = ?, password = ? WHERE id = ?");
                        $stmt->execute([$nombre, $telefono, $password_hash, $cliente_id]);
                        $success_msg = "Perfil y contraseña actualizados correctamente.";
                        // Sincronizamos el nombre en sesión para reflejar el cambio en la navegación
                        $_SESSION["cliente_nombre"] = $nombre;
                    }
                } else {
                    // Si la contraseña viene vacía, actualizamos únicamente los datos de contacto
                    $stmt = $db->prepare("UPDATE clientes SET nombre = ?, telefono = ? WHERE id = ?");
                    $stmt->execute([$nombre, $telefono, $cliente_id]);
                    $success_msg = "Perfil actualizado correctamente.";
                    $_SESSION["cliente_nombre"] = $nombre;
                }
            } catch (PDOException $e) {
                error_log("Error actualizando perfil: " . $e->getMessage());
                $error_msg = "Error interno al actualizar el perfil.";
            }
        }
    }
}

/* Obtenemos los datos del cliente con 'prepared statement' */
$stmt = $db->prepare("SELECT * FROM clientes WHERE id = ?");
$stmt->execute([$cliente_id]);
$cliente = $stmt->fetch(PDO::FETCH_ASSOC);

/* Obtenemos las reservas del cliente con 'JOIN' a espacios */
$stmt = $db->prepare("
    SELECT r.*, e.nombre as espacio_nombre, e.slug as espacio_slug
    FROM reservas r
    JOIN espacios e ON r.espacio_id = e.id
    WHERE r.cliente_id = ?
    ORDER BY r.creado_en DESC
    LIMIT 10
");
$stmt->execute([$cliente_id]);
$reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);

$page_title = "Mi Perfil — " . SITE_NAME;
$extra_stylesheets = ["assets/css/client/auth.css"];

require_once "../includes/header.php";
require_once "../includes/nav.php";
require __DIR__ . '/perfil.view.php';
?>
