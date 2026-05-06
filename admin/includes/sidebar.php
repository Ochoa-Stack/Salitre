<?php
/* 'includes/sidebar.php' define la barra lateral común para todas las páginas del admin, es decir, el menú de navegación lateral izquierdo */
$current = basename($_SERVER['PHP_SELF']);

$nav_items = [
    ['href' => $base . 'admin/index.php',                  'label' => 'Inicio', 'file' => 'index.php',
     'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg>'],
    ['href' => $base . 'admin/espacios/listar.php',        'label' => 'Espacios',  'file' => 'listar.php',
     'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>'],
    ['href' => $base . 'admin/reservas/listar.php',        'label' => 'Reservas',  'file' => 'listar.php',
     'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>'],
    ['href' => $base . 'admin/clientes/listar.php',        'label' => 'Clientes',  'file' => 'listar.php',
     'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>'],
    ['href' => $base . 'admin/eventos/listar.php',         'label' => 'Agenda',    'file' => 'listar.php',
     'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><circle cx="12" cy="16" r="2"/></svg>'],
    ['href' => $base . 'admin/contacto/listar.php',        'label' => 'Mensajes',  'file' => 'listar.php',
     'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>'],
];
?>
<aside class="sidebar" role="complementary" aria-label="Menú de Administración">

  <div class="sidebar__brand">
    <img src="<?= $base ?>assets/img/logo/logo-white.png"
         alt="Salitre Admin"
         style="height: 32px; width: auto;"
         onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-flex'">
    <span class="sidebar__brand-name" style="display:none;">Panel Salitre</span>
  </div>

  <div class="sidebar__user">
    <span class="sidebar__user-role"><?php echo $staff_rol; ?></span>
    <span class="sidebar__user-name"><?php echo $staff_nombre; ?></span>
  </div>

  <nav class="sidebar__nav" aria-label="Navegación principal del admin">
    <p class="sidebar__nav-section">Menú</p>
    <ul class="sidebar__nav-list">
<?php foreach ($nav_items as $item) :
    // Aplicamos la logica para determinar si el item actual es el activo, para marcarlo en el menú
    $is_active = false;
    $pagina_actual = basename($_SERVER["PHP_SELF"]);
    
    // Si estamos en 'index.php' y el link es a 'index.php'
    if ($pagina_actual === 'index.php' && strpos($item['href'], 'index.php') !== false) {
        $is_active = true;
    } 
    // Para las otras secciones, comprobamos si la url actual contiene el path
    else if ($pagina_actual !== 'index.php' && strpos($item['href'], 'index.php') === false) {
        $partes = explode('/', parse_url($item['href'], PHP_URL_PATH));
        $seccion = count($partes) >= 2 ? $partes[count($partes)-2] : '';
        if ($seccion && strpos($_SERVER["REQUEST_URI"], '/' . $seccion . '/') !== false) {
            $is_active = true;
        }
    }
    
    $active_class = $is_active ? ' active' : '';
?>
      <li class="sidebar__nav-item">
        <a href="<?php echo htmlspecialchars($item['href'], ENT_QUOTES, 'UTF-8'); ?>"
           class="<?php echo $active_class; ?>"
           <?php echo $is_active ? 'aria-current="page"' : ''; ?>>
          <?php echo $item['icon']; ?>
          <?php echo htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8'); ?>
        </a>
      </li>
<?php endforeach; ?>
    </ul>
  </nav>

  <div class="sidebar__logout">
    <a href="<?php echo htmlspecialchars($base . 'admin/logout.php', ENT_QUOTES, 'UTF-8'); ?>">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
        <polyline points="16 17 21 12 16 7"/>
        <line x1="21" y1="12" x2="9" y2="12"/>
      </svg>
      Cerrar Sesión
    </a>
  </div>

</aside>
