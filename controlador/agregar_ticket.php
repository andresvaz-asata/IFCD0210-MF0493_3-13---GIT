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
            <?php echo PAGINA_AGREGAR_USUARIO; ?>
            <a href="<?php echo ENLACE_PAG_01; ?>">
                <img src="../imgs/volver.png" alt="Volver" style="width: 25px; height: 25px; border: none;" />
            </a>
        </h2><br>
        <?php
        // Incluir el archivo de conexión
        require_once '../modelo/db.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'];
            $apellidos = $_POST['apellidos'];
            $correo = $_POST['correo'];
            $password = $_POST['password'];
            $rol = $_POST['rol'];
            $ubicacion = $_POST['ubicacion'];

            try {
                // Insertar el nuevo usuario en la base de datos
                $consulta = "INSERT INTO Usuario (nombre, apellidos, correo, password, rol, ubicacion_id) VALUES (:nombre, :apellidos, :correo, :password, :rol, :ubicacion)";
                $stmt = $db->prepare($consulta);
                $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
                $stmt->bindParam(':apellidos', $apellidos, PDO::PARAM_STR);
                $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
                $stmt->bindParam(':password', $password, PDO::PARAM_STR);
                $stmt->bindParam(':rol', $rol, PDO::PARAM_STR);
                $stmt->bindParam(':ubicacion', $ubicacion, PDO::PARAM_INT);
                $stmt->execute();

                echo '<p>El nuevo usuario se agregó exitosamente.</p>';
            } catch (PDOException $e) {
                echo 'Error: ' . $e->getMessage();
            }
        }
        ?>

        <form action="" method="POST">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" id="nombre" required><br><br>

            <label for="apellidos">Apellidos:</label>
            <input type="text" name="apellidos" id="apellidos" required><br><br>

            <label for="correo">Correo electrónico:</label>
            <input type="email" name="correo" id="correo" required><br><br>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" value="<?php echo generateRandomPassword(20); ?>" required><br><br>

            <label for="rol">Rol:</label>
            <select name="rol" id="rol" required>
                <option value="cliente">Cliente</option>
                <option value="administrador">Administrador</option>
            </select><br><br>

            <label for="ubicacion">Ubicación:</label>
            <select name="ubicacion" id="ubicacion" required>
                <?php
                $ubicaciones = getUbicaciones($db);
                foreach ($ubicaciones as $ubicacion) {
                    echo '<option value="' . htmlspecialchars($ubicacion['id'], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($ubicacion['ciudad'], ENT_QUOTES, 'UTF-8') . '</option>';
                }
                ?>
            </select><br><br>

            <button class="estilos-input" type="submit">Agregar usuario</button>
        </form>
    </section>
</main>

<script src="../js/javascript.js"></script>
<?php include '../includes/footer.php'; ?>

<?php
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
