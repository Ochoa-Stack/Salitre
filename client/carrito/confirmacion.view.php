<main class="confirmacion-page">
    <div class="container container--wide">
        <div class="confirmacion-card">
            <div class="confirmacion-icon">✓</div>
            <h1>¡Reserva Solicitada!</h1>
            <p class="confirmacion-subtitle">
                Hemos recibido tu solicitud. Te contactaremos pronto para confirmar.
            </p>
            
            <div class="confirmacion-detalles">
                <div class="detalle-row">
                    <span class="label">Número de Folio:</span>
                    <span class="value">#<?= str_pad((string)$reserva["id"], 6, "0", STR_PAD_LEFT) ?></span>
                </div>
                <div class="detalle-row">
                    <span class="label">Espacio:</span>
                    <span class="value"><?= htmlspecialchars($reserva["espacio_nombre"]) ?></span>
                </div>
                <div class="detalle-row">
                    <span class="label">Check-in:</span>
                    <span class="value"><?= date("d/m/Y", strtotime($reserva["fecha_entrada"])) ?></span>
                </div>
                <div class="detalle-row">
                    <span class="label">Check-out:</span>
                    <span class="value"><?= date("d/m/Y", strtotime($reserva["fecha_salida"])) ?></span>
                </div>
                <div class="detalle-row">
                    <span class="label">Noches:</span>
                    <span class="value"><?= $reserva["noches"] ?></span>
                </div>
                <div class="detalle-row total">
                    <span class="label">Total pagado:</span>
                    <span class="value">$<?= number_format((float)$reserva["precio_total"], 2) ?> MXN</span>
                </div>
                <?php if ($pago_resumen): ?>
                    <div class="detalle-row" style="border-top: 1px dashed var(--neutral-200); margin-top: 10px; padding-top: 10px;">
                        <span class="label">Método de Pago:</span>
                        <span class="value">
                            <?= ucfirst($pago_resumen['metodo']) ?> 
                            <?php if ($pago_resumen['ultimos4']): ?>
                                <?= htmlspecialchars($pago_resumen['marca']) ?> ****<?= $pago_resumen['ultimos4'] ?>
                            <?php endif; ?>
                        </span>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="confirmacion-notas">
                <h3>Próximos pasos:</h3>
                <ol>
                    <li>Recibirás un email con los detalles de acceso en breve.</li>
                    <li>El staff te contactará si hay alguna actualización climática.</li>
                    <li>Tu pago ha sido procesado y confirmado exitosamente.</li>
                </ol>
            </div>
            
            <div class="confirmacion-actions">
                <a href="<?= BASE_URL ?>client/auth/perfil.php" class="btn btn-outline">
                    Ver en Mi Perfil
                </a>
                <a href="<?= BASE_URL ?>client/index.php" class="btn btn-primary">
                    Volver al Inicio
                </a>
            </div>
        </div>
    </div>
</main>

<?php require_once "../includes/footer.php"; ?>
