<div class="page-offset"></div>

<section class="auth-section section-pad flex-center">
    <div class="auth-card fade-in">
        
        <h1 class="auth-card__title">Recuperar Contraseña</h1>
        <p class="auth-card__subtitle text-muted">Ingresa tu correo para recibir un enlace de restablecimiento.</p>

        <?php if (!empty($message)) : ?>
            <div class="alert alert--success mb-4">
                <p><?= htmlspecialchars($message) ?></p>
            </div>
        <?php endif; ?>

        <?php if (!empty($error)) : ?>
            <div class="alert alert--error mb-4">
                <p><?= htmlspecialchars($error) ?></p>
            </div>
        <?php endif; ?>

        <form action="" method="POST" class="auth-form">
            <?php /* Insertamos el token CSRF generado en el controlador */ ?>
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">

            <div class="field">
                <label for="email" class="field__label">Correo Electrónico</label>
                <input type="email" id="email" name="email" class="field__input" required autocomplete="email" placeholder="correo@dominio.com">
            </div>

            <button type="submit" class="btn btn-primary btn-full-center">Enviar Enlace</button>
        </form>

        <div class="auth-card__footer mt-6 text-center text-sm">
            <p class="text-muted">¿Recordaste tu contraseña? <a href="<?= $base ?>client/auth/login.php" class="text-accent fw-600">Inicia Sesión</a></p>
        </div>

    </div>
</section>

<?php require dirname(__DIR__) . "/includes/footer.php"; ?>
