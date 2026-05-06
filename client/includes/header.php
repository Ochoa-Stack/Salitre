<?php
/* Definimos el header común para todas las páginas del cliente - 'client/includes/header.php' */
declare(strict_types=1);
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once dirname(dirname(__DIR__)) . '/config/constants.php';
$base = BASE_URL;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title ?? 'Hotel Salitre', ENT_QUOTES, 'UTF-8') ?></title>
    <meta name="description" content="<?= htmlspecialchars($page_description ?? 'Hotel boutique costero para nómadas digitales. Sal de la oficina. No del trabajo.', ENT_QUOTES, 'UTF-8') ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= $base ?>assets/img/logo/favicon.png">
    
    <link rel="stylesheet" href="<?= $base ?>assets/css/shared/variables.css">
    <link rel="stylesheet" href="<?= $base ?>assets/css/shared/reset.css">
    <link rel="stylesheet" href="<?= $base ?>assets/css/client/main.css">
    
    <?php if (isset($extra_stylesheets) && is_array($extra_stylesheets)): ?>
        <?php foreach ($extra_stylesheets as $stylesheet): ?>
            <link rel="stylesheet" href="<?= htmlspecialchars($base . $stylesheet, ENT_QUOTES, 'UTF-8') ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    