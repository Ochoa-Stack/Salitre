<?php
/* Creamos la conexión a la base de datos mediante PDO */
function conectarDB() {
    $host = 'localhost';
    $dbname = 'salitre_db';
    $username = 'root';
    $password = '';

    try {
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        
        $opciones = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        return new PDO($dsn, $username, $password, $opciones);

    } catch (PDOException $e) {
        error_log("Fallo la conexión a la base de datos: " . $e->getMessage());
        die("Error de conexión. Consulte al administrador.");
    }
}
?>
