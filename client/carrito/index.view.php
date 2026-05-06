<div class="page-offset"></div>

<main class="carrito-page">
    <div class="container container--wide">
        
        <div class="text-center mb-8 fade-in">
            <h1 class="section-title">Confirma tu reserva</h1>
            <p class="text-muted">Asegura tu lugar en la costa y mejora tu productividad.</p>
        </div>

        <div class="carrito-grid">
            
            <!-- Definimos la columna izquierda -->
            <div class="carrito-info fade-in" data-delay="100">
                <div class="carrito-foto">
                    <?php if (!empty($espacio['foto_principal'])) : ?>
                        <picture>
                            <source srcset="<?= htmlspecialchars($base . str_replace('.jpg', '.webp', $espacio['foto_principal']), ENT_QUOTES, 'UTF-8') ?>" type="image/webp">
                            <img src="<?= htmlspecialchars($base . $espacio['foto_principal'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars((string)$espacio['nombre'], ENT_QUOTES, 'UTF-8') ?>" loading="lazy">
                        </picture>
                    <?php else : ?>
                        <div class="img-placeholder">
                            <span><?= htmlspecialchars((string)$espacio['nombre'], ENT_QUOTES, 'UTF-8') ?></span>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="carrito-detalles">
                    <span class="badge badge--accent mb-2"><?= htmlspecialchars((string)$espacio['tipo'], ENT_QUOTES, 'UTF-8') ?></span>
                    <h2><?= htmlspecialchars((string)$espacio['nombre'], ENT_QUOTES, 'UTF-8') ?></h2>
                    
                    <div class="carrito-fechas text-sm text-muted">
                        <div>Check In: <strong><?= date('d M, Y', strtotime($cart['fecha_entrada'])) ?></strong></div>
                        <div>Check Out: <strong><?= date('d M, Y', strtotime($cart['fecha_salida'])) ?></strong></div>
                    </div>
                </div>
            </div>

            <!-- Definimos la columna derecha -->
            <div class="carrito-resumen fade-in" data-delay="200">
                <h2>Desglose de Costos</h2>
                
                <div class="desglose-row">
                    <span>$<?= number_format((float)$espacio['precio_noche'], 0) ?> x <?= $cart['noches'] ?> noches</span>
                    <span>$<?= number_format((float)$cart['subtotal'], 2) ?></span>
                </div>
                <div class="desglose-row text-muted">
                    <span>Fee de limpieza e integración</span>
                    <span>$<?= number_format((float)$cart['limpieza'], 2) ?></span>
                </div>
                <div class="desglose-row text-muted pb-3">
                    <span>Impuestos (IVA <?= IVA * 100 ?>%)</span>
                    <span>$<?= number_format((float)$cart['iva'], 2) ?></span>
                </div>
                <div class="desglose-row total">
                    <span class="text-lg fw-600">Total</span>
                    <span class="value">$<?= number_format((float)$cart['total'], 2) ?></span>
                </div>

                <form id="form-carrito-checkout" action="<?= $base ?>client/carrito/procesar_reserva.php" method="POST">
                    <input type="hidden" name="cart_intent" value="1">
                    <button type="submit" class="btn btn-primary btn-lg w-full mt-6" id="btn-request">Solicitar Reserva</button>
                </form>

                <div class="carrito-politicas mt-6">
                    <h3>Políticas de Cancelación</h3>
                    <p>Reembolso del 100% si cancelas al menos 72 horas antes del check-in. De lo contrario se cobrará la primera noche.</p>
                </div>

                <div class="carrito-seguridad mt-6" style="text-align: center; border-top: 1px solid var(--color-border); padding-top: 1.5rem;">
                    <p style="font-size: 0.8125rem; color: var(--color-text-muted); margin-bottom: 0.8rem; font-weight: 500;">Transacción Segura y Encriptada</p>
                    <div style="display: flex; gap: 0.85rem; justify-content: center; align-items: center; flex-wrap: wrap; opacity: 0.8;">
                        <img src="<?= $base ?>assets/img/payments/visa.svg" alt="Visa" style="height: 20px;">
                        <img src="<?= $base ?>assets/img/payments/mastercard.svg" alt="Mastercard" style="height: 20px;">
                        <img src="<?= $base ?>assets/img/payments/amex.svg" alt="American Express" style="height: 20px;">
                        <img src="<?= $base ?>assets/img/payments/paypal.svg" alt="PayPal" style="height: 20px;">
                        <img src="<?= $base ?>assets/img/payments/oxxo.svg" alt="OXXO" style="height: 20px;">
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>

<?php require dirname(__DIR__) . "/includes/footer.php"; ?>
