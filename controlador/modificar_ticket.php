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
            <?php echo PAGINA_MODIFICAR_TICKET; ?>
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
                    // Mostrar el formulario con los valores actuales del ticket
                    ?>
                    <form action="" method="POST">
                        <label for="descripcion">Descripción:</label>
                        <textarea name="descripcion" id="descripcion"  rows="6" cols="40"  class="textarea-con-estilo" required><?php echo htmlspecialchars($ticket['descripcion'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                        <br><br>
                        <label for="estado">Estado:</label>
                        <select name="estado" id="estado" required>
                            <option value="abierto" <?php if ($ticket['estado'] == 'abierto')
                                echo 'selected'; ?>>Abierto</option>
                            <option value="en proceso" <?php if ($ticket['estado'] == 'en proceso')
                                echo 'selected'; ?>>En Proceso
                            </option>
                            <option value="cerrado" <?php if ($ticket['estado'] == 'cerrado')
                                echo 'selected'; ?>>Cerrado</option>
                        </select><br><br>

                        <button class="estilos-input" type="submit">Guardar cambios</button>
                    </form>
                    <?php
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
            if (isset($_GET['id'])) {
                $ticketId = $_GET['id'];

                // Obtener los datos del formulario
                $descripcion = $_POST['descripcion'];
                $estado = $_POST['estado'];

                try {
                    // Obtener los datos del ticket antes de la actualización
                    $consulta = "SELECT * FROM Ticket WHERE id = :ticketId";
                    $stmt = $db->prepare($consulta);
                    $stmt->bindParam(':ticketId', $ticketId, PDO::PARAM_INT);
                    $stmt->execute();
                    $ticketOriginal = $stmt->fetch(PDO::FETCH_ASSOC);

                    // Actualizar los datos del ticket en la base de datos
                    $consulta = "UPDATE Ticket SET descripcion = :descripcion, estado = :estado WHERE id = :ticketId";
                    $stmt = $db->prepare($consulta);
                    $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
                    $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
                    $stmt->bindParam(':ticketId', $ticketId, PDO::PARAM_INT);
                    $stmt->execute();

                    // Si el ticket pasó a estado "cerrado", enviar un correo al usuario
                    if ($estado === 'cerrado' && $ticketOriginal['estado'] !== 'cerrado') {
                        require_once '../vendor/autoload.php';

                        // Configurar PHPMailer
                        $mail = new PHPMailer;
                        $mail->isSMTP();
                        // Configurar los detalles del servidor SMTP
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = '';
                        $mail->Password = '';
                        $mail->SMTPSecure = 'tls';
                        $mail->Port = 587;

                        // Configurar el correo electrónico
                        $mail->setFrom('tu_correo', 'Nombre Remitente');
                        $mail->addAddress($ticketOriginal['correo_usuario'], $ticketOriginal['nombre_usuario']); // Cambia a la dirección y nombre del usuario

                        $mail->Subject = 'Ticket Cerrado';
                        $mail->Body = 'Estimado ' . $ticketOriginal['nombre_usuario'] . ',<br><br>Tu ticket con la siguiente descripción:<br><br>' . $ticketOriginal['descripcion'] . '<br><br>ha sido cerrado.<br><br>Atentamente,<br>El Equipo de Soporte';
                        $mail->isHTML(true);

                        if ($mail->send()) {
                            echo '<p>Los cambios se guardaron exitosamente y se envió un correo al usuario.</p>';
                        } else {
                            echo '<p>Los cambios se guardaron exitosamente, pero no se pudo enviar el correo al usuario.</p>';
                        }
                    } else {
                        echo '<p>Los cambios se guardaron exitosamente.</p>';
                    }
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
