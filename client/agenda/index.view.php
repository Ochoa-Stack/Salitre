<div class="page-offset"></div>

<header class="agenda-hero section-pad text-center fade-in">
    <div class="container">
        <h1 class="section-title">Agenda de Experiencias</h1>
        <p class="text-muted text-lg max-w-2xl mx-auto">Encuentros diseñados para inspirar, conectar y crear vínculos en la costa.</p>
    </div>
</header>

<section class="agenda-content pb-12">
    <div class="container container--narrow">
        
        <?php if (empty($eventos)): ?>
            <div class="agenda-empty fade-in text-center p-8 mt-4" style="background:var(--color-surface); border:1px dashed var(--color-border); border-radius:var(--radius-lg);">
                <p class="text-muted text-lg">No hay eventos programados próximamente.</p>
            </div>
        <?php else: ?>
            <div class="agenda-list grid gap-6">
                <?php 
                $meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
                $dias = ['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo'];

                foreach ($eventos as $idx => $ev):
                    // Definimos un formato de fecha legible
                    $dateObj = new DateTime($ev["fecha_evento"]);
                    $res = $dateObj->format('N');
                    $fecha_legible = $dias[$res - 1] . " " . $dateObj->format('d') . " de " . $meses[$dateObj->format('n') - 1];

                    // Definimos un formato de hora legible
                    $horaStr = "Todo el día";
                    if (!empty($ev["hora_inicio"])) {
                        $horaStr = date('H:i', strtotime($ev["hora_inicio"]));
                        if (!empty($ev["hora_fin"])) {
                            $horaStr .= " - " . date('H:i', strtotime($ev["hora_fin"]));
                        }
                    }

                    // Definimos el cupo de cada evento
                    $cupoStr = (!empty($ev["cupo"]) && $ev["cupo"] > 0) ? "Cupo: " . (int)$ev["cupo"] . " personas" : "Cupo ilimitado";
                    
                    // Definimos la descripción truncada multi-byte
                    $descRaw = (string)$ev["descripcion"];
                    $descTruncada = mb_strlen($descRaw) > 150 ? mb_substr($descRaw, 0, 150) . "..." : $descRaw;
                ?>
                    <article class="agenda-card fade-in flex" data-delay="<?= ($idx % 5) * 100 ?>">
                        
                        <div class="agenda-card__time text-center">
                            <span class="agenda-card__day text-accent fw-700"><?= $dateObj->format('d') ?></span>
                            <span class="agenda-card__month text-sm text-muted uppercase"><?= substr($meses[$dateObj->format('n')-1], 0, 3) ?></span>
                        </div>
                        
                        <div class="agenda-card__body">
                            <div class="flex-between align-center mb-2">
                                <span class="agenda-card__full-date text-sm fw-600 text-muted">
                                    <?= ucfirst(mb_convert_encoding($fecha_legible, 'UTF-8')) ?> · <?= $horaStr ?>
                                </span>
                                <!-- Creamos el Badge Dummy ya que eventos bd no tiene 'tipo', el request dice Incluido/Con Costo pero es string, daremos Included por default -->
                                <span class="badge" style="background:var(--color-surface); border:1px solid var(--color-border); color:var(--color-text-muted); font-size:10px;">Incluido</span>
                            </div>
                            
                            <h2 class="agenda-card__title text-xl fw-700 mb-2"><?= htmlspecialchars((string)$ev["titulo"], ENT_QUOTES, 'UTF-8') ?></h2>
                            
                            <?php if (!empty($descTruncada)): ?>
                                <p class="agenda-card__desc text-base text-muted mb-4"><?= htmlspecialchars($descTruncada, ENT_QUOTES, 'UTF-8') ?></p>
                            <?php endif; ?>
                            
                            <div class="agenda-card__footer flex align-center gap-2 text-sm text-muted">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                <span><?= $cupoStr ?></span>
                            </div>
                        </div>

                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</section>

<?php require_once dirname(__DIR__) . "/includes/footer.php"; ?>
