<?php require_once '../includes/header.php'; ?>
<?php require_once '../includes/sidebar.php'; ?>
<main class="main-content">
  <div class="topbar">
    <h1 class="topbar__title">Editar Espacio #<?php echo $id; ?></h1>
    <span class="topbar__meta">
      <a href="<?php echo htmlspecialchars(BASE_URL . 'admin/espacios/listar.php', ENT_QUOTES, 'UTF-8'); ?>"
         style="color:#6b7280;font-size:.875rem;">← Volver al listado</a>
    </span>
  </div>

  <div class="content-area">

    <?php if (!empty($errors)) : ?>
    <div class="flash flash--error" role="alert">
      <strong>Corrige los siguientes errores:</strong>
      <ul style="margin-top:.4rem;padding-left:1.25rem;">
        <?php foreach ($errors as $err) : ?>
        <li><?php echo htmlspecialchars($err, ENT_QUOTES, 'UTF-8'); ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
    <?php endif; ?>

    <div class="form-card">
      <form method="post" action="<?php echo htmlspecialchars(BASE_URL . 'admin/espacios/editar.php?id=' . $id, ENT_QUOTES, 'UTF-8'); ?>" novalidate>

        <div class="form-grid">

          <div class="form-group">
            <label class="form-label" for="nombre">Nombre <span>*</span></label>
            <input class="form-input" type="text" id="nombre" name="nombre" required maxlength="100"
                   value="<?php echo htmlspecialchars($values['nombre'], ENT_QUOTES, 'UTF-8'); ?>">
          </div>

          <div class="form-group">
            <label class="form-label" for="slug">Slug <span>*</span></label>
            <input class="form-input" type="text" id="slug" name="slug" required maxlength="100"
                   pattern="[a-z0-9\-]+"
                   value="<?php echo htmlspecialchars($values['slug'], ENT_QUOTES, 'UTF-8'); ?>">
            <span class="form-hint">Formato: estudio-playa (minúsculas, guiones)</span>
          </div>

          <div class="form-group">
            <label class="form-label" for="tipo">Tipo <span>*</span></label>
            <select class="form-select" id="tipo" name="tipo" required>
              <?php foreach (['estudio' => 'Estudio', 'loft' => 'Loft', 'suite' => 'Suite', 'villa' => 'Villa'] as $val => $lbl) : ?>
              <option value="<?php echo $val; ?>" <?php echo ($values['tipo'] === $val) ? 'selected' : ''; ?>>
                <?php echo $lbl; ?>
              </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label class="form-label" for="capacidad">Capacidad <span>*</span></label>
            <input class="form-input" type="number" id="capacidad" name="capacidad"
                   min="1" max="20" required
                   value="<?php echo htmlspecialchars($values['capacidad'], ENT_QUOTES, 'UTF-8'); ?>">
          </div>

          <div class="form-group">
            <label class="form-label" for="precio_noche">Precio / noche (USD) <span>*</span></label>
            <input class="form-input" type="number" id="precio_noche" name="precio_noche"
                   min="0" step="0.01" required
                   value="<?php echo htmlspecialchars($values['precio_noche'], ENT_QUOTES, 'UTF-8'); ?>">
          </div>

          <div class="form-group" style="align-self:end;">
            <label class="form-check">
              <input type="checkbox" name="activo" value="1"
                     <?php echo ($values['activo'] === '1') ? 'checked' : ''; ?>>
              Espacio activo
            </label>
          </div>

          <div class="form-group form-group--full">
            <label class="form-label" for="descripcion">Descripción</label>
            <textarea class="form-textarea" id="descripcion" name="descripcion" maxlength="2000"><?php echo htmlspecialchars($values['descripcion'], ENT_QUOTES, 'UTF-8'); ?></textarea>
          </div>

        </div>

        <div class="form-actions">
          <button class="btn btn--primary" type="submit">Guardar cambios</button>
          <a class="btn btn--ghost"
             href="<?php echo htmlspecialchars(BASE_URL . 'admin/espacios/listar.php', ENT_QUOTES, 'UTF-8'); ?>">
            Cancelar
          </a>
        </div>

      </form>
    </div>

<?php require_once '../includes/footer.php'; ?>
