<?php
include_once "../include/funciones.php";
session_start();
if(empty($_SESSION['usuario'])) header("location:auth/login.php");
$cartas = [
    ["titulo" => "<a href='../modules/ventas/reporte_ventase.php'>Total ventas</a>","Total ventas", "icono" => "fa fa-money-bill", "total" => "$".obtenerTotalVentas(), "color" => "#A71D45"],
    ["titulo" => "<a href='../modules/ventas/reporte_ventase.php'>Total ventas Hoy</a>","Ventas hoy", "icono" => "fa fa-calendar-day", "total" => "$".obtenerTotalVentasHoy(), "color" => "#2A8D22"],
    ["titulo" => "<a href='../modules/ventas/reporte_ventase.php'>Total ventas en la Semana</a>","Ventas semana", "icono" => "fa fa-calendar-week", "total" => "$".obtenerTotalVentasSemana(), "color" => "#223D8D"],
    ["titulo" => "<a href='../modules/ventas/reporte_ventase.php'>Total ventas de este Mes</a>","Ventas mes", "icono" => "fa fa-calendar-alt", "total" => "$".obtenerTotalVentasMes(), "color" => "#D55929"],
];

$totales = [
	["nombre" => "<a href='../modules/productos/productose.php'>Total productos</a>","Total productos", "total" => obtenerNumeroProductos(), "imagen" => "../img/producosss.jpg"],
	["nombre" => "<a href='../modules/ventas/reporte_ventase.php'>Ventas registradas</a>","Ventas registradas", "total" => obtenerNumeroVentas(), "imagen" => "img/ventasss.png"],
	["nombre" => "<a href='../modules/usuarios/usuariose.php'>Usuarios Registrados</a>","Usuarios registrados", "total" => obtenerNumeroUsuarios(), "imagen" => "img/usuariosss.png"],
	["nombre" => "<a href='../modules/clientes/clientese.php'>Clientes registrados</a>","Clientes registrados", "total" => obtenerNumeroClientes(), "imagen" => "img/clientesss.jpg"],
];

$ventasUsuarios = obtenerVentasPorUsuario();
$ventasClientes = obtenerVentasPorCliente();
$productosMasVendidos = obtenerProductosMasVendidos();

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
      <a href="../modules/usuarios/perfil.php" class="btn btn-primary me-2">
        <i class="fa fa-user-circle"></i> Perfil de vendedor
      </a>
      <a href="../auth/cerrar_sesion.php" class="btn btn-danger">
        <i class="fa fa-sign-out-alt"></i> Cerrar Sesión
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
          <a class="nav-link active" href="../modules/productos/productose.php">
            <i class="fa fa-box-open"></i> Productos
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="../modules/usuarios/usuariose.php">
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
          <a class="nav-link active" href="../modules/ventas/reporte_ventase.php">
            <i class="fa fa-chart-line"></i> Reporte ventas
          </a>
        </li>
      </ul>

      <!-- Botones de perfil y salir alineados a la derecha -->
      
    </div>
  </div>
</nav>
<div class="container">
	<div  class="alert " role="alert">
		<h1 class="moving-text ">
			Hola, vendedor  <?= $_SESSION['usuario']?>
			<i class="fa fa-running running-icon"></i> 
		</h1>
	</div>
	<div class="card-deck row mb-2">
	<?php foreach($totales as $total){?>
		<div class="col-xs-12 col-sm-6 col-md-3" >
			<div class="card text-center">
				<div class="card-body">
					<img class="img-thumbnail" src="<?= $total['imagen']?>" alt="">
					<h4 class="card-title" >
						<?= $total['nombre']?>
					</h4>
					<h2><?= $total['total']?></h2>

				</div>

			</div>
		</div>
		<?php }?>
	</div>

	 <div class="card-deck row">
		<?php foreach($cartas as $carta){?>
		<div class="col-xs-12 col-sm-6 col-md-3" style="color: <?=  $carta['color']?> !important">
			<div class="card text-center">
				<div class="card-body">
					<h4 class="card-title" >
						<i class="fa <?= $carta['icono']?>"></i>
						<?= $carta['titulo']?>
					</h4>
					<h2><?= $carta['total']?></h2>

				</div>

			</div>
		</div>
		<?php }?>
	</div>
	 <div class="row mt-2">
	 	
	 	<div class="col">
			<div class="card">
				<div class="card-body">
					<h4>Ventas por clientes</h4>
					<table class="table">
						<thead>
							<tr>
								<th>Nombre cliente</th>
								<th>Número compras</th>
								<th>Total ventas</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($ventasClientes as $cliente) {?>
								<tr>
									<td><?= $cliente->cliente?></td>
									<td><?= $cliente->numeroCompras?></td>
									<td>$<?= $cliente->total?></td>
								</tr>
							<?php }?>
						</tbody>
					</table>
				</div>
			</div>
	 	</div>
	 </div>
	 <div class="col">
			<div class="card">
				<div class="card-body">
					<h4>Ventas por usuarios</h4>
					<table class="table">
						<thead>
							<tr>
								<th>Nombre usuario</th>
								<th>Número ventas</th>
								<th>Total ventas</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($ventasUsuarios as $usuario) {?>
								<tr>
									<td><?= $usuario->usuario?></td>
									<td><?= $usuario->numeroVentas?></td>
									<td>$<?= $usuario->total?></td>
								</tr>
							<?php }?>
						</tbody>
					</table>
				</div>
			</div>	 		
	 	</div>
	 <h4>10 Productos más vendidos</h4>
	 <table class="table">
	 	<thead>
	 		<tr>
	 			<th>Producto</th>
	 			<th>Unidades vendidas</th>
	 			<th>Total</th>
	 		</tr>
	 	</thead>
	 	<tbody>
	 		<?php foreach($productosMasVendidos as $producto) {?>
	 		<tr>
	 			<td><?= $producto->nombre?></td>
	 			<td><?= $producto->unidades?></td>
	 			<td>$<?= $producto->total?></td>
	 		</tr>
	 		<?php }?>
	 	</tbody>
	 </table>
</div>	
<footer class="footer text-white pt-5 pb-4">
    <div class="footer-overlay"></div>
    <div class="container">
        <div class="row">
            <!-- Logo y descripción -->
            <div class="col-md-4 mb-4">
                <h5 class="text-uppercase fw-bold">Inventario ARIEL</h5>
                <p>Simplifica la gestión de tu inventario con nuestra plataforma innovadora y eficiente.</p>
                <img src="img/logotipo.png" alt="InventarioPro Logo" class="img-fluid">
            </div>
            <!-- Enlaces útiles -->
            <div class="col-md-4 mb-4">
                <h5 class="text-uppercase fw-bold">REFERENCIAS</h5>
                <ul class="list-unstyled">
                    <li><a href="#" class="text-white text-decoration-none">Inicio</a></li>
                    <li><a href="#" class="text-white text-decoration-none">Productos</a></li>
                    <li><a href="#" class="text-white text-decoration-none">Servicios</a></li>
                    <li><a href="#" class="text-white text-decoration-none">Contacto</a></li>
                </ul>
            </div>
            <!-- Redes Sociales -->
            <div class="col-md-4 mb-4">
                <h5 class="text-uppercase fw-bold">SOCIAL</h5>
                <p>Conéctate con nosotros en redes sociales para recibir las últimas actualizaciones.</p>
                <div class="social-icons">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
        </div>
        <hr class="bg-secondary">
        <div class="text-center">
            <p class="mb-0">&copy; 2024 InventarioPro. Todos los derechos reservados.</p>
        </div>
    </div>
</footer>
</html>