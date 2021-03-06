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
            
                $stmt = $link->prepare("SELECT e.id, e.Serie, e.Marca, e.Modelo, te.NomEquipo AS Tipo, u.nomUsuario AS Asignacion, e.Economico FROM equipos AS e INNER JOIN tipoequipo AS te ON e.Tipo = te.IdTipo INNER JOIN usuarios AS u ON e.Asignacion = u.idUsuario WHERE Modelo LIKE ? OR u.nomUsuario LIKE ? OR Marca LIKE ?");
                $stmt->bind_param("sss", $cadenaBusqueda, $cadenaBusqueda, $cadenaBusqueda);
            
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
                    $idAditamento = 0;

                    $stmt->close();

                    $stmt = $link->prepare('CALL proc_nuevoMovimientoEquipo(?,?,?,?,?);');
                    $stmt->bind_param('iiiis', $idUsuario, $idEquipo, $idAditamento, $idTipoMovimiento, $query);

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
                    $idAditamento = 0;

                    $stmt = $link->prepare('CALL proc_nuevoMovimientoEquipo(?,?,?,?,?);');
                    $stmt->bind_param('iiiis', $idUsuario, $idEquipo, $idAditamento, $idTipoMovimiento, $query);

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
                            $rows[$i]['Aditamento'] = utf8_encode($rows[$i]['Aditamento']);
                            $rows[$i]['descAditamento'] = utf8_encode($rows[$i]['descAditamento']);
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
                $query = "CALL proc_agregarAditamento($idAsignacion,'$TipoAditamento','$Tipo');";
                $stmt->bind_param('iss', $idAsignacion, $TipoAditamento, $Tipo);

                if($stmt->execute()) {
                    $row = mysqli_fetch_array($stmt->get_result(), MYSQLI_ASSOC);

                    $idTipoMovimiento = 4;
                    $idUsuario = $_SESSION['IdUsuario'];
                    $idAditamento = $row['id'];

                    $stmt->close();

                    $stmt = $link->prepare('CALL proc_nuevoMovimientoEquipo(?,?,?,?,?);');
                    $stmt->bind_param('iiiis', $idUsuario, $idAsignacion, $idAditamento, $idTipoMovimiento, $query);

                    $stmt->execute();

                } else {
                    $err = 1;
                }

                $salida['err'] = $err;

                echo json_encode($salida);

                $stmt->close();
                break;
            case 'obtenerTiposAditamentos':
                $stmt = $link->prepare('SELECT * FROM tipoaditamentos;');

                if($stmt->execute()) {
                    $rows = mysqli_fetch_all($stmt->get_result(), MYSQLI_ASSOC);

                    for($i = 0; $i < count($rows); $i++) {
                        $rows[$i]['Aditamento'] = utf8_encode($rows[$i]['Aditamento']);
                    }

                    $salida['res'] = $rows;
                } else {
                    $err = 1;
                }

                $salida['err'] = $err;

                echo json_encode($salida);

                $stmt->close();
                break;
            case 'obtenerUsuarios':
                $stmt = $link->prepare('SELECT u.id, idUsuario, idTipoUsuario, tu.descripcion AS tipoUsuario, correo, nomUsuario, usuario FROM usuarios AS u INNER JOIN tiposUsuarios AS tu ON u.idTipoUsuario = tu.id;');

                if($stmt->execute()) {
                    $rows = mysqli_fetch_all($stmt->get_result(), MYSQLI_ASSOC);
                    
                    for($i = 0; $i < count($rows); $i++) {
                        $rows[$i]['tipoUsuario'] = utf8_encode($rows[$i]['tipoUsuario']);
                        $rows[$i]['nomUsuario'] = utf8_encode($rows[$i]['nomUsuario']);
                        $rows[$i]['usuario'] = utf8_encode($rows[$i]['usuario']);
                    }

                    $salida['res'] = $rows;
                } else {
                    $err = 1;
                }

                $salida['err'] = $err;

                echo json_encode($salida);

                $stmt->close();
                break;
            case 'enviarCorreoAsignaciones':
                $idUsuario = utf8_decode($_GET['idUsuario']);
                $tipoCorreo = 'asignacionesUsuario';
                $asunto = 'Listado de asignaciones';

                $stmt = $link->prepare('SELECT u.id, idUsuario, idTipoUsuario, tu.descripcion AS tipoUsuario, correo, nomUsuario, usuario FROM usuarios AS u INNER JOIN tiposUsuarios AS tu ON u.idTipoUsuario = tu.id WHERE u.idUsuario = ?;');
                $stmt->bind_param('s', $idUsuario);

                $listaAsignaciones = '';
                if($stmt->execute()) {
                    $rowUsuario = mysqli_fetch_array($stmt->get_result(), MYSQLI_ASSOC);
                    $rowUsuario = array_map("utf8_encode", $rowUsuario);
                    $nombre = $rowUsuario['nomUsuario'];
                    $correo = $rowUsuario['correo'];
                    
                    $stmt = $link->prepare('SELECT id, Serie, Marca, Modelo, Tipo, te.NomEquipo AS tipoEquipo, Asignacion, Economico, estatus FROM equipos AS e INNER JOIN tipoequipo AS te ON e.Tipo = te.IdTipo WHERE estatus = 1 AND Asignacion = ?;');
                    $stmt->bind_param('s', $idUsuario);

                    if($stmt->execute()) {
                        $rowsEquipos = mysqli_fetch_all($stmt->get_result(), MYSQLI_ASSOC);                
        
                        $stmt->close();
                        $listaAsignaciones .= '<ol>';
                        for($i = 0; $i < count($rowsEquipos); $i++) {
                            $rowsEquipos[$i] = array_map("utf8_encode", $rowsEquipos[$i]);
                            $listaAsignaciones .= '<li>Equipo: ';
                            $listaAsignaciones .= $rowsEquipos[$i]['Marca'] . ' ' . $rowsEquipos[$i]['Modelo'];

                            $stmt = $link->prepare('SELECT id, a.idAditamento, TipoAditamento, ta.Aditamento AS nomTipoAditamento, Tipo FROM aditamentos AS a INNER JOIN tipoaditamentos AS ta ON a.TipoAditamento = ta.IdAditamento WHERE idAsignacion = ?;');
                            $stmt->bind_param('i', $rowsEquipos[$i]['id']);
        
                            if($stmt->execute()) {
                                $rowsAditamentos = mysqli_fetch_all($stmt->get_result(), MYSQLI_ASSOC);
                                
                                if(count($rowsAditamentos) != 0) {
                                    $listaAsignaciones .= '<ul>';
                                    for($k = 0; $k < count($rowsAditamentos); $k++) {
                                        $rowsAditamentos[$k] = array_map("utf8_encode", $rowsAditamentos[$k]);
                                        $listaAsignaciones .= '<li>Aditamento: ';
                                        $listaAsignaciones .= $rowsAditamentos[$k]['nomTipoAditamento'] . ' ' . $rowsAditamentos[$k]['Tipo'];
                                        $listaAsignaciones .= '</li>';
                                    }
                                    $listaAsignaciones .= '</ul>';
                                }
                                $listaAsignaciones .= '</li>';
                            }
        
                            $stmt->close();
        
                        }

                        $listaAsignaciones .= '<ol>';
                        
                        if(count($rowsEquipos) == 0)
                            $listaAsignaciones = 'No tienes equipos asignados';

                    }    
                }
                include('../PHPMailer/enviar-correo.php');

                $salida['err'] = $err;
                echo json_encode($salida);
                break;
        }
    }

    $link->close();
?>