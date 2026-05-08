<?php
/* 'admin/includes/helpers.php' — Funciones auxiliares compartidas por los controladores del admin */
declare(strict_types=1);

/**
 * Gestiona el upload de una imagen de espacio validando el MIME type real.
 * Retorna el nombre del archivo guardado, o null si no hay archivo.
 * Agrega mensajes de error al array $errors pasado por referencia.
 *
 * @param  string      $input_name  Nombre del campo <input type="file">
 * @param  array       &$errors     Array de errores al que se agregan mensajes si falla
 * @param  string|null $foto_actual Nombre del archivo existente (para preservarlo si no hay nuevo upload)
 * @return string|null              Nombre del archivo a guardar en BD, o null si no hay imagen
 */
function procesarUploadImagen(string $input_name, array &$errors, ?string $foto_actual = null): ?string
{
    // Si no se subió ningún archivo o el campo fue omitido, devolvemos la foto actual sin cambios
    if (!isset($_FILES[$input_name]) || $_FILES[$input_name]['error'] === UPLOAD_ERR_NO_FILE) {
        return $foto_actual;
    }

    if ($_FILES[$input_name]['error'] !== UPLOAD_ERR_OK) {
        $errors[] = 'Error al recibir el archivo de imagen (código ' . $_FILES[$input_name]['error'] . ').';
        return $foto_actual;
    }

    // Validamos el MIME type real usando finfo para no confiar en la extensión del nombre
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime  = finfo_file($finfo, $_FILES[$input_name]['tmp_name']);
    finfo_close($finfo);

    $allowed_mimes = ['image/jpeg', 'image/png', 'image/webp'];
    if (!in_array($mime, $allowed_mimes, true)) {
        $errors[] = 'Formato de imagen no permitido. Solo JPG, PNG o WEBP.';
        return $foto_actual;
    }

    // Generamos un nombre único para evitar colisiones y sobreescrituras accidentales
    $ext        = pathinfo($_FILES[$input_name]['name'], PATHINFO_EXTENSION);
    $nuevo_nombre = uniqid('esp_') . '.' . $ext;

    if (!is_dir(UPLOAD_PATH)) {
        mkdir(UPLOAD_PATH, 0755, true);
    }

    if (!move_uploaded_file($_FILES[$input_name]['tmp_name'], UPLOAD_PATH . $nuevo_nombre)) {
        $errors[] = 'Error al guardar la imagen en el servidor.';
        return $foto_actual;
    }

    // Si había una foto anterior y se reemplazó con éxito, la eliminamos del disco
    if ($foto_actual && file_exists(UPLOAD_PATH . $foto_actual)) {
        unlink(UPLOAD_PATH . $foto_actual);
    }

    return $nuevo_nombre;
}
