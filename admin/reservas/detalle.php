<?php
/* 'admin/reservas/detalle.php' muestra el detalle completo de una reserva específica */
declare(strict_types=1);
require_once '../includes/auth_check.php';

require_once dirname(__DIR__, 2) . '/config/constants.php';
require_once dirname(__DIR__, 2) . '/config/database.php';

const ESTADOS_RESERVA = ['pendiente', 'confirmada', 'cancelada', 'completada'];
// Validamos los parámetros de entrada 'GET'
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    header('Location: ' . BASE_URL . 'admin/reservas/listar.php');
    exit();
}

$pdo     = conectarDB();
$msg_ok  = isset($_GET['msg']) && $_GET['msg'] === 'actualizado';
$error   = '';
// Actualizamos el estado de la reserva usando lógica 'POST' (si se envió el formulario)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevo_estado = trim((string) ($_POST['estado'] ?? ''));
    if (!in_array($nuevo_estado, ESTADOS_RESERVA, true)) {
        $error = 'Estado no válido.';
    } else {
        try {
            $st = $pdo->prepare('UPDATE reservas SET estado = :estado WHERE id = :id');
            $st->execute([':estado' => $nuevo_estado, ':id' => $id]);
            header('Location: ' . BASE_URL . 'admin/reservas/listar.php?success=updated');
            exit();
        } catch (Throwable $e) {
            error_log('Admin reservas/detalle UPDATE: ' . $e->getMessage());
            $error = 'Error al actualizar el estado.';
        }
    }
}
// Obtenemos el detalle completo de la reserva, con JOINs para traer datos relacionados de cliente y espacio
$reserva = null;
try {
    $stmt = $pdo->prepare(
        'SELECT
           r.id,
           r.fecha_entrada,
           r.fecha_salida,
           r.noches,
           r.precio_total,
           r.estado,
           r.notas,
           r.creado_en,
           c.id        AS cliente_id,
           c.nombre    AS cliente_nombre,
           c.email     AS cliente_email,
           c.telefono  AS cliente_telefono,
           e.id        AS espacio_id,
           e.nombre    AS espacio_nombre,
           e.tipo      AS espacio_tipo,
           e.precio_noche
         FROM reservas r
         JOIN clientes  c ON r.cliente_id  = c.id
         JOIN espacios  e ON r.espacio_id  = e.id
         WHERE r.id = :id
         LIMIT 1'
    );
    $stmt->execute([':id' => $id]);
    $reserva = $stmt->fetch();
} catch (Throwable $e) {
    error_log('Admin reservas/detalle SELECT: ' . $e->getMessage());
}

if (!$reserva) {
    header('Location: ' . BASE_URL . 'admin/reservas/listar.php');
    exit();
}
// Validamos que el estado actual de la reserva sea uno de los estados permitidos, si no, asignamos 'pendiente' por defecto
$estado_actual = in_array($reserva['estado'], ESTADOS_RESERVA, true)
    ? $reserva['estado']
    : 'pendiente';

$tipo_labels = ['estudio' => 'Estudio', 'loft' => 'Loft', 'suite' => 'Suite', 'villa' => 'Villa'];

$created = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', (string) $reserva['creado_en']);
// Definimos el título de la página, cargamos el header y sidebar
$page_title = 'Reserva #' . $id . ' - Panel Salitre';
$extra_css  = ['assets/css/admin/crud.css'];
require __DIR__ . '/detalle.view.php';
