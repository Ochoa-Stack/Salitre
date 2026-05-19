<!-- Definimos el footer común para todas las páginas del cliente - 'client/includes/footer.php' -->
<?php $base = BASE_URL; ?>
<footer class="footer" role="contentinfo">
    <div class="container container--wide">
        <!-- Definimos la franja superior (logo y tagline) -->
        <div class="footer-top">
            <div class="footer-logo">
                <img src="/assets/img/logo/logo-white.png"
                     alt="Salitre"
                     width="120"
                     height="40">
                <p class="footer-tagline">Sal de la oficina. No del trabajo.</p>
            </div>
        </div>
        
        <!-- Definimos tres columnas de enlaces -->
        <div class="footer-grid">
            <!-- Definimos la columna de 'Explorar' (primer columna) -->
            <div class="footer-col">
                <h3>Explorar</h3>
                <ul>
                    <li><a href="/client/espacios/index.php">Espacios</a></li>
                    <li><a href="/client/servicios/index.php">Servicios</a></li>
                    <li><a href="/client/agenda/index.php">Agenda</a></li>
                    <li><a href="/client/proyecto/index.php">Proyecto</a></li>
                </ul>
            </div>
            
            <!-- Definimos la columna de 'Información' (segunda columna) -->
            <div class="footer-col">
                <h3>Información</h3>
                <ul>
                    <li><a href="/client/proyecto/index.php#intro">Proyecto</a></li>
                    <li><a href="/client/proyecto/index.php#conocenos">Conócenos</a></li>
                    <li><a href="/client/proyecto/index.php#ubicacion">Ubicación</a></li>
                    <li><a href="/client/contacto/index.php">Contacto</a></li>
                    <li><a href="/admin/login.php">Portal Staff</a></li>
                </ul>
            </div>
            
            <!-- Definimos la columna de 'Contáctanos' (tercer columna) -->
            <div class="footer-col">
                <h3>Contáctanos</h3>
                <ul class="footer-contact">
                    <li>Costa Mexicana, México</li>
                    <li>+52 656 123 456</li>
                    <li>contacto@salitre.mx</li>
                    <li>Check In: 15:00 · Check Out: 11:00</li>
                    <li>Developed by: Elias Ochoa</li>
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

<script src="/assets/js/shared/animations.js" defer></script>
<script src="/assets/js/shared/alerts.js" defer></script>
<script src="/assets/js/client/main.js" defer></script>

<?php if (isset($extra_scripts) && is_array($extra_scripts)): ?>
    <?php foreach ($extra_scripts as $script): ?>
        <?php $script_path = str_starts_with($script, '/') ? $script : '/' . $script; ?>
        <script src="<?= htmlspecialchars($script_path, ENT_QUOTES, 'UTF-8') ?>" defer></script>
    <?php endforeach; ?>
<?php endif; ?>

</body>
</html>
