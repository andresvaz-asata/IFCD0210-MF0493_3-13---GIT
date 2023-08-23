<!-- ejecución desde la raíz del proyecto -->
<!DOCTYPE html>
<html lang="es-es">

<head>
  <meta charset="UTF-8">
  <title>
    <?php echo TITULO; ?>
  </title>
  <!-- Enlazamos la hoja de estilos -->
  <link rel="stylesheet" href="css/estilos.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
  <link rel="icon" type="image/x-icon" href="./imgs/favicon/favicon.png">
</head>

<body>
<header>
    <div class="logo">
      <a href="<?php echo ENLACE_INDEX; ?>">
        <img src="./imgs/logos/svg/logo.png" alt="Logo de WebTicketPro">
      </a>
    </div>

    <?php
    if (isset($_SESSION['usuario_id'], $_SESSION['nombre'], $_SESSION['apellidos'])) {
      echo 'Loguead@ como: ' . $_SESSION['nombre'] . ' ' . $_SESSION['apellidos'];
    } ?>
  </header>