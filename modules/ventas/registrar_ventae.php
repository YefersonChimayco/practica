<?php
include_once "../../include/funciones.php";


session_start();
$productos = $_SESSION['lista'];
$idUsuario = $_SESSION['idUsuario'];
$total = calcularTotalLista($productos);
$idCliente = $_SESSION['clienteVenta'];

if(count($productos) === 0) {
    header("location: modules/ventas/vendere.php");
    return;
};
$resultado = registrarVenta($productos, $idUsuario, $idCliente, $total);

if(!$resultado) {
    echo "Error al registrar la venta";
    return;
}

$_SESSION['lista'] = [];
$_SESSION['clienteVenta'] = "";

echo "
<script type='text/javascript'>
    window.location.href='vendere.php'
    alert('Venta realizada con éxito')
</script>";
//header("location: vendere.php");

?>