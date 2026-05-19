<?php
declare(strict_types=1);
/* 'client/auth/recuperar_password.php' permite al cliente solicitar un reset de contraseña */

session_start();
require_once "../../config/database.php";
require_once "../../config/constants.php";
require_once dirname(__DIR__) . "/../config/csrf.php";
$csrf_token = generarTokenCSRF();

$message = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validamos el token CSRF
    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        http_response_code(403);
        die('CSRF token validation failed');
    }
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

    $email = filter_var(trim($_POST["email"] ?? ""), FILTER_VALIDATE_EMAIL);

    if (!$email) {
        $error = "Por favor ingresa un correo electrónico válido.";
    } else {
        try {
            $db = conectarDB();
            
            // Buscamos al cliente
            $stmt = $db->prepare("SELECT id FROM clientes WHERE email = ?");
            $stmt->execute([$email]);
            $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($cliente) {
                // Generamos un token
                $token = bin2hex(random_bytes(32));
                $expira = date("Y-m-d H:i:s", strtotime("+1 hour"));

                // Invalidamos los tokens anteriores
                $stmt = $db->prepare("UPDATE password_reset_tokens SET usado = 1 WHERE cliente_id = ?");
                $stmt->execute([$cliente['id']]);

                // Guardamos el nuevo token
                $stmt = $db->prepare("INSERT INTO password_reset_tokens (cliente_id, token, expira_en) VALUES (?, ?, ?)");
                $stmt->execute([$cliente['id'], $token, $expira]);

                // Construimos el enlace
                $link = BASE_URL . "client/auth/restablecer_password.php?token=" . $token;

                // Enviamos el correo (o lo logueamos en local)
                $asunto = "Restablecer contraseña - Salitre";
                $cuerpo = "Hola, has solicitado restablecer tu contraseña. Haz clic en el siguiente enlace para continuar:\n\n" . $link . "\n\nEste enlace expira en 1 hora.";
                $cabeceras = "From: no-reply@salitre.com";

                if (strpos(BASE_URL, 'localhost') !== false) {
                    error_log("RESET PASSWORD LINK for $email: " . $link);
                } else {
                    mail($email, $asunto, $cuerpo, $cabeceras);
                }
            }

            // Mensaje genérico por seguridad
            $message = "Si ese correo está registrado, recibirás un enlace en breve.";

        } catch (PDOException $e) {
            error_log("Error en recuperación de password: " . $e->getMessage());
            $error = "Ocurrió un error en el sistema. Intenta más tarde.";
        }
    }
}

$page_title = "Recuperar Contraseña — " . SITE_NAME;
$extra_stylesheets = ["assets/css/client/auth.css"];
$base = BASE_URL;

require_once "../includes/header.php";
require_once "../includes/nav.php";
require __DIR__ . '/recuperar_password.view.php';
?>
