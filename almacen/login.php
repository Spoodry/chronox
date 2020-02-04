<?php
  session_name('almacen');
  session_start();
  date_default_timezone_set('America/Monterrey');
  session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login | Spood</title>

    <link rel="stylesheet" href="../css/core-style.css">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container w-50">
        <div class="card">
            <div class="card-body">
                <h1 class="text-center font-weight-light text-secondary">Iniciar Sesión</h1>
                <form id="formLogin" autocomplete="off">
                    <input type="text" class="form-control mb-3" name="usuario" placeholder="Usuario">
                    <input type="password" class="form-control mb-3" name="clave" placeholder="Contraseña">
                </form>
                <div class="text-center">
                    <button class="btn btn-success" onclick="iniciarSesion()">Ingresar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery (Necessary for All JavaScript Plugins) -->
    <script src="../js/jquery/jquery-2.2.4.min.js"></script>
    <!-- Popper js -->
    <script src="../js/popper.min.js"></script>
    <!-- Bootstrap js -->
    <script src="../js/bootstrap.min.js"></script>
    <!-- Plugins js -->
    <script src="../js/plugins.js"></script>
    <!-- Classy Nav js -->
    <script src="../js/classy-nav.min.js"></script>

    <script src="js/login.js"></script>
</body>
</html>