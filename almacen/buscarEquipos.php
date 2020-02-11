<?php
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
    
    <title>Document</title>

    <link rel="stylesheet" href="../css/core-style.css">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <?php
        include('topbar.php');
    ?>
    <div class="container">
        <form method="GET" action="plantilla.php">
            <div class="form-group">
                <label>Asignación</label>
                <select class="form-control" name="asignacion">
                <?php
                    for($i = 0; $i < count($rows); $i++) {
                        $id = $rows[$i]['idUsuario'];
                        $nombre = $rows[$i]['nomUsuario'];
                        echo "<option value='$id'>$nombre</option>";
                    }
                ?>
                </select>
            </div>
            <div class="text-center">
                <input type="submit" class="btn btn-primary" value="Generar PDF">
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
        activar('nvItemInform');
    </script>

</body>
</html>