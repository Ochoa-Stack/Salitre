<?php
declare(strict_types=1);
/* Definimos el procesamiento del formulario de contacto - 'client/includes/procesar_contacto.php' */

require_once dirname(dirname(__DIR__)) . '/config/constants.php';
require_once dirname(dirname(__DIR__)) . '/config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        http_response_code(403);
        die('CSRF token validation failed');
    }
    // Regeneramos el token tras cada validación para prevenir ataques de replay
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

    /* Validamos y procesamos el formulario de contacto con enfoque en prevención de inyecciones.
       Los datos se guardan sin transformar para mantener la integridad en la base de datos. */

    $nombre = trim($_POST['nombre']  ?? '');
    $email  = trim($_POST['email']   ?? '');
    $mensaje = trim($_POST['mensaje'] ?? '');

    if (empty($nombre) || empty($email) || empty($mensaje)) {
        header('Location: ' . BASE_URL . 'client/contacto/index.php?contacto=error');
        exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: ' . BASE_URL . 'client/contacto/index.php?contacto=error');
        exit;
    }
    // Validamos límites de longitud para prevenir abusos - 500 caracteres
    if (strlen($mensaje) > 500) {
        header('Location: ' . BASE_URL . 'client/contacto/index.php?contacto=error');
        exit;
    }
    // Los datos se guardan sin transformar, la sanitización de salida
    // (htmlspecialchars) se aplica exclusivamente en las vistas al mostrarlos

    try {
        $pdo  = conectarDB();
        $stmt = $pdo->prepare('INSERT INTO contacto (nombre, email, mensaje) VALUES (?, ?, ?)');
        $stmt->execute([$nombre, $email, $mensaje]);
        header('Location: ' . BASE_URL . 'client/contacto/index.php?contacto=ok');
        exit;
    } catch (Throwable $e) {
        error_log('Error contacto: ' . $e->getMessage());
        header('Location: ' . BASE_URL . 'client/contacto/index.php?contacto=error');
        exit;
    }
} else {
    header('Location: ' . BASE_URL . 'client/contacto/index.php');
    exit;
}
