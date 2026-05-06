<div class="page-offset"></div>

<section class="auth-section section-pad flex-center">
    <div class="auth-card fade-in" data-delay="100">
        
        <h1 class="auth-card__title">Crear Cuenta</h1>
        <p class="auth-card__subtitle text-muted">Acelera tus reservas como Huésped Registrado.</p>

        <?php if (isset($_GET['error'])) : ?>
            <div class="alert alert--error mb-4">
                <?php if ($_GET['error'] === 'email_exists') : ?>
                    <p>Este correo ya está registrado.</p>
                <?php elseif ($_GET['error'] === 'passwords_mismatch') : ?>
                    <p>Las contraseñas no coinciden.</p>
                <?php elseif ($_GET['error'] === 'short_password') : ?>
                    <p>La contraseña debe tener al menos 6 caracteres.</p>
                <?php elseif ($_GET['error'] === 'empty_fields') : ?>
                    <p>Todos los campos son obligatorios.</p>
                <?php elseif ($_GET['error'] === 'invalid_email') : ?>
                    <p>El formato del correo es inválido.</p>
                <?php else : ?>
                    <p>Ups, ha ocurrido un error. Intenta de nuevo.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <form action="<?= $base ?>client/auth/procesar_auth.php" method="POST" class="auth-form" onsubmit="return validatePasswords()">
            <input type="hidden" name="action" value="registro">

            <div class="field">
                <label for="nombre" class="field__label">Nombre completo</label>
                <input type="text" id="nombre" name="nombre" class="field__input" required autocomplete="name" placeholder="nombre apellido">
            </div>

            <div class="field">
                <label for="email" class="field__label">Correo electrónico</label>
                <input type="email" id="email" name="email" class="field__input" required autocomplete="email" placeholder="correo@dominio.com">
            </div>

            <div class="field">
                <label for="password" class="field__label">Contraseña</label>
                <input type="password" id="password" name="password" class="field__input" required minlength="6" placeholder="Mínimo 6 caracteres">
            </div>

            <div class="field">
                <label for="confirmar_password" class="field__label">Confirmar Contraseña</label>
                <input type="password" id="confirmar_password" name="confirmar_password" class="field__input" required minlength="6" placeholder="Confirma tu contraseña">
            </div>

            <button type="submit" class="btn btn-primary w-full mt-4" style="justify-content:center;">Registrarme</button>
        </form>

        <div class="auth-card__footer mt-6 text-center text-sm">
            <p class="text-muted">¿Ya tienes cuenta? <a href="<?= $base ?>client/auth/login.php" class="text-accent fw-600">Inicia Sesión</a></p>
        </div>

    </div>
</section>

<script>
function validatePasswords() {
    var pwd1 = document.getElementById("password").value;
    var pwd2 = document.getElementById("confirmar_password").value;
    if (pwd1 !== pwd2) {
        if (window.showAlert) {
            window.showAlert.show('error', 'Error de registro', 'Las contraseñas no coinciden. Por favor, corrígelo.');
        } else {
            alert("Las contraseñas no coinciden. Por favor, corrígelo.");
        }
        return false;
    }
    return true;
}
</script>

<?php require dirname(__DIR__) . "/includes/footer.php"; ?>
