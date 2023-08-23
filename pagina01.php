<?php
// Verificar y comenzar la sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Si no se accedió como administrador redirige a index.php
if (!isset($_SESSION['usuario_id']) || $_SESSION["rol"] !== 'administrador') {
    header("location:index.php");
    exit();
}
// Incluimos el header y las constantes
require_once './includes/constantes.php';
require_once './includes/header_1.php';
?>

<main>
    <?php require_once './includes/navegacion_1.php'; ?>
    <section>
        <h2>
            <?php echo PAGINA01; ?>
            <a href="<?php echo ENLACE_PAG00; ?>">
                <img src="./imgs/volver.png" alt="Volver" style="width: 25px; height: 25px; border: none;" />
            </a>
        </h2><br>
        <?php
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrador') {
            echo 'Como usuario del tipo invitado no se le permite modificar, eliminar ni agregar usuarios.' . '<br>';
        }
        ?>

        <?php
        // Incluir el archivo de conexión a la base de datos
        require_once './modelo/db.php';

        $porPagina = 5;
        $paginaActual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;

        try {
            $totalUsuarios = $db->query("SELECT COUNT(*) FROM Usuario")->fetchColumn();
            $totalPaginas = ceil($totalUsuarios / $porPagina);

            $consulta = "SELECT * FROM Usuario LIMIT :inicio, :porPagina";
            $stmt = $db->prepare($consulta);
            $inicio = ($paginaActual - 1) * $porPagina;
            $stmt->bindParam(':inicio', $inicio, PDO::PARAM_INT);
            $stmt->bindParam(':porPagina', $porPagina, PDO::PARAM_INT);
            $stmt->execute();
            $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($usuarios)) {
                echo '<br>';
                echo '<table style="border-collapse: collapse;">';
                echo '<tr><th>ID</th><th>Nombre</th><th>Apellidos</th><th>Correo Electrónico</th><th>Password</th><th>Rol</th><th>Ubicación</th><th>Acciones</th></tr>';

                foreach ($usuarios as $usuario) {
                    echo '<tr>';
                    echo '<td style="border: 1px solid black; padding: 5px;">' . htmlspecialchars($usuario['id'], ENT_QUOTES, 'UTF-8') . '</td>';
                    echo '<td style="border: 1px solid black; padding: 5px;">' . htmlspecialchars($usuario['nombre'], ENT_QUOTES, 'UTF-8') . '</td>';
                    echo '<td style="border: 1px solid black; padding: 5px;">' . htmlspecialchars($usuario['apellidos'], ENT_QUOTES, 'UTF-8') . '</td>';
                    echo '<td style="border: 1px solid black; padding: 5px;">' . htmlspecialchars($usuario['correo'], ENT_QUOTES, 'UTF-8') . '</td>';
                    echo '<td style="border: 1px solid black; padding: 5px;">' . htmlspecialchars($usuario['password'], ENT_QUOTES, 'UTF-8') . '</td>';
                    echo '<td style="border: 1px solid black; padding: 5px;">' . htmlspecialchars($usuario['rol'], ENT_QUOTES, 'UTF-8') . '</td>';

                    // Consultar el nombre de la ciudad según el ubicacion_id
                    $ubicacionId = $usuario['ubicacion_id'];
                    $consultaUbicacion = "SELECT ciudad FROM Ubicacion WHERE id = :ubicacionId";
                    $stmtUbicacion = $db->prepare($consultaUbicacion);
                    $stmtUbicacion->bindParam(':ubicacionId', $ubicacionId, PDO::PARAM_INT);
                    $stmtUbicacion->execute();
                    $ubicacion = $stmtUbicacion->fetchColumn();

                    echo '<td style="border: 1px solid black; padding: 5px;">' . htmlspecialchars($ubicacion, ENT_QUOTES, 'UTF-8') . '</td>';

                    echo '<td style="border: 1px solid black; padding: 5px;">';

                    // Verificar si el rol del usuario actual es "administrador"
                    if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'administrador') {
                        echo '
            <form action="./controlador/modificar_usuario.php" method="GET" style="display: inline;">
                <input type="hidden" name="id" value="' . $usuario['id'] . '">
                <button class="estilos-editar" type="submit">Modificar</button>
            </form>
            <form action="./controlador/eliminar_usuario.php" method="GET" style="display: inline;">
                <input type="hidden" name="id" value="' . $usuario['id'] . '">
                <button class="estilos-eliminar" type="submit">Eliminar</button>
            </form>
        ';
                    }

                    echo '</td>';
                    echo '</tr>';
                }

                echo '</table><br>';

                if ($totalPaginas > 1) {
                    echo '<div class="paginacion">';
                    if ($paginaActual > 1) {
                        echo '<a href="?pagina=' . ($paginaActual - 1) . '">Anterior </a>';
                    }
                    for ($i = 1; $i <= $totalPaginas; $i++) {
                        if ($i == $paginaActual) {
                            echo '<a href="?pagina=' . $i . '" style="font-weight: bold; font-size: 16px; color: red; margin: 0 5px;">' . $i . '</a>';
                        } else {
                            echo '<a href="?pagina=' . $i . '" style="margin: 0 5px;">' . $i . '</a>';
                        }
                    }
                    if ($paginaActual < $totalPaginas) {
                        echo '<a href="?pagina=' . ($paginaActual + 1) . '"> Siguiente</a>';
                    }
                    echo '</div><br>';
                }

                // Verificar si el rol del usuario actual es "administrador"
                if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'administrador') {
                    // Enlace para agregar un usuario
                    echo '<button class="estilos-input" type="button" onclick="location.href=\'./controlador/agregar_usuario.php\'">Agregar usuario</button>';
                }
            } else {
                echo 'No se encontraron usuarios.';
            }
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
        ?>
    </section>
</main>

<script src="./js/javascript.js"></script>
<?php include './includes/footer.php'; ?>