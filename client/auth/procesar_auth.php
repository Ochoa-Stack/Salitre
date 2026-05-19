<?php
declare(strict_types=1);
/* 'client/auth/procesar_auth.php' procesa tanto el registro como el login de clientes */
session_start();
require_once dirname(__DIR__) . "/../config/database.php";
require_once dirname(__DIR__) . "/../config/constants.php";
require_once dirname(__DIR__) . "/includes/pricing.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: " . BASE_URL . "client/auth/login.php");
    exit;
}

if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    http_response_code(403);
    die('CSRF token validation failed');
}
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

$action = $_POST["action"] ?? "";

try {
    $pdo = conectarDB();
} catch (PDOException $e) {
    // Si la DB falla temporalmente, lo pateamos al index
    error_log("Fallo Auth DB: " . $e->getMessage());
    header("Location: " . BASE_URL . "client/index.php");
    exit;
}

function restaurarCarrito(PDO $pdo, int $clienteId, bool $fetchSlug = false): void {
    if (isset($_SESSION["carrito_pendiente"])) {
        $pendiente = $_SESSION["carrito_pendiente"];
        $esp_id = filter_var($pendiente["espacio_id"] ?? 0, FILTER_VALIDATE_INT);
        $fe = $pendiente["fecha_entrada"] ?? "";
        $fs = $pendiente["fecha_salida"] ?? "";
        
        if ($esp_id && $fe && $fs) {
            try {
                $entrada = new DateTime($fe);
                $salida = new DateTime($fs);
                $diff = $entrada->diff($salida);
                $noches = ($diff->invert || $diff->days < 1) ? 0 : $diff->days;
            } catch (Exception $ex) {
                $noches = 0;
            }
            
            if ($noches > 0) {
                // Obtenemos los precios usando la función centralizada
                $totales = calcularTotalesCarrito([
                    "espacio_id" => $esp_id,
                    "noches" => $noches
                ], $pdo);
                
                if (!empty($totales)) {
                    $_SESSION["carrito"] = [
                        "espacio_id" => (int)$esp_id,
                        "fecha_entrada" => $fe,
                        "fecha_salida" => $fs,
                        "noches" => $noches,
                        "subtotal" => $totales["subtotal"],
                        "iva" => $totales["iva"],
                        "limpieza" => $totales["limpieza"],
                        "total" => $totales["total"]
                    ];
                }
            }
        }
        unset($_SESSION["carrito_pendiente"]);
    }
}

/* Procesamos el registro o login */
if ($action === "registro") {
    // Recibimos los datos sin mutarlos, solo quitamos los espacios en blanco
    $nombre = trim($_POST["nombre"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm_password = $_POST["confirmar_password"] ?? "";
    $telefono = isset($_POST["telefono"]) ? trim($_POST["telefono"]) : "";
    
    // Validamos teléfono si se envió
    if (!empty($telefono)) {
        // Permitir dígitos, espacios, +, -, (, ) y máximo 15 caracteres
        if (!preg_match('/^[0-9\s\+\-\(\)]{1,15}$/', $telefono)) {
            header("Location: " . BASE_URL . "client/auth/registro.php?error=invalid_phone");
            exit;
        }
    } else {
        $telefono = null;
    }
    
    // Validamos los campos obligatorios
    if (empty($nombre) || empty($email) || empty($password)) {
        header("Location: " . BASE_URL . "client/auth/registro.php?error=empty_fields");
        exit;
    }
    
    // Revisamos que el correo tenga un formato válido
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: " . BASE_URL . "client/auth/registro.php?error=invalid_email");
        exit;
    }
    
    // Validamos que las contraseñas coincidan
    if ($password !== $confirm_password) {
        header("Location: " . BASE_URL . "client/auth/registro.php?error=passwords_mismatch");
        exit;
    }
    
    // Validamos la longitud de la contraseña (mínimo 6 caracteres)
    if (strlen($password) < 6) {
        header("Location: " . BASE_URL . "client/auth/registro.php?error=short_password");
        exit;
    }
    
    // Verificamos que el email ingresado no exista en la base de datos
    $stmt = $pdo->prepare("SELECT id FROM clientes WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        header("Location: " . BASE_URL . "client/auth/registro.php?error=email_exists");
        exit;
    }
    
    // Hasseamos la contraseña
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    
    // Insertamos el nuevo cliente en la base de datos - 'INSERT'
    $stmt = $pdo->prepare(
        "INSERT INTO clientes (nombre, email, password, telefono) VALUES (?, ?, ?, ?)"
    );
    $stmt->execute([$nombre, $email, $password_hash, $telefono]);
    
    /* Restauramos el carrito pendiente si existe, vinculándolo al cliente recién autenticado */
    restaurarCarrito($pdo, (int)$_SESSION["cliente_id"], false);
    
    // Redirect a carrito si hay pendiente, o a home
    if (isset($_SESSION["redirect_after_login"]) && $_SESSION["redirect_after_login"] === "carrito") {
        unset($_SESSION["redirect_after_login"]);
        header("Location: " . BASE_URL . "client/carrito/index.php");
    } else {
        header("Location: " . BASE_URL . "client/index.php");
    }
    exit;
}

/* Procesamos el login */
if ($action === "login") {
    // Extraemos el correo sin alterarlo
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    
    // Verificamos que los campos no estén vacíos
    if (empty($email) || empty($password)) {
        header("Location: " . BASE_URL . "client/auth/login.php?error=1");
        exit;
    }
    
    // Revisamos que el formato del correo sea válido antes de continuar
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: " . BASE_URL . "client/auth/login.php?error=1");
        exit;
    }
    
    // Buscamos el usuario
    $stmt = $pdo->prepare("SELECT id, nombre, password FROM clientes WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Verificamos las contraseñas sin mostrar detalles
    if (!$usuario || !password_verify($password, $usuario["password"])) {
        header("Location: " . BASE_URL . "client/auth/login.php?error=1");
        exit;
    }
    
    // Iniciamos sesión
    session_regenerate_id(true);
    $_SESSION["cliente_id"] = $usuario["id"];
    $_SESSION["cliente_nombre"] = $usuario["nombre"];
    
    /* Restauramos el carrito pendiente si existe */
    restaurarCarrito($pdo, (int)$_SESSION["cliente_id"], true);
    
    /* Redirigimos según parámetro 'redirect' del formulario */
    $redirect = $_POST["redirect"] ?? "home";
    if ($redirect === "carrito") {
        header("Location: " . BASE_URL . "client/carrito/index.php");
    } else {
        header("Location: " . BASE_URL . "client/index.php");
    }
    exit;
}

// Si llega aquí, 'action' inválido
header("Location: " . BASE_URL . "client/auth/login.php");
exit;
