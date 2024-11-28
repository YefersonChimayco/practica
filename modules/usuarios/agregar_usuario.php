<?php
include_once "../../include/encabezado.php";
include_once "../../include/navbar.php";
include_once "../../include/funciones.php";
session_start();

if(empty($_SESSION['usuario'])) header("location: login.php");

?>
<div class="container">
    <h3>Agregar usuario</h3>
    <form method="post">
        <div class="mb-3">
            <label for="usuario" class="form-label">Nombre de usuario</label>
            <input type="text" name="usuario" class="form-control" id="usuario" placeholder="Escribe el nombre de usuario">
        </div>
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre completo</label>
            <input type="text" name="nombre" class="form-control" id="nombre" placeholder="Escribe el nombre completo del usuario">
        </div>
        <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="text" name="telefono" class="form-control" id="telefono" placeholder="Escribe el telefono">
        </div>
        <div class="mb-3">
            <label for="direccion" class="form-label">Dirección</label>
            <input type="text" name="direccion" class="form-control" id="direccion" placeholder="Ecribe la direccion">
        </div>
        <div class="mb-3">
            <label for="rol" class="form-label">Tu ROL</label>
            <input type="text" name="rol" class="form-control" id="rol" placeholder="1= empleado 2 = admin">
        </div>

        <div class="text-center mt-3">
            <input type="submit" name="registrar" value="Registrar" class="btn btn-primary btn-lg">
            
            </input>
            <a href="usuarios.php" class="btn btn-danger btn-lg">
                <i class="fa fa-times"></i> 
                Cancelar
            </a>
            <a href="usuarios.php" class="btn btn-dark btn-lg">
                
                ver Usuarios
            </a>
        </div>
    </form>
</div>
<?php
if (isset($_POST['registrar'])) {
    $usuario = $_POST['usuario'];
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $rol = isset($_POST['rol']) ? (int)$_POST['rol'] : 1; // Rol predeterminado como empleado (1)

    if (empty($usuario) || empty($nombre) || empty($telefono) || empty($direccion) || empty($rol)) {
        echo '
        <div class="alert alert-danger mt-3" role="alert">
            Debes completar todos los datos.
        </div>';
        return;
    }

    include_once "../../include/funciones.php";
    $resultado = registrarUsuario($usuario, $nombre, $telefono, $direccion, $rol); // Ahora incluye el rol
    if ($resultado) {
        echo '
        <div class="alert alert-success mt-3" role="alert">
            Usuario registrado con éxito.
        </div>';
    } else {
        echo '
        <div class="alert alert-danger mt-3" role="alert">
            Ocurrió un error al registrar el usuario. Verifica los datos e inténtalo nuevamente.
        </div>';
    }
}

 
include_once "../../include/footer.php";

?>