<?php
// Datos de conexión a la base de datos
$host = '127.0.0.1';
$dbname = 'sistematickets';
$username = 'root';
$password = '';

// Intentar establecer la conexión a la base de datos
try {
    // Crear instancia de PDO
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Habilitar el modo de errores para mostrar excepciones
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Mostrar mensaje de error en caso de fallo en la conexión
    die("Error de conexión a la base de datos: " . $e->getMessage());
}
?>
