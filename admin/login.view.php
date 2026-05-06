<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Acceso - Panel Salitre</title>
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/admin/auth.css">
</head>
<body class="auth-body">

  <div class="auth-card">
    <div class="auth-card__brand">
      <svg class="auth-card__icon" width="32" height="32" viewBox="0 0 24 24" fill="none">
        <path d="M12 2.5c3.9 0 7 3.1 7 7 0 5.5-7 12-7 12S5 15 5 9.5c0-3.9 3.1-7 7-7Z"
              stroke="currentColor" stroke-width="1.5"/>
        <path d="M9.2 10.3c.6-1.5 2.1-2.6 3.8-2.6 1.2 0 2.3.5 3 1.4"
              stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
      </svg>
      <span class="auth-card__title">Panel de Control - Salitre</span>
    </div>

    <p class="auth-card__subtitle">Acceso Restringido - Solo Personal Autorizado</p>

<?php if ($error !== '') : ?>
    <p class="auth-error" role="alert"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
<?php endif; ?>

    <form class="auth-form" method="post" action="" novalidate>
      <div class="auth-form__group">
        <label class="auth-form__label" for="email">Correo electrónico</label>
        <input
          class="auth-form__input"
          type="email"
          id="email"
          name="email"
          required
          maxlength="150"
          autocomplete="email"
          value="<?php echo htmlspecialchars((string) ($_POST['email'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="auth-form__group">
        <label class="auth-form__label" for="password">Contraseña</label>
        <input
          class="auth-form__input"
          type="password"
          id="password"
          name="password"
          required
          maxlength="100"
          autocomplete="current-password">
      </div>
      <button class="auth-form__submit" type="submit">Ingresar</button>
    </form>
  </div>

</body>
</html>
