<?php
    session_name('almacen');
    session_start();
    date_default_timezone_set('America/Monterrey');
    include('conexion.php');
    
    $link = Conectar();
    $err = 0;
    
    if(isset($_GET['opc']))
        $opcion = $_GET['opc'];
    else if(isset($_POST['opc']))
        $opcion = $_POST['opc'];

    if(!(isset($_SESSION['IdUsuario'])) || $_SESSION['IdUsuario'] == -1) {
        switch($opcion) {
            case 'login':
                $_SESSION['IdUsuario'] = -1;
                $_SESSION['NomUsuario'] = -1;
                $_SESSION['Usuario'] = -1;
    
                $usuario = utf8_decode($_POST['usuario']);
                $clave = utf8_decode($_POST['clave']);
    
                $stmt = $link->prepare('CALL proc_login(?,?);');
                $stmt->bind_param("ss", $usuario, $clave);
    
                if($stmt->execute()) {
                    $row = mysqli_fetch_array($stmt->get_result(), MYSQLI_ASSOC);
    
                    $_SESSION['IdUsuario'] = $row['id'];
                    $_SESSION['NomUsuario'] = utf8_encode($row['nomUsuario']);
                    $_SESSION['Usuario'] = utf8_encode($usuario);
                    
                    if(!isset($row['id']))
                        $err = 1;
                } else {
                    $err = 1;
                }
    
                $salida['err'] = $err;
                echo json_encode($salida);
    
                $stmt->close();
                break;
        }
    } else {
        switch($opcion) {
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
            
                $stmt = $link->prepare("SELECT e.id, e.Serie, e.Marca, e.Modelo, te.NomEquipo AS Tipo, u.nomUsuario AS Asignacion, e.Economico FROM equipos AS e INNER JOIN tipoequipo AS te ON e.Tipo = te.IdTipo INNER JOIN usuarios AS u ON e.Asignacion = u.idUsuario WHERE Modelo LIKE ? OR u.nomUsuario LIKE ?");
                $stmt->bind_param("ss", $cadenaBusqueda, $cadenaBusqueda);
            
                if($stmt->execute()) {
                    $rows = mysqli_fetch_all($stmt->get_result(), MYSQLI_ASSOC);

                    for($i = 0; $i < count($rows); $i++) {
                        $rows[$i]['Serie'] = utf8_encode($rows[$i]['Serie']);
                        $rows[$i]['Marca'] = utf8_encode($rows[$i]['Marca']);
                        $rows[$i]['Modelo'] = utf8_encode($rows[$i]['Modelo']);
                        $rows[$i]['Tipo'] = utf8_encode($rows[$i]['Tipo']);
                        $rows[$i]['Asignacion'] = utf8_encode($rows[$i]['Asignacion']);
                    }

                    $salida['res'] = $rows;
                } else {
                    $err = 1;
                }
            
                $salida['err'] = $err;
                echo json_encode($salida);
    
                $stmt->close();
                break;
            case 'obtenerTiposEquipos':
                $stmt = $link->prepare('SELECT * FROM tipoequipo');

                if($stmt->execute()) {
                    $rows = mysqli_fetch_all($stmt->get_result(), MYSQLI_ASSOC);

                    for($i = 0; $i < count($rows); $i++) {
                        $rows[$i]['NomEquipo'] = utf8_encode($rows[$i]['NomEquipo']);
                    }
                    $salida['res'] = $rows;
                } else {
                    $err = 1;
                }

                $salida['err'] = $err;

                echo json_encode($salida);

                $stmt->close();
                break;
            case 'obtenerUsuariosAsignacion':
                $stmt = $link->prepare('SELECT idUsuario, nomUsuario FROM usuarios');

                if($stmt->execute()) {
                    $rows = mysqli_fetch_all($stmt->get_result(), MYSQLI_ASSOC);

                    for($i = 0; $i < count($rows); $i++) {
                        $rows[$i]['nomUsuario'] = utf8_encode($rows[$i]['nomUsuario']);
                    }
                    $salida['res'] = $rows;
                } else {
                    $err = 1;
                }

                $salida['err'] = $err;

                echo json_encode($salida);

                $stmt->close();
                break;
            case 'altaEquipo':
                $serie = utf8_decode($_POST['serie']);
                $marca = utf8_decode($_POST['marca']);
                $modelo = utf8_decode($_POST['modelo']);
                $tipo = utf8_decode($_POST['tipo']);
                $asignacion = utf8_decode($_POST['asignacion']);
                $economico = utf8_decode($_POST['economico']);
                $imagen = '';

                if(isset($_FILES['imagen'])) {
                    $imagen = $_FILES['imagen']['name'];
                }

                $stmt = $link->prepare('CALL proc_altaEquipo(?,?,?,?,?,?,?);');
                $query = "CALL proc_altaEquipo('$serie','$marca','$modelo','$tipo','$asignacion','$economico','$imagen');";
                $stmt->bind_param('sssssss', $serie, $marca, $modelo, $tipo, $asignacion, $economico, $imagen);

                if($stmt->execute()) {
                    $row = mysqli_fetch_array($stmt->get_result(), MYSQLI_ASSOC);

                    if(isset($_FILES['imagen'])) {
                        $dir = '../imagenes/';
                        $fileSubido = $dir . basename($_FILES['imagen']['name']);
                        if(move_uploaded_file($_FILES['imagen']['tmp_name'], $fileSubido)) {
                            $imagen = $_FILES['imagen']['name'];
                        }
                    }

                    $idEquipo = $row['id'];

                    $salida['res'] = $row;

                    $idTipoMovimiento = 1;
                    $idUsuario = $_SESSION['IdUsuario'];

                    $stmt->close();

                    $stmt = $link->prepare('CALL proc_nuevoMovimientoEquipo(?,?,?,?);');
                    $stmt->bind_param('iiis', $idUsuario, $idEquipo, $idTipoMovimiento, $query);

                    $stmt->execute();

                } else {
                    $err = 1;
                    echo $link->error;
                }

                $salida['err'] = $err;

                echo json_encode($salida);

                $stmt->close();
                break;
            case 'eliminarEquipo':
                $idEquipo = $_GET['idEquipo'];

                $stmt = $link->prepare("CALL proc_eliminarEquipo(?);");
                $query = "CALL proc_eliminarEquipo($idEquipo);";
                $stmt->bind_param("i", $idEquipo);

                if($stmt->execute()) {
                    $stmt->close();

                    $idTipoMovimiento = 2;
                    $idUsuario = $_SESSION['IdUsuario'];

                    $stmt = $link->prepare('CALL proc_nuevoMovimientoEquipo(?,?,?,?);');
                    $stmt->bind_param('iiis', $idUsuario, $idEquipo, $idTipoMovimiento, $query);

                    $stmt->execute();
                } else {
                    $err = 1;
                }

                $salida['err'] = $err;

                echo json_encode($salida);

                $stmt->close();
                break;
            case 'obtenerEquipos':
                $stmt = $link->prepare("SELECT e.id, e.Serie, e.Marca, e.Modelo, te.NomEquipo AS Tipo, u.nomUsuario AS Asignacion, e.Economico FROM equipos AS e INNER JOIN tipoequipo AS te ON e.Tipo = te.IdTipo INNER JOIN usuarios AS u ON e.Asignacion = u.idUsuario");
            
                if($stmt->execute()) {
                    $rows = mysqli_fetch_all($stmt->get_result(), MYSQLI_ASSOC);

                    for($i = 0; $i < count($rows); $i++) {
                        $rows[$i]['Serie'] = utf8_encode($rows[$i]['Serie']);
                        $rows[$i]['Marca'] = utf8_encode($rows[$i]['Marca']);
                        $rows[$i]['Modelo'] = utf8_encode($rows[$i]['Modelo']);
                        $rows[$i]['Tipo'] = utf8_encode($rows[$i]['Tipo']);
                        $rows[$i]['Asignacion'] = utf8_encode($rows[$i]['Asignacion']);
                    }

                    $salida['res'] = $rows;
                } else {
                    $err = 1;
                }
            
                $salida['err'] = $err;
                echo json_encode($salida);
    
                $stmt->close();
                break;
            case 'obtenerHistorial':
                $idEquipo = $_GET['idEquipo'];

                $stmt = $link->prepare('CALL proc_obtenerDatosEquipo(?);');
                $stmt->bind_param('i', $idEquipo);

                if($stmt->execute()) {
                    $row = mysqli_fetch_array($stmt->get_result(), MYSQLI_ASSOC);
                    
                    $row['Serie'] = utf8_encode($row['Serie']);
                    $row['Marca'] = utf8_encode($row['Marca']);
                    $row['Modelo'] = utf8_encode($row['Modelo']);
                    $row['Tipo'] = utf8_encode($row['Tipo']);
                    $row['Asignacion'] = utf8_encode($row['Asignacion']);
                    $row['Economico'] = utf8_encode($row['Economico']);
                    $row['Imagen'] = utf8_encode($row['Imagen']);

                    $salida['equipo'] = $row;

                    $stmt->close();

                    $stmt = $link->prepare('CALL proc_obtenerHistorial(?);');
                    $stmt->bind_param('i', $idEquipo);

                    if($stmt->execute()) {
                        $rows = mysqli_fetch_all($stmt->get_result(), MYSQLI_ASSOC);

                        for($i = 0; $i < count($rows); $i++) {
                            $rows[$i]['nomUsuario'] = utf8_encode($rows[$i]['nomUsuario']);
                            $rows[$i]['tipoMovimiento'] = utf8_encode($rows[$i]['tipoMovimiento']);
                            $rows[$i]['Serie'] = utf8_encode($rows[$i]['Serie']);
                        }

                        $salida['movimientos'] = $rows;
                    } else {
                        $err = 1;
                        echo $link->error;
                    }

                } else {
                    $err = 1;
                    echo $link->error;
                }

                $salida['err'] = $err;

                echo json_encode($salida);

                $stmt->close();
                break;
            case 'agregarAditamento':
                $idAsignacion = $_GET['idAsignacion'];
                $TipoAditamento = utf8_decode($_GET['TipoAditamento']);
                $Tipo = utf8_decode($_GET['Tipo']);

                $stmt = $link->prepare('CALL proc_agregarAditamento(?,?,?);');
                $stmt->bind_param('iss', $idAsignacion, $TipoAditamento, $Tipo);

                if(!$stmt->execute()) {
                    $err = 1;
                }

                $salida['err'] = $err;

                echo json_encode($salida);

                $stmt->close();
                break;
        }
    }

    $link->close();
?>