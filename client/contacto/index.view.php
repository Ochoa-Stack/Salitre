<div class="page-offset"></div>

<main class="contacto-page">
    <section id="contacto" class="contacto fade-in">
      <div class="container">
        <div class="contacto__grid">

          <div class="contacto__left">
            <h1 class="section-title">¿Tienes dudas?</h1>

    <?php if (isset($_GET['contacto']) && $_GET['contacto'] === 'ok') : ?>
            <p class="contacto__text alert--success" role="status">Tu mensaje fue enviado. Te respondemos pronto.</p>
    <?php elseif (isset($_GET['contacto']) && $_GET['contacto'] === 'error') : ?>
            <p class="contacto__text alert--error" role="alert">No pudimos enviar tu mensaje. Inténtalo de nuevo.</p>
    <?php endif; ?>

            <!-- Formulario de contacto - 'procesar_contacto.php' en 'client/includes/' -->
            <form class="contact-form" method="post"
                  action="<?= htmlspecialchars($base . 'client/includes/procesar_contacto.php', ENT_QUOTES, 'UTF-8') ?>">
              <div class="field">
                <label class="field__label" for="contacto-nombre">Nombre</label>
                <input class="field__input" type="text" id="contacto-nombre" name="nombre"
                       required maxlength="100" autocomplete="name">
              </div>
              <div class="field">
                <label class="field__label" for="contacto-email">Email</label>
                <input class="field__input" type="email" id="contacto-email" name="email"
                       required maxlength="150" autocomplete="email">
              </div>
              <div class="field">
                <label class="field__label" for="contacto-mensaje">Mensaje</label>
                <textarea class="field__textarea" id="contacto-mensaje" name="mensaje"
                          maxlength="500" required></textarea>
              </div>
              <button class="btn btn-primary btn-submit-contacto" type="submit">Enviar Mensaje</button>
            </form>
          </div>

          <div class="contacto__right">
            <h3 class="contacto__heading">Contactanos</h3>
            <div class="contacto__info-list">
                <p class="contacto__text">Costa del Pacífico, México</p>
                <p class="contacto__text">+00 000 000 0000</p>
                <p class="contacto__text">hola@salitre.mx</p>
                <div class="contacto__schedule">
                    <p class="contacto__text"><strong>Check In:</strong> 3:00pm</p>
                    <p class="contacto__text"><strong>Check Out:</strong> 12:00pm</p>
                </div>
            </div>
          </div>

        </div>
      </div>
    </section>
</main>

<?php require_once dirname(__DIR__) . "/includes/footer.php"; ?>
