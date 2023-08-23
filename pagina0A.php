<!-- Landing page -->
<?php
// Verificar y comenzar la sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Si no se accedió a sesión o no se accedió como invitado, vuelve a index.php
if (empty($_SESSION['autor_id']) && empty($_GET['invitado'])) {
    header("location:index.php");
    exit();
}
// Si se accedió como invitado, establecer la variable de sesión
if (!empty($_GET['invitado'])) {
    $_SESSION['invitado'] = true;
}
//  Incluimos el header y las constantes
require_once './includes/constantes.php';
require_once './includes/header_1.php';
?>
<br>
<main>
<?php 
require_once './includes/navegacion_1.php';
?>
<article>
<H2>Sistema de Tickets</H2>
<p>Una solución eficiente para gestionar tus tickets</p>
</article>
<article>
<h2>Características destacadas</h2>
<p>Descubre las funcionalidades clave de nuestro sistema de tickets</p>
</article>
<article>
<h3>Comunicación en tiempo real</H3>
<p>Organiza y prioriza tus tickets de manera efectiva</p>
</article>
<article>
<h3>Automatización de tareas</h3>
<p>Mantén a tu equipo y clientes informados con actualizaciones en tiempo real</p>
</article>


¿Aún no está registrado? Cree una cuenta
</main>
<!-- LLamada a un script que maneja el modo claro / oscuro -->
<script src="./js/javascript.js"></script>
<!-- Incluir el pie de página -->
<?php require_once 'includes/footer.php'; ?>