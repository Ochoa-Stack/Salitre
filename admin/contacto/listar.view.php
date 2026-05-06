<?php require_once '../includes/header.php'; ?>
<?php require_once '../includes/sidebar.php'; ?>
<main class="main-content">
  <div class="topbar">
    <h1 class="topbar__title">Bandeja de Mensajes</h1>
    <span class="topbar__meta">
      <?php if ($no_leidos > 0) : ?>
        <strong style="color:#1d4ed8;"><?php echo $no_leidos; ?> sin leer</strong>
        &nbsp;·&nbsp; <?php echo count($mensajes); ?> en total
      <?php else : ?>
        <?php echo count($mensajes); ?> mensaje(s) &nbsp;·&nbsp; Todos leídos
      <?php endif; ?>
    </span>
  </div>

  <div class="content-area">

    <?php if (isset($_GET['success']) && $_GET['success'] === 'marked_read') : ?>
    <div class="flash flash--success" role="status">
      Mensaje marcado como leído.
    </div>
    <?php endif; ?>

    <div class="data-table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>Fecha</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Mensaje</th>
            <th>Estado</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
<?php if (empty($mensajes)) : ?>
          <tr>
            <td colspan="6" class="table-empty">No hay mensajes de contacto aún.</td>
          </tr>
<?php else : ?>
<?php foreach ($mensajes as $m) :
    $id    = (int) $m['id'];
    $leido = (bool) $m['leido'];
    // Preparamos una versión corta del mensaje para mostrar en la tabla - solo 80 caracteres
    $msg_raw    = (string) $m['mensaje'];
    $msg_corto  = mb_strlen($msg_raw) > 80
        ? htmlspecialchars(mb_substr($msg_raw, 0, 80), ENT_QUOTES, 'UTF-8') . '&hellip;'
        : htmlspecialchars($msg_raw, ENT_QUOTES, 'UTF-8');
    // Formateamos la fecha de creación del mensaje para mostrarla en formato 'd/m/Y H:i'
    $fecha_obj = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', (string) $m['creado_en']);
    $fecha_fmt = $fecha_obj
        ? $fecha_obj->format('d/m/Y H:i')
        : htmlspecialchars((string) $m['creado_en'], ENT_QUOTES, 'UTF-8');

    $proc_action = htmlspecialchars(BASE_URL . 'admin/contacto/procesar_lectura.php', ENT_QUOTES, 'UTF-8');
?>
          <tr<?php echo !$leido ? ' style="background:#eff6ff;"' : ''; ?>>
            <td style="white-space:nowrap;"><?php echo $fecha_fmt; ?></td>
            <td><?php echo htmlspecialchars((string) $m['nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td>
              <a href="mailto:<?php echo htmlspecialchars((string) $m['email'], ENT_QUOTES, 'UTF-8'); ?>"
                 style="color:#3b82f6;">
                <?php echo htmlspecialchars((string) $m['email'], ENT_QUOTES, 'UTF-8'); ?>
              </a>
            </td>
            <td style="max-width:22rem;"
                title="<?php echo htmlspecialchars($msg_raw, ENT_QUOTES, 'UTF-8'); ?>">
              <?php echo $msg_corto; ?>
            </td>
            <td>
              <?php if (!$leido) : ?>
                <span class="badge badge--active">Nuevo</span>
              <?php else : ?>
                <span class="badge badge--inactive">Leído</span>
              <?php endif; ?>
            </td>
            <td>
              <?php if (!$leido) : ?>
                <a class="btn btn--edit" href="<?php echo $proc_action; ?>?id=<?php echo $id; ?>">Marcar como leído</a>
              <?php endif; ?>
            </td>
          </tr>
<?php endforeach; ?>
<?php endif; ?>
        </tbody>
      </table>
    </div>

<?php require_once '../includes/footer.php'; ?>
