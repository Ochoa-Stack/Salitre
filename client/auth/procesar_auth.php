<?php
/* 'client/auth/procesar_auth.php' procesa tanto el registro como el login de clientes */
session_start();
require_once dirname(__DIR__) . "/../config/database.php";
require_once dirname(__DIR__) . "/../config/constants.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: " . BASE_URL . "client/auth/login.php");
    exit;
}

$action = $_POST["action"] ?? "";

try {
    $pdo = conectarDB();
} catch (PDOException $e) {
    // Si la DB falla temporalmente, lo pateamos al index
    error_log("Fallo Auth DB: " . $e->getMessage());
    header("Location: " . BASE_URL . "client/auth/login.php");
    exit;
}

/* Procesamos el registro o login */
if ($action === "registro") {
    // Sanitizamos los inputs
    $nombre = trim($_POST["nombre"] ?? "");
    $email = filter_var(trim($_POST["email"] ?? ""), FILTER_SANITIZE_EMAIL);
    $password = $_POST["password"] ?? "";
    $confirm_password = $_POST["confirmar_password"] ?? "";
    
    // Validamos los campos obligatorios
    if (empty($nombre) || empty($email) || empty($password)) {
        header("Location: " . BASE_URL . "client/auth/registro.php?error=empty_fields");
        exit;
    }
    
    // Validamos el formato del email
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
        "INSERT INTO clientes (nombre, email, password) VALUES (?, ?, ?)"
    );
    $stmt->execute([$nombre, $email, $password_hash]);
    
    // Iniciamos sesión automáticamente después del registro
    $_SESSION["cliente_id"] = $pdo->lastInsertId();
    $_SESSION["cliente_nombre"] = $nombre;
    session_regenerate_id(true);
    
    /* Restauramos el carrito pendiente si existe (mismo patrón que login) */
    if (isset($_SESSION["carrito_pendiente"])) {
        $pendiente = $_SESSION["carrito_pendiente"];
        $esp_id = filter_var($pendiente["espacio_id"] ?? 0, FILTER_VALIDATE_INT);
        $fe = $pendiente["fecha_entrada"] ?? "";
        $fs = $pendiente["fecha_salida"] ?? "";
        
        if ($esp_id && $fe && $fs) {
            $stmtE = $pdo->prepare("SELECT id, precio_noche FROM espacios WHERE id = ? AND activo = 1");
            $stmtE->execute([$esp_id]);
            $espCart = $stmtE->fetch(PDO::FETCH_ASSOC);
            
            if ($espCart) {
                try {
                    $entrada = new DateTime($fe);
                    $salida = new DateTime($fs);
                    $diff = $entrada->diff($salida);
                    $noches = ($diff->invert || $diff->days < 1) ? 0 : $diff->days;
                } catch (Exception $ex) {
                    $noches = 0;
                }
                
                if ($noches > 0) {
                    $subtotal = $espCart["precio_noche"] * $noches;
                    $iva = $subtotal * IVA;
                    $total = $subtotal + LIMPIEZA_FEE + $iva;
                    
                    $_SESSION["carrito"] = [
                        "espacio_id" => (int)$esp_id,
                        "fecha_entrada" => $fe,
                        "fecha_salida" => $fs,
                        "noches" => $noches,
                        "subtotal" => $subtotal,
                        "iva" => $iva,
                        "limpieza" => LIMPIEZA_FEE,
                        "total" => $total
                    ];
                }
            }
        }
        unset($_SESSION["carrito_pendiente"]);
    }
    
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
    $email = filter_var(trim($_POST["email"] ?? ""), FILTER_SANITIZE_EMAIL);
    $password = $_POST["password"] ?? "";
    
    // Validamos los campos obligatorios
    if (empty($email) || empty($password)) {
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
    $_SESSION["cliente_id"] = $usuario["id"];
    $_SESSION["cliente_nombre"] = $usuario["nombre"];
    session_regenerate_id(true);
    
    /* Restauramos el carrito pendiente si existe, recalculamos precios directamente con la base de datos (por posibles modificaciones de terceros) */
    if (isset($_SESSION["carrito_pendiente"])) {
        $pendiente = $_SESSION["carrito_pendiente"];
        $esp_id = filter_var($pendiente["espacio_id"] ?? 0, FILTER_VALIDATE_INT);
        $fe = $pendiente["fecha_entrada"] ?? "";
        $fs = $pendiente["fecha_salida"] ?? "";
        
        if ($esp_id && $fe && $fs) {
            $stmtE = $pdo->prepare("SELECT id, precio_noche, slug FROM espacios WHERE id = ? AND activo = 1");
            $stmtE->execute([$esp_id]);
            $espCart = $stmtE->fetch(PDO::FETCH_ASSOC);
            
            if ($espCart) {
                try {
                    $entrada = new DateTime($fe);
                    $salida = new DateTime($fs);
                    $diff = $entrada->diff($salida);
                    $noches = ($diff->invert || $diff->days < 1) ? 0 : $diff->days;
                } catch (Exception $ex) {
                    $noches = 0;
                }
                
                if ($noches > 0) {
                    $subtotal = $espCart["precio_noche"] * $noches;
                    $iva = $subtotal * IVA;
                    $total = $subtotal + LIMPIEZA_FEE + $iva;
                    
                    $_SESSION["carrito"] = [
                        "espacio_id" => (int)$esp_id,
                        "fecha_entrada" => $fe,
                        "fecha_salida" => $fs,
                        "noches" => $noches,
                        "subtotal" => $subtotal,
                        "iva" => $iva,
                        "limpieza" => LIMPIEZA_FEE,
                        "total" => $total
                    ];
                }
            }
        }
        unset($_SESSION["carrito_pendiente"]);
    }
    
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
