<?php
    session_name('almacen');
    session_start();
    date_default_timezone_set('America/Monterrey');

    if(!isset($_SESSION['IdUsuario']) || $_SESSION['IdUsuario'] == -1) {
        header('location: login.php');
    }

    include 'files/conexion.php';
    $link = Conectar();
    $stmt = $link->prepare('SELECT * FROM usuarios');

    if($stmt->execute()) {
        $rows = mysqli_fetch_all($stmt->get_result(), MYSQLI_ASSOC);
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

    <link rel="stylesheet" href="../css/core-style.css">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <?php
        include('topbar.php');
    ?>
    <div class="container">
        <h1 class="h2 font-weight-light mb-2">Usuarios</h1>

        <div class="table-responsive mb-4">
            <table class="table" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Usuario</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
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
        activar('nvItemInform');
    </script>

</body>
</html>