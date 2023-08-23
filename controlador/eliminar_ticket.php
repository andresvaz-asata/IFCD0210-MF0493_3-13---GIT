<?php
// Verificar y comenzar la sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluir el archivo de conexión
require_once '../modelo/db.php';

// Si no se inició sesión como administrador, redirige a index.php
if ($_SESSION["rol"] !== 'administrador') {
    header("location: ../index.php");
    exit(); // Salir del script para evitar que el resto del código se ejecute innecesariamente
}

// Incluimos el header y las constantes
require_once '../includes/constantes.php';
require_once '../includes/header_2.php';
?>

<main>
    <?php require_once '../includes/navegacion_2.php'; ?>
    <section>
        <h2>
            <?php echo PAGINA_ELIMINAR_TICKET; ?>
            <a href="<?php echo ENLACE_PAG_12; ?>">
                <img src="../imgs/volver.png" alt="Volver" style="width: 25px; height: 25px; border: none;" />
            </a>
        </h2><br>
        <?php
        // Incluir el archivo de conexión
        require_once '../modelo/db.php';

        if (isset($_GET['id'])) {
            $ticketId = $_GET['id'];

            try {
                // Obtener los datos del ticket seleccionado
                $consulta = "SELECT * FROM Ticket WHERE id = :ticketId";
                $stmt = $db->prepare($consulta);
                $stmt->bindParam(':ticketId', $ticketId, PDO::PARAM_INT);
                $stmt->execute();
                $ticket = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($ticket) {
                    echo '<p>¿Estás seguro de que deseas eliminar el siguiente ticket?</p>';
                    echo '<p>ID: ' . $ticket['id'] . '</p>';
                    echo '<p>Fecha: ' . $ticket['fecha'] . '</p>';
                    echo '<p>Descripción: ' . $ticket['descripcion'] . '</p>';
                    echo '<p>Estado: ' . $ticket['estado'] . '</p>';

                    // Mostrar el formulario de confirmación
                    echo '<form action="" method="POST">';
                    echo '<input type="hidden" name="ticket_id" value="' . $ticket['id'] . '">';
                    echo '<button class="estilos-input" type="submit">Eliminar Ticket</button>';
                    echo '</form>';
                } else {
                    echo 'No se encontró información del ticket seleccionado.';
                }
            } catch (PDOException $e) {
                echo 'Error: ' . $e->getMessage();
            }
        } else {
            echo 'No se proporcionó un ID de ticket válido.';
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['ticket_id'])) {
                $ticketId = $_POST['ticket_id'];

                try {
                    // Eliminar la relación en la tabla UsuarioTicket
                    $consultaRelacion = "DELETE FROM UsuarioTicket WHERE id_ticket = :ticketId";
                    $stmtRelacion = $db->prepare($consultaRelacion);
                    $stmtRelacion->bindParam(':ticketId', $ticketId, PDO::PARAM_INT);
                    $stmtRelacion->execute();

                    // Ahora que la relación ha sido eliminada, puedes eliminar el ticket de la tabla Ticket
                    $consultaTicket = "DELETE FROM Ticket WHERE id = :ticketId";
                    $stmtTicket = $db->prepare($consultaTicket);
                    $stmtTicket->bindParam(':ticketId', $ticketId, PDO::PARAM_INT);
                    $stmtTicket->execute();

                    echo '<p>Ticket eliminado exitosamente.</p>';
                } catch (PDOException $e) {
                    echo 'Error: ' . $e->getMessage();
                }
            } else {
                echo 'No se proporcionó un ID de ticket válido.';
            }
        }
        ?>
    </section>
</main>

<script src="../js/javascript.js"></script>
<?php include '../includes/footer.php'; ?>
