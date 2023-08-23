<?php
// Verificar y comenzar la sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Si no se accedió a sesión y no se accedió como invitado vuelve a index.php
if (empty($_SESSION['usuario_id']) && !$_SESSION['invitado']) {
    header("location:index.php");
}

// Incluimos el header y las constantes
require_once './includes/constantes.php';
require_once './includes/header_1.php';
?>

<main>
    <?php require_once './includes/navegacion_1.php'; ?>
    <section>
        <h2>
            <?php echo PAGINA21; ?>
            <a href="<?php echo ENLACE_PAG00; ?>">
                <img src="./imgs/volver.png" alt="Volver" style="width: 25px; height: 25px; border: none;" />
            </a>
        </h2><br>
        <?php
        // Incluir el archivo de conexión a la base de datos
        require_once './modelo/db.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $descripcion = $_POST['descripcion'];
            $estado = 'abierto'; // El estado será por defecto "abierto"
            $usuario_id = $_SESSION['usuario_id']; // Obtener el ID del usuario logueado

            try {
                // Insertar un nuevo ticket en la tabla Ticket
                $consulta = "INSERT INTO Ticket (fecha, descripcion, estado) VALUES (NOW(), :descripcion, :estado)";
                $stmt = $db->prepare($consulta);
                $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
                $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
                $stmt->execute();

                // Obtener el ID del ticket recién insertado
                $ticketId = $db->lastInsertId();

                // Insertar una relación en la tabla UsuarioTicket
                $consulta = "INSERT INTO UsuarioTicket (id_usuario, id_ticket) VALUES (:usuario_id, :ticket_id)";
                $stmt = $db->prepare($consulta);
                $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
                $stmt->bindParam(':ticket_id', $ticketId, PDO::PARAM_INT);
                $stmt->execute();

                echo '<p>Ticket creado exitosamente.</p>';
            } catch (PDOException $e) {
                echo 'Error: ' . $e->getMessage();
            }
        }
        ?>
        <form action="" method="POST">
            <label for="descripcion">Descripción:</label>
            <textarea name="descripcion" id="descripcion" rows="6" cols="40" class="textarea-con-estilo" required></textarea><br><br>
            <button class="estilos-input" type="submit">Crear Ticket</button>
        </form>
    </section>
</main>

<script src="./js/javascript.js"></script>
<?php include './includes/footer.php'; ?>
