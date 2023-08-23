<?php
// Verificar y comenzar la sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Si no se accedió como administrador redirige a index.php
if (!isset($_SESSION['rol']) || $_SESSION["rol"] !== 'administrador') {
    header("location:index.php");
    exit();
}
//  Incluimos el header y las constantes
require_once './includes/constantes.php';
require_once './includes/header_1.php';
?>

<main>
    <?php require_once './includes/navegacion_1.php'; ?>
    <section>
        <h2>
            <?php echo PAGINA11; ?>
            <a href="<?php echo ENLACE_PAG00; ?>">
                <img src="./imgs/volver.png" alt="Volver" style="width: 25px; height: 25px; border: none;" />
            </a>
        </h2><br>
        <?php
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrador') {
            echo 'Como usuario del tipo invitado no se le permite consultar los tickets abiertos.' . '<br>';
        }
        ?>

        <?php
        // Incluir el archivo de conexión a la base de datos
        require_once './modelo/db.php';

        try {
            // Consulta para obtener todos los tickets con estado "abierto" y los nombres de los usuarios asociados, ordenados por fecha de creación
            $consulta = "SELECT t.id, t.fecha, t.descripcion, t.estado, u.nombre, u.apellidos, ubic.ciudad AS ubicacion FROM Ticket t
                        INNER JOIN UsuarioTicket ut ON t.id = ut.id_ticket
                        INNER JOIN Usuario u ON ut.id_usuario = u.id
                        INNER JOIN Ubicacion ubic ON u.ubicacion_id = ubic.id
                        WHERE t.estado = 'abierto' ORDER BY t.fecha ASC";
            $stmt = $db->prepare($consulta);
            $stmt->execute();
            $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($tickets) {
                echo '<br>';
                echo '<table style="border-collapse: collapse;">';
                echo '<tr><th>ID</th><th>Fecha</th><th>Descripción</th><th>Estado</th><th>Usuario</th><th>Ubicación</th><th>Acciones</th></tr>';

                foreach ($tickets as $ticket) {
                    echo '<tr>';
                    echo '<td style="border: 1px solid black; padding: 5px;">' . htmlspecialchars($ticket['id'], ENT_QUOTES, 'UTF-8') . '</td>';
                    echo '<td style="border: 1px solid black; padding: 5px;">' . htmlspecialchars($ticket['fecha'], ENT_QUOTES, 'UTF-8') . '</td>';
                    echo '<td style="border: 1px solid black; padding: 5px;">' . htmlspecialchars($ticket['descripcion'], ENT_QUOTES, 'UTF-8') . '</td>';
                    echo '<td style="border: 1px solid black; padding: 5px;">' . htmlspecialchars($ticket['estado'], ENT_QUOTES, 'UTF-8') . '</td>';
                    echo '<td style="border: 1px solid black; padding: 5px;">' . htmlspecialchars($ticket['nombre'] . ' ' . $ticket['apellidos'], ENT_QUOTES, 'UTF-8') . '</td>';
                    echo '<td style="border: 1px solid black; padding: 5px;">' . htmlspecialchars($ticket['ubicacion'], ENT_QUOTES, 'UTF-8') . '</td>'; // Nueva celda para la ubicación
                    echo '<td style="border: 1px solid black; padding: 5px;">';
                    // Botones de Acciones
                    echo '
                        <form action="./controlador/modificar_ticket.php" method="GET" style="display: inline;">
                            <input type="hidden" name="id" value="' . $ticket['id'] . '">
                            <button class="estilos-editar" type="submit">Modificar</button>
                        </form>
                        <form action="./controlador/eliminar_ticket.php" method="GET" style="display: inline;">
                            <input type="hidden" name="id" value="' . $ticket['id'] . '">
                            <button class="estilos-eliminar" type="submit">Eliminar</button>
                        </form>
                    ';

                    echo '</td>';
                    echo '</tr>';
                }

                echo '</table><br>';
            } else {
                echo 'No se encontraron tickets abiertos.';
            }
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
        ?>
    </section>
</main>

<script src="./js/javascript.js"></script>
<?php include './includes/footer.php'; ?>
