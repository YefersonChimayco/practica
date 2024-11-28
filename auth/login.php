<?php
include_once "../include/encabezado.php";
?>
<div class="container">
    <div class="row m-5  no-gutters shadow-lg justify-content-center">
        <!-- Columna con imagen y formulario apilados -->
        <div class="col-12 col-md-4 bg-white p-4">
            <!-- Imagen -->
            <div class="text-center mb-4">
                <img src="../img/logotipo.png" class="img-fluid" style="width: 50%;" />
            </div>

            <!-- Formulario -->
            <h3 class="pb-3 text-center">Iniciar sesión</h3>
            <form action="iniciar_sesion.php" method="post">
                <div class="form-group pb-3">
                    <h5>Usuario</h5>
                    <input type="text" placeholder=" Ingresa tu Usuario" class="form-control" name="usuario" id="exampleInputEmail1" aria-describedby="emailHelp">
                </div>
                <div class="form-group pb-3">
                <h5>Contraseña</h5>
                    <input type="password" placeholder="Ingresa tuContraseña" class="form-control" name="password" id="exampleInputPassword1">
                </div>

                <div class="pb-2">
                    <button type="submit" name="ingresar" class="btn btn-primary w-100 font-weight-bold mt-2">Ingresar</button>
                </div>
                <div class="pb-2">
                    <a href="https://web.whatsapp.com/" name="" class="">Si olvidó la contraseña comuniquese con el Administrador (932534222)</a>
                </div>
            </form>
        </div>
    </div>
</div>
