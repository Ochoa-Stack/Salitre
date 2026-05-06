<?php require_once '../includes/header.php'; ?>
<?php require_once '../includes/sidebar.php'; ?>
<main class="main-content">
  <div class="topbar">
    <h1 class="topbar__title">Reservas</h1>
    <span class="topbar__meta"><?php echo count($reservas); ?> reserva(s) en total</span>
  </div>

  <div class="content-area">

    <div class="page-header">
      <p class="page-header__title">Listado de Reservas</p>
    </div>

    <?php if (isset($_GET['success']) && $_GET['success'] === 'updated') : ?>
    <div class="flash flash--success" role="status">
      Estado de la reserva actualizado exitosamente.
    </div>
    <?php endif; ?>

    <div class="data-table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Cliente</th>
            <th>Espacio</th>
            <th>Entrada</th>
            <th>Salida</th>
            <th>Noches</th>
            <th>Total</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
<?php if (empty($reservas)) : ?>
          <tr>
            <td colspan="9" class="table-empty">No hay reservas registradas aún.</td>
          </tr>
<?php else : ?>
<?php foreach ($reservas as $r) :
    $id      = (int) $r['id'];
    $estado  = in_array($r['estado'], $estados_validos, true) ? $r['estado'] : 'pendiente';
    $detalle = htmlspecialchars(BASE_URL . 'admin/reservas/detalle.php?id=' . $id, ENT_QUOTES, 'UTF-8');
?>
          <tr>
            <td><?php echo $id; ?></td>
            <td><?php echo htmlspecialchars((string) $r['cliente'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars((string) $r['espacio'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars((string) $r['fecha_entrada'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars((string) $r['fecha_salida'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo (int) $r['noches']; ?></td>
            <td>$<?php echo number_format((float) $r['precio_total'], 2); ?></td>
            <td>
              <span class="badge badge-<?php echo htmlspecialchars($estado, ENT_QUOTES, 'UTF-8'); ?>">
                <?php echo ucfirst($estado); ?>
              </span>
            </td>
            <td>
              <div class="actions-cell">
                <a class="btn btn--edit" href="<?php echo $detalle; ?>">Ver detalle</a>
              </div>
            </td>
          </tr>
<?php endforeach; ?>
<?php endif; ?>
        </tbody>
      </table>
    </div>

<?php require_once '../includes/footer.php'; ?>
