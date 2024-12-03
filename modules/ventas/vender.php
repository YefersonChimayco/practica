<?php
include_once "../../include/encabezado.php";
include_once "../../include/navbar.php";
include_once "../../include/funciones.php";
session_start();
if(empty($_SESSION['usuario'])) header("location: ../../auth/login.php");
$_SESSION['lista'] = (isset($_SESSION['lista'])) ? $_SESSION['lista'] : [];
$total = calcularTotalLista($_SESSION['lista']);
$clientes = obtenerClientes();
$clienteSeleccionado = (isset($_SESSION['clienteVenta'])) ? obtenerClientePorId($_SESSION['clienteVenta']) : null;

// Lógica para actualizar la cantidad
if (isset($_POST['actualizarCantidad'])) {
    $idProducto = $_POST['idProducto'];
    $nuevaCantidad = $_POST['cantidad'];

    // Verifica que la cantidad sea mayor que 0
    if ($nuevaCantidad > 0) {
        // Actualiza la cantidad del producto en la lista de la sesión
        foreach ($_SESSION['lista'] as &$producto) {
            if ($producto->id == $idProducto) {
                $producto->cantidad = $nuevaCantidad;
                break;
            }
        }
    }
}
?>

<div class="container mt-3">
    <form action="agregar_producto_venta.php" method="post" class="row">
        <div class="col-5">
            <input class="form-control form-control-lg" name="codigo" autofocus id="codigo" type="text" placeholder="Código de barras del producto" aria-label="codigoBarras">
        </div>
        <div class="col-5">
            <input class="form-control form-control-lg" name="nombre" id="nombre" type="text" placeholder="Nombre del producto" aria-label="nombreProducto">
        </div>
        <div class="col">
            <input type="submit" value="Agregar" name="agregar" class="btn btn-success mt-2">
        </div>
    </form>

    <?php if ($_SESSION['lista']) { ?>
        <div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th>Quitar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['lista'] as $lista) { ?>
                        <tr>
                            <td><?php echo $lista->codigo; ?></td>
                            <td><?php echo $lista->nombre; ?></td>
                            <td>S/<?php echo $lista->venta; ?></td>
                            <td>
                                <!-- Formulario para actualizar la cantidad -->
                                <form action="vender.php" method="post" class="form-inline">
                                    <input type="number" name="cantidad" value="<?php echo $lista->cantidad; ?>" min="1" class="form-control" style="width: 60px;">
                                    <input type="hidden" name="idProducto" value="<?php echo $lista->id; ?>">
                                    <button type="submit" name="actualizarCantidad" class="btn btn-warning ml-2">Actualizar</button>
                                </form>
                            </td>
                            <td>S/<?php echo floatval($lista->cantidad * $lista->venta); ?></td>
                            <td>
                                <a href="quitar_producto_venta.php?id=<?php echo $lista->id ?>" class="btn btn-danger">
                                    <i class="fa fa-times"></i>
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <form class="row" method="post" action="establecer_cliente_venta.php">
                <div class="col-10">
                    <select class="form-select" aria-label="Default select example" name="idCliente">
                        <option selected value="">Cliente Por Defecto</option>
                        <?php foreach ($clientes as $cliente) { ?>
                            <option value="<?php echo $cliente->id ?>"><?php echo $cliente->nombre ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-auto">
                    <input class="btn btn-info" type="submit" value="Seleccionar cliente">
                    </input>
                </div>
            </form>

            <?php if ($clienteSeleccionado) { ?>
                <div class="alert alert-primary mt-3" role="alert">
                    <b>Cliente seleccionado: </b>
                    <br>
                    <b>Nombre: </b> <?php echo $clienteSeleccionado->nombre ?><br>
                    <b>Teléfono: </b> <?php echo $clienteSeleccionado->telefono ?><br>
                    <b>Dirección: </b> <?php echo $clienteSeleccionado->direccion ?><br>
                    <a href="quitar_cliente_venta.php" class="btn btn-warning">Quitar</a>
                </div>
            <?php } ?>

            <div class="text-center mt-3">
                <h1>Total: S/<?php echo $total; ?></h1>
                <a class="btn btn-primary btn-lg" href="registrar_venta.php">
                    <i class="fa fa-check"></i>
                    Terminar venta
                </a>
                <a class="btn btn-danger btn-lg" href="cancelar_venta.php">
                    <i class="fa fa-times"></i>
                    Cancelar
                </a>
            </div>
        </div>
    <?php } ?>
</div>
