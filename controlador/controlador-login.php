<?php
// Incluir el archivo de conexión a la base de datos
require_once '../modelo/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $correo = $_POST['correo'];
    $clave = $_POST['clave'];

    // Verificar las credenciales de inicio de sesión
    try {
        $sql = "SELECT id, nombre, apellidos, rol FROM Usuario WHERE correo = :correo AND password = :clave";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
        $stmt->bindParam(':clave', $clave, PDO::PARAM_STR);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            // Credenciales válidas, iniciar sesión y redirigir al área personal del usuario
            session_start();
            $_SESSION['usuario_id'] = $resultado['id'];
            $_SESSION['nombre'] = $resultado['nombre'];
            $_SESSION['apellidos'] = $resultado['apellidos'];
            $_SESSION['rol'] = $resultado['rol'];
            header('Location: ../pagina00.php');
            // exit();
        } else {
            // Credenciales inválidas, redirigir a index.php
            header('Location: ../index.php?error=Credenciales inválidas');
            exit();
        }
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
?>
