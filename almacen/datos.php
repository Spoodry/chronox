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

    <title>Datos | Almacen</title>

    <link rel="icon" href="../img/core-img/favicon.ico">

    <!-- Core Style CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <link rel="stylesheet" href="js/datatables/dataTables.bootstrap4.min.css">

    <style>
        .table td {
            vertical-align: middle;
        }
    </style>

</head>
<body>
    <?php
        include('topbar.php');
    ?>
    <div class="container">
        <div class="row">
            <div class="col">
                <button class="btn btn-primary" id="btnEquipos">
                    <span class="icon text-white-50">
                        <i class="fa fa-download"></i>
                    </span>
                    <span class="text">Equipos</span>
                </button>
            </div>
            <div class="col">
                <button class="btn btn-info" id="btnAditamentos">
                    <span class="icon text-white-50">
                        <i class="fa fa-download"></i>
                    </span>
                    <span class="text">Aditamentos</span>
                </button>
            </div>
            <div class="col">
                <button class="btn btn-danger" id="btnUsuarios">
                    <span class="icon text-white-50">
                        <i class="fa fa-download"></i>
                    </span>
                    <span class="text">Usuarios</span>
                </button>
            </div>
            <div class="col">
                <button class="btn btn-warning" id="btnMovEquipos">
                    <span class="icon text-black-50">
                        <i class="fa fa-download"></i>
                    </span>
                    <span class="text">Movimientos</span>
                </button>
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
    <script src="https://kit.fontawesome.com/34336e5f41.js" crossorigin="anonymous"></script>

    <script src="js/sweetalert2/sweetalert2.all.min.js"></script>

    <script src="js/datatables/jquery.dataTables.min.js"></script>
    <script src="js/datatables/dataTables.bootstrap4.min.js"></script>

    <script src="js/funciones.js"></script>

    <script src="js/recibir-datos.js"></script>

    <script src="js/envio-datos.js"></script>

    <script>
        activar('nvItemDatos');

        $("#btnMovEquipos").click(function() {
            var url = "CSV.php?tabla=3";

            window.open(url, "_blank");
        });

        $("#btnUsuarios").click(function() {
            var url = "CSV.php?tabla=2";

            window.open(url, "_blank");
        });

        $("#btnAditamentos").click(function() {
            var url = "CSV.php?tabla=1";

            window.open(url, "_blank");
        });

        $("#btnEquipos").click(function() {
            var url = "CSV.php?tabla=0";

            window.open(url, "_blank");
        });
    </script>

</body>
</html>