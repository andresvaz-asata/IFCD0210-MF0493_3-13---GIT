<?php
// Verificar y comenzar la sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!-- ejecución desde la raíz del proyecto -->
<nav>
    <button class="button-link" onclick="modoOscuro()">Modo claro / oscuro</button><br>
    <!-- Enlace de Salir si el usuario ha iniciado sesión, de lo contrario enlace a la página de login -->
    <?php if (isset($_SESSION['usuario_id'], $_SESSION['nombre'], $_SESSION['apellidos'])): ?>
        <a href="<?php echo ENLACE_SALIR; ?>" class="button-link"><?php echo PAGINA_SALIR; ?></a>
    <?php else: ?>
        <a href="<?php echo ENLACE_INDEX; ?>" class="button-link"><?php echo MENU_LOGIN; ?></a>
    <?php endif; ?>

    <ol>
        <?php if (isset($_SESSION['usuario_id']) && $_SESSION["rol"] == 'administrador'): ?>
            <!-- Menú para el usuario administrador -->
            <h3>
                <?php echo PAGINA00; ?>
            </h3>
            <li><a href="<?php echo ENLACE_PAG_01; ?>"><?php echo PAGINA01; ?></a></li>
        <?php endif; ?>
        <!-- Menú para todos -->
        <h3>
            <?php echo PAGINA10; ?>
        </h3>
        <?php if (isset($_SESSION['usuario_id']) && $_SESSION["rol"] == 'administrador'): ?>
            <!-- Menú para el usuario administrador -->
            <li><a href="<?php echo ENLACE_PAG_11; ?>"><?php echo PAGINA11; ?></a></li>
            <li><a href="<?php echo ENLACE_PAG_12; ?>"><?php echo PAGINA12; ?></a></li>
            <li><a href="<?php echo ENLACE_PAG_13; ?>"><?php echo PAGINA13; ?></a></li>
            <li><a href="<?php echo ENLACE_PAG_14; ?>"><?php echo PAGINA14; ?></a></li>
        <?php endif; ?>

        <?php if (isset($_SESSION['usuario_id']) && $_SESSION["rol"] == 'cliente'): ?>
            <!-- Menú para el usuario cliente -->
            <li><a href="<?php echo ENLACE_PAG_20; ?>"><?php echo PAGINA20; ?></a></li>
            <li><a href="<?php echo ENLACE_PAG_21; ?>"><?php echo PAGINA21; ?></a></li>
        <?php endif; ?>
    </ol>
</nav>