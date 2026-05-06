<div class="page-offset"></div>

<section class="detalle-page section-pad">
    <div class="container container--wide">
        <!-- Galería (100% Top) -->
        <div class="detalle-galeria fade-in" data-delay="0">
            <div class="gallery-main-container">
                <?php if (!empty($todas_fotos)) : ?>
                    <picture>
                        <source srcset="<?= htmlspecialchars($base . $todas_fotos[0], ENT_QUOTES, 'UTF-8') ?>" type="image/webp">
                        <img class="gallery-main" id="main-gallery-img" src="<?= htmlspecialchars($base . $todas_fotos[0], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars((string)$espacio['nombre'], ENT_QUOTES, 'UTF-8') ?>" data-idx="0">
                    </picture>
                <?php else : ?>
                    <div class="img-placeholder gallery-main" style="aspect-ratio: 16/9;">
                        <span><?= htmlspecialchars((string)$espacio['nombre'], ENT_QUOTES, 'UTF-8') ?> - Principal</span>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php if (count($todas_fotos) > 1) : ?>
                <div class="detalle-galeria__thumbs mt-4">
                    <?php foreach ($todas_fotos as $idx => $foto) : ?>
                        <div class="detalle-galeria__thumb <?= $idx === 0 ? 'active' : '' ?>">
                            <img src="<?= htmlspecialchars($base . $foto, ENT_QUOTES, 'UTF-8') ?>" alt="Miniatura <?= $idx+1 ?>" data-full="<?= htmlspecialchars($base . $foto, ENT_QUOTES, 'UTF-8') ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Definimos el grid de contenido en un formato de 65/35 -->
        <div class="detalle-grid mt-8">
            <!-- Info (65%) -->
            <div class="detalle-info fade-in" data-delay="100">
                <div class="detalle-header mb-6">
                    <span class="badge badge--accent"><?= htmlspecialchars((string)$espacio['tipo'], ENT_QUOTES, 'UTF-8') ?></span>
                    <h1 class="detalle-info__title mt-2"><?= htmlspecialchars((string)$espacio['nombre'], ENT_QUOTES, 'UTF-8') ?></h1>
                    <p class="detalle-precio mb-4">
                        $<?= number_format((float)$espacio['precio_noche'], 0) ?> <span class="period text-sm">/noche</span>
                    </p>
                </div>

                <div class="detalle-descripcion mb-8">
                    <h3 class="text-xl mb-4">Descripción</h3>
                    <p class="text-muted"><?= nl2br(htmlspecialchars((string)$espacio['descripcion'], ENT_QUOTES, 'UTF-8')) ?></p>
                </div>
                
                <div class="detalle-amenidades mb-8">
                    <h3 class="text-xl mb-4">Amenidades</h3>
                    <?php if (!empty($amenidades)) : ?>
                        <ul class="amenidades-list">
                            <?php foreach ($amenidades as $amenidad) : ?>
                                <li class="amenidad-item">
                                    <span class="amenidad-check text-green-600">✓</span>
                                    <span><?= htmlspecialchars((string)$amenidad, ENT_QUOTES, 'UTF-8') ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else : ?>
                        <p class="text-muted">No indicadas.</p>
                    <?php endif; ?>
                </div>

                <div class="detalle-capacidad mt-6 mb-6 p-4 bg-gray-100 rounded flex items-center gap-2">
                    <span class="capacidad-icon text-xl"></span>
                    <span>Capacidad: <?= (int)$espacio['capacidad'] ?> pers.</span>
                </div>
            </div>

            <!-- Definimos el panel de reserva sticky en un 35% -->
            <aside class="detalle-booking-panel fade-in" data-delay="200">
                <div class="booking-card">
                    <div class="booking-price text-center mb-6 pb-4 border-b">
                        <span class="price text-3xl font-bold">$<?= number_format((float)$espacio['precio_noche'], 0) ?></span>
                        <span class="period">/noche</span>
                    </div>

                    <form id="booking-form" action="<?= $base ?>client/carrito/agregar.php" method="POST">
                        <input type="hidden" name="espacio_id" value="<?= (int)$espacio['id'] ?>">
                        
                        <div class="booking-dates">
                            <div class="date-field">
                                <label for="fecha_entrada" class="block text-sm text-gray-500 mb-1">Check-in</label>
                                <input type="date" id="fecha_entrada" name="fecha_entrada" class="w-full p-2 border rounded" min="<?= date('Y-m-d') ?>" required>
                            </div>
                            <div class="date-field">
                                <label for="fecha_salida" class="block text-sm text-gray-500 mb-1">Check-out</label>
                                <input type="date" id="fecha_salida" name="fecha_salida" class="w-full p-2 border rounded" disabled required>
                            </div>
                        </div>

                        <div class="booking-capacity mt-4 text-sm text-gray-600">
                            <span>Capacidad: <?= (int)$espacio['capacidad'] ?> pers.</span>
                        </div>
                        
                        <div id="booking-total-preview" class="booking-preview mt-4 p-3 bg-gray-50 rounded" style="display:none;">
                            <div class="flex justify-between w-full">
                                <span><span id="booking-nights">0</span> noches</span>
                                <strong>$ <span id="booking-subtotal">0</span></strong>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-full w-full mt-6" id="btn-add-cart" disabled>
                            Reservar Fechas
                        </button>
                        
                        <p class="booking-note mt-3 text-center text-gray-500">
                            <small>No se realizará ningún cargo hasta confirmar.</small>
                        </p>
                    </form>
                </div>
            </aside>
        </div>
    </div>
</section>

<!-- Definimos las variables para JS -->
<script>
    window.ESPACIO_PRECIO = <?= (float)$espacio['precio_noche'] ?>;
</script>

<?php require dirname(__DIR__) . "/includes/footer.php"; ?>
