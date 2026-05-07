<?php
/* 'admin/espacios/editar.php' es la página para editar un espacio existente desde el panel de administración */
declare(strict_types=1);
require_once '../includes/auth_check.php';
require_once dirname(__DIR__, 2) . '/config/constants.php';
require_once dirname(__DIR__, 2) . '/config/database.php';

// Validamos los parámetros de entrada 'GET'; es decir, el id del espacio a editar
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    header('Location: ' . BASE_URL . 'admin/espacios/listar.php');
    exit();
}

$pdo     = conectarDB();
$espacio = null;

try {
    $stmt = $pdo->prepare(
        'SELECT id, nombre, slug, tipo, descripcion, precio_noche, capacidad, activo, foto_principal FROM espacios WHERE id = :id LIMIT 1'
    );
    $stmt->execute([':id' => $id]);
    $espacio = $stmt->fetch();
} catch (Throwable $e) {
    error_log('Admin espacios/editar fetch: ' . $e->getMessage());
}

if (!$espacio) {
    header('Location: ' . BASE_URL . 'admin/espacios/listar.php');
    exit();
}

$errors = [];
$values = [
    'nombre'       => (string) $espacio['nombre'],
    'slug'         => (string) $espacio['slug'],
    'tipo'         => (string) $espacio['tipo'],
    'descripcion'  => (string) $espacio['descripcion'],
    'precio_noche' => (string) $espacio['precio_noche'],
    'capacidad'    => (string) $espacio['capacidad'],
    'activo'       => (string) $espacio['activo'],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        http_response_code(403);
        die('CSRF token validation failed');
    }
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

    // Sanitizamos y validamos los datos del formulario
    $values['nombre']       = trim((string) ($_POST['nombre']       ?? ''));
    $values['slug']         = trim((string) ($_POST['slug']         ?? ''));
    $values['tipo']         = trim((string) ($_POST['tipo']         ?? ''));
    $values['descripcion']  = trim((string) ($_POST['descripcion']  ?? ''));
    $values['precio_noche'] = trim((string) ($_POST['precio_noche'] ?? ''));
    $values['capacidad']    = trim((string) ($_POST['capacidad']    ?? ''));
    $values['activo']       = isset($_POST['activo']) ? '1' : '0';

    // Hacemos las respectivas validaciones basicas
    if ($values['nombre'] === '') {
        $errors[] = 'El nombre es obligatorio.';
    }
    if (!preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $values['slug'])) {
        $errors[] = 'El slug debe ser url-amigable (solo letras minúsculas, números y guiones).';
    }
    if (!in_array($values['tipo'], ['estudio', 'loft', 'suite', 'villa'], true)) {
        $errors[] = 'Tipo de espacio no válido.';
    }
    $precio = filter_var($values['precio_noche'], FILTER_VALIDATE_FLOAT);
    if ($precio === false || $precio <= 0) {
        $errors[] = 'El precio debe ser un número positivo.';
    }
    $capacidad = filter_var($values['capacidad'], FILTER_VALIDATE_INT);
    if ($capacidad === false || $capacidad < 1) {
        $errors[] = 'La capacidad debe ser al menos 1.';
    }

    if (empty($errors)) {
        $foto_nombre = $espacio['foto_principal'];
        if (isset($_FILES['foto_principal']) && $_FILES['foto_principal']['error'] === UPLOAD_ERR_OK) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $_FILES['foto_principal']['tmp_name']);
            finfo_close($finfo);

            $allowed_mimes = ['image/jpeg', 'image/png', 'image/webp'];
            if (!in_array($mime, $allowed_mimes, true)) {
                $errors[] = 'Formato de imagen no permitido. Solo JPG, PNG o WEBP.';
            } else {
                $ext = pathinfo($_FILES['foto_principal']['name'], PATHINFO_EXTENSION);
                $nuevo_nombre = uniqid('esp_') . '.' . $ext;
                
                if (!is_dir(UPLOAD_PATH)) {
                    mkdir(UPLOAD_PATH, 0777, true);
                }
                if (move_uploaded_file($_FILES['foto_principal']['tmp_name'], UPLOAD_PATH . $nuevo_nombre)) {
                    if ($foto_nombre && file_exists(UPLOAD_PATH . $foto_nombre)) {
                        unlink(UPLOAD_PATH . $foto_nombre);
                    }
                    $foto_nombre = $nuevo_nombre;
                } else {
                    $errors[] = 'Error al subir la nueva imagen.';
                }
            }
        }

        if (empty($errors)) {
            try {
                $stmt = $pdo->prepare(
                    'UPDATE espacios
                     SET nombre = :nombre, slug = :slug, tipo = :tipo, descripcion = :descripcion,
                         precio_noche = :precio_noche, capacidad = :capacidad, activo = :activo, foto_principal = :foto_principal
                     WHERE id = :id'
                );
                $stmt->execute([
                    ':nombre'       => $values['nombre'],
                    ':slug'         => $values['slug'],
                    ':tipo'         => $values['tipo'],
                    ':descripcion'  => $values['descripcion'],
                    ':precio_noche' => $precio,
                    ':capacidad'    => $capacidad,
                    ':activo'       => (int) $values['activo'],
                    ':foto_principal'=> $foto_nombre,
                    ':id'           => $id,
                ]);
                header('Location: ' . BASE_URL . 'admin/espacios/listar.php?msg=editado');
                exit();
            } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $errors[] = 'El slug "' . htmlspecialchars($values['slug'], ENT_QUOTES, 'UTF-8') . '" ya existe. Usa uno diferente.';
            } else {
                error_log('Admin espacios/editar update: ' . $e->getMessage());
                $errors[] = 'Error al guardar. Inténtalo de nuevo.';
            }
        }
        }
    }
}

// Si no hay errores, se muestra el formulario con los valores actuales o ingresados
$page_title = 'Editar Espacio - Panel Salitre';
$extra_css  = ['assets/css/admin/crud.css'];
require __DIR__ . '/editar.view.php';
