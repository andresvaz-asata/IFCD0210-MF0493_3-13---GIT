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
            <?php echo PAGINA_ELIMINAR_USUARIO; ?>
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
                    // Mostrar los detalles del usuario
                    echo '<h3>Detalles del usuario a eliminar:</h3>';
                    echo '<table style="border-collapse: collapse;">';
                    echo '<tr><th>Campo</th><th>Valor</th></tr>';

                    foreach ($usuario as $campo => $valor) {
                        echo '<tr>';
                        echo '<td style="border: 1px solid black; padding: 5px;">' . htmlspecialchars($campo, ENT_QUOTES, 'UTF-8') . '</td>';
                        echo '<td style="border: 1px solid black; padding: 5px;">' . htmlspecialchars($valor, ENT_QUOTES, 'UTF-8') . '</td>';
                        echo '</tr>';
                    }

                    echo '</table><br>';

                    // Mostrar el formulario de confirmación de eliminación
                    ?>
                    <form action="" method="POST">
                        <input type="hidden" name="id" value="<?php echo $usuarioId; ?>">
                        <p>¿Estás seguro de que deseas eliminar este usuario?</p>
                        <button class="estilos-eliminar" type="submit">Eliminar usuario</button>
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
            if (isset($_POST['id'])) {
                $usuarioId = $_POST['id'];

                try {
                    // Eliminar los registros de UsuarioTicket asociados
                    $consultaEliminarUsuarioTicket = "DELETE FROM UsuarioTicket WHERE id_usuario = :usuarioId";
                    $stmtEliminarUsuarioTicket = $db->prepare($consultaEliminarUsuarioTicket);
                    $stmtEliminarUsuarioTicket->bindParam(':usuarioId', $usuarioId, PDO::PARAM_INT);
                    $stmtEliminarUsuarioTicket->execute();

                    // Eliminar el usuario de la base de datos
                    $consultaEliminarUsuario = "DELETE FROM Usuario WHERE id = :usuarioId";
                    $stmtEliminarUsuario = $db->prepare($consultaEliminarUsuario);
                    $stmtEliminarUsuario->bindParam(':usuarioId', $usuarioId, PDO::PARAM_INT);
                    $stmtEliminarUsuario->execute();

                    echo '<p>Usuario y tickets asociados eliminados exitosamente.</p>';
                } catch (PDOException $e) {
                    echo 'Error: ' . $e->getMessage();
                }
            } else {
                echo 'No se proporcionó un ID de usuario válido.';
            }
        }
        ?>
    </section>
</main>

<script src="../js/javascript.js"></script>
<?php include '../includes/footer.php'; ?>
