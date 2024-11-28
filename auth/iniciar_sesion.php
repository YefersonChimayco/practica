<?php
include_once "../include/funciones.php";

if(isset($_POST['ingresar'])){
    if(empty($_POST['usuario']) || empty($_POST['password'])){
        echo'
        <div class="alert alert-warning mt-3" role="alert">
            Debes completar todos los datos.
            <a href="login.php">Regresar</a>
        </div>';
        return;
    }



    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    session_start();

    $datosSesion = iniciarSesion($usuario, $password);

    if(!$datosSesion){
        echo'
        <div class="alert alert-danger mt-3" role="alert">
            Nombre de usuario y/o contraseña incorrectas.
            <a href="login.php">Regresar</a>
        </div>';
        return;
    }

    // Almacenar datos en la sesión
    $_SESSION['usuario'] = $datosSesion->usuario;
    $_SESSION['idUsuario'] = $datosSesion->id;
    $_SESSION['rol'] = $datosSesion->rol;

    // Redirigir según el rol
    if($datosSesion->rol == 1){
        header("location: ../empleado/index.php");
    } elseif($datosSesion->rol == 2){
        header("location: ../index.php");
    } else {
        // Si el rol no es reconocido
        echo'
        <div class="alert alert-warning mt-3" role="alert">
            Rol no reconocido.
            <a href="login.php">Regresar</a>
        </div>';
        return;
    }
}
?>
