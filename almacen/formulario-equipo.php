<?php
    include('files/conexion.php');

    if(isset($_POST['serie']))
    {
        $serie = $_POST['serie'];
    }
    if(isset($_POST['marca']))
    {
        $marca = $_POST['marca'];
    }
    if(isset($_POST['modelo']))
    {
        $modelo = $_POST['modelo'];
    }
    if(isset($_POST['tipo']))
    {
        $tipo = $_POST['tipo'];
    }
    if(isset($_POST['asignacion']))
    {
        $asignacion = $_POST['asignacion'];
    }
    if(isset($_POST['economico']))
    {
        $economico = $_POST['economico'];
    }

    if(isset($_FILES['imagen'])) {
        $dirSubida = 'imagenes/';
        $fileSubido = $dirSubida . basename($_FILES['imagen']['name']);
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $fileSubido)) {
            $imagen = $_FILES['imagen']['name'];
        }
    }

    $database = Conectar();
    $stmt = $database->prepare("insert into equipos(Serie,Marca,Modelo,Tipo,Asignacion,Economico,Imagen) values(?,?,?,?,?,?,?)");
    $stmt->bind_param("sssssss",$serie,$marca,$modelo,$tipo,$asignacion,$economico,$imagen);
    if($stmt->execute()) {
        $stmt->close();
        $stmt = $database->prepare("SELECT id FROM equipos ORDER BY id DESC LIMIT 1;");
        $stmt->execute();
        $row = mysqli_fetch_array($stmt->get_result(), MYSQLI_ASSOC);

        $stmt->close();

        $idEquipo = $row['id'];

        echo "<a href=\"entrada-inventario.php?idEquipo=$idEquipo\" target=\"_blank\">PDF Entrada</a>";
    }

?>
<!DOCTYPE html>
<html lang="en">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
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
        <form method="POST" enctype="multipart/form-data">
            serie:<br>
            <input type = "text" name = "serie"><br><br>
            marca:<br>
            <input type = "text" name = "marca"><br><br>
            modelo:<br>
            <input type = "text" name = "modelo"><br><br>
            tipo:<br>
            <select name = "tipo">
                <option></option>
                <option value="0001">Escritorio</option>
                <option value="0002">Laptop</option>
                <option value="0003">Portatil</option>
                <option value="0004">Fax</option>
                <option value="0005">Telefono</option>
                <option value="0006">Impresora</option>
                <option value="0007">Escaner</option>
                <option value="0008">Microfono</option>
                <option value="0009">Bocina</option>
                <option value="0010">Tablet</option>
                <option value="0011">Punto de Acceso</option>
                <option value="0012">Router</option>
                <option value="0013">Switch</option>
                <option value="0015">Camara</option>
                <option value="0016">Modem</option>
                <option value="0017">Smartphone</option>
                <option value="0018">Radio</option>
                <option value="0019">SmartWatch</option>
                <option value="0020">Smart Tv</option>
                <option value="0021">Consola de Videojuegos</option>
            </select><br><br>
            asignacion:<br>
            <input type = "text" name = "asignacion"><br><br>
            economico:<br>
            <input type = "text" name = "economico"><br><br>
            <input type="file" name="imagen"><br><br>
            <input type = "submit">
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
        activar('nvItemFormEq');
    </script>

</body>
</html>