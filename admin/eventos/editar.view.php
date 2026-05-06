<?php require_once dirname(__DIR__) . "/includes/header.php"; ?>
<?php require_once dirname(__DIR__) . "/includes/sidebar.php"; ?>

<main class="main-content">
    <div class="topbar">
        <h1 class="topbar__title">Modificar Evento (#<?= $id ?>)</h1>
        <span class="topbar__meta"><a href="listar.php" class="btn btn--ghost">‹ Volver</a></span>
    </div>

    <div class="content-area">
        
        <?php if (!empty($errores)): ?>
            <div class="flash flash--error" role="alert">
                <ul style="margin:0; padding-left:1.5rem;">
                    <?php foreach ($errores as $error): ?>
                        <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="form-card">
            <form method="POST" action="editar.php?id=<?= $id ?>" class="form-grid">
                
                <div class="form-group form-group--full">
                    <label for="titulo" class="form-label">Título del Evento <span>*</span></label>
                    <input type="text" id="titulo" name="titulo" class="form-input" required value="<?= htmlspecialchars($_POST['titulo'] ?? $evento["titulo"], ENT_QUOTES, 'UTF-8') ?>">
                </div>
                
                <div class="form-group form-group--full">
                    <label for="descripcion" class="form-label">Descripción Completa</label>
                    <textarea id="descripcion" name="descripcion" class="form-textarea"><?= htmlspecialchars($_POST['descripcion'] ?? $evento["descripcion"], ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>
                
                <div class="form-group form-group--full">
                    <label for="fecha_evento" class="form-label">Día del Evento <span>*</span></label>
                    <input type="date" id="fecha_evento" name="fecha_evento" class="form-input" required value="<?= htmlspecialchars($_POST['fecha_evento'] ?? $evento["fecha_evento"], ENT_QUOTES, 'UTF-8') ?>">
                </div>
                
                <div class="form-group">
                    <label for="hora_inicio" class="form-label">Hora de Inicio</label>
                    <input type="time" id="hora_inicio" name="hora_inicio" class="form-input" value="<?= htmlspecialchars($_POST['hora_inicio'] ?? $evento["hora_inicio"], ENT_QUOTES, 'UTF-8') ?>">
                </div>
                
                <div class="form-group">
                    <label for="hora_fin" class="form-label">Hora de Cierre</label>
                    <input type="time" id="hora_fin" name="hora_fin" class="form-input" value="<?= htmlspecialchars($_POST['hora_fin'] ?? $evento["hora_fin"], ENT_QUOTES, 'UTF-8') ?>">
                </div>
                
                <div class="form-group">
                    <label for="cupo" class="form-label">Límite de Cupo <span class="form-hint">(Dejar vacío si es ilimitado)</span></label>
                    <input type="number" id="cupo" name="cupo" min="1" class="form-input" value="<?= htmlspecialchars((string)($_POST['cupo'] ?? $evento["cupo"]), ENT_QUOTES, 'UTF-8') ?>">
                </div>
                
                <div class="form-group" style="justify-content: center;">
                    <label class="form-check">
                        <!-- Si hay POST manda el valor del checkbox o 0, sino la DB original -->
                        <?php $isActivo = isset($_POST['titulo']) ? isset($_POST['activo']) : (bool)$evento['activo']; ?>
                        <input type="checkbox" name="activo" id="activo" <?= $isActivo ? 'checked' : '' ?>>
                        Evento
                    </label>
                </div>
                
                <div class="form-actions form-group--full" style="justify-content: flex-end;">
                    <a href="listar.php" class="btn btn--ghost">Cancelar</a>
                    <button type="submit" class="btn btn--primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
        
    </div>
</main>

<?php require_once dirname(__DIR__) . "/includes/footer.php"; ?>
