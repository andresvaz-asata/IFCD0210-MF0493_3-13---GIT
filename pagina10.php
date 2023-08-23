<?php
// Verificar y comenzar la sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Si no se accedió a sesión o no se accedió como invitado vuelve a index.php
if (empty($_SESSION['autor_id']) || !isset($_SESSION['invitado'])) {
    header("location:index.php");
}
//  Incluimos el header y las constantes
require_once './includes/constantes.php';
require_once './includes/header_1.php';
?>
<main>
    <?php
    require_once './includes/navegacion_1.php';
    ?>
</main>
<!-- LLamada a un script que maneja el modo claro / oscuro -->
<script src="./js/javascript.js"></script>
<!-- Incluir el pie de página -->
<?php require_once 'includes/footer.php'; ?>