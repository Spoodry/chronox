<?php
    session_name('almacen');
    session_start();
    date_default_timezone_set('America/Monterrey');

    if(!isset($_SESSION['IdUsuario']) || $_SESSION['IdUsuario'] == -1) {
        header('location: login.php');
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Almacen</title>

    <!-- Core Style CSS -->
    <link rel="stylesheet" href="../css/core-style.css">
    <link rel="stylesheet" href="../style.css">

</head>
<body>
        <nav class="navbar fixed-top navbar-expand-md mb-4 navbar-dark bg-danger">
            <a class="navbar-brand font-weight-light text-white">Almacen</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                  <li class="nav-item active">
                    <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                  </li>
                  <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Dropdown
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                      <a class="dropdown-item" href="#">Action</a>
                      <a class="dropdown-item" href="#">Another action</a>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link disabled" href="#">Disabled</a>
                  </li>
                </ul>
                <form class="form-inline my-2 my-lg-0">
                  <button class="btn btn-outline-light my-2 my-sm-0" type="submit" onclick="window.location='login.php';">Cerrar Sesión</button>
                </form>
            </div>
        </nav>
        <div class="container">
            <div class="form-group">
                <input type="text" class="form-control" id="txtCadena">
            </div>
            <div class="form-group d-flex justify-content-around">
                <div>
                    <input type="radio" name="rdTipo" id="rdInicio" value="Principio" checked>
                    <label class="font-weight-normal h5" for="rdInicio">Principio</label>
                </div>
                <div>
                    <input type="radio" name="rdTipo" id="rdMedio" value="Medio">
                    <label class="font-weight-normal h5" for="rdMedio">Medio</label>
                </div>
                <div>
                    <input type="radio" name="rdTipo" id="rdFin" value="Final">
                    <label class="font-weight-normal h5" for="rdFin">Final</label>
                </div>
            </div>
            <div class="form-group text-center">
                <input type="submit" class="btn bg-success text-white" onclick="buscar()">
            </div>
            <div class="table-responsive d-none" id="tablaCompleta">
                <table class="table" style="visibility: visible;">
                    <thead>
                        <tr>
                            <th>Serie</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Tipo</th>
                            <th>Asignación</th>
                            <th>Economico</th>
                        </tr>
                    </thead>
                    <tbody id="tableContenido">
                        <tr>
                            <td>Marca</td>
                        </tr>
                    </tbody>
                </table>
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

    <script src="js/funciones.js"></script>
</body>
</html>