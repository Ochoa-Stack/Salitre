<?php
declare(strict_types=1);
/* 'client/auth/restablecer_password.php' permite al cliente establecer una nueva contraseña mediante un token */

session_start();
require_once "../../config/database.php";
require_once "../../config/constants.php";

$error = "";
$token_val = $_GET["token"] ?? $_POST["token"] ?? "";

if (empty($token_val)) {
    header("Location: " . BASE_URL . "client/auth/login.php");
    exit;
}

try {
    $db = conectarDB();
    
    // Validamos el token
    $stmt = $db->prepare("SELECT * FROM password_reset_tokens WHERE token = ? AND usado = 0 AND expira_en > NOW()");
    $stmt->execute([$token_val]);
    $token_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$token_data) {
        $error = "El enlace es inválido o ha expirado.";
        // Si es inválido, mostramos error y link para solicitar otro
    } else {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Validar CSRF
            if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
                http_response_code(403);
                die('CSRF token validation failed');
            }
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

            $password = $_POST["password"] ?? "";
            $confirm_password = $_POST["confirm_password"] ?? "";

            if (strlen($password) < 8) {
                $error = "La contraseña debe tener al menos 8 caracteres.";
            } elseif ($password !== $confirm_password) {
                $error = "Las contraseñas no coinciden.";
            } else {
                // Actualizamos la contraseña del cliente
                $password_hash = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $db->prepare("UPDATE clientes SET password = ? WHERE id = ?");
                $stmt->execute([$password_hash, $token_data['cliente_id']]);

                // Marcamos el token como usado
                $stmt = $db->prepare("UPDATE password_reset_tokens SET usado = 1 WHERE id = ?");
                $stmt->execute([$token_data['id']]);

                header("Location: " . BASE_URL . "client/auth/login.php?reset_success=1");
                exit;
            }
        }
    }

} catch (PDOException $e) {
    error_log("Error en restablecimiento de password: " . $e->getMessage());
    $error = "Ocurrió un error en el sistema. Intenta más tarde.";
}

if (!empty($error) && !isset($token_data)) {
    // Si el token es inválido, mostramos una vista mínima o redirigimos con mensaje
    $page_title = "Error de Enlace — " . SITE_NAME;
    $base = BASE_URL;
    require_once "../includes/header.php";
    require_once "../includes/nav.php";
    echo '<div class="page-offset"></div><section class="section-pad flex-center"><div class="auth-card text-center"><p class="alert alert--error mb-4">' . htmlspecialchars($error) . '</p><a href="' . $base . 'client/auth/recuperar_password.php" class="btn btn-primary">Solicitar nuevo enlace</a></div></section>';
    require_once "../includes/footer.php";
    exit;
}

$page_title = "Restablecer Contraseña — " . SITE_NAME;
$extra_stylesheets = ["assets/css/client/auth.css"];
$base = BASE_URL;

require_once "../includes/header.php";
require_once "../includes/nav.php";
require __DIR__ . '/restablecer_password.view.php';
?>
