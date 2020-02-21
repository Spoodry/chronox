<?php
    include 'files/conexion.php';
    $link = Conectar();

    $stmt = $link->prepare("SELECT * FROM equipos");

    if($stmt->execute()) {
        $rows = mysqli_fetch_all($stmt->get_result(), MYSQLI_ASSOC);
    }

    $stmt->close();

    if(isset($_GET['idEquipo'])) {
        $idEquipo = $_GET['idEquipo'];

        $estatus = 0;

        $stmt = $link->prepare("CALL proc_eliminarEquipo(?)");
        $stmt->bind_param("i", $idEquipo);

        if($stmt->execute()) {
            $stmt->close();

            echo "<a href=\"entrada-inventario.php?idEquipo=$idEquipo\" target=\"_blank\">PDF Salida</a>";
        } else {
            echo $link->error;
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Baja Equipo | Almacen</title>

    <link rel="icon" href="../img/core-img/favicon.ico">

    <link rel="stylesheet" href="../css/core-style.css">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <?php
        include('topbar.php');
    ?>    

    <div class="container">
        <form method="GET" action="">
            <div class="form-group">
                <label>Equipos</label>
                <select class="form-control" name="idEquipo">
                <?php
                    for($i = 0; $i < count($rows); $i++) {
                        $id = $rows[$i]['id'];
                        $Serie = $rows[$i]['Serie'];   
                        $Marca = $rows[$i]['Marca'];
                        $Modelo = $rows[$i]['Modelo'];

                        echo "<option value='$id'>$Serie $Marca $Modelo</option>";
                    }
                ?>
                </select>
            </div>
            <div class="text-center">
                <input type="submit" class="btn btn-danger" value="Dar de Baja">
            </div>
        </form>
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
        activar('nvItemBajaEq');
    </script>
</body>
</html>