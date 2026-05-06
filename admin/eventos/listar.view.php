<?php require_once dirname(__DIR__) . "/includes/header.php"; ?>
<?php require_once dirname(__DIR__) . "/includes/sidebar.php"; ?>

<main class="main-content">
    <div class="topbar">
        <h1 class="topbar__title">Gestión de Eventos</h1>
        <span class="topbar__meta"><a href="crear.php" class="btn btn-primary" style="padding: 0.5rem 1rem;">+ Nuevo Evento</a></span>
    </div>

    <div class="content-area">
        
        <?php if (isset($_GET['success'])): ?>
        <div class="flash flash--success" role="status">
            <?php if ($_GET['success'] === 'created'): ?>
                Evento creado exitosamente.
            <?php elseif ($_GET['success'] === 'updated'): ?>
                Evento actualizado exitosamente.
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <div class="card p-0">
            <div style="overflow-x:auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Fecha</th>
                            <th>Horario</th>
                            <th>Cupo</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($eventos)): ?>
                            <tr>
                                <td colspan="7" style="padding:2rem; text-align:center; color:var(--color-text-muted);">
                                    No hay eventos registrados.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($eventos as $evento): ?>
                                <tr>
                                    <td>#<?= str_pad((string)$evento["id"], 4, "0", STR_PAD_LEFT) ?></td>
                                    <td style="font-weight:600;"><?= htmlspecialchars((string)$evento["titulo"], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= date('d M Y', strtotime((string)$evento["fecha_evento"])) ?></td>
                                    <td>
                                        <?= !empty($evento["hora_inicio"]) ? date('H:i', strtotime((string)$evento["hora_inicio"])) : "—" ?> 
                                        <?= !empty($evento["hora_fin"]) ? " a " . date('H:i', strtotime((string)$evento["hora_fin"])) : "" ?>
                                    </td>
                                    <td><?= (int)$evento["cupo"] > 0 ? (int)$evento["cupo"] : "∞" ?></td>
                                    <td>
                                        <span class="badge badge--<?= $evento["activo"] ? 'active' : 'inactive' ?>">
                                            <?= $evento["activo"] ? "Activo" : "Inactivo" ?>
                                        </span>
                                    </td>
                                    <td class="actions-cell">
                                        <a href="editar.php?id=<?= $evento["id"] ?>" class="btn btn--edit">Editar</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php require_once dirname(__DIR__) . "/includes/footer.php"; ?>
