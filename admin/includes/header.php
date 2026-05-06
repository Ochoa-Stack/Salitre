<?php
/* Definimos la cabecera común para todas las páginas del admin */
declare(strict_types=1);
$page_title   = $page_title ?? 'Panel · Hotel Salitre';
$staff_nombre = isset($_SESSION['staff_nombre']) ? htmlspecialchars((string) $_SESSION['staff_nombre'], ENT_QUOTES, 'UTF-8') : 'Staff';
$staff_rol    = isset($_SESSION['staff_rol'])    ? htmlspecialchars((string) $_SESSION['staff_rol'],    ENT_QUOTES, 'UTF-8') : '';
$base         = BASE_URL;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8'); ?></title>
  <link rel="icon" type="image/png" sizes="32x32" href="<?= $base ?>assets/img/logo/favicon.png">

  <!-- Mandamos a llamar a las hojas de estilo unicas y globales -->
  <link rel="stylesheet" href="<?= $base ?>assets/css/shared/variables.css">
  <!-- Mandamos a llamar al reset cross-browser -->
  <link rel="stylesheet" href="<?= $base ?>assets/css/shared/reset.css">
  <!-- Mandamos a llamar al estilo principal del admin -->
  <link rel="stylesheet" href="<?= $base ?>assets/css/admin/main.css">

  <?php if (!empty($extra_css) && is_array($extra_css)) : ?>
    <?php foreach ($extra_css as $css) : ?>
      <link rel="stylesheet" href="<?= $base . htmlspecialchars(ltrim((string) $css, '/'), ENT_QUOTES, 'UTF-8') ?>">
    <?php endforeach; ?>
  <?php endif; ?>
</head>
<body class="admin-body">
