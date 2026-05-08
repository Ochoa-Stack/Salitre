<main class="pago-page">
    <div class="container container--wide">
        <h1>Confirmar Reserva y Pago</h1>

        <?php if (isset($_SESSION['pago_error'])): ?>
            <div class="alert alert--error mb-6">
                <?= htmlspecialchars($_SESSION['pago_error']) ?>
                <?php unset($_SESSION['pago_error']); ?>
            </div>
        <?php endif; ?>

        <div class="pago-container">
            <!-- Columna Resumen -->
            <section class="pago-resumen">
                <h2>Resumen de Reserva</h2>
                <div class="resumen-detalle">
                    <div class="resumen-item">
                        <span>Espacio:</span>
                        <span class="fw-600"><?= htmlspecialchars($carrito['espacio_id']) ?> (ID)</span>
                    </div>
                    <div class="resumen-item">
                        <span>Check-in:</span>
                        <span><?= date("d/m/Y", strtotime($carrito['fecha_entrada'])) ?></span>
                    </div>
                    <div class="resumen-item">
                        <span>Check-out:</span>
                        <span><?= date("d/m/Y", strtotime($carrito['fecha_salida'])) ?></span>
                    </div>
                    <div class="resumen-item">
                        <span>Noches:</span>
                        <span><?= $carrito['noches'] ?></span>
                    </div>
                    <div class="resumen-item">
                        <span>Subtotal:</span>
                        <span>$<?= number_format($carrito['subtotal'], 2) ?></span>
                    </div>
                    <div class="resumen-item">
                        <span>Limpieza:</span>
                        <span>$<?= number_format($carrito['limpieza'], 2) ?></span>
                    </div>
                    <div class="resumen-item">
                        <span>IVA:</span>
                        <span>$<?= number_format($carrito['iva'], 2) ?></span>
                    </div>
                    <div class="resumen-item total">
                        <span>Total a pagar:</span>
                        <span>$<?= number_format($carrito['total'], 2) ?></span>
                    </div>
                </div>
            </section>

            <!-- Columna Formulario -->
            <section class="pago-formulario">
                <h2>Método de Pago</h2>
                <form action="<?= BASE_URL ?>client/carrito/procesar_pago.php" method="POST" id="payment-form">
                    <?php if(empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); ?>
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <input type="hidden" name="metodo_pago" id="metodo_pago_hidden" value="tarjeta">

                    <div class="metodo-selector">
                        <div class="metodo-option active" data-metodo="tarjeta">
                            <img src="<?= BASE_URL ?>assets/img/payments/visa.svg" alt="Tarjeta">
                            <span>Tarjeta</span>
                        </div>
                        <div class="metodo-option" data-metodo="oxxo">
                            <img src="<?= BASE_URL ?>assets/img/payments/oxxo.svg" alt="OXXO">
                            <span>OXXO</span>
                        </div>
                        <div class="metodo-option" data-metodo="paypal">
                            <img src="<?= BASE_URL ?>assets/img/payments/paypal.svg" alt="PayPal">
                            <span>PayPal</span>
                        </div>
                    </div>

                    <div id="redirect-message" style="display:none;" class="alert alert--info mb-4">
                        Serás redirigido al método seleccionado tras confirmar.
                    </div>

                    <div id="card-fields" class="card-fields active">
                        <div class="card-brand-display">
                            <img src="<?= BASE_URL ?>assets/img/payments/visa.svg" id="brand-visa" alt="Visa">
                            <img src="<?= BASE_URL ?>assets/img/payments/mastercard.svg" id="brand-mastercard" alt="Mastercard">
                            <img src="<?= BASE_URL ?>assets/img/payments/amex.svg" id="brand-amex" alt="Amex">
                        </div>

                        <div class="field">
                            <label for="card_number" class="field__label">Número de Tarjeta</label>
                            <input type="text" id="card_number" name="numero_tarjeta" class="field__input" placeholder="0000 0000 0000 0000" maxlength="19">
                        </div>

                        <div class="field">
                            <label for="card_name" class="field__label">Nombre en la Tarjeta</label>
                            <input type="text" id="card_name" name="nombre_tarjeta" class="field__input" placeholder="JUAN PEREZ">
                        </div>

                        <div class="form-row">
                            <div class="field">
                                <label for="card_exp" class="field__label">Vencimiento (MM/AA)</label>
                                <input type="text" id="card_exp" name="expiracion" class="field__input" placeholder="MM/AA" maxlength="5">
                            </div>
                            <div class="field">
                                <label for="card_cvv" class="field__label">CVV</label>
                                <input type="password" id="card_cvv" name="cvv" class="field__input" placeholder="123" maxlength="4">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-pago-submit" id="submit-button">
                        <span class="spinner"></span>
                        <span id="button-text">Pagar $<?= number_format($carrito['total'], 2) ?></span>
                    </button>

                    <div class="demo-note">
                        Entorno de demostración. Ningún cargo real será realizado.
                    </div>

                    <details class="test-cards">
                        <summary>Ver tarjetas de prueba</summary>
                        <ul>
                            <li>Visa: 4242 4242 4242 4242</li>
                            <li>Mastercard: 5555 5555 5555 4444</li>
                            <li>AMEX: 3782 822463 10005</li>
                        </ul>
                    </details>
                </form>
            </section>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const metodoOptions = document.querySelectorAll('.metodo-option');
    const metodoHidden = document.getElementById('metodo_pago_hidden');
    const cardFields = document.getElementById('card-fields');
    const redirectMsg = document.getElementById('redirect-message');
    const paymentForm = document.getElementById('payment-form');
    const submitBtn = document.getElementById('submit-button');
    const cardNumber = document.getElementById('card_number');
    const cardExp = document.getElementById('card_exp');
    const brands = {
        visa: document.getElementById('brand-visa'),
        mastercard: document.getElementById('brand-mastercard'),
        amex: document.getElementById('brand-amex')
    };

    // Cambio de método de pago
    metodoOptions.forEach(opt => {
        opt.addEventListener('click', () => {
            metodoOptions.forEach(o => o.classList.remove('active'));
            opt.classList.add('active');
            const metodo = opt.dataset.metodo;
            metodoHidden.value = metodo;

            if (metodo === 'tarjeta') {
                cardFields.classList.add('active');
                redirectMsg.style.display = 'none';
            } else {
                cardFields.classList.remove('active');
                redirectMsg.style.display = 'block';
            }
        });
    });

    // Formateo de número de tarjeta y detección de marca
    cardNumber.addEventListener('input', (e) => {
        let value = e.target.value.replace(/\D/g, '');
        
        // Detección de marca
        Object.values(brands).forEach(b => b.classList.remove('active'));
        if (value.startsWith('4')) brands.visa.classList.add('active');
        else if (/^5[1-5]/.test(value)) brands.mastercard.classList.add('active');
        else if (/^3[47]/.test(value)) brands.amex.classList.add('active');

        // Formateo con espacios
        let formatted = '';
        if (value.startsWith('34') || value.startsWith('37')) {
            // AMEX: 4-6-5
            for (let i = 0; i < value.length; i++) {
                if (i === 4 || i === 10) formatted += ' ';
                formatted += value[i];
            }
        } else {
            // Estándar: 4-4-4-4
            for (let i = 0; i < value.length; i++) {
                if (i > 0 && i % 4 === 0) formatted += ' ';
                formatted += value[i];
            }
        }
        e.target.value = formatted.trim();
    });

    // Formateo de expiración
    cardExp.addEventListener('input', (e) => {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 2) {
            e.target.value = value.substring(0, 2) + '/' + value.substring(2, 4);
        } else {
            e.target.value = value;
        }
    });

    // Validación de Luhn
    function validateLuhn(number) {
        let sum = 0;
        let shouldDouble = false;
        for (let i = number.length - 1; i >= 0; i--) {
            let digit = parseInt(number.charAt(i));
            if (shouldDouble) {
                if ((digit *= 2) > 9) digit -= 9;
            }
            sum += digit;
            shouldDouble = !shouldDouble;
        }
        return (sum % 10) === 0;
    }

    // Submit del formulario
    paymentForm.addEventListener('submit', function(e) {
        if (metodoHidden.value === 'tarjeta') {
            const rawNumber = cardNumber.value.replace(/\s/g, '');
            const name = document.getElementById('card_name').value.trim();
            const exp = cardExp.value;
            const cvv = document.getElementById('card_cvv').value;

            let error = '';
            if (!validateLuhn(rawNumber) || rawNumber.length < 13) error = 'Número de tarjeta inválido.';
            else if (!name) error = 'Ingresa el nombre del titular.';
            else if (!/^\d{2}\/\d{2}$/.test(exp)) error = 'Formato de fecha inválido (MM/AA).';
            else if (cvv.length < 3) error = 'CVV inválido.';

            if (error) {
                e.preventDefault();
                alert(error);
                return;
            }
        }

        // Efecto visual de procesamiento
        submitBtn.disabled = true;
        submitBtn.classList.add('btn-processing');
        document.getElementById('button-text').innerText = 'Procesando pago...';
    });
});
</script>

<?php require_once "../includes/footer.php"; ?>
