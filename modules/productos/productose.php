<?php
include_once "../../include/encabezado.php";
include_once "../../include/funciones.php";

session_start();

if(empty($_SESSION['usuario'])) header("location: login.php");
$nombreProducto = (isset($_POST['nombreProducto'])) ? $_POST['nombreProducto'] : null;

$productos = obtenerProductos($nombreProducto);

$cartas = [
    ["titulo" => "No. Productos", "icono" => "fa fa-box", "total" => count($productos), "color" => "#3578FE"],
    ["titulo" => "Total productos", "icono" => "fa fa-shopping-cart", "total" => obtenerNumeroProductos(), "color" => "#4F7DAF"],
    ["titulo" => "Total inventario", "icono" => "fa fa-money-bill", "total" => "$". obtenerTotalInventario(), "color" => "#1FB824"],
    ["titulo" => "Ganancia", "icono" => "fa fa-wallet", "total" => "$". calcularGananciaProductos(), "color" => "#D55929"],
];
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-secundary mb-2  ">
  <div class="container-fluid">
    <!-- Logotipo (opcional) y botón de menú en dispositivos móviles -->
    <a class="navbar-brand" href="#">
      <img src="../../img/logotipo.png" alt="Logotipo" width="150" height="75" class="d-inline-block align-text-top">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <!-- Contenedor colapsable con la lista centrada -->
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <!-- Menú de navegación centrado -->
      <ul class="navbar-nav mx-auto">
        <li class="nav-item">
          <a class="nav-link active" href="../../empleado/index.php">
            <i class="fa fa-house-user"></i> Inicio
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="../../modules/productos/productose.php">
            <i class="fa fa-box-open"></i> Productos
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="../../modules/usuarios/usuariose.php">
            <i class="fa fa-user-tie"></i> Usuarios
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="../../modules/clientes/clientese.php">
            <i class="fa fa-address-book"></i> Clientes
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="../../modules/ventas/vendere.php">
            <i class="fa fa-hand-holding-usd"></i> Vender
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="../../modules/ventas/reporte_ventase.php">
            <i class="fa fa-chart-line"></i> Reporte ventas
          </a>
        </li>
      </ul>

      <!-- Botones de perfil y salir alineados a la derecha -->
      
    </div>
  </div>
</nav>


<div class="container mt-3">
    <h1>
        <a class="btn btn-success btn-lg" href="agregar_producto.php">
            <i class="fa fa-plus"></i>
            Agregar
        </a>
        Productos
    </h1>
    <?php include_once "../ventas/cartas_totales.php"; ?>

    <form action="" method="post" class="input-group mb-3 mt-3">
        <input autofocus name="nombreProducto" type="text" class="form-control" placeholder="Escribe el nombre o código del producto que deseas buscar" aria-label="Nombre producto" aria-describedby="button-addon2">
        <button type="submit" name="buscarProducto" class="btn btn-primary" id="button-addon2">
            <i class="fa fa-search"></i>
            Buscar
        </button>
    </form>
    <table class="table">
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Precio compra</th>
                <th>Precio venta</th>
                <th>Ganancia</th>
                <th>Existencia</th>
               
            </tr>
        </thead>
        <tbody>
            <?php
            foreach($productos as $producto){
            ?>
                <tr>
                    <td><?= $producto->codigo; ?></td>
                    <td><?= $producto->nombre; ?></td>
                    <td><?= '$'.$producto->compra; ?></td>
                    <td><?= '$'.$producto->venta; ?></td>
                    <td><?= '$'. floatval($producto->venta - $producto->compra); ?></td>
                    <td><?= $producto->existencia; ?></td>
                   
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php 
include_once "../../include/footer.php";
?>