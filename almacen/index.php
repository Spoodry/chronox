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
        <?php
            include('topbar.php');
        ?>
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

    <script>
        activar('nvItemInv');
    </script>

</body>
</html>