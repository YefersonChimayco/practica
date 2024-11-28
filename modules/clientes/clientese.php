<?php
include_once "../../include/encabezado.php";
include_once "../../include/funciones.php";
session_start();

if(empty($_SESSION['usuario'])) header("location: login.php");

$clientes = obtenerClientes();
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
<div class="container">
    <h1>
        <a class="btn btn-success btn-lg" href="agregar_cliente.php">
            <i class="fa fa-plus"></i>
            Agregar
        </a>
        Clientes
    </h1>
    <table class="table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                
            </tr>
        </thead>
        <tbody>
            <?php
            foreach($clientes as $cliente){
            ?>
                <tr>
                    <td><?php echo $cliente->nombre; ?></td>
                    <td><?php echo $cliente->telefono; ?></td>
                    <td><?php echo $cliente->direccion; ?></td>
                   
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>