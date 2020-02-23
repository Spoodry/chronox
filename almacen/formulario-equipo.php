<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Alta Equipo | Almacen</title>

    <link rel="icon" href="../img/core-img/favicon.ico">

    <link rel="stylesheet" href="../css/core-style.css">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <?php
        include('topbar.php');
    ?>
    <div class="container">
        <form method="POST" enctype="multipart/form-data" id="formAltaEquipo">
            <input type="hidden" name="opc" value="altaEquipo">
            <label class="h6 font-weight-light">Serie</label>
            <input type = "text" class="form-control mb-2" name = "serie">
            <label class="h6 font-weight-light">Marca</label>
            <input type = "text" class="form-control mb-2" name = "marca">
            <label class="h6 font-weight-light">Modelo</label>
            <input type = "text" class="form-control mb-2" name = "modelo">
            <label class="h6 font-weight-light">Tipo</label>
            <select name = "tipo" id="slTipoEquipo" class="form-control mb-2">
            </select>
            <label class="h6 font-weight-light">Asignación</label>
            <select name="asignacion" id="slAsign" class="form-control mb-2">
            </select>
            <label class="h6 font-weight-light">Económico</label>
            <input type = "text" class="form-control mb-2" name = "economico">
            <label class="h6 font-weight-light">Imagen</label>
            <input type="file" class="form-control-file mb-4" name="imagen">
            <div class="text-center mb-5">
                <input type="submit" class="btn btn-primary">
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
    <script src="https://kit.fontawesome.com/34336e5f41.js" crossorigin="anonymous"></script>

    <script src="js/funciones.js"></script>
    
    <script src="js/recibir-datos.js"></script>

    <script src="js/envio-datos.js"></script>

    <script>
        activar('nvItemFormEq');

        obtenerTiposEquipo();
        obtenerUsuariosAsignacion();
    </script>

</body>
</html>