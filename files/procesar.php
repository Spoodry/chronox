<?php
    session_name('chronox');
    session_start();
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
                    
                    for($i = 0; $i < count($rows); $i++) {
                        $rows[$i]['producto'] = utf8_encode($rows[$i]['producto']);
                        $rows[$i]['marca'] = utf8_encode($rows[$i]['marca']);
                        $rows[$i]['nombreImagen'] = utf8_encode($rows[$i]['nombreImagen']);
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

                    for($i = 0; $i < count($rows); $i++) {
                        $rows[$i]['producto'] = utf8_encode($rows[$i]['producto']);
                        $rows[$i]['marca'] = utf8_encode($rows[$i]['marca']);
                        $rows[$i]['nombreImagen'] = utf8_encode($rows[$i]['nombreImagen']);
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
            case 'obtenerProductosXTipoPublico':
                $idTipoPublico = $_GET['idTipoPublico'];

                $stmt = $link->prepare('CALL p_obtenerProductosXTipoPublico(?)');
                $stmt->bind_param("i", $idTipoPublico);

                if($stmt->execute()) {
                    $rows = mysqli_fetch_all($stmt->get_result(), MYSQLI_ASSOC);
                    
                    for($i = 0; $i < count($rows); $i++) {
                        $rows[$i]['producto'] = utf8_encode($rows[$i]['producto']);
                        $rows[$i]['marca'] = utf8_encode($rows[$i]['marca']);
                        $rows[$i]['tipoPublico'] = utf8_encode($rows[$i]['tipoPublico']);
                        $rows[$i]['nombreImagen'] = utf8_encode($rows[$i]['nombreImagen']);
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
            case 'cantProductosXTipoPublico':
                $idTipoPublico = $_GET['idTipoPublico'];
                $stmt = $link->prepare('CALL p_cantProductosXTipoPublico(?)');
                $stmt->bind_param('i', $idTipoPublico);

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
            case 'crearUsuarioTemp':
                $nombreUsuario = $_GET['usuario'];

                $_SESSION['IdUsuario'] = -1;

                $stmt = $link->prepare('CALL p_crearUsuarioTemp(?)');
                $stmt->bind_param('s',$nombreUsuario);

                if(!$stmt->execute()) {
                    $err = 1;

                    $salida['err'] = $err;
                    echo json_encode($salida);
                } else {
                    $row = mysqli_fetch_array($stmt->get_result(), MYSQLI_ASSOC);
                    $_SESSION['IdUsuario'] = $row['id'];
                }

                if($err == 0) {
                    $salida['err'] = $err;
                    $salida['res'] = $row;

                    echo json_encode($salida);
                }

                $stmt->close();
                $link->close();
                break;
            case 'addToCarrito':
                $idProducto = $_GET['idProducto'];
                $idUsuario = $_GET['idUsuario'];

                $stmt = $link->prepare('CALL p_agregarACarrito(?,?)');
                $stmt->bind_param('ii', $idProducto, $idUsuario);

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
            case 'obtenerProductosEnCarrito':
                $idCarrito = $_GET['idCarrito'];

                $stmt = $link->prepare('CALL p_obtenerProductosEnCarrito(?)');
                $stmt->bind_param('i', $idCarrito);

                if($stmt->execute()) {
                    $rows = mysqli_fetch_all($stmt->get_result(), MYSQLI_ASSOC);

                    for($i = 0; $i < count($rows); $i++) {
                        $rows[$i]['nombre'] = utf8_encode($rows[$i]['nombre']);
                        $rows[$i]['marca'] = utf8_encode($rows[$i]['marca']);
                        $rows[$i]['color'] = utf8_encode($rows[$i]['color']);
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
            case 'obtenerIdCarrito':
                $idUsuario = $_GET['idUsuario'];

                $stmt = $link->prepare('CALL p_obtenerIdCarrito(?)');
                $stmt->bind_param('i',$idUsuario);

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
            case 'cantProductosCarrito':
                $idCarrito = $_GET['idCarrito'];

                $stmt = $link->prepare('CALL p_cantProductosCarrito(?)');
                $stmt->bind_param('i',$idCarrito);

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