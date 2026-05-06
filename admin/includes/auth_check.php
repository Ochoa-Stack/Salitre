<?php
/* Verificamos que el usuario esté autenticado como personal del admin */
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once dirname(__DIR__, 2) . '/config/constants.php';

if (!isset($_SESSION['staff_id'])) {
    header('Location: ' . BASE_URL . 'admin/login.php');
    exit();
}
