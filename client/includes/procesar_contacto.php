<?php
declare(strict_types=1);
/* Definimos el procesamiento del formulario de contacto - 'client/includes/procesar_contacto.php' */

require_once dirname(dirname(__DIR__)) . '/config/constants.php';
require_once dirname(dirname(__DIR__)) . '/config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /* Validamos y procesamos el formulario de contacto - prevención de XSS e inyecciones */

    $nombre = trim($_POST['nombre']  ?? '');
    $email  = filter_var(trim($_POST['email']   ?? ''), FILTER_SANITIZE_EMAIL);
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
    // 'htmlspecialchars()' nos ayuda a prevenir XSS cuando el staff lo lea
    $nombre = htmlspecialchars($nombre, ENT_QUOTES, "UTF-8");
    $mensaje = htmlspecialchars($mensaje, ENT_QUOTES, "UTF-8");

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
