<?php
declare(strict_types=1);
/* Creamos la conexión a la base de datos mediante PDO */
function conectarDB(): PDO {
    /* Las credenciales se leen desde variables de entorno del servidor si existen.
       En producción: SetEnv DB_HOST, DB_NAME, DB_USER, DB_PASS en VirtualHost o .htaccess.
       En XAMPP local: se usan los valores por defecto sin ninguna configuración adicional */
       
    $host     = (string) (getenv('DB_HOST') ?: 'localhost');
    $dbname   = (string) (getenv('DB_NAME') ?: 'salitre_db');
    $username = (string) (getenv('DB_USER') ?: 'root');
    $password = (string) (getenv('DB_PASS') ?: '');

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
