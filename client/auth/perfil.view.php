<main class="perfil-page">
    <div class="container container--wide">
        <h1>Mi Perfil</h1>
        
        <?php if (!empty($success_msg)): ?>
            <div class="alert alert--success mb-6"><?= htmlspecialchars($success_msg) ?></div>
        <?php elseif (isset($_GET['success']) && $_GET['success'] === 'reserva_cancelada'): ?>
            <div class="alert alert--success mb-6">Tu reserva ha sido cancelada exitosamente.</div>
        <?php endif; ?>

        <?php if (!empty($error_msg)): ?>
            <div class="alert alert--error mb-6"><?= htmlspecialchars($error_msg) ?></div>
        <?php elseif (isset($_GET['error'])): ?>
            <div class="alert alert--error mb-6">
                <?php 
                    if ($_GET['error'] === 'not_allowed') echo "No tienes permiso para cancelar esta reserva o su estado no lo permite.";
                    elseif ($_GET['error'] === 'invalid_reserva') echo "Reserva inválida.";
                    elseif ($_GET['error'] === 'db_error') echo "Ocurrió un error en el sistema al intentar cancelar.";
                    else echo "Ups, algo salió mal. Intenta de nuevo.";
                ?>
            </div>
        <?php endif; ?>

        <section class="perfil-info">
            <h2>Editar Información Personal</h2>
            <form action="" method="POST" class="auth-form">
                <?php if(empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); ?>
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="action" value="update_profile">
                
                <div class="field">
                    <label for="nombre" class="field__label">Nombre completo</label>
                    <input type="text" id="nombre" name="nombre" class="field__input" required value="<?= htmlspecialchars($cliente["nombre"]) ?>">
                </div>

                <div class="field">
                    <label for="email" class="field__label">Correo electrónico</label>
                    <input type="email" id="email" name="email" class="field__input" disabled value="<?= htmlspecialchars($cliente["email"]) ?>">
                    <small class="text-muted">El correo electrónico no se puede cambiar.</small>
                </div>
                
                <div class="field">
                    <label for="telefono" class="field__label">Teléfono</label>
                    <input type="tel" id="telefono" name="telefono" class="field__input" value="<?= htmlspecialchars($cliente["telefono"] ?? "") ?>" placeholder="+52 000 000 0000">
                </div>

                <div class="field">
                    <label for="password" class="field__label">Nueva Contraseña (dejar en blanco para no cambiar)</label>
                    <input type="password" id="password" name="password" class="field__input" minlength="8">
                </div>

                <button type="submit" class="btn btn-primary mt-4">Guardar Cambios</button>
            </form>
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
                            <th>Acciones</th>
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
                                <td>
                                    <?php if ($reserva["estado"] === "pendiente" || $reserva["estado"] === "confirmada"): ?>
                                        <form action="<?= BASE_URL ?>client/auth/cancelar_reserva.php" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas cancelar esta reserva?')">
                                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                            <input type="hidden" name="reserva_id" value="<?= $reserva["id"] ?>">
                                            <button type="submit" class="btn btn-outline btn-sm btn-danger-outline">
                                                Cancelar
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-muted text-sm">-</span>
                                    <?php endif; ?>
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
