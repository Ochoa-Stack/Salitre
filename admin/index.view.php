<?php require_once 'includes/header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>
<main class="main-content">
  <div class="topbar">
    <h1 class="topbar__title">Dashboard Principal</h1>
    <span class="topbar__meta">Bienvenido, <?php echo $staff_nombre; ?></span>
  </div>

  <div class="content-area">

    <!-- Definimos el contenido de las stat cards -->
    <div class="stats-grid">

      <div class="stat-card stat-card--pending">
        <span class="stat-card__label">Reservas pendientes</span>
        <span class="stat-card__value"><?php echo $stat_reservas_pendientes; ?></span>
        <span class="stat-card__desc">Requieren confirmación</span>
      </div>

      <div class="stat-card stat-card--active">
        <span class="stat-card__label">Espacios activos</span>
        <span class="stat-card__value"><?php echo $stat_espacios_activos; ?></span>
        <span class="stat-card__desc">Disponibles para reserva</span>
      </div>

      <div class="stat-card stat-card--clients">
        <span class="stat-card__label">Clientes registrados</span>
        <span class="stat-card__value"><?php echo $stat_clientes; ?></span>
        <span class="stat-card__desc">Total en base de datos</span>
      </div>

    </div>

<?php require_once 'includes/footer.php'; ?>
