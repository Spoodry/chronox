<?php
    include 'conexion.php';
    $link = Conectar();
    $stmt = $link->prepare('SELECT * FROM usuario');

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
</head>
<body>
    <form method="GET" action="plantilla.php">
        <label>Asignación: </label>
        <select name="asignacion">
        <?php
            for($i = 0; $i < count($rows); $i++) {
                $id = $rows[$i]['IdUsuario'];
                $nombre = $rows[$i]['NomUsuario'];
                echo "<option value='$id'>$nombre</option>";
            }
        ?>
        </select> <br><br>
        <input type="submit" value="Generar PDF">
    </form>
</body>
</html>