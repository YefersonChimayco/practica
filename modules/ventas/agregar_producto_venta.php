<?php   
    include_once "../../include/funciones.php";
    session_start();
    if(isset($_POST['agregar'])){

        $producto = null; // Inicializamos la variable producto como null
    
        // Verificamos si se ingresó un código de producto
        if(isset($_POST['codigo']) && !empty($_POST['codigo'])) {
            $codigo = $_POST['codigo'];
            $producto = obtenerProductoPorId($codigo); // Busca por código
        }
    
        // Verificamos si se ingresó un nombre de producto
        if(isset($_POST['nombre']) && !empty($_POST['nombre'])) {
            $nombre = $_POST['nombre'];
            $producto = obtenerProductoPorNombre($nombre); // Busca por nombre
        }
    
        if($producto) {
            if(!$producto) {
                echo "
                <script type='text/javascript'>
                    window.location.href='vender.php'
                    alert('No se ha encontrado el producto')
                </script>";
                return;
            }
            
            print_r($producto);
            $_SESSION['lista'] = agregarProductoALista($producto, $_SESSION['lista']);
            unset($_POST['codigo']);
            unset($_POST['nombre']);
            header("location: vender.php");
        }
    }
    
?>

