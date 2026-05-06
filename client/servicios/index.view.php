<main class="servicios-page page-offset">
    <section id="servicios" class="servicios fade-in">
        <div class="index-container">
            <h1 class="section-title" style="text-align:center; font-size:var(--text-4xl); margin-bottom:var(--space-12);">Más que una habitación</h1>
            <div class="servicios__grid">
<?php foreach ($services as $svc) : ?>
                <article class="service-card">
                    <div class="service-card__icon" aria-hidden="true">
                        <?= $service_icons[$svc['key']] ?? '' ?>
                    </div>
                    <h3 class="service-card__title"><?= htmlspecialchars($svc['nombre'], ENT_QUOTES, 'UTF-8') ?></h3>
                    <p class="service-card__text"><?= htmlspecialchars($svc['descripcion'], ENT_QUOTES, 'UTF-8') ?></p>
                </article>
<?php endforeach; ?>
            </div>
        </div>
    </section>
</main>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
