<?php
/* 'admin/login.php' es la página de inicio de sesión para el panel de administración de Salitre */
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once dirname(__DIR__) . '/config/constants.php';

// Si ya hay sesión activa, redireccionamos al dashboard
if (isset($_SESSION['staff_id'])) {
    header('Location: ' . BASE_URL . 'admin/index.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim((string) ($_POST['email'] ?? ''));
    $pass  = (string) ($_POST['password'] ?? '');

    if ($email === '' || $pass === '') {
        $error = 'Completa todos los campos.';
    } else {
        require_once dirname(__DIR__) . '/config/database.php';
        try {
            $pdo  = conectarDB();
            $stmt = $pdo->prepare(
                'SELECT id, nombre, password, rol FROM staff WHERE email = :email AND activo = 1 LIMIT 1'
            );
            $stmt->execute([':email' => $email]);
            $staff = $stmt->fetch();

            if ($staff && password_verify($pass, (string) $staff['password'])) {
                session_regenerate_id(true);
                $_SESSION['staff_id']     = (int) $staff['id'];
                $_SESSION['staff_nombre'] = (string) $staff['nombre'];
                $_SESSION['staff_rol']    = (string) $staff['rol'];
                header('Location: ' . BASE_URL . 'admin/index.php');
                exit();
            } else {
                $error = 'Credenciales incorrectas.';
            }
        } catch (Throwable $e) {
            error_log('Admin login error: ' . $e->getMessage());
            $error = 'Error de conexión. Inténtalo de nuevo.';
        }
    }
}
require __DIR__ . '/login.view.php';
?>
