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
            
                $stmt = $link->prepare("SELECT e.Serie, e.Marca, e.Modelo, te.NomEquipo AS Tipo, e.Asignacion, e.Economico FROM equipos AS e INNER JOIN tipoequipo AS te ON e.Tipo = te.IdTipo WHERE Modelo LIKE ?");
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
        }
    }

    $link->close();
?>