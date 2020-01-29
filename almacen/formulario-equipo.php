<?php
    include('conexion.php');

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
        $dirSubida = '/home/chronoxme/public_html/almacen/imagenes/';
        $fileSubido = $dirSubida . basename($_FILES['imagen']['name']);
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $fileSubido)) {
            $imagen = $_FILES['imagen']['name'];
        }
    }

    $database = Conectar();
    $stmt = $database->prepare("insert into equipo(Serie,Marca,Modelo,Tipo,Asignacion,Economico,Imagen) values(?,?,?,?,?,?,?)");
    $stmt->bind_param("sssssss",$serie,$marca,$modelo,$tipo,$asignacion,$economico,$imagen);
    $stmt->execute();

?>
<!DOCTYPE html>
<html lang="en">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
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
</body>
</html>