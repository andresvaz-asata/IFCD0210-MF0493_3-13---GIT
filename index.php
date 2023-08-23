<?php
// Verificar y comenzar la sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Incluir el header y las constantes
require_once './includes/constantes.php';
require_once './includes/header_0.php';
?>

<main>

    <section>
        <button class="button-link" onclick="modoOscuro()">Modo claro / oscuro</button>
        <!-- Enlace de Salir si el usuario ha iniciado sesión -->
        <?php if (isset($_SESSION['usuario_id'], $_SESSION['nombre'], $_SESSION['apellidos'])): ?>
            <a href="<?php echo ENLACE_SALIR; ?>" class="button-link"><?php echo PAGINA_SALIR; ?></a>
            <a href="<?php echo ENLACE_PAG00; ?>" class="button-link"><?php echo PAGINA_00; ?></a>
        <?php endif; ?>
        <br><br>

        <section class="welcome-section">
            <div class="welcome-content">
                <h2>BIENVENIDOS AL CENTRO DE SOPORTE DE WebTicketPro</h2><br>
                <p>Con el fin de agilizar las solicitudes de soporte y mantenimiento utilizamos un sistema de tickets. A
                    cada solicitud que nos envíe se le asignará un número único que se puede utilizar para revisar el
                    progreso y las respuestas de nuestros técnicos directamente y online. Para su información le
                    proporcionaremos todos los datos y la historia de todas sus solicitudes de soporte. El único dato
                    obligatorio será una dirección de email válida.</p>
            </div>
        </section><br>
        <h3>Por favor, introduzca sus datos para acceder a sus tickets:</h3>

        <form action="./controlador/controlador-login.php" method="POST">
            <label for="correo">Correo electrónico:</label>
            <input type="email" name="correo" id="correo" required><br><br>

            <label for="clave">Contraseña:</label>
            <input type="password" name="clave" id="clave" required>
            <span toggle="#clave" class="eye-toggle" onclick="togglePasswordVisibility(this)"></span>
            <br><br>

            <button class="estilos-input" type="submit">Iniciar sesión</button>
        </form><br>

        <p>O puedes <a href="pagina0A.php?invitado=true">entrar como invitado</a>.</p>

    </section>

</main>

<script src="./js/javascript.js"></script>
<script>
    function togglePasswordVisibility(icon) {
        const input = document.querySelector(icon.getAttribute('toggle'));
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.add('active');
        } else {
            input.type = 'password';
            icon.classList.remove('active');
        }
    }
</script>
<?php include './includes/footer.php'; ?>