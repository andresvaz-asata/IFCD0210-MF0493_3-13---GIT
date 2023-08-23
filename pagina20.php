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
      <?php echo PAGINA20; ?>
      <a href="<?php echo ENLACE_PAG00; ?>">
        <img src="./imgs/volver.png" alt="Volver" style="width: 25px; height: 25px; border: none;" />
      </a>
    </h2><br>
    <?php
    // Si no se inició sesión como administrador muestra el aviso en pantalla
    if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrador') {
      echo '<p style="color: orange;">No podrá modificar los tickets una vez creados. Póngase en contacto con el administrador de ser necesario.</p><br>';
    }

    // Incluir el archivo de conexión a la base de datos
    require_once './modelo/db.php';

    try {
      $consulta = "SELECT t.*, u.nombre, u.apellidos FROM Ticket t
                         JOIN UsuarioTicket ut ON t.id = ut.id_ticket
                         JOIN Usuario u ON ut.id_usuario = u.id
                         WHERE u.id = :usuario_id
                         ORDER BY t.fecha ASC";
      $stmt = $db->prepare($consulta);
      $stmt->bindParam(':usuario_id', $_SESSION['usuario_id'], PDO::PARAM_INT);
      $stmt->execute();
      $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

      if (!empty($tickets)) {
        echo '<br>';
        echo '<table style="border-collapse: collapse;">';
        echo '<tr><th>ID</th><th>Fecha</th><th>Descripción</th><th>Estado</th></tr>';

        foreach ($tickets as $ticket) {
          echo '<tr>';
          echo '<td style="border: 1px solid black; padding: 5px;">' . htmlspecialchars($ticket['id'], ENT_QUOTES, 'UTF-8') . '</td>';
          echo '<td style="border: 1px solid black; padding: 5px;">' . htmlspecialchars($ticket['fecha'], ENT_QUOTES, 'UTF-8') . '</td>';
          echo '<td style="border: 1px solid black; padding: 5px;">' . htmlspecialchars($ticket['descripcion'], ENT_QUOTES, 'UTF-8') . '</td>';
          echo '<td style="border: 1px solid black; padding: 5px;">' . htmlspecialchars($ticket['estado'], ENT_QUOTES, 'UTF-8') . '</td>';
        }

        echo '</table><br>';
      } else {
        echo 'No se encontraron tickets.';
      }
    } catch (PDOException $e) {
      echo 'Error: ' . $e->getMessage();
    }
    ?>
  </section>
</main>

<script src="./js/javascript.js"></script>
<?php include './includes/footer.php'; ?>