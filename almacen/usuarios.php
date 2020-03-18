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
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Informe | Almacen</title>

    <link rel="icon" href="../img/core-img/favicon.ico">

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
        <h1 class="h2 font-weight-light mb-4">Usuarios</h1>

        <div class="table-responsive mb-4">
            <table class="table" id="tUsuarios" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Usuario</th>
                        <th>Tipo de Usuario</th>
                        <th style="text-align: center;">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tbodyUsuarios">
                    <td colspan="5" class="text-center">
                        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </td>
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

    <script src="js/sweetalert2/sweetalert2.all.min.js"></script>

    <script src="js/datatables/jquery.dataTables.min.js"></script>
    <script src="js/datatables/dataTables.bootstrap4.min.js"></script>

    <script src="js/funciones.js"></script>
    <script src="js/recibir-datos.js"></script>

    <script>
        activar('nvItemInform');
        
        obtenerUsuarios();
    </script>

</body>
</html>