<?php
    require 'vendor/autoload.php';

    use PhpOffice\PhpSpreadsheet\IOFactory;
    use PhpOffice\PhpSpreadsheet\PhpSpreadsheet;

    include('files/conexion.php');

    $tabla = $_GET['tabla'];
    
    $link = Conectar();

    $query = '';
    switch($tabla) {
        case 0:
            $query = 'SELECT * FROM equipos;';
            break;
        case 1:
            $query = 'SELECT * FROM aditamentos;';
            break;
        case 2:
            $query = 'SELECT * FROM usuarios;';
            break;
        case 3:
            $query = 'SELECT * FROM movimientosEquipos;';
            break;
    }

    $stmt = $link->prepare($query);

    if($stmt->execute()) {
        $rowsDatos = mysqli_fetch_all($stmt->get_re);
    }

?>