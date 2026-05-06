<!-- Definimos el footer común para todas las páginas del cliente - 'client/includes/footer.php' -->
<?php $base = BASE_URL; ?>
<footer class="footer" role="contentinfo">
    <div class="container container--wide">
        <!-- Definimos la franja superior (logo y tagline) -->
        <div class="footer-top">
            <div class="footer-logo">
                <img src="<?= BASE_URL ?>assets/img/logo/logo-white.png"
                     alt="Salitre"
                     width="120"
                     height="40">
                <p class="footer-tagline">Nah, I'd Win...</p>
            </div>
        </div>
        
        <!-- Definimos tres columnas de enlaces -->
        <div class="footer-grid">
            <!-- Definimos la columna de 'Explorar' (primer columna) -->
            <div class="footer-col">
                <h3>Explorar</h3>
                <ul>
                    <li><a href="<?= BASE_URL ?>client/espacios/index.php">Espacios</a></li>
                    <li><a href="<?= BASE_URL ?>client/servicios/index.php">Servicios</a></li>
                    <li><a href="<?= BASE_URL ?>client/agenda/index.php">Agenda</a></li>
                    <li><a href="<?= BASE_URL ?>client/proyecto/index.php">Proyecto</a></li>
                </ul>
            </div>
            
            <!-- Definimos la columna de 'Información' (segunda columna) -->
            <div class="footer-col">
                <h3>Información</h3>
                <ul>
                    <li><a href="<?= BASE_URL ?>client/proyecto/index.php#intro">Proyecto</a></li>
                    <li><a href="<?= BASE_URL ?>client/proyecto/index.php#conocenos">Conócenos</a></li>
                    <li><a href="<?= BASE_URL ?>client/proyecto/index.php#ubicacion">Ubicación</a></li>
                    <li><a href="<?= BASE_URL ?>client/contacto/index.php">Contacto</a></li>
                    <li><a href="<?= BASE_URL ?>admin/login.php">Portal Staff</a></li>
                </ul>
            </div>
            
            <!-- Definimos la columna de 'Contáctanos' (tercer columna) -->
            <div class="footer-col">
                <h3>Contáctanos</h3>
                <ul class="footer-contact">
                    <li>Costa Mexicana, México</li>
                    <li>+00 000 000 0000</li>
                    <li>contacto@salitre.mx</li>
                    <li>Check In: 15:00 · Check Out: 11:00</li>
                </ul>
            </div>
        </div>
        
        <!-- Definimos la franja inferior (copyright y redes) -->
        <div class="footer-bottom">
            <p>&copy; <?= date("Y") ?> Salitre. Todos los derechos reservados.</p>
            <div class="footer-social">
                <a href="#" aria-label="Facebook">FB</a>
                <a href="#" aria-label="Instagram">IG</a>
                <a href="#" aria-label="Twitter">YT</a>
            </div>
        </div>
    </div>
</footer>

<script src="<?= $base ?>assets/js/shared/animations.js" defer></script>
<script src="<?= $base ?>assets/js/shared/alerts.js" defer></script>
<script src="<?= $base ?>assets/js/client/main.js" defer></script>

<?php if (isset($extra_scripts) && is_array($extra_scripts)): ?>
    <?php foreach ($extra_scripts as $script): ?>
        <script src="<?= htmlspecialchars($base . $script, ENT_QUOTES, 'UTF-8') ?>" defer></script>
    <?php endforeach; ?>
<?php endif; ?>

</body>
</html>
