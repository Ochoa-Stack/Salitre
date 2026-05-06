<?php
/* 'admin/espacios/crear.php' puede crear un nuevo espacio desde el panel de administración */
declare(strict_types=1);
require_once '../includes/auth_check.php';

require_once dirname(__DIR__, 2) . '/config/constants.php';
require_once dirname(__DIR__, 2) . '/config/database.php';

$errors = [];
$values = [
    'nombre'       => '',
    'slug'         => '',
    'tipo'         => 'estudio',
    'descripcion'  => '',
    'precio_noche' => '',
    'capacidad'    => '1',
    'activo'       => '1',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        try {
            $pdo  = conectarDB();
            $stmt = $pdo->prepare(
                'INSERT INTO espacios (nombre, slug, tipo, descripcion, precio_noche, capacidad, activo)
                 VALUES (:nombre, :slug, :tipo, :descripcion, :precio_noche, :capacidad, :activo)'
            );
            $stmt->execute([
                ':nombre'       => $values['nombre'],
                ':slug'         => $values['slug'],
                ':tipo'         => $values['tipo'],
                ':descripcion'  => $values['descripcion'],
                ':precio_noche' => $precio,
                ':capacidad'    => $capacidad,
                ':activo'       => (int) $values['activo'],
            ]);
            header('Location: ' . BASE_URL . 'admin/espacios/listar.php?msg=creado');
            exit();
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $errors[] = 'El slug "' . htmlspecialchars($values['slug'], ENT_QUOTES, 'UTF-8') . '" ya existe. Usa uno diferente.';
            } else {
                error_log('Admin espacios/crear: ' . $e->getMessage());
                $errors[] = 'Error al guardar. Inténtalo de nuevo.';
            }
        }
    }
}

// Definimos el título de la página y los estilos adicionales, luego incluimos el header y sidebar comunes
$page_title = 'Crear Espacio - Panel Salitre';
$extra_css  = ['assets/css/admin/crud.css'];
require __DIR__ . '/crear.view.php';
