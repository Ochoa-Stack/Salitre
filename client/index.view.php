<!-- Definimos las secciones hero de video, recursos adicionales y otras secciones -->
<section id="hero" class="hero">
  <video class="hero__video" autoplay muted loop playsinline>
    <source src="<?= $base ?>assets/video/hero-bg.mp4" type="video/mp4">
    <source src="<?= $base ?>assets/video/hero-bg.webm" type="video/webm">
  </video>
  <div class="hero__overlay" aria-hidden="true"></div>
  <div class="hero__content">
    <div class="index-container">
      <p class="hero__tag">Hotel Boutique · Costa Mexicana</p>
      <h1 class="hero__title">Sal de la oficina.<br>
      <span class="hero__title-accent">No del trabajo.</span></h1>
      <p class="hero__lead">Fibra óptica garantizada. Mobiliario ergonómico. El mar a metros.</p>
      <div class="hero__actions">
        <a class="btn btn-primary" href="<?= htmlspecialchars($base . 'client/espacios/', ENT_QUOTES, 'UTF-8') ?>"> Ver Espacios </a>
        <a class="btn btn-secondary" href="#propuesta"> Conocer Más </a>
      </div>
    </div>
  </div>
</section>

<section id="propuesta" class="propuesta fade-in">
  <div class="index-container">
    <div class="propuesta__grid">
      
    <article class="feature">
      <div class="feature__icon" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
          <path d="M5 12.55a11 11 0 0 1 14.08 0"/>
          <path d="M1.42 9a16 16 0 0 1 21.16 0"/>
          <path d="M8.53 16.11a6 6 0 0 1 6.95 0"/>
          <circle cx="12" cy="20" r="1" fill="currentColor"/>
        </svg>
      </div>
      <h3 class="feature__title">Conectividad</h3>
      <p class="feature__text">Fibra óptica garantizada en cada espacio.</p>
    </article>
    
    <article class="feature">
      <div class="feature__icon" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
          <rect x="2" y="3" width="20" height="14" rx="2"/>
          <line x1="8" y1="21" x2="16" y2="21"/>
          <line x1="12" y1="17" x2="12" y2="21"/>
        </svg>
      </div>
      <h3 class="feature__title">Ergonomía</h3>
      <p class="feature__text">Escritorios reales. Sillas corporativas. Monitores externos.</p>
    </article>
    
    <article class="feature">
      <div class="feature__icon" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
          <line x1="3" y1="12" x2="21" y2="12"/>
          <line x1="3" y1="6"  x2="21" y2="6"/>
          <line x1="3" y1="18" x2="21" y2="18"/>
        </svg>
      </div>
      <h3 class="feature__title">Diseño</h3>
      <p class="feature__text">Minimalismo funcional. Sin distracciones.</p>
    </article>
  
  </div>
</div>
</section>

<section id="espacios" class="espacios fade-in">
  <div class="index-container">
    <h2 class="section-title">Elige tu espacio</h2>
    <div class="espacios__grid">
<?php if (empty($espacios)) : ?>
      <p>Espacios disponibles próximamente.</p>
<?php else : ?>
<?php foreach ($espacios as $espacio) :
    $detalle_href = htmlspecialchars(
        $base . 'client/espacios/detalle.php?slug=' . rawurlencode((string) $espacio['slug']),
        ENT_QUOTES, 'UTF-8'
    );
?>
      <article class="space-card">
        <div class="space-card__media">
<?php if (!empty($espacio['foto_principal'])) : ?>
          <picture>
            <source srcset="<?= htmlspecialchars($base . $espacio['foto_principal'], ENT_QUOTES, 'UTF-8') ?>"
                    type="image/webp">
            <img src="<?= htmlspecialchars($base . $espacio['foto_principal'], ENT_QUOTES, 'UTF-8') ?>"
                 alt="<?= htmlspecialchars((string) $espacio['nombre'], ENT_QUOTES, 'UTF-8') ?>"
                 loading="lazy">
          </picture>
<?php else : ?>
          <div class="space-card__placeholder" aria-hidden="true"></div>
<?php endif; ?>
        </div>
        <div class="space-card__body">
          <span class="space-card__badge"><?= htmlspecialchars((string) $espacio['tipo'], ENT_QUOTES, 'UTF-8') ?></span>
          <h3 class="space-card__name"><?= htmlspecialchars((string) $espacio['nombre'], ENT_QUOTES, 'UTF-8') ?></h3>
          <p class="space-card__price">$<?= number_format((float) $espacio['precio_noche'], 0) ?>/noche</p>
          <a class="btn btn-primary space-card__button" href="<?= $detalle_href ?>">Ver espacio</a>
        </div>
      </article>
<?php endforeach; ?>
<?php endif; ?>
    </div>
  </div>
</section>

<section id="servicios-cta" class="servicios-cta fade-in" style="padding: var(--space-16) 0; background-color: var(--color-surface); text-align: center; border-top: 1px solid var(--color-border); border-bottom: 1px solid var(--color-border);">
  <div class="index-container">
    <h2 class="section-title" style="margin-bottom: var(--space-4);">Más que una habitación</h2>
    <p style="color: var(--color-text-muted); max-width: 600px; margin: 0 auto var(--space-8); font-size: var(--text-lg);">Descubre todas las amenidades, desde espacios de coworking hasta clases de yoga frente al mar, diseñadas para que tu trabajo y descanso fluyan.</p>
    <a href="<?= $base ?>client/servicios/index.php" class="btn btn-primary" style="padding: var(--space-4) var(--space-8); font-size: var(--text-base);">Ver todos los servicios</a>
  </div>
</section>

<section class="tagline fade-in" aria-hidden="true">
  <div class="index-container">
    <h2 class="tagline__text">El mar de fondo.<br>Tú en primer plano.</h2>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
