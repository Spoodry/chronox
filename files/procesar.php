<?php
    include('conexion.php');

    $err = 0;
    $link = Conectar();

    if(isset($_GET['opc'])) {
        $opcion = $_GET['opc'];

        switch ($opcion) {
            case 'obtenerProductos':
                $stmt = $link->prepare('CALL p_obtenerProductos()');

                if($stmt->execute()) {
                    $rows = mysqli_fetch_all($stmt->get_result(), MYSQLI_ASSOC);

                    foreach($rows as $row) {
                        $row['producto'] = utf8_encode($row['producto']);
                        $row['marca'] = utf8_encode($row['marca']);
                        $row['nombreImagen'] = utf8_encode($row['nombreImagen']);
                    }
                } else {
                    $err = 1;
                    
                    $salida['err'] = $err;
                    echo json_encode($salida);
                }

                if($err == 0) {
                    $stmt->close();
                    $link->close();

                    $salida['err'] = $err;
                    $salida['res'] = $rows;

                    echo json_encode($salida);
                }
                break;
            case 'cantProductos':
                $stmt = $link->prepare('CALL p_cantProductos()');

                if($stmt->execute()) {
                    $row = mysqli_fetch_array($stmt->get_result(), MYSQLI_ASSOC);
                } else {
                    $err = 1;

                    $salida['err'] = $err;
                    echo json_encode($salida);
                }

                if($err == 0) {
                    $salida['err'] = $err;
                    $salida['res'] = $row;

                    echo json_encode($salida);
                }

                $stmt->close();
                $link->close();

                break;
            case 'obtenerProductosXMarca':
                $idMarca = $_GET['idMarca'];

                $stmt = $link->prepare('CALL p_obtenerProductosXMarca(?)');
                $stmt->bind_param("i", $idMarca);

                if($stmt->execute()) {
                    $rows = mysqli_fetch_all($stmt->get_result(), MYSQLI_ASSOC);

                    foreach($rows as $row) {
                        $row['producto'] = utf8_encode($row['producto']);
                        $row['marca'] = utf8_encode($row['marca']);
                        $row['nombreImagen'] = utf8_encode($row['nombreImagen']);
                    }
                } else {
                    $err = 1;
                    
                    $salida['err'] = $err;
                    echo json_encode($salida);
                }

                if($err == 0) {
                    $salida['err'] = $err;
                    $salida['res'] = $rows;

                    echo json_encode($salida);
                }

                $stmt->close();
                $link->close();
                break;
            case 'cantProductosXMarca':
                $idMarca = $_GET['idMarca'];

                $stmt = $link->prepare('CALL p_cantProductosXMarca(?)');
                $stmt->bind_param('i', $idMarca);

                if($stmt->execute()) {
                    $row = mysqli_fetch_array($stmt->get_result(), MYSQLI_ASSOC);
                } else {
                    $err = 1;

                    $salida['err'] = $err;
                    echo json_encode($salida);
                }

                if($err == 0) {
                    $salida['err'] = $err;
                    $salida['res'] = $row;

                    echo json_encode($salida);
                }

                $stmt->close();
                $link->close();
                break;
            default:
                # code...
                break;
        }
    } else {
        $err = 1;
        $salida['err'] = $err;

        echo json_encode($salida);
    }

?>