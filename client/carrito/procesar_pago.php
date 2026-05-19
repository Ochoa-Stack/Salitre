<?php
declare(strict_types=1);
/* 'client/carrito/procesar_pago.php' procesa el pago y crea la reserva final */

session_start();
require_once dirname(__DIR__) . "/../config/database.php";
require_once dirname(__DIR__) . "/../config/constants.php";

// Verificamos sesión activa
if (!isset($_SESSION["cliente_id"])) {
    header("Location: " . BASE_URL . "client/auth/login.php");
    exit;
}

// Validamos CSRF
if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    http_response_code(403);
    die('CSRF token validation failed');
}
// Invalidamos el token usado para asegurar que el pago no se reenvíe
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

// Verificamos que el carrito siga vigente en la sesión
if (!isset($_SESSION["carrito"]) || empty($_SESSION["carrito"])) {
    header("Location: " . BASE_URL . "client/espacios/index.php");
    exit;
}

$carrito = $_SESSION["carrito"];
$cliente_id = (int)$_SESSION["cliente_id"];
$metodo = $_POST["metodo_pago"] ?? "tarjeta";

// Simulación de procesamiento de pago
$pago_aprobado = true;
$ultimos4 = null;
$marca = "Visa";

if ($metodo === "tarjeta") {
    $numero = str_replace(' ', '', $_POST["numero_tarjeta"] ?? "");
    $ultimos4 = substr($numero, -4);
    
    // Determinamos la marca de forma simplificada para el registro
    if (str_starts_with($numero, '4')) $marca = "Visa";
    elseif (preg_match('/^5[1-5]/', $numero)) $marca = "Mastercard";
    elseif (str_starts_with($numero, '34') || str_starts_with($numero, '37')) $marca = "American Express";
    else $marca = "Tarjeta";

    // Regla de simulación: si el número termina en impar, el pago es rechazado
    if ((int)substr($numero, -1) % 2 !== 0) {
        $pago_aprobado = false;
    }
}

if (!$pago_aprobado) {
    $_SESSION['pago_error'] = "Pago rechazado. Verifica los datos de tu tarjeta.";
    header("Location: " . BASE_URL . "client/carrito/pago.php");
    exit;
}

// Pago aprobado: Procedemos a crear la reserva y registrar el pago
try {
    $pdo = conectarDB();
    $pdo->beginTransaction();

    // Insertamos la reserva
    $stmt = $pdo->prepare(
        "INSERT INTO reservas (cliente_id, espacio_id, fecha_entrada, fecha_salida, noches, precio_total, estado, estado_pago) 
         VALUES (?, ?, ?, ?, ?, ?, 'confirmada', 'pagado')"
    );
    $stmt->execute([
        $cliente_id,
        $carrito['espacio_id'],
        $carrito['fecha_entrada'],
        $carrito['fecha_salida'],
        $carrito['noches'],
        $carrito['total']
    ]);
    
    $reserva_id = $pdo->lastInsertId();

    // Insertamos el registro del pago
    $stmtPago = $pdo->prepare(
        "INSERT INTO pagos (reserva_id, monto, metodo, estado, ultimos4, marca_tarjeta) 
         VALUES (?, ?, ?, 'aprobado', ?, ?)"
    );
    $stmtPago->execute([
        $reserva_id,
        $carrito['total'],
        $metodo,
        $ultimos4,
        $marca
    ]);

    $pdo->commit();

    // Guardamos datos para la vista de confirmación
    $_SESSION["reserva_confirmacion_id"] = $reserva_id;
    $_SESSION["pago_resumen"] = [
        "metodo" => $metodo,
        "ultimos4" => $ultimos4,
        "marca" => $marca,
        "total" => $carrito['total']
    ];

    // Limpiamos el carrito
    unset($_SESSION["carrito"]);

    header("Location: " . BASE_URL . "client/carrito/confirmacion.php");
    exit;

} catch (PDOException $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    error_log("Error en procesamiento de pago/reserva: " . $e->getMessage());
    $_SESSION['pago_error'] = "Ocurrió un error al procesar tu reserva. Intenta de nuevo.";
    header("Location: " . BASE_URL . "client/carrito/pago.php");
    exit;
}
