<?php
session_start();
if(empty($_SESSION['usuario'])) header("location: login.php");


include_once "../include/funciones.php";
$idUsuario = $_SESSION['idUsuario'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome from CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="../styles.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="logo_principal.png">
    <title>Ventas-PHP</title>
</head>
<body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<nav class="navbar navbar-expand-lg navbar-dark bg-black">
  <div class="container-fluid">
    <!-- Botones de perfil y salir alineados a la derecha -->
    <div class="d-flex ms-auto">
      <a href="modules/usuarios/perfil.php" class="btn btn-primary me-2">
        <i class="fa fa-user-circle"></i> Perfil
      </a>
      <a href="./auth/cerrar_sesion.php" class="btn btn-danger">
        <i class="fa fa-sign-out-alt"></i> Salir
      </a>
    </div>
  </div>
</nav>
<nav class="navbar navbar-expand-lg navbar-dark bg-secundary mb-2  ">
  <div class="container-fluid">
    <!-- Logotipo (opcional) y botón de menú en dispositivos móviles -->
    <a class="navbar-brand" href="#">
      <img src="../img/logotipo.png" alt="Logotipo" width="150" height="75" class="d-inline-block align-text-top">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <!-- Contenedor colapsable con la lista centrada -->
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <!-- Menú de navegación centrado -->
      <ul class="navbar-nav mx-auto">
        <li class="nav-item">
          <a class="nav-link active" href="index.php">
            <i class="fa fa-house-user"></i> Inicio
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="../modules/productos/productos.php">
            <i class="fa fa-box-open"></i> Productos
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="../modules/usuarios/usuarios.php">
            <i class="fa fa-user-tie"></i> Usuarios
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="../modules/clientes/clientes.php">
            <i class="fa fa-address-book"></i> Clientes
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="../modules/ventas/vender.php">
            <i class="fa fa-hand-holding-usd"></i> Vender
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="../modules/ventas/reporte_ventas.php">
            <i class="fa fa-chart-line"></i> Reporte ventas
          </a>
        </li>
      </ul>

      <!-- Botones de perfil y salir alineados a la derecha -->
      
    </div>
  </div>
</nav>
<div class="container">
	<h1>Cambiar contraseña</h1>
	<form action="" method="post">
		<div class="mb-3">
            <label for="actual" class="form-label">Contraseña actual</label>
            <input type="password" name="actual" class="form-control" id="actual" placeholder="Escribe tu contraseña actual">
        </div>

        <div class="mb-3">
            <label for="nueva" class="form-label">Contraseña nueva</label>
            <input type="password" name="nueva" class="form-control" id="nueva" placeholder="Escribe tu contraseña nueva">
        </div>

        <div class="mb-3">
            <label for="repite" class="form-label">Repite la nueva contraseña</label>
            <input type="password" name="repite" class="form-control" id="repite" placeholder="Escribe la contraseña nueva de nuevo">
        </div>

        <div class="text-center">
        	<input type="submit" name="cambiar" class=" btn btn-primary btn-lg" value="Cambiar contraseña">
        	<a href="../modules/usuarios/perfil.php" class="btn btn-danger btn-lg">
        		Cancelar
        	</a>
            <a href="../modules/usuarios/perfil.php" class="btn btn-success btn-lg">
                <i class="fa fa-sign-out-alt"></i> 
                volver a mi perfil
            </a>
        </div>
	</form>
</div>
<?php
if(isset($_POST['cambiar'])){
	if(empty($_POST['actual']) || empty($_POST['nueva']) || empty($_POST['repite'])){
		echo'
        <div class="alert alert-danger mt-3" role="alert">
            Debes completar todos los datos.
        </div>';
        return;
	}

	$actual = $_POST['actual'];
	$nueva = $_POST['nueva'];
	$repite = $_POST['repite'];

    if(strlen($nueva) < 8){
        echo'
        <div class="alert alert-danger mt-3" role="alert">
            La contraseña nueva debe tener al menos 8 caracteres.
        </div>';
        return;
    }

	if($nueva !== $repite) {
		echo'
        <div class="alert alert-danger mt-3" role="alert">
            La contraseña repetida debe coincidir con la nueva.
        </div>';
        return;
	}

	$passwordVerificada = verificarPassword($idUsuario, $actual);
	if(!$passwordVerificada){
		echo'
        <div class="alert alert-danger mt-3" role="alert">
            La contraseña actual es incorrecta.
        </div>';
        return;
	}

    $resultado = cambiarPassword($idUsuario, $repite);
    if($resultado){
        echo'
        <div class="alert alert-success mt-3" role="alert">
            Contraseña actualizada.
        </div>';
        return;
    }

    
}
?>