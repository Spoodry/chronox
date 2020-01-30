<?php
    include('conexion.php');
    
    $link = Conectar();
    $err = 0;
    
    $opcion = $_GET['opc'];

    switch($opcion) {
        case 'login':
            
            break;
        case 'busqueda':
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
        
            $stmt = $link->prepare("SELECT e.Serie, e.Marca, e.Modelo, te.NomEquipo AS Tipo, e.Asignacion, e.Economico FROM equipo AS e INNER JOIN tipoequipo AS te ON e.Tipo = te.IdTipo WHERE Modelo LIKE ?");
            $stmt->bind_param("s", $cadenaBusqueda);
        
            if($stmt->execute()) {
                $rows = mysqli_fetch_all($stmt->get_result(), MYSQLI_ASSOC);
                $salida['res'] = $rows;
            } else {
                $err = 1;
            }
        
            $salida['err'] = $err;
            echo json_encode($salida);

            $stmt->close();
            break;
    }
    $link->close();
?>