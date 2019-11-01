<?php
    include('conexion.php');
    $link = Conectar();

    $err = 0;
    $cadena = $_GET['cadena'];
    $tipo = $_GET['tipo'];

    $cadenaBusqueda = "";
    switch($tipo) {
        case 'Principio':
            $cadenaBusqueda = $cadena . "%";
            break;
        case 'Medio':
            $cadenaBusqueda = "%" . $cadena . "%";
            break;
        case 'Final':
            $cadenaBusqueda = "%" . $cadena;
            break;
    }

    $stmt = $link->prepare("SELECT * FROM equipo WHERE Modelo LIKE ?");
    $stmt->bind_param("s", $cadenaBusqueda);

    if($stmt->execute()) {
        $rows = mysqli_fetch_all($stmt->get_result(), MYSQLI_ASSOC);
    } else {
        $err = 1;
    }

    $stmt->close();
    $link->close();

    $salida['err'] = $err;
    $salida['res'] = $rows;
    
    echo json_encode($salida);
?>