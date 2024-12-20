<?php

define("PASSWORD_PREDETERMINADA", "catalino");
define("HOY", date("Y-m-d"));

function iniciarSesion($usuario, $password) {
    // Incluimos el campo "rol" en el SELECT
    $sentencia = "SELECT id, usuario, rol FROM usuarios WHERE usuario = ?";
    $resultado = select($sentencia, [$usuario]);

    if ($resultado) {
        $usuario = $resultado[0];
        
        // Verificar la contraseña
        $verificaPass = verificarPassword($usuario->id, $password);
        
        // Retornar el objeto usuario si la contraseña es correcta
        if ($verificaPass) return $usuario;
    }

    // Retornar false si no se encuentra el usuario o contraseña no coincide
    return false;
}


function verificarPassword($idUsuario, $password){
    $sentencia = "SELECT password FROM usuarios WHERE id = ?";
    $contrasenia = select($sentencia, [$idUsuario])[0]->password;
    $verifica = password_verify($password, $contrasenia);
    if($verifica) return true;
}

function cambiarPassword($idUsuario, $password){
    $nueva = password_hash($password, PASSWORD_DEFAULT);
    $sentencia = "UPDATE usuarios SET password = ? WHERE id = ?";
    return editar($sentencia, [$nueva, $idUsuario]);
}

function eliminarUsuario($id){
    $sentencia = "DELETE FROM usuarios WHERE id = ?";
    return eliminar($sentencia, $id);
}

function editarUsuario($usuario, $nombre, $telefono, $direccion, $id){
    $sentencia = "UPDATE usuarios SET usuario = ?, nombre = ?, telefono = ?, direccion = ? WHERE id = ?";
    $parametros = [$usuario, $nombre, $telefono, $direccion, $id];
    return editar($sentencia, $parametros);
}

function obtenerUsuarioPorId($id){
    $sentencia = "SELECT id, usuario, nombre, telefono, direccion FROM usuarios WHERE id = ?";
    return select($sentencia, [$id])[0];
}

function obtenerUsuarios(){
    $sentencia = "SELECT id, usuario, nombre, telefono, direccion FROM usuarios";
    return select($sentencia);
}

function registrarUsuario($usuario, $nombre, $telefono, $direccion, $rol) {
    $password = password_hash(PASSWORD_PREDETERMINADA, PASSWORD_DEFAULT); // Contraseña predeterminada encriptada
    $sentencia = "INSERT INTO usuarios (usuario, nombre, telefono, direccion, password, rol) VALUES (?,?,?,?,?,?)";
    $parametros = [$usuario, $nombre, $telefono, $direccion, $password, $rol];
    return insertar($sentencia, $parametros);
}



function eliminarCliente($id){
    $sentencia = "DELETE FROM clientes WHERE id = ?";
    return eliminar($sentencia, $id);
}

function editarCliente($nombre, $telefono, $direccion, $id){
    $sentencia = "UPDATE clientes SET nombre = ?, telefono = ?, direccion = ? WHERE id = ?";
    $parametros = [$nombre, $telefono, $direccion, $id];
    return editar($sentencia, $parametros);
}

function obtenerClientePorId($id){
    $sentencia = "SELECT * FROM clientes WHERE id = ?";
    $cliente = select($sentencia, [$id]);
    if($cliente) return $cliente[0];
}

function obtenerClientes(){
    $sentencia = "SELECT * FROM clientes";
    return select($sentencia);
}

function registrarCliente($nombre, $telefono, $direccion){
    $sentencia = "INSERT INTO clientes (nombre, telefono, direccion) VALUES (?,?,?)";
    $parametros = [$nombre, $telefono, $direccion];
    return insertar($sentencia, $parametros);
}

function obtenerNumeroVentas(){
    $sentencia = "SELECT IFNULL(COUNT(*),0) AS total FROM ventas";
    return select($sentencia)[0]->total;
}

function obtenerNumeroUsuarios(){
    $sentencia = "SELECT IFNULL(COUNT(*),0) AS total FROM usuarios";
    return select($sentencia)[0]->total;
}

function obtenerNumeroClientes(){
    $sentencia = "SELECT IFNULL(COUNT(*),0) AS total FROM clientes";
    return select($sentencia)[0]->total;
}


function obtenerVentasPorUsuario(){
    $sentencia = "SELECT SUM(ventas.total) AS total, usuarios.usuario, COUNT(*) AS numeroVentas 
    FROM ventas
    INNER JOIN usuarios ON usuarios.id = ventas.idUsuario
    GROUP BY ventas.idUsuario
    ORDER BY total DESC";
    return select($sentencia);
}

function obtenerVentasPorCliente(){
    $sentencia = "SELECT SUM(ventas.total) AS total, IFNULL(clientes.nombre, 'MOSTRADOR') AS cliente,
    COUNT(*) AS numeroCompras
    FROM ventas
    LEFT JOIN clientes ON clientes.id = ventas.idCliente
    GROUP BY ventas.idCliente
    ORDER BY total DESC";
    return select($sentencia);
}

function obtenerProductosMasVendidos(){
    $sentencia = "SELECT SUM(productos_ventas.cantidad * productos_ventas.precio) AS total, SUM(productos_ventas.cantidad) AS unidades,
    productos.nombre FROM productos_ventas INNER JOIN productos ON productos.id = productos_ventas.idProducto
    GROUP BY productos_ventas.idProducto
    ORDER BY total DESC
    LIMIT 10";
    return select($sentencia);
}

function obtenerTotalVentas($idUsuario = null){
    $parametros = [];
    $sentencia = "SELECT IFNULL(SUM(total),0) AS total FROM ventas";
    if(isset($idUsuario)){
        $sentencia .= " WHERE idUsuario = ?";
        array_push($parametros, $idUsuario);
    }
    $fila = select($sentencia, $parametros);
    if($fila) return $fila[0]->total;
}

function obtenerTotalVentasHoy($idUsuario = null){
    $parametros = [];
    $sentencia = "SELECT IFNULL(SUM(total),0) AS total FROM ventas WHERE DATE(fecha) = CURDATE() ";
    if(isset($idUsuario)){
        $sentencia .= " AND idUsuario = ?";
        array_push($parametros, $idUsuario);
    }
    $fila = select($sentencia, $parametros);
    if($fila) return $fila[0]->total;
}

function obtenerTotalVentasSemana($idUsuario = null){
    $parametros = [];
    $sentencia = "SELECT IFNULL(SUM(total),0) AS total FROM ventas  WHERE WEEK(fecha) = WEEK(NOW())";
    if(isset($idUsuario)){
        $sentencia .= " AND  idUsuario = ?";
        array_push($parametros, $idUsuario);
    }
    $fila = select($sentencia, $parametros);
    if($fila) return $fila[0]->total;
}

function obtenerTotalVentasMes($idUsuario = null){
    $parametros = [];
    $sentencia = "SELECT IFNULL(SUM(total),0) AS total FROM ventas  WHERE MONTH(fecha) = MONTH(CURRENT_DATE()) AND YEAR(fecha) = YEAR(CURRENT_DATE())";
    if(isset($idUsuario)){
        $sentencia .= " AND  idUsuario = ?";
        array_push($parametros, $idUsuario);
    }
    $fila = select($sentencia, $parametros);
    if($fila) return $fila[0]->total;
}

function calcularTotalVentas($ventas){
    $total = 0;
    foreach ($ventas as $venta) {
        $total += $venta->total;
    }
    return $total;
}

function calcularProductosVendidos($ventas){
    $total = 0;
    foreach ($ventas as $venta) {
        foreach ($venta->productos as $producto) {
            $total += $producto->cantidad;
        }
    }
    return $total;
}

function obtenerGananciaVentas($ventas){
    $total = 0;
    foreach ($ventas as $venta) {
        foreach ($venta->productos as $producto) {
            $total += $producto->cantidad * ($producto->precio - $producto->compra);
        }
    }
    return $total;
}

function obtenerVentas($fechaInicio, $fechaFin, $cliente, $usuario){
    $parametros = [];
    $sentencia  = "SELECT ventas.*, usuarios.usuario, IFNULL(clientes.nombre, 'MOSTRADOR') AS cliente
    FROM ventas 
    INNER JOIN usuarios ON usuarios.id = ventas.idUsuario
    LEFT JOIN clientes ON clientes.id = ventas.idCliente";

    if(isset($usuario)){
        $sentencia .= " WHERE ventas.idUsuario = ?";
        array_push($parametros, $usuario);
        $ventas = select($sentencia, $parametros);
        return agregarProductosVendidos($ventas);
    }

    if(isset($cliente)){
        $sentencia .= " WHERE ventas.idCliente = ?";
        array_push($parametros, $cliente);
        $ventas = select($sentencia, $parametros);
        return agregarProductosVendidos($ventas);
    }

    if(empty($fechaInicio) && empty($fechaFin)){
        $sentencia .= " WHERE DATE(ventas.fecha) = ? ";
        array_push($parametros, HOY);
        $ventas = select($sentencia, $parametros);
        return agregarProductosVendidos($ventas);
    }

    if(isset($fechaInicio) && isset($fechaFin)){
        $sentencia .= " WHERE DATE(ventas.fecha) >= ? AND DATE(ventas.fecha) <= ?";
        array_push($parametros, $fechaInicio, $fechaFin);
    }

    $ventas = select($sentencia, $parametros);
   
    return agregarProductosVendidos($ventas);
}

function agregarProductosVendidos($ventas){
    foreach($ventas as $venta){
        $venta->productos = obtenerProductosVendidos($venta->id);
    }
    return $ventas;
}

function obtenerProductosVendidos($idVenta){
    $sentencia = "SELECT productos_ventas.cantidad, productos_ventas.precio, productos.nombre,
    productos.compra
    FROM productos_ventas
    INNER JOIN productos ON productos.id = productos_ventas.idProducto
    WHERE idVenta  = ? ";
    return select($sentencia, [$idVenta]);
}

function registrarVenta($productos, $idUsuario, $idCliente, $total){
    $sentencia =  "INSERT INTO ventas (fecha, total, idUsuario, idCliente) VALUES (?,?,?,?)";
    $parametros = [date("Y-m-d H:i:s"), $total, $idUsuario, $idCliente];

    $resultadoVenta = insertar($sentencia, $parametros);
    if($resultadoVenta){
        $idVenta = obtenerUltimoIdVenta();
        $productosRegistrados = registrarProductosVenta($productos, $idVenta);
        return $resultadoVenta && $productosRegistrados;
    }
}

function registrarProductosVenta($productos, $idVenta){
    $sentencia = "INSERT INTO productos_ventas (cantidad, precio, idProducto, idVenta) VALUES (?,?,?,?)";
    foreach ($productos as $producto ) {
        $parametros = [$producto->cantidad, $producto->venta, $producto->id, $idVenta];
        insertar($sentencia, $parametros);
        descontarProductos($producto->id, $producto->cantidad);
    }
    return true;
}

function descontarProductos($idProducto, $cantidad){
    $sentencia =  "UPDATE productos SET existencia  = existencia - ? WHERE id = ?";
    $parametros = [$cantidad, $idProducto];
    return editar($sentencia, $parametros);
}

function obtenerUltimoIdVenta(){
    $sentencia  = "SELECT id FROM ventas ORDER BY id DESC LIMIT 1";
    return select($sentencia)[0]->id;
}

function calcularTotalLista($lista){
    $total = 0;
    foreach($lista as $producto){
        $total += floatval($producto->venta * $producto->cantidad);
    }
    return $total;
}

function agregarProductoALista($producto, $listaProductos){
    // Si la existencia es menor que 1, no agregar el producto a la lista
    if($producto->existencia < 1) return $listaProductos;
    
    // Asignar la cantidad inicial a 1
    $producto->cantidad = 1;
    
    // Verificar si el producto ya está en la lista por su ID o nombre
    $existe = verificarSiEstaEnLista($producto->id, $listaProductos);

    if(!$existe){
        // Si el producto no está en la lista, agregarlo
        array_push($listaProductos, $producto);
    } else {
        // Si el producto ya está en la lista, verificar la existencia de la cantidad
        $existenciaAlcanzada = verificarExistencia($producto->id, $listaProductos, $producto->existencia);
        
        if($existenciaAlcanzada){
            // Si ya se alcanzó la existencia máxima, no agregar más
            return $listaProductos;
        }

        // Si no se ha alcanzado la existencia máxima, agregar cantidad
        $listaProductos = agregarCantidad($producto->id, $listaProductos);
    }
    
    return $listaProductos;
}


function verificarExistencia($productoIdentificador, $listaProductos, $existencia){
    foreach($listaProductos as $producto){
        // Compara tanto por ID como por nombre
        if($producto->id == $productoIdentificador || $producto->nombre == $productoIdentificador){
            // Si la cantidad ya alcanza la existencia máxima, retornamos true
            if($existencia <= $producto->cantidad) {
                return true;
            }
        }
    }
    return false; // Si no se encontró el producto o la cantidad no alcanza la existencia
}


function verificarSiEstaEnLista($productoIdentificador, $listaProductos){
    foreach($listaProductos as $producto){
        // Compara tanto por ID como por nombre
        if($producto->id == $productoIdentificador || $producto->nombre == $productoIdentificador){
            return true; // El producto ya está en la lista
        }
    }
    return false; // El producto no está en la lista
}


function agregarCantidad($idProducto, $listaProductos){
    foreach($listaProductos as $producto){
        if($producto->id == $idProducto){
            $producto->cantidad++;
        }
    }
    return $listaProductos;
}

function obtenerProductoPorCodigo($codigo){
    $sentencia = "SELECT * FROM productos WHERE codigo = ?";
    $producto = select($sentencia, [$codigo]);
    if($producto) return $producto[0];
    return [];
}

function obtenerNumeroProductos(){
    $sentencia = "SELECT IFNULL(SUM(existencia),0) AS total FROM productos";
    $fila = select($sentencia);
    if($fila) return $fila[0]->total;
}

function obtenerTotalInventario(){
    $sentencia = "SELECT IFNULL(SUM(existencia * venta),0) AS total FROM productos";
    $fila = select($sentencia);
    if($fila) return $fila[0]->total;
}

function calcularGananciaProductos(){
    $sentencia = "SELECT IFNULL(SUM(existencia*venta) - SUM(existencia*compra),0) AS total FROM productos";
    $fila = select($sentencia);
    if($fila) return $fila[0]->total;
}

function eliminarProducto($id){
    $sentencia = "DELETE FROM productos WHERE id = ?";
    return eliminar($sentencia, $id);
}

function editarProducto($codigo, $nombre, $compra, $venta, $existencia, $id){
    $sentencia = "UPDATE productos SET codigo = ?, nombre = ?, compra = ?, venta = ?, existencia = ? WHERE id = ?";
    $parametros = [$codigo, $nombre, $compra, $venta, $existencia, $id];
    return editar($sentencia, $parametros);
}

// Función para obtener un producto por su ID (código de barras)
function obtenerProductoPorId($codigo){
    $sentencia = "SELECT * FROM productos WHERE codigo = ?";
    $resultado = select($sentencia, [$codigo]);

    // Comprobar si hay resultados antes de acceder a la primera posición del array
    if (count($resultado) > 0) {
        return $resultado[0]; // Retorna el primer producto
    } else {
        return null; // Si no hay resultados, retorna null o maneja el caso como desees
    }
}


// Función para obtener un producto por su nombre
function obtenerProductoPorNombre($nombre){
    $sentencia = "SELECT * FROM productos WHERE nombre = ?";
    return select($sentencia, [$nombre])[0]; // Suponiendo que "select" es tu función de acceso a la base de datos
}



function obtenerProductos($busqueda = null){
    $parametros = [];
    $sentencia = "SELECT * FROM productos ";
    if(isset($busqueda)){
        $sentencia .= " WHERE nombre LIKE ? OR codigo LIKE ?";
        array_push($parametros, "%".$busqueda."%", "%".$busqueda."%"); 
    } 
    return select($sentencia, $parametros);
}

function registrarProducto($codigo, $nombre, $compra, $venta, $existencia) {
    // Verificar si el código ya existe en la base de datos
    $sentencia = "SELECT COUNT(*) AS count FROM productos WHERE codigo = ?";
    $resultado = select($sentencia, [$codigo]);

    // Si el código ya existe, mostrar un warning
    if ($resultado[0]->count > 0) {
        trigger_error("Error: El código del producto '$codigo' ya existe.", E_USER_WARNING);
        return false; // No insertamos el producto, pero continuamos el flujo
    }

    // Verificar si el nombre del producto ya existe en la base de datos
    $sentencia = "SELECT COUNT(*) AS count FROM productos WHERE nombre = ?";
    $resultado = select($sentencia, [$nombre]);

    // Si el nombre ya existe, mostrar un warning
    if ($resultado[0]->count > 0) {
        trigger_error("Error: El nombre del producto '$nombre' ya existe.", E_USER_WARNING);
        return false; // No insertamos el producto, pero continuamos el flujo
    }

    // Si el código y el nombre no existen, proceder con la inserción
    $sentencia = "INSERT INTO productos(codigo, nombre, compra, venta, existencia) VALUES (?,?,?,?,?)";
    $parametros = [$codigo, $nombre, $compra, $venta, $existencia];
    return insertar($sentencia, $parametros);
}



function select($sentencia, $parametros = []){
    $bd = conectarBaseDatos();
    $respuesta = $bd->prepare($sentencia);
    $respuesta->execute($parametros);
    return $respuesta->fetchAll();
}

function insertar($sentencia, $parametros ){
    $bd = conectarBaseDatos();
    $respuesta = $bd->prepare($sentencia);
    return $respuesta->execute($parametros);
}

function eliminar($sentencia, $id ){
    $bd = conectarBaseDatos();
    $respuesta = $bd->prepare($sentencia);
    return $respuesta->execute([$id]);
}

function editar($sentencia, $parametros ){
    $bd = conectarBaseDatos();
    $respuesta = $bd->prepare($sentencia);
    return $respuesta->execute($parametros);
}

function conectarBaseDatos() {
	$host = "localhost";
	$db   = "dpwebcom_inventario_practica";
	$user = "dpwebcom_yeferson";
	$pass = "=V1Sss?}Yx#+";
	$charset = 'utf8mb4';

	$options = [
	    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
	    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
	    \PDO::ATTR_EMULATE_PREPARES   => false,
	];
	$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
	try {
	     $pdo = new \PDO($dsn, $user, $pass, $options);
	     return $pdo;
	} catch (\PDOException $e) {
	     throw new \PDOException($e->getMessage(), (int)$e->getCode());
	}
}