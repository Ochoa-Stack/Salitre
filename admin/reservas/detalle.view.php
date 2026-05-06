<?php require_once '../includes/header.php'; ?>
<?php require_once '../includes/sidebar.php'; ?>
<main class="main-content">
  <div class="topbar">
    <h1 class="topbar__title">Detalle de Reserva #<?php echo $id; ?></h1>
    <span class="topbar__meta">
      <a href="<?php echo htmlspecialchars(BASE_URL . 'admin/reservas/listar.php', ENT_QUOTES, 'UTF-8'); ?>"
         style="color:#6b7280;font-size:.875rem;">← Volver al listado</a>
    </span>
  </div>

  <div class="content-area">

    <?php if ($msg_ok) : ?>
    <div class="flash flash--success" role="status">Estado actualizado correctamente.</div>
    <?php endif; ?>

    <?php if ($error !== '') : ?>
    <div class="flash flash--error" role="alert"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>

    <!-- Definimos el grid de paneles -->
    <div class="detail-grid">

      <!-- Definimos el panel del cliente -->
      <div class="detail-panel">
        <p class="detail-panel__title">Cliente</p>
        <dl class="detail-dl">
          <dt>Nombre</dt>
          <dd><?php echo htmlspecialchars((string) $reserva['cliente_nombre'], ENT_QUOTES, 'UTF-8'); ?></dd>
          <dt>Email</dt>
          <dd><?php echo htmlspecialchars((string) $reserva['cliente_email'], ENT_QUOTES, 'UTF-8'); ?></dd>
          <dt>Teléfono</dt>
          <dd><?php echo $reserva['cliente_telefono']
              ? htmlspecialchars((string) $reserva['cliente_telefono'], ENT_QUOTES, 'UTF-8')
              : '<span style="color:#9ca3af">—</span>'; ?></dd>
        </dl>
      </div>

      <!-- Definimos el panel del espacio -->
      <div class="detail-panel">
        <p class="detail-panel__title">Espacio</p>
        <dl class="detail-dl">
          <dt>Nombre</dt>
          <dd><?php echo htmlspecialchars((string) $reserva['espacio_nombre'], ENT_QUOTES, 'UTF-8'); ?></dd>
          <dt>Tipo</dt>
          <dd><?php echo htmlspecialchars($tipo_labels[$reserva['espacio_tipo']] ?? $reserva['espacio_tipo'], ENT_QUOTES, 'UTF-8'); ?></dd>
          <dt>Precio / noche</dt>
          <dd>$<?php echo number_format((float) $reserva['precio_noche'], 2); ?> USD</dd>
        </dl>
      </div>

      <!-- Definimos el panel de desglose de reserva -->
      <div class="detail-panel">
        <p class="detail-panel__title">Desglose de Reserva</p>
        <dl class="detail-dl">
          <dt>Entrada</dt>
          <dd><?php echo htmlspecialchars((string) $reserva['fecha_entrada'], ENT_QUOTES, 'UTF-8'); ?></dd>
          <dt>Salida</dt>
          <dd><?php echo htmlspecialchars((string) $reserva['fecha_salida'], ENT_QUOTES, 'UTF-8'); ?></dd>
          <dt>Noches</dt>
          <dd><?php echo (int) $reserva['noches']; ?></dd>
          <dt>Precio total</dt>
          <dd><strong>$<?php echo number_format((float) $reserva['precio_total'], 2); ?> USD</strong></dd>
          <dt>Creada el</dt>
          <dd><?php echo $created ? htmlspecialchars($created->format('d/m/Y H:i'), ENT_QUOTES, 'UTF-8') : htmlspecialchars((string) $reserva['creado_en'], ENT_QUOTES, 'UTF-8'); ?></dd>
        </dl>
      </div>

      <!-- Definimos el panel de notas -->
      <div class="detail-panel">
        <p class="detail-panel__title">Notas del cliente</p>
        <?php if (!empty($reserva['notas'])) : ?>
        <div class="detail-notes"><?php echo htmlspecialchars((string) $reserva['notas'], ENT_QUOTES, 'UTF-8'); ?></div>
        <?php else : ?>
        <p style="font-size:.875rem;color:#9ca3af;">Sin notas.</p>
        <?php endif; ?>
      </div>

      <!-- Definimos el panel de gestión de estado — full width -->
      <div class="detail-panel detail-panel--span2">
        <p class="detail-panel__title">Gestión de Estado</p>
        <p style="margin-bottom:.875rem;font-size:.875rem;color:#6b7280;">
          Estado actual:
          <span class="badge badge-<?php echo htmlspecialchars($estado_actual, ENT_QUOTES, 'UTF-8'); ?>">
            <?php echo ucfirst($estado_actual); ?>
          </span>
        </p>
        <form class="status-form" method="post"
              action="<?php echo htmlspecialchars(BASE_URL . 'admin/reservas/detalle.php?id=' . $id, ENT_QUOTES, 'UTF-8'); ?>">
          <select class="form-select" name="estado" id="estado" required>
            <?php foreach (ESTADOS_RESERVA as $opt) : ?>
            <option value="<?php echo $opt; ?>" <?php echo ($opt === $estado_actual) ? 'selected' : ''; ?>>
              <?php echo ucfirst($opt); ?>
            </option>
            <?php endforeach; ?>
          </select>
          <button class="btn btn--primary" type="submit">Actualizar estado</button>
        </form>
      </div>

    </div>

<?php require_once '../includes/footer.php'; ?>
