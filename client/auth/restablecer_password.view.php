<div class="page-offset"></div>

<section class="auth-section section-pad flex-center">
    <div class="auth-card fade-in">
        
        <h1 class="auth-card__title">Nueva Contraseña</h1>
        <p class="auth-card__subtitle text-muted">Ingresa tu nueva contraseña para acceder a tu cuenta.</p>

        <?php if (!empty($error)) : ?>
            <div class="alert alert--error mb-4">
                <p><?= htmlspecialchars($error) ?></p>
            </div>
        <?php endif; ?>

        <form action="" method="POST" class="auth-form">
            <?php if(empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); ?>
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token_val) ?>">

            <div class="field">
                <label for="password" class="field__label">Nueva Contraseña</label>
                <input type="password" id="password" name="password" class="field__input" required minlength="8" placeholder="Mínimo 8 caracteres">
            </div>

            <div class="field">
                <label for="confirm_password" class="field__label">Confirmar Nueva Contraseña</label>
                <input type="password" id="confirm_password" name="confirm_password" class="field__input" required minlength="8" placeholder="Confirma tu contraseña">
            </div>

            <button type="submit" class="btn btn-primary btn-full-center">Cambiar Contraseña</button>
        </form>

    </div>
</section>

<?php require dirname(__DIR__) . "/includes/footer.php"; ?>
