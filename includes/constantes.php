<!-- Constantes de la aplicación -->
<?php
// Define la constante de versión de la aplicación
define('VERSION', '1.0');

// Otras constantes
define('AUTOR', 'Jose Antonio Vega');
define('TITULO', 'WebTicketPro');

// Títulos de las páginas y menús

define('MENU_LOGIN', 'Login');
define('PAGINA_LOGIN', 'Bienvenido a la app ');
define('MENU_SALIR', 'Salir');
define('PAGINA_SALIR', 'Salir/Logout');

define('PAGINA0A', 'Página para invitados');

define('PAGINA_00', 'Menú principal');
define('PAGINA00', 'Usuarios y clientes');
define('PAGINA01', 'Modificar usuarios o clientes');

define('PAGINA10', 'Tickets');
define('PAGINA11', 'Todos los tickets abiertos');
define('PAGINA12', 'Todos los tickets en proceso');
define('PAGINA13', 'Todos los tickets cerrados');
define('PAGINA14', 'Tickets por cliente');

define('PAGINA20', 'Consultar mis tickets');
define('PAGINA21', 'Crear ticket nuevo');
define('PAGINA22', 'Crear ticket nuevo (admin)');


define('PAGINA_AGREGAR_USUARIO', 'Agregar usuario');
define('PAGINA_MODIFICAR_USUARIO', 'Modificar usuario');
define('PAGINA_ELIMINAR_USUARIO', 'Eliminar usuario');
define('PAGINA_AGREGAR_TICKET', 'Agregar TICKET');
define('PAGINA_MODIFICAR_TICKET', 'Modificar TICKET');
define('PAGINA_ELIMINAR_TICKET', 'Eliminar TICKET');


// Constantes de enlaces para navegacion_1 (ejecución desde la raíz del proyecto)

define('ENLACE_INDEX', 'index.php');
define('ENLACE_SALIR', './controlador/controlador-logout.php');
define('ENLACE_PAG00', 'pagina00.php');
define('ENLACE_PAG01', 'pagina01.php');

define('ENLACE_PAG10', 'pagina10.php');
define('ENLACE_PAG11', 'pagina11.php');
define('ENLACE_PAG12', 'pagina12.php');
define('ENLACE_PAG13', 'pagina13.php');
define('ENLACE_PAG14', 'pagina14.php');

define('ENLACE_PAG20', 'pagina20.php');
define('ENLACE_PAG21', 'pagina21.php');
define('ENLACE_PAG22', 'pagina22.php');

// Constantes de enlaces para navegacion_2 (ejecución desde dentro de cualquier carpeta del proyecto)
define('ENLACE_INDEX_', '../index.php');
define('ENLACE_SALIR_', 'controlador-logout.php');
define('ENLACE_PAG_00', '../pagina00.php');
define('ENLACE_PAG_01', '../pagina01.php');

define('ENLACE_PAG_10', '../pagina10.php');
define('ENLACE_PAG_11', '../pagina11.php');
define('ENLACE_PAG_12', '../pagina12.php');
define('ENLACE_PAG_13', '../pagina13.php');
define('ENLACE_PAG_14', '../pagina14.php');

define('ENLACE_PAG_20', '../pagina20.php');
define('ENLACE_PAG_21', '../pagina21.php');

?>
