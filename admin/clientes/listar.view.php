<?php require_once '../includes/header.php'; ?>
<?php require_once '../includes/sidebar.php'; ?>
<main class="main-content">
  <div class="topbar">
    <h1 class="topbar__title">Clientes</h1>
    <span class="topbar__meta"><?php echo count($clientes); ?> cliente(s) registrado(s)</span>
  </div>

  <div class="content-area">

    <div class="page-header">
      <p class="page-header__title">Listado de Clientes</p>
      <span style="font-size:.8125rem;color:#6b7280;">Solo lectura - Los clientes se autogestionan desde el portal público.</span>
    </div>

    <div class="data-table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Teléfono</th>
            <th>Total Reservas</th>
            <th>Registrado el</th>
          </tr>
        </thead>
        <tbody>
<?php if (empty($clientes)) : ?>
          <tr>
            <td colspan="6" class="table-empty">No hay clientes registrados aún.</td>
          </tr>
<?php else : ?>
<?php foreach ($clientes as $c) : ?>
          <tr>
            <td><?php echo (int) $c['id']; ?></td>
            <td><?php echo htmlspecialchars((string) $c['nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars((string) $c['email'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo $c['telefono'] !== null ? htmlspecialchars((string) $c['telefono'], ENT_QUOTES, 'UTF-8') : '<span style="color:#9ca3af;">—</span>'; ?></td>
            <td>
                <?php $total_res = (int) $c['total_reservas']; ?>
                <?php if ($total_res > 0): ?>
                    <span class="badge badge--active"><?php echo $total_res; ?></span>
                <?php else: ?>
                    <span class="badge badge--inactive">0</span>
                <?php endif; ?>
            </td>
            <td><?php
                $fecha = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', (string) $c['creado_en']);
                echo $fecha ? htmlspecialchars($fecha->format('d/m/Y H:i'), ENT_QUOTES, 'UTF-8') : htmlspecialchars((string) $c['creado_en'], ENT_QUOTES, 'UTF-8');
            ?></td>
          </tr>
<?php endforeach; ?>
<?php endif; ?>
        </tbody>
      </table>
      <?php if (isset($total_pages) && $total_pages > 1) : ?>
      <div style="margin-top: 1rem; display: flex; gap: 0.5rem; justify-content: center; align-items: center; padding: 1rem 0;">
          <?php if ($page > 1) : ?>
              <a href="?page=<?= $page - 1 ?>" class="btn btn--secondary">&laquo; Anterior</a>
          <?php endif; ?>
          <span style="font-size: 0.875rem; color: var(--adm-color-text-muted);">Página <?= $page ?> de <?= $total_pages ?></span>
          <?php if ($page < $total_pages) : ?>
              <a href="?page=<?= $page + 1 ?>" class="btn btn--secondary">Siguiente &raquo;</a>
          <?php endif; ?>
      </div>
      <?php endif; ?>
    </div>

<?php require_once '../includes/footer.php'; ?>
