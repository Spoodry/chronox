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

    <link rel="icon" href="../img/core-img/favicon.ico">

    <!-- Core Style CSS -->
    <link rel="stylesheet" href="../css/core-style.css">
    <link rel="stylesheet" href="../style.css">

</head>
<body>
        <?php
            include('topbar.php');
        ?>
        <div class="container">
            <h1 class="h2 font-weight-light mb-4">Inventario</h1>
            <div class="form-group row">
                <div class="col-xl-7 col-7">
                    <input type="text" class="form-control" id="txtCadena">
                </div>
                <div class="col-xl-3 col-5">
                    <select class="form-control" id="rdTipo">
                        <option value="Principio">Principio</option>
                        <option value="Medio">Medio</option>
                        <option value="Final">Final</option>
                    </select>
                </div>
                
                <div class="col-xl-2 mt-xl-0 mt-3">
                    <div class="text-xl-left text-center">
                        <button class="btn btn-primary btn-icon-split" onclick="buscar()">
                            <span class="icon text-white-50">
                                <i class="fa fa-search"></i>
                            </span>
                            <span class="text">Buscar</span>
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="table-responsive" id="tablaCompleta">
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
    <script src="https://kit.fontawesome.com/34336e5f41.js" crossorigin="anonymous"></script>

    <script src="js/funciones.js"></script>

    <script>
        activar('nvItemInv');
        buscar();
    </script>

</body>
</html>