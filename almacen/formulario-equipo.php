<?php
    include('conexion.php');

    if(isset($_GET['serie']))
    {
        $serie = $_GET['serie'];
    }
    if(isset($_GET['marca']))
    {
        $marca = $_GET['marca'];
    }
    if(isset($_GET['modelo']))
    {
        $modelo = $_GET['modelo'];
    }
    if(isset($_GET['tipo']))
    {
        $tipo = $_GET['tipo'];
    }
    if(isset($_GET['asignacion']))
    {
        $asignacion = $_GET['asignacion'];
    }
    if(isset($_GET['economico']))
    {
        $economico = $_GET['economico'];
    }

    $database = Conectar();
    $stmt = $database->prepare("insert into equipo(Serie,Marca,Modelo,Tipo,Asignacion,Economico) values(?,?,?,?,?,?)");
    $stmt->bind_param("ssssss",$serie,$marca,$modelo,$tipo,$asignacion,$economico);
    $stmt->execute();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form>
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
        <input type = "submit">
    </form>
</body>
</html>