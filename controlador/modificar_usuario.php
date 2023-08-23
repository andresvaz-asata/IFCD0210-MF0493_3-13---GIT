<?php
// Verificar y comenzar la sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Si no se inició sesión con el rol de administrador redirige a index.php
if ($_SESSION["rol"] != 'administrador') {
    header("location:../index.php");
}
// Incluimos el header y las constantes
require_once '../includes/constantes.php';
require_once '../includes/header_2.php';
?>

<main>
    <?php require_once '../includes/navegacion_2.php'; ?>
    <section>
        <h2>
            <?php echo PAGINA_MODIFICAR_USUARIO; ?>
            <a href="<?php echo ENLACE_PAG_01; ?>">
                <img src="../imgs/volver.png" alt="Volver" style="width: 25px; height: 25px; border: none;" />
            </a>
        </h2><br>
        <?php
        // Incluir el archivo de conexión
        require_once '../modelo/db.php';

        if (isset($_GET['id'])) {
            $usuarioId = $_GET['id'];

            try {
                // Obtener los datos del usuario seleccionado
                $consulta = "SELECT * FROM Usuario WHERE id = :usuarioId";
                $stmt = $db->prepare($consulta);
                $stmt->bindParam(':usuarioId', $usuarioId, PDO::PARAM_INT);
                $stmt->execute();
                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($usuario) {
                    // Mostrar el formulario con los valores actuales del usuario
                    ?>
                    <form action="" method="POST">
                        <label for="nombre">Nombre:</label>
                        <input type="text" name="nombre" id="nombre" value="<?php echo htmlspecialchars($usuario['nombre'], ENT_QUOTES, 'UTF-8'); ?>" required><br><br>

                        <label for="apellidos">Apellidos:</label>
                        <input type="text" name="apellidos" id="apellidos" value="<?php echo htmlspecialchars($usuario['apellidos'], ENT_QUOTES, 'UTF-8'); ?>" required><br><br>

                        <label for="correo">Correo electrónico:</label>
                        <input type="email" name="correo" id="correo" value="<?php echo htmlspecialchars($usuario['correo'], ENT_QUOTES, 'UTF-8'); ?>" required><br><br>

                        <label for="password_actual">Password actual:</label>
                        <input type="password" name="password_actual" id="password_actual" value="<?php echo htmlspecialchars($usuario['password'], ENT_QUOTES, 'UTF-8'); ?>" required>
                        <label><input type="checkbox" name="generar_nuevo_password"> Generar nuevo password</label><br><br>

                        <label for="rol">Rol:</label>
                        <select name="rol" id="rol" required>
                            <option value="cliente" <?php if ($usuario['rol'] == 'cliente') echo 'selected'; ?>>Cliente</option>
                            <option value="administrador" <?php if ($usuario['rol'] == 'administrador') echo 'selected'; ?>>Administrador</option>
                        </select><br><br>

                        <label for="ubicacion_id">Ubicación:</label>
                        <select name="ubicacion_id" id="ubicacion_id" required>
                            <?php
                            $ubicaciones = getUbicaciones($db);
                            foreach ($ubicaciones as $ubicacion) {
                                echo '<option value="' . $ubicacion['id'] . '"';
                                if ($usuario['ubicacion_id'] == $ubicacion['id']) {
                                    echo ' selected';
                                }
                                echo '>' . htmlspecialchars($ubicacion['ciudad'], ENT_QUOTES, 'UTF-8') . '</option>';
                            }
                            ?>
                        </select><br><br>

                        <button class="estilos-input" type="submit">Guardar cambios</button>
                    </form>
                    <?php
                } else {
                    echo 'No se encontró información del usuario seleccionado.';
                }
            } catch (PDOException $e) {
                echo 'Error: ' . $e->getMessage();
            }
        } else {
            echo 'No se proporcionó un ID de usuario válido.';
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_GET['id'])) {
                $usuarioId = $_GET['id'];

                // Obtener los datos del formulario
                $nombre = $_POST['nombre'];
                $apellidos = $_POST['apellidos'];
                $correo = $_POST['correo'];
                $password_actual = $_POST['password_actual'];
                $rol = $_POST['rol'];
                $ubicacion_id = $_POST['ubicacion_id'];

                if (isset($_POST['generar_nuevo_password'])) {
                    $password = generateRandomPassword(20);
                } else {
                    $password = $password_actual;
                }

                try {
                    // Actualizar los datos del usuario en la base de datos
                    $consulta = "UPDATE Usuario SET nombre = :nombre, apellidos = :apellidos, correo = :correo, password = :password, rol = :rol, ubicacion_id = :ubicacion_id WHERE id = :usuarioId";
                    $stmt = $db->prepare($consulta);
                    $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
                    $stmt->bindParam(':apellidos', $apellidos, PDO::PARAM_STR);
                    $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
                    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
                    $stmt->bindParam(':rol', $rol, PDO::PARAM_STR);
                    $stmt->bindParam(':ubicacion_id', $ubicacion_id, PDO::PARAM_INT);
                    $stmt->bindParam(':usuarioId', $usuarioId, PDO::PARAM_INT);
                    $stmt->execute();

                    echo '<p>Los cambios se guardaron exitosamente.</p>';
                } catch (PDOException $e) {
                    echo 'Error: ' . $e->getMessage();
                }
            } else {
                echo 'No se proporcionó un ID de usuario válido.';
            }
        }

        function generateRandomPassword($length) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $password = '';
            for ($i = 0; $i < $length; $i++) {
                $index = rand(0, strlen($characters) - 1);
                $password .= $characters[$index];
            }
            return $password;
        }

        function getUbicaciones($db) {
            $ubicaciones = [];
            try {
                $consulta = "SELECT * FROM Ubicacion ORDER BY ciudad";
                $stmt = $db->prepare($consulta);
                $stmt->execute();
                $ubicaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo 'Error: ' . $e->getMessage();
            }
            return $ubicaciones;
        }
        ?>
    </section>
</main>

<script src="../js/javascript.js"></script>
<script>
    // Mostrar u ocultar el campo de ubicación adicional según la selección
    const ubicacionSelect = document.getElementById('ubicacion_id');
    const otraUbicacionDiv = document.getElementById('otraUbicacion');
    ubicacionSelect.addEventListener('change', function () {
        if (ubicacionSelect.value === 'Otro') {
            otraUbicacionDiv.style.display = 'block';
        } else {
            otraUbicacionDiv.style.display = 'none';
        }
    });
</script>
<?php include '../includes/footer.php'; ?>
