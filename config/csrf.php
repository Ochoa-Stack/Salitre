<?php
declare(strict_types=1);

/* Generamos el token CSRF para asegurar los formularios */
function generarTokenCSRF(): string {
    if (session_status() === PHP_SESSION_NONE && php_sapi_name() !== 'cli') {
        session_start();
    }
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}
