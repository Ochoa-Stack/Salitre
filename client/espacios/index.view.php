<header class="espacios-header flex-center flex-col text-center">
    <div class="container">
        <h1 class="espacios-header__title fade-in">Elige tu espacio</h1>
        <p class="espacios-header__subtitle fade-in" data-delay="100">Cuatro formas distintas de vivir la costa, un solo estándar de comodidad.</p>
    </div>
</header>

<section class="espacios-catalog section-pad">
    <div class="container">
        
        <!-- Definimos un filtro de catálogo - 5 secciones -->
        <div class="espacios-filters fade-in" data-delay="200" role="tablist" aria-label="Filtrar espacios por tipo">
            <button class="btn btn-outline filter-btn active" data-tipo="todos" role="tab" aria-selected="true">Todos</button>
            <button class="btn btn-outline filter-btn" data-tipo="Estudio" role="tab" aria-selected="false">Estudios</button>
            <button class="btn btn-outline filter-btn" data-tipo="Loft" role="tab" aria-selected="false">Lofts</button>
            <button class="btn btn-outline filter-btn" data-tipo="Suite" role="tab" aria-selected="false">Suites</button>
            <button class="btn btn-outline filter-btn" data-tipo="Villa" role="tab" aria-selected="false">Villas</button>
        </div>

        <!-- Grid de Tarjetas -->
        <div class="espacios__grid js-catalog-grid" aria-live="polite">
            <?php if (empty($espacios)) : ?>
                <div class="espacios-empty">
                    <p>No hay espacios disponibles en este momento.</p>
                </div>
            <?php else : ?>
                <?php foreach ($espacios as $index => $espacio) : 
                    $amenidades = json_decode((string)$espacio['amenidades'], true) ?? [];
                    $amenidades_top = array_slice($amenidades, 0, 3);
                    $detalle_href = htmlspecialchars($base . 'client/espacios/detalle.php?slug=' . rawurlencode((string)$espacio['slug']), ENT_QUOTES, 'UTF-8');
                    $tipo = htmlspecialchars((string)$espacio['tipo'], ENT_QUOTES, 'UTF-8');
                ?>
                    <article class="space-card catalog-card fade-in" data-tipo="<?= $tipo ?>" data-delay="<?= ($index % 3) * 100 ?>">
                        <div class="space-card__media">

                            <?php if (!empty($espacio['foto_principal'])) : ?>
                                <picture>
                                    <source srcset="<?= htmlspecialchars($base . $espacio['foto_principal'], ENT_QUOTES, 'UTF-8') ?>" type="image/webp">
                                    <img src="<?= htmlspecialchars($base . $espacio['foto_principal'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars((string)$espacio['nombre'], ENT_QUOTES, 'UTF-8') ?>" loading="lazy">
                                </picture>
                            <?php else : ?>
                                <div class="img-placeholder" aria-label="Placeholder para <?= htmlspecialchars((string)$espacio['nombre'], ENT_QUOTES, 'UTF-8') ?>">
                                    <span><?= htmlspecialchars((string)$espacio['nombre'], ENT_QUOTES, 'UTF-8') ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="space-card__body">
                            <span class="space-card__badge"><?= $tipo ?></span>
                            <div class="flex-between catalog-card__title-row">
                                <h2 class="space-card__name"><?= htmlspecialchars((string)$espacio['nombre'], ENT_QUOTES, 'UTF-8') ?></h2>
                            </div>
                            
                            <div class="catalog-card__meta">
                                <span class="meta-item">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="meta-icon"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                    Capacidad: <?= (int)$espacio['capacidad'] ?> pers.
                                </span>
                            </div>

                            <?php if (!empty($amenidades_top)) : ?>
                                <ul class="catalog-card__amenities">
                                    <?php foreach ($amenidades_top as $amenidad) : ?>
                                        <li><?= htmlspecialchars((string)$amenidad, ENT_QUOTES, 'UTF-8') ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>

                            <div class="catalog-card__footer flex-between">
                                <p class="space-card__price">$<?= number_format((float)$espacio['precio_noche'], 0) ?>/noche</p>
                                <a class="btn btn-outline space-card__button" href="<?= $detalle_href ?>">Ver detalles</a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require dirname(__DIR__) . "/includes/footer.php"; ?>
