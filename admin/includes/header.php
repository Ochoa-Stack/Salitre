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
  <link rel="icon" type="image/png" sizes="32x32" href="/assets/img/logo/favicon.png">

  <!-- Importamos Inter desde Google Fonts para consistencia tipográfica entre módulos -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

  <!-- Mandamos a llamar a las hojas de estilo unicas y globales -->
  <link rel="stylesheet" href="/assets/css/shared/variables.css">
  <link rel="stylesheet" href="/assets/css/shared/reset.css">
  <link rel="stylesheet" href="/assets/css/admin/main.css">

  <?php if (!empty($extra_css) && is_array($extra_css)) : ?>
    <?php foreach ($extra_css as $css) : ?>
      <?php $style_path = str_starts_with($css, '/') ? $css : '/' . $css; ?>
      <link rel="stylesheet" href="<?= htmlspecialchars($style_path, ENT_QUOTES, 'UTF-8'); ?>">
    <?php endforeach; ?>
  <?php endif; ?>
</head>
<body class="admin-body">
