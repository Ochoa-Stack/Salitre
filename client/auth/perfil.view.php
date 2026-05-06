<main class="perfil-page">
    <div class="container container--wide">
        <h1>Mi Perfil</h1>
        
        <section class="perfil-info">
            <h2>Información Personal</h2>
            <div class="info-grid">
                <div class="info-item">
                    <label>Nombre:</label>
                    <span><?= htmlspecialchars($cliente["nombre"]) ?></span>
                </div>
                <div class="info-item">
                    <label>Email:</label>
                    <span><?= htmlspecialchars($cliente["email"]) ?></span>
                </div>
                <div class="info-item">
                    <label>Teléfono:</label>
                    <span><?= htmlspecialchars($cliente["telefono"] ?? "No registrado") ?></span>
                </div>
                <div class="info-item">
                    <label>Cliente desde:</label>
                    <span><?= date("d/m/Y", strtotime($cliente["creado_en"])) ?></span>
                </div>
            </div>
        </section>
        
        <section class="perfil-reservas">
            <h2>Mis Reservas</h2>
            <?php if (count($reservas) > 0): ?>
                <table class="reservas-table">
                    <thead>
                        <tr>
                            <th>Folio</th>
                            <th>Espacio</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Noches</th>
                            <th>Total</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservas as $reserva): ?>
                            <tr>
                                <td>#<?= str_pad((string)$reserva["id"], 6, "0", STR_PAD_LEFT) ?></td>
                                <td>
                                    <a href="<?= BASE_URL ?>client/espacios/detalle.php?slug=<?= htmlspecialchars($reserva["espacio_slug"]) ?>">
                                        <?= htmlspecialchars($reserva["espacio_nombre"]) ?>
                                    </a>
                                </td>
                                <td><?= date("d/m/Y", strtotime($reserva["fecha_entrada"])) ?></td>
                                <td><?= date("d/m/Y", strtotime($reserva["fecha_salida"])) ?></td>
                                <td><?= $reserva["noches"] ?></td>
                                <td>$<?= number_format((float)$reserva["precio_total"], 2) ?></td>
                                <td>
                                    <span class="badge badge-<?= $reserva["estado"] ?>">
                                        <?= htmlspecialchars($reserva["estado"]) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="no-reservas">No tienes reservas registradas.</p>
                <a href="<?= BASE_URL ?>client/espacios/index.php" class="btn btn-primary">
                    Ver espacios disponibles
                </a>
            <?php endif; ?>
        </section>
    </div>
</main>

<?php require_once "../includes/footer.php"; ?>
