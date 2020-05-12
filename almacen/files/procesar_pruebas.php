<?php
    header("Access-Control-Allow-Origin: *");
    //session_name("trackcenter");
    if(isset($_GET['psid'])) { session_id($_GET['psid']); }
    if(isset($_POST['psid'])) { session_id($_POST['psid']); }

    
    session_start();
    include("conexion.php");

    if(isset($_SESSION['lgId'])) { $lg_idUsuario = $_SESSION['lgId']; }//46;//57;//46;//33;//46;
    if(isset($_SESSION['pwRaiz'])) { $lg_idRaiz = $_SESSION['pwRaiz']; }//28;//31;//28; //9;//28;
    if(isset($_SESSION['pwAdministrador'])) { $lg_Administrador = $_SESSION['pwAdministrador'];}//1;
    if(isset($_SESSION['lgUsuario'])) { $lg_usuario = $_SESSION['lgUsuario'];}
    if(isset($_SESSION['verTodos'])) { $lg_verTodos = $_SESSION['verTodos'];}
    if(isset($_SESSION['adminGeo'])) { $lg_adminGeo = $_SESSION['adminGeo'];}
    if(isset($_SESSION['tipoMapa'])) { $lg_tipoMapa = $_SESSION['tipoMapa'];}
    if(isset($_SESSION['tipoMapaCliente'])) { $lg_tipoMapaCliente = $_SESSION['tipoMapaCliente'];}
    if(isset($_SESSION['pwRaizRevendedor'])) { $pwRaizRevendedor = $_SESSION['pwRaizRevendedor'];}
  

    

    $opc='';
    if(isset($_GET['opc'])) { $opc = $_GET['opc']; }
    if(isset($_POST['opc'])) { $opc = $_POST['opc']; }
    
    switch($opc){
        case 'test':
            $salida['idUsuario'] = $_SESSION['lgId'];
            $salida['res'] = $_SESSION['lgUsuario'];
            echo json_encode($salida);
        break;
        case 'cargarDatosUsuario':
            $err = 0; $res = ''; $totreg = 0; $tc=0;

            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "p_Usuario_cargarDatos ?,?";
            $params = array($lg_idUsuario,$lg_idRaiz);
            $stmt = sqlsrv_query($conn, $sql, $params);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                do {
                    $tc = sqlsrv_num_fields($stmt);
                    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                        $totreg++;
                        $res .= utf8_encode($row['nombre']) . '|';
                        $res .= utf8_encode($row['correoElectronico']);
                    }
                } while (sqlsrv_next_result($stmt));
            }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            $salida['err'] = $err;
            $salida['res'] = $res;

            echo json_encode($salida);
        break;        
        case "actualizarDatosUsuario":
            $err = 0; $res = ''; $totreg = 1; $tc=0;
            $errSql = 0;
            $nombre = strtoupper($_GET['txtADUNombre']);
            $email = strtolower($_GET['txtADUEmail']);
            $pass1 = strtolower($_GET['txtADUPass1']);
            $pass2 = strtolower($_GET['txtADUPass2']);

            if($pass1 == $pass2) {

                $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
                $sql = "p_UsuarioActuaizar_datos_cs_pass ?,?,?,?,?";
                $params = array($lg_idUsuario,$lg_idRaiz,$nombre,$email,$pass1);
                $stmt = sqlsrv_query($conn, $sql, $params);
                if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }
                $rows = sqlsrv_has_rows($stmt);
                if ($rows === true) {
                    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                            $errSql = $row['err'];
                    }
                    if($errSql == 1) {
                        $err = 2;
                    }
                } else {
                    $err = 1;
                }                
            } else {
                $err = 1;
            }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            $salida['err'] = $err;
            $salida['res'] = $res;

            echo json_encode($salida);
        break;
        case "actualizarUsuario":
            $err = 0; $res = ''; $totreg = 1; $tc=0;
            $errSql = 0;
            $nombre = strtoupper($_GET['txtNombre']);
            $email = strtolower($_GET['txtEmail']);
            $pass1 = strtolower($_GET['txtPass1']);
            $pass2 = strtolower($_GET['txtPass2']);
            $origen = $_GET['txtOrigen'];
            $so = $_GET['txtSO'];
            $navegador = $_GET['txtNavegador'];
            $nav_version = $_GET['txtVersion'];
            $agent = $_GET['txtAgent'];
            $cli_IP = $_GET['txtIP'];

            if($pass1 == $pass2) {

                $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
                $sql = "p_UsuarioActuaizar_datos ?,?,?,?,?";
                $params = array($lg_idUsuario,$lg_idRaiz,$nombre,$email,$pass1);
                $stmt = sqlsrv_query($conn, $sql, $params);
                if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }
                $rows = sqlsrv_has_rows($stmt);
                if ($rows === true) {
                    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                            $errSql = $row['err'];
                    }
                    if($errSql == 1) {
                        $err = 2;
                    } else {
                        $_SESSION["actualizoDatos"] = 1;
                        guardarLogActualizarDatos(1,$lg_idUsuario,$lg_idRaiz,$origen,$cli_IP,$so,$navegador,$nav_version,$agent);

                    }
                } else {
                    $err = 1;
                }                
            } else {
                $err = 1;
            }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            $salida['err'] = $err;
            $salida['res'] = $res;

            echo json_encode($salida);
        break;
        case 'recordarPass':
            $err = 0;
            $res = '';
            $encontro = 0;
            $correoElectronico = '';
            $correoElectronicoEncrip = '';
            $texto = $_GET['txtRecordar'];
            $srv_host = '';

            $origen = $_GET['txtOrigen'];
            $so = $_GET['txtSO'];
            $navegador = $_GET['txtNavegador'];
            $nav_version = $_GET['txtVersion'];
            $agent = $_GET['txtAgent'];
            $cli_IP = $_GET['txtIP'];

            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "{call p_recordarPass(?)}";
            $params = array($texto);
            $stmt = sqlsrv_query($conn, $sql,$params);
            if( $stmt === false ) $err = 1;
            //sqlsrv_next_result($stmt);
            //sqlsrv_next_result($stmt);
            $rows = sqlsrv_has_rows($stmt);
            if ($rows === true) {
                while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                        $encontro = $row['encontro'];
                        $clave = utf8_encode($row['password']);
                        $correoElectronico = utf8_encode($row['correoElectronico']);
                        $srv_host = utf8_encode($row['srv_host']);
                        $srv_user = utf8_encode($row['srv_user']);
                        $srv_pass = utf8_encode($row['srv_pass']);
                        $srv_ssl = $row['srv_ssl'];
                        $srv_port = $row['srv_port'];
                        $empresa = utf8_encode($row['empresa']);
                        $remitente = utf8_encode($row['remitente']);
                        $nomRemitente = utf8_encode($row['nomRemitente']);
                        $err = 0;
                }
            } else {
                $err = 1;
            }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);
            $errorMail = 0;


            if(strlen($srv_user) > 0){
                $correoElectronicoEncrip = encriptarEmail($correoElectronico);    
                //$errorMail = enviarPass('mail.ubicacenter.com','recordarclave@ubicacenter.com','abc.1526','ssl',465,'UbicaCenter','recordarclave@ubicacenter.com','blasteradmin@gmail.com','abc');
                //enviarPass($srv_host,$srv_user,$srv_pass,$srv_ssl,$srv_port,$empresa,$remitente,$destinatario,$clave)
                $_SESSION['enviarPass'] = 1;
                $_SESSION['srv_host'] = $srv_host;
                $_SESSION['srv_user'] = $srv_user;
                $_SESSION['srv_pass'] = $srv_pass;
                $_SESSION['srv_ssl'] = $srv_ssl;
                $_SESSION['srv_port'] = $srv_port;
                $_SESSION['empresa'] = $empresa;
                $_SESSION['remitente'] = $remitente;
                $_SESSION['nomRemitente'] = $nomRemitente;
                $_SESSION['destinatario'] = $correoElectronico;
                $_SESSION['clave'] = $clave;
                $_SESSION['correoPass'] = $correoElectronicoEncrip;
                guardarLogEnviarPass($texto,$srv_host,$correoElectronico,$origen,$cli_IP,$so,$navegador,$nav_version,$agent,0);
            } else {
                guardarLogEnviarPass($texto,$srv_host,$correoElectronico,$origen,$cli_IP,$so,$navegador,$nav_version,$agent,1);
            }


            

            $salida['err'] = $err;
            $salida['encontro'] = $encontro;
            $salida['correoElectronico'] = $correoElectronicoEncrip;
            $salida['errorMail'] = $errorMail;

            echo json_encode($salida);            
        break;
        case 'verificarEnvioPass':
                $salida['enviarPass'] = $_SESSION['enviarPass'];
                $salida['srv_host'] = $_SESSION['srv_host'];
                $salida['srv_user'] = $_SESSION['srv_user'];
                $salida['srv_pass'] = $_SESSION['srv_pass'];
                $salida['srv_ssl'] = $_SESSION['srv_ssl'];
                $salida['srv_port'] = $_SESSION['srv_port'];
                $salida['empresa'] = $_SESSION['empresa'];
                $salida['remitente'] = $_SESSION['remitente'];
                $salida['nomRemitente'] = $_SESSION['nomRemitente'];
                $salida['destinatario'] = $_SESSION['destinatario'];
                $salida['clave'] = $_SESSION['clave'];
                $salida['correoPass'] = $_SESSION['correoPass'];
                echo json_encode($salida);            
        break;
        case 'verificarSession':
            $valido = 0;
            $idSession = "";
            $lgId = "";
            $lgUsuario = "";
            $pwRaiz = "";
            $pwRaizRevendedor = "";

            $pwMotor = 0;
            $pwHistorial =  0;
            $pwPosicion = 0;
            $pwAUsuarios = 0;
            $pwADispositivos = 0;
            $pwAGeocercas = 0;
            $pwAPuntos = 0;
            $pwARutas = 0;
            $tienePDI = 0;
            $actualizoDatos = 0;
            $lgUsuario = 0;
            $lgNombre = 0;
            $pwTipo = 0;
            $verTodos = 0;
            $adminGeo = 0;
            $noActDatos = 0;
            $getAppVersion = 0;
            $tipoMapa = 0;
            $tipoMapaCliente = 0;
            $ultimo = "";

            $idSession = $_GET['psid'];
            if(isset($_SESSION['lgId'])) { 
                $valido = 1;
                if(isset($_SESSION['lgId'])) {
                    if($_SESSION['lgId'] > 0 ) {
                        $lgId = $_SESSION['lgId'];
                        $pwRaiz = $_SESSION['pwRaiz'];
                        $lgUsuario = $_SESSION['lgUsuario'];
                        $lgNombre = $_SESSION['lgNombre'];
                        $pwRaizRevendedor = $_SESSION['pwRaizRevendedor'];

                        $pwMotor = $_SESSION['pwMotor'];
                        $pwTipo = $_SESSION['pwTipo'];
                        $pwHistorial =  $_SESSION['pwHistorial'];
                        $pwPosicion = $_SESSION['pwPosicion'];
                        $pwAUsuarios = $_SESSION['pwAUsuarios'];
                        $pwADispositivos = $_SESSION['pwADispositivos'];
                        $pwAGeocercas = $_SESSION['pwAGeocercas'];
                        $pwAPuntos = $_SESSION['pwAPuntos'];
                        $pwARutas = $_SESSION['pwARutas'];
                        $tienePDI = $_SESSION['tienePDI'];
                        $actualizoDatos = $_SESSION['actualizoDatos'];
                        $verTodos = $_SESSION['verTodos'];
                        $adminGeo = $_SESSION['adminGeo'];
                        $noActDatos = 0;//$_SESSION['noActDatos'];
                        if(strlen($_SESSION['ultimo']) > 0) { $ultimo = $_SESSION['ultimo']; } else { $ultimo=""; }
                        $pwAdministrador = $_SESSION['pwAdministrador'];
                        $tipoMapa = $_SESSION['tipoMapa'];
                        $tipoMapaCliente = $_SESSION['tipoMapaCliente'];
                    }
                }
            }
            if(isset($_GET['getAppVersion'])){
                $getAppVersion = appAndroidVersion();
            }
            
            $salida['lgId'] = $lgId;
            $salida['valido'] = $valido;
            $salida['lgUsuario'] = $lgUsuario;
            $salida['pwRaiz'] = $pwRaiz;
            $salida['pwRaizRevendedor'] = $pwRaizRevendedor;
            $salida['pwMotor'] = $pwMotor;
            $salida['pwTipo'] = $pwTipo;
            $salida['pwHistorial'] = $pwHistorial;
            $salida['pwPosicion'] = $pwPosicion;
            $salida['pwAUsuarios'] = $pwAUsuarios;
            $salida['pwADispositivos'] = $pwADispositivos;
            $salida['pwAGeocercas'] = $pwAGeocercas;
            $salida['pwAPuntos'] = $pwAPuntos;
            $salida['pwARutas'] = $pwARutas;
            $salida['tienePDI'] = $tienePDI;
            $salida['lgNombre'] = $lgNombre;
            $salida['actualizoDatos'] = $actualizoDatos;            
            $salida['verTodos'] = $verTodos;            
            $salida['adminGeo'] = $adminGeo; 
            $salida['noActDatos']=$noActDatos;          
            $salida['ultimo']=$ultimo;          
            $salida['getAppVersion'] = $getAppVersion;
            $salida['pwAdministrador'] = $pwAdministrador;
            $salida['tipoMapa'] = $tipoMapa;
            $salida['tipoMapaCliente'] = $tipoMapaCliente;
            $salida['mapaUrl'] = getMapaUrl($pwRaizRevendedor);
            $salida['apiGoogle'] = getApiGoogle($pwRaizRevendedor);
/*            $_SESSION['pwAdminRoot'] = $row['adminroot']; //adminroot
            $_SESSION['pwRoot'] = $row['adminroot']; //Historial
            $estatus = $row['Status']; //Pago Vencido
            $statusCliente = $row['statusCliente']; //0 al Corriente 1. Pago Vencido
*/
            
            echo json_encode($salida);
        break;
        case 'verificarSession2':
            $valido = 0;
            $idSession = "";
            $lgId = "";
            $lgUsuario = "";
            $pwRaiz = "";
            $pwRaizRevendedor = "";

            $pwMotor = 0;
            $pwHistorial =  0;
            $pwPosicion = 0;
            $pwAUsuarios = 0;
            $pwADispositivos = 0;
            $pwAGeocercas = 0;
            $pwAPuntos = 0;
            $pwARutas = 0;
            $tienePDI = 0;
            $actualizoDatos = 0;
            $lgUsuario = 0;
            $lgNombre = 0;
            $pwTipo = 0;
            $verTodos = 0;
            $adminGeo = 0;
            $noActDatos = 0;
            $getAppVersion = 0;
            $tipoMapa = 0;
            $tipoMapaCliente = 0;
            $ultimo = "";

            $idSession = $_GET['psid'];
            if(isset($_SESSION['lgId'])) { 
                $valido = 1;
                if(isset($_SESSION['lgId'])) {
                    if($_SESSION['lgId'] > 0 ) {
                        $lgId = $_SESSION['lgId'];
                        $pwRaiz = $_SESSION['pwRaiz'];
                        $lgUsuario = $_SESSION['lgUsuario'];
                        $lgNombre = $_SESSION['lgNombre'];
                        $pwRaizRevendedor = $_SESSION['pwRaizRevendedor'];

                        $pwMotor = $_SESSION['pwMotor'];
                        $pwTipo = $_SESSION['pwTipo'];
                        $pwHistorial =  $_SESSION['pwHistorial'];
                        $pwPosicion = $_SESSION['pwPosicion'];
                        $pwAUsuarios = $_SESSION['pwAUsuarios'];
                        $pwADispositivos = $_SESSION['pwADispositivos'];
                        $pwAGeocercas = $_SESSION['pwAGeocercas'];
                        $pwAPuntos = $_SESSION['pwAPuntos'];
                        $pwARutas = $_SESSION['pwARutas'];
                        $tienePDI = $_SESSION['tienePDI'];
                        $actualizoDatos = $_SESSION['actualizoDatos'];
                        $verTodos = $_SESSION['verTodos'];
                        $adminGeo = $_SESSION['adminGeo'];
                        $noActDatos = 0;//$_SESSION['noActDatos'];
                        if(strlen($_SESSION['ultimo']) > 0) { $ultimo = $_SESSION['ultimo']; } else { $ultimo=""; }
                        $pwAdministrador = $_SESSION['pwAdministrador'];
                        $tipoMapa = $_SESSION['tipoMapa'];
                        $tipoMapaCliente = $_SESSION['tipoMapaCliente'];
                    }
                }
            }
            if(isset($_GET['getAppVersion'])){
                $getAppVersion = appAndroidVersion();
            }
            
            $salida = '';
            $salida .= $lgId . '|';                 //0
            $salida .= $valido . '|';
            $salida .= $lgUsuario . '|';
            $salida .= $pwRaiz . '|';               //3
            $salida .= $pwRaizRevendedor . '|';
            $salida .= $pwMotor . '|';              //5
            $salida .= $pwTipo . '|';
            $salida .= $pwHistorial . '|';
            $salida .= $pwPosicion . '|';
            $salida .= $pwAUsuarios . '|';
            $salida .= $pwADispositivos . '|';         //10
            $salida .= $pwAGeocercas . '|';
            $salida .= $pwAPuntos . '|';
            $salida .= $pwARutas . '|';
            $salida .= $tienePDI . '|';
            $salida .= $lgNombre . '|';                 //15
            $salida .= $actualizoDatos . '|';            
            $salida .= $verTodos . '|';                 //17
            $salida .= $adminGeo . '|';                 //18
            $salida .=$noActDatos . '|';     
            $salida .=$ultimo . '|';                //20
            $salida .= $getAppVersion . '|';
            $salida .= $pwAdministrador . '|';
            $salida .= $tipoMapa . '|';
            $salida .= $tipoMapaCliente . '|';
            $salida .= getMapaUrl($pwRaizRevendedor) . '|';     //25
            $salida .= getApiGoogle($pwRaizRevendedor);
/*            $_SESSION['pwAdminRoot'] = $row['adminroot']; //adminroot
            $_SESSION['pwRoot'] = $row['adminroot']; //Historial
            $estatus = $row['Status']; //Pago Vencido
            $statusCliente = $row['statusCliente']; //0 al Corriente 1. Pago Vencido
*/
            
            echo $salida;
        break;
        case 'verificarSession3':
            $valido = 0;
            $idSession = "";
            $lgId = "";
            $lgUsuario = "";
            $pwRaiz = "";
            $pwRaizRevendedor = "";

            $pwMotor = 0;
            $pwHistorial =  0;
            $pwPosicion = 0;
            $pwAUsuarios = 0;
            $pwADispositivos = 0;
            $pwAGeocercas = 0;
            $pwAPuntos = 0;
            $pwARutas = 0;
            $tienePDI = 0;
            $actualizoDatos = 0;
            $lgUsuario = 0;
            $lgNombre = 0;
            $pwTipo = 0;
            $verTodos = 0;
            $adminGeo = 0;
            $noActDatos = 0;
            $getAppVersion = 0;
            $tipoMapa = 0;
            $tipoMapaCliente = 0;
            $ultimo = "";

            $idSession = $_GET['psid'];
            if(isset($_SESSION['lgId'])) { 
                $valido = 1;
                if(isset($_SESSION['lgId'])) {
                    if($_SESSION['lgId'] > 0 ) {
                        $lgId = $_SESSION['lgId'];
                        $pwRaiz = $_SESSION['pwRaiz'];
                        $lgUsuario = $_SESSION['lgUsuario'];
                        $lgNombre = $_SESSION['lgNombre'];
                        $pwRaizRevendedor = $_SESSION['pwRaizRevendedor'];

                        $pwMotor = $_SESSION['pwMotor'];
                        $pwTipo = $_SESSION['pwTipo'];
                        $pwHistorial =  $_SESSION['pwHistorial'];
                        $pwPosicion = $_SESSION['pwPosicion'];
                        $pwAUsuarios = $_SESSION['pwAUsuarios'];
                        $pwADispositivos = $_SESSION['pwADispositivos'];
                        $pwAGeocercas = $_SESSION['pwAGeocercas'];
                        $pwAPuntos = $_SESSION['pwAPuntos'];
                        $pwARutas = $_SESSION['pwARutas'];
                        $tienePDI = $_SESSION['tienePDI'];
                        $actualizoDatos = $_SESSION['actualizoDatos'];
                        $verTodos = $_SESSION['verTodos'];
                        $adminGeo = $_SESSION['adminGeo'];
                        $noActDatos = 0;//$_SESSION['noActDatos'];
                        if(strlen($_SESSION['ultimo']) > 0) { $ultimo = $_SESSION['ultimo']; } else { $ultimo=""; }
                        $pwAdministrador = $_SESSION['pwAdministrador'];
                        $tipoMapa = $_SESSION['tipoMapa'];
                        $tipoMapaCliente = $_SESSION['tipoMapaCliente'];
                    }
                }
            }
            if(isset($_GET['getAppVersion'])){
                $getAppVersion = appAndroidVersion();
            }
            
            $salida = '';
            $salida .= $lgId . '^';                 //0
            $salida .= $valido . '^';
            $salida .= $lgUsuario . '^';
            $salida .= $pwRaiz . '^';               //3
            $salida .= $pwRaizRevendedor . '^';
            $salida .= $pwMotor . '^';              //5
            $salida .= $pwTipo . '^';
            $salida .= $pwHistorial . '^';
            $salida .= $pwPosicion . '^';
            $salida .= $pwAUsuarios . '^';
            $salida .= $pwADispositivos . '^';         //10
            $salida .= $pwAGeocercas . '^';
            $salida .= $pwAPuntos . '^';
            $salida .= $pwARutas . '^';
            $salida .= $tienePDI . '^';
            $salida .= $lgNombre . '^';                 //15
            $salida .= $actualizoDatos . '^';            
            $salida .= $verTodos . '^';                 //17
            $salida .= $adminGeo . '^';                 //18
            $salida .=$noActDatos . '^';     
            $salida .=$ultimo . '^';                //20
            $salida .= $getAppVersion . '^';
            $salida .= $pwAdministrador . '^';
            $salida .= $tipoMapa . '^';
            $salida .= $tipoMapaCliente . '^';
            $salida .= getMapaUrl($pwRaizRevendedor) . '^';     //25
            $salida .= getApiGoogle($pwRaizRevendedor);
/*            $_SESSION['pwAdminRoot'] = $row['adminroot']; //adminroot
            $_SESSION['pwRoot'] = $row['adminroot']; //Historial
            $estatus = $row['Status']; //Pago Vencido
            $statusCliente = $row['statusCliente']; //0 al Corriente 1. Pago Vencido
*/
            
            echo $salida;
        break;
        case 'Login':
            $err = 0;
            $estatus = 1;
            $statusCliente = 1;

            $_SESSION['ultimoImei'] = -1;
            $_SESSION['ultimoNombre'] = -1;
            $_SESSION['ultimoUsuario'] = -1;
            $origen = '';
            $so = '';
            $navegador = '';
            $nav_version = '';
            $agen='';
            $cli_IP ='';


            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            
            if(isset($_GET["txtEnc"])){
                if($_GET["txtEnc"] == 1){
                    $user = trim(base64_decode($_GET['txtUser']));
                    $pass = trim(base64_decode($_GET['txtPass']));
                    $_SESSION["ultimo"] = $_GET['txtImei'];
                    $_SESSION["noActDatos"] = 1;
                } else {
                    $user = $_GET['txtUser'];
                    $pass = $_GET['txtPass'];    
                }
            } else {
                $user = $_GET['txtUser'];
                $pass = $_GET['txtPass'];
            }

            
            if(isset($_GET['txtOrigen'])) { $origen = $_GET['txtOrigen']; }
            if(isset($_GET['txtSO'])) { $so = $_GET['txtSO']; }
            if(isset($_GET['txtNavegador'])) { $navegador = $_GET['txtNavegador']; }
            if(isset($_GET['txtVersion'])) { $nav_version = $_GET['txtVersion']; }
            if(isset($_GET['txtAgent'])) { $agent = $_GET['txtAgent']; }
            if(isset($_GET['txtIP'])) { $cli_IP = $_GET['txtIP']; }

            $sql = "{call web_Login(?,?)}";
            $params = array($user,$pass);
            $stmt = sqlsrv_query($conn, $sql,$params);
            if( $stmt === false ) $err = 1;
            //sqlsrv_next_result($stmt);
            //sqlsrv_next_result($stmt);
            $rows = sqlsrv_has_rows($stmt);
            if ($rows === true) {
                while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                        $_SESSION['lgId'] = $row['id'];
                        $_SESSION['lgUsuario'] = $row['usuario'];
                        $_SESSION['lgNombre'] = utf8_encode($row['nombre']);
                        $_SESSION['pwTipo'] = $row['tipo'];
                        $_SESSION['pwRaiz'] = $row['raiz'];
                        $_SESSION['pwRaizRevendedor'] = $row['RaizRevendedor'];
                        $_SESSION['pwAdministrador'] = $row['Administrador'];
                        $_SESSION['pwAdminRoot'] = $row['adminroot']; //adminroot

                        $_SESSION['lgPermisos'] = $row['tipo']
                                            . "," . $row['raiz']
                                            . "," . $row['Administrador']
                                            . "," . $row['PDetenerMotor'] //Denter motor
                                            . "," . $row['PHistorial'] //Historial
                                            . "," . $row['adminroot'] //adminroot
                                            ;

                        $_SESSION['pwMotor'] = $row['PDetenerMotor']; //Denter motor
                        $_SESSION['pwHistorial'] = $row['PHistorial']; //Historial
                        $_SESSION['pwPosicion'] = $row['PPosicion']; //Solicitar Posición
                        $_SESSION['pwAUsuarios'] = $row['PAUsuarios']; //Asignacion de USuarios
                        $_SESSION['pwADispositivos'] = $row['PADispositivos']; //Info de Dispositivos
                        $_SESSION['pwAGeocercas'] = $row['PAGeocercas']; //Geocercas
                        $_SESSION['pwAPuntos'] = $row['PAPuntos']; //Administrar Puntos de Interes
                        $_SESSION['pwARutas'] = $row['PARutas']; //Administrar Rutas

                        $_SESSION['pwRoot'] = $row['adminroot']; //Historial
                        $estatus = $row['Status']; //Pago Vencido
                        $statusCliente = $row['statusCliente']; //0 al Corriente 1. Pago Vencido

                        $_SESSION['tienePDI'] = tienePDI($row['raiz']); //Administrar Puntos de Interes
                        $_SESSION['actualizoDatos'] = $row['actualizoDatos']; //Obligar a actualizar nombre, contraseña e email.
                        $_SESSION['verTodos'] = $row['verTodos']; //opcion para ver todos los gps de sus clientes
                        $_SESSION['adminGeo'] = $row['adminGeo']; //opcion para ver todos los gps de sus clientes
                        $_SESSION['tipoMapa'] = $row['tipoMapa']; //Conocer Mapa Actual
                        $_SESSION['tipoMapaCliente'] = $row['tipoMapaCliente']; //Conocer Mapa Actual
                }
                $err = 0;
            } else {
                $err = 1;
            }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            $lgId_tmp = 0;
            if(isset($_SESSION['lgId'])){
                $lgId_tmp = $_SESSION['lgId'];
            }

            guardarLogLogin($lgId_tmp,$origen,$so,$navegador,$nav_version,$agent,$cli_IP,$err,$user);
            //guardarPSID();

            $salida['err'] = $err;
            $salida['Status'] = $estatus;
            $salida['statusCliente'] = $statusCliente;

            echo json_encode($salida);
        break;
        case 'guardar_ultimo':
            $valor = $_GET['valor'];
            
//            $_SESSION['ultimo'] = $valor;
            $_SESSION['ultimoValor'] = $valor;
            
            $salida['res'] = 'cargado';
            $salida['ultimo'] = $_SESSION['ultimoValor'];
            $salida['lgUsuario'] = $_SESSION['lgUsuario'];
            $salida['valor'] = $valor;
            echo json_encode($salida);        
        break;
        case 'cargar_ultimo':
//            $valor = $_SESSION['ultimo'];
            $valor = $_SESSION['ultimoValor'];
            if(strlen($valor) == 0) $valor = 'x';
            $salida['res'] =  $valor;     
//            $salida['ultimo'] = $_SESSION['ultimo'];
            $salida['ultimo'] = $_SESSION['ultimoValor'];
            $salida['lgUsuario'] = $_SESSION['lgUsuario'];
            echo json_encode($salida);       
        break;        
    	case 'cargarGruposDisp':
            $err = 0;
            $res = '';
            $totreg = 0;

            //$lg_idUsuario = 57;//46;//33;//46;
            //$lg_idRaiz = 31;//28; //9;//28;
            //$lg_Administrador = 1;
            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "p_dispositivos_cargar_grupos ?,?";
            $params = array($lg_idRaiz,$lg_idUsuario);
			$stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
	            do {
	                while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
	                    $totreg++;
	                    //$res .= $row['Nombre'] . ',' . $row['Descripcion'] . $row['Id'] . ',' . $row['Modelo'] .  '|';
	                    $res .= utf8_encode($row['nombre'])                //0
                                . ',' . utf8_encode($row['nivel']) 
                                . '|';
	                }
	            } while (sqlsrv_next_result($stmt));            
			}
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            $salida['err'] = $err;
            $salida['totreg'] = $totreg;
            $salida['res'] = $res;

            echo json_encode($salida);
        break;
        case 'Dispositivos':
            $err = 0;
            $res = '';
            $totreg = 0;

            //$lg_idUsuario = 57;//46;//33;//46;
            //$lg_idRaiz = 31;//28; //9;//28;
            //$lg_Administrador = 1;
            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "p_dispositivos_cargar_todos ?,?,?,?";
            $params = array($lg_idUsuario,$lg_idRaiz,$lg_Administrador,$lg_verTodos);
            $stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                do {
                    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                        $totreg++;
                        //$res .= $row['Nombre'] . ',' . $row['Descripcion'] . $row['Id'] . ',' . $row['Modelo'] .  '|';
                        $res .= utf8_encode($row['Nombre'])                //0
                                . ',' . utf8_encode($row['Descripcion']) 
                                . ',' . $row['Id'] 
                                . ',' . $row['Modelo'] 
                                . ',' . $row['idGeocerca'] 
                                . ',' . $row['AlarmaGeocerca']              //5
                                . ',' . $row['ApagadoGeocerca'] 
                                . ',' . $row['Puerto'] 
                                . ',' . $row['infoDisp']                    //8
                                . ',' . $row['alarmaRuta']                    //9
                                . ',' . $row['rutaC']                    //10
                                . ',' . $row['salida2']                    //11
                                . ',' . $row['cmdBloqueoMotor']                    //12
                                . ',' . $row['cmdSalida2']                    //13
                                . ',' . $row['idDisp']                    //14
                                . ',' . utf8_encode($row['grupo'])                    //15
                                . '|';
                    }
                } while (sqlsrv_next_result($stmt));            
            }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            $salida['err'] = $err;
            $salida['totreg'] = $totreg;
            $salida['res'] = $res;

            echo json_encode($salida);
        break;
        case 'Dispositivos_wg':
            $err = 0;
            $res = '';
            $gru = '';
            $totreg = 0;

            //$lg_idUsuario = 57;//46;//33;//46;
            //$lg_idRaiz = 31;//28; //9;//28;
            //$lg_Administrador = 1;
            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}


            $sql = "p_dispositivos_cargar_grupos ?,?";
            $params = array($lg_idRaiz,$lg_idUsuario);
            $stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                do {
                    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                        $totreg++;
                        //$res .= $row['Nombre'] . ',' . $row['Descripcion'] . $row['Id'] . ',' . $row['Modelo'] .  '|';
                        $gru .= utf8_encode($row['nombre'])                //0
                                . ',' . utf8_encode($row['nivel']) 
                                . '|';
                    }
                } while (sqlsrv_next_result($stmt));            
            }



            $sql = "p_dispositivos_cargar_todos ?,?,?,?";
            $params = array($lg_idUsuario,$lg_idRaiz,$lg_Administrador,$lg_verTodos);
            $stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                do {
                    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                        $totreg++;
                        //$res .= $row['Nombre'] . ',' . $row['Descripcion'] . $row['Id'] . ',' . $row['Modelo'] .  '|';
                        $res .= utf8_encode($row['Nombre'])                //0
                                . ',' . utf8_encode($row['Descripcion']) 
                                . ',' . $row['Id'] 
                                . ',' . $row['Modelo'] 
                                . ',' . $row['idGeocerca'] 
                                . ',' . $row['AlarmaGeocerca']              //5
                                . ',' . $row['ApagadoGeocerca'] 
                                . ',' . $row['Puerto'] 
                                . ',' . $row['infoDisp']                    //8
                                . ',' . $row['alarmaRuta']                    //9
                                . ',' . $row['rutaC']                    //10
                                . ',' . $row['salida2']                    //11
                                . ',' . $row['cmdBloqueoMotor']                    //12
                                . ',' . $row['cmdSalida2']                    //13
                                . ',' . $row['idDisp']                    //14
                                . ',' . utf8_encode($row['grupo'])                    //15
                                . ',' . $row['statusMotor']                    //16
                                . ',' . $row['bloqueMotor']                    //17
                                . '|';
                    }
                } while (sqlsrv_next_result($stmt));            
            }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            $salida['err'] = $err;
            $salida['totreg'] = $totreg;
            $salida['grupos'] = $gru;
            $salida['res'] = $res;

            echo json_encode($salida);
        break;
        case 'buscar':
            $err = 0; $res = ''; $totreg = 0; $nextID = 0;
            $_SESSION["ultimo"] = "";
            //Parametros-----------------------
            $todos = $_GET['todos'];
            $dispositivo = $_GET['imei'];
            $ultimoID = $_GET['oldID'];

            /*$lg_idUsuario = 57;//46;//33;//46;
            $lg_idRaiz = 31;//28; //9;//28;
            $lg_Administrador = 1;*/


            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "p_registrosObtener ?,?,?,?,?,?";
            $params = array($todos, $dispositivo, $lg_idUsuario, $lg_Administrador, $lg_idRaiz, $ultimoID);
            
            //echo "<hr>" . $todos . ',' . $dispositivo . ',' . $lg_idUsuario . ',' . $lg_Administrador . ',' . $lg_idRaiz . "<hr>";

			$stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);


            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
	            do {
	            	$tc = sqlsrv_num_fields($stmt);
	                while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
	                    $totreg++;

                        $velocidad = ($tc>1?$row['Velocidad']:'');

	                    $res .= utf8_encode($row['Estatus']) . ','; 						//0
	                    $res .= ($tc>1?$row['IdRegistro']:'') . ',';
	                    $res .= ($tc>1?$row['Dispositivo']:'') . ',';
	                    $res .= ($tc>1?utf8_encode($row['Nombre']):'') . ',';
	                    $res .= ($tc>1?utf8_encode($row['Tipo Vehiculo']):'') . ',';
	                    $res .= ($tc>1?$row['Estado Motor']:'') . ',';						//5
	                    $res .= ($tc>1?$row['Estado Bloqueo']:'') . ',';
	                    $res .= ($tc>1?$row['Estado Puertas']:'') . ',';
	                    $res .= ($tc>1?utf8_encode($row['Nombre Geocerca']):'') . ',';
	                    $res .= ($tc>1?$row['Estado Geocerca']:'') . ',';
	                    $res .= ($tc>1?$row['Fecha Registro']:'') . ',';					//10
	                    $res .= ($tc>1?$row['Latitud']:'') . ',';
	                    $res .= ($tc>1?$row['Longitud']:'') . ',';
	                    $res .= $velocidad . ',';
	                    $res .= ($tc>1?$row['Direccion']:'') . ',';
	                    $res .= ($tc>1?$row['Orientacion']:'') . ',';						//15
	                    $res .= ($tc>1?$row['Altitud']:'') . ',';
	                    $res .= ($tc>1?$row['Nivel Combustible1']:'') . ',';
	                    $res .= ($tc>1?$row['Nivel Combustible2']:'') . ',';
	                    $res .= ($tc>1?$row['Nivel Temperatura']:'') . ',';
	                    $res .= ($tc>1?utf8_encode($row['Tipo Posicion']):'') . ','; //20
	                    $res .= ($tc>1?$row['Posicion']:'') . ',';
	                    $res .= ($tc>1?utf8_encode($row['Nombre Ruta']):'') . ',';
	                    $res .= ($tc>1?$row['Estado Ruta']:'') . ',';
	                    $res .= ($tc>1?utf8_encode($row['PDI']):'') . ',';								//24
	                    $res .= ($tc>1?$row['Distancia Restante']:'') . ',';				//25
                        $res .= ($tc>1?$row['Minutos Detenido']:'') . ',';                //26
                        $res .= ($tc>1?$row['bateria']:'') . ',';                //27
                        $res .= ($tc>1?$row['detenidoOff']:'') . ',';                //28
                        $res .= ($tc>1?$row['detenidoOff']:'') . ',';                //29
                        $res .= ($tc>1?$row['duracionOff']:'') . ',';                //30
                        $res .= ($tc>1?$row['duracionOn']:'') . ',';                //31                     
                        $res .= ($tc>1?$row['KilometrajeActual']:'') . ',';                //32
                        $res .= ($tc>1?$row['Tiempo']:'') . ',';                //33
                        $res .= ($tc>1?$row['alarmaPDI']:'') . ',';                //34
                        $res .= ($tc>1?$row['idPDICercano']:'') . ',';                //35
                        $res .= ($tc>1?utf8_encode($row['nombrePDICercano']):'') . ',';                //36
                        $res .= ($tc>1?$row['distanciaPDIC']:'') . ',';                //37
                        $res .= ($tc>1?$row['pdiCRadio']:'') . ',';                //38
                        $res .= ($tc>1?$row['pdiCImagen']:'') . ',';                //39
                        $res .= ($tc>1?$row['pdiCLat']:'') . ',';                //40
                        $res .= ($tc>1?$row['pdiCLon']:'') . ',';                //41
                        $res .= ($tc>1?$row['entrada1']:'') . ',';                //42
                        $res .= ($tc>1?$row['ignision2']:'') . ',';                //43
                        $res .= ($tc>1?$row['ltsCombustible1']:'') . ',';                //44
                        $res .= ($tc>1?$row['ltsCombustible2']:'') . ',';                //45
                        $res .= ($tc>1?$row['TipoGPS']:'') . '|';                       //46

	                    $nextID = ($tc>1?$row['IdRegistro']:0);
	                }
	            } while (sqlsrv_next_result($stmt));            
			}
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            $salida['err'] = $err;
            $salida['totreg'] = $totreg;
            $salida['totcol'] = $tc;
            $salida['ultimoID'] = $nextID;
            $salida['oldID'] = $ultimoID;
            $salida['res'] = $res;

            echo json_encode($salida);
        break;
        case 'obd_info':
            $err = 0; $res = '';

            $dispositivo = $_GET['imei'];

            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}

            $sql = "select rp.id,rp.imei,convert(varchar, rp.fecha, 120) as fecha,rp.pid,rp.pidvalue,r.referencia,r.unidad from registropid as rp left join referenciapid as r on rp.pid=r.pid WHERE rp.fecha = (SELECT MAX(fecha) FROM registropid WHERE imei = '$dispositivo') AND imei = '$dispositivo'";
            $stmt = sqlsrv_query($conn, $sql);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                //do {
                    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                        //$res .= $row['Nombre'] . ',' . $row['Descripcion'] . $row['Id'] . ',' . $row['Modelo'] .  '|';
                        $res .= $row['id']
                                . ',' . $row['imei']                    //1
                                . ',' . $row['fecha']                    //2
                                . ',' . $row['pid']
                                . ',' . $row['pidvalue']
                                . ',' . utf8_encode($row['referencia'])
                                . ',' . utf8_encode($row['unidad'])
                                . '|';
                    }
                //} while (sqlsrv_next_result($stmt));
            }
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);
            
            $salida['err'] = $err;
            $salida['res'] = $res;

            echo json_encode($salida);
            break;
        case 'buscar_wg':
            $err = 0; $res = ''; $totreg = 0; $nextID = 0; $sm = '';
            $_SESSION["ultimo"] = "";
            //Parametros-----------------------
            $todos = $_GET['todos'];
            $dispositivo = $_GET['imei'];
            $ultimoID = $_GET['oldID'];

            /*$lg_idUsuario = 57;//46;//33;//46;
            $lg_idRaiz = 31;//28; //9;//28;
            $lg_Administrador = 1;*/


            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}


            if($todos == '1'){
                $sql = "p_dispositivos_cargar_todos ?,?,?,?";
                $params = array($lg_idUsuario,$lg_idRaiz,$lg_Administrador,$lg_verTodos);
                $stmt = sqlsrv_query($conn, $sql, $params);
                //var_dump($conn, $sql);

                if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

                if($err == 0) {
                    do {
                        while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                            $totreg++;
                            //$res .= $row['Nombre'] . ',' . $row['Descripcion'] . $row['Id'] . ',' . $row['Modelo'] .  '|';
                            $sm .= $row['Id']
                                    . ',' . $row['statusMotor']                    //1
                                    . ',' . $row['bloqueMotor']                    //2
                                    . '|';
                        }
                    } while (sqlsrv_next_result($stmt));            
                }
            }




            $sql = "p_registrosObtener ?,?,?,?,?,?";
            $params = array($todos, $dispositivo, $lg_idUsuario, $lg_Administrador, $lg_idRaiz, $ultimoID);
            
            //echo "<hr>" . $todos . ',' . $dispositivo . ',' . $lg_idUsuario . ',' . $lg_Administrador . ',' . $lg_idRaiz . "<hr>";

            $stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);


            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                do {
                    $tc = sqlsrv_num_fields($stmt);
                    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                        $totreg++;

                        $velocidad = ($tc>1?$row['Velocidad']:'');

                        $res .= utf8_encode($row['Estatus']) . ',';                         //0
                        $res .= ($tc>1?$row['IdRegistro']:'') . ',';
                        $res .= ($tc>1?$row['Dispositivo']:'') . ',';
                        $res .= ($tc>1?utf8_encode($row['Nombre']):'') . ',';
                        $res .= ($tc>1?utf8_encode($row['Tipo Vehiculo']):'') . ',';
                        $res .= ($tc>1?$row['Estado Motor']:'') . ',';                      //5
                        $res .= ($tc>1?$row['Estado Bloqueo']:'') . ',';
                        $res .= ($tc>1?$row['Estado Puertas']:'') . ',';
                        $res .= ($tc>1?utf8_encode($row['Nombre Geocerca']):'') . ',';
                        $res .= ($tc>1?$row['Estado Geocerca']:'') . ',';
                        $res .= ($tc>1?$row['Fecha Registro']:'') . ',';                    //10
                        $res .= ($tc>1?$row['Latitud']:'') . ',';
                        $res .= ($tc>1?$row['Longitud']:'') . ',';
                        $res .= $velocidad . ',';
                        $res .= ($tc>1?$row['Direccion']:'') . ',';
                        $res .= ($tc>1?$row['Orientacion']:'') . ',';                       //15
                        $res .= ($tc>1?$row['Altitud']:'') . ',';
                        $res .= ($tc>1?$row['Nivel Combustible1']:'') . ',';
                        $res .= ($tc>1?$row['Nivel Combustible2']:'') . ',';
                        $res .= ($tc>1?$row['Nivel Temperatura']:'') . ',';
                        $res .= ($tc>1?utf8_encode($row['Tipo Posicion']):'') . ','; //20
                        $res .= ($tc>1?$row['Posicion']:'') . ',';
                        $res .= ($tc>1?utf8_encode($row['Nombre Ruta']):'') . ',';
                        $res .= ($tc>1?$row['Estado Ruta']:'') . ',';
                        $res .= ($tc>1?utf8_encode($row['PDI']):'') . ',';                              //24
                        $res .= ($tc>1?$row['Distancia Restante']:'') . ',';                //25
                        $res .= ($tc>1?$row['Minutos Detenido']:'') . ',';                //26
                        $res .= ($tc>1?$row['bateria']:'') . ',';                //27
                        $res .= ($tc>1?$row['detenidoOff']:'') . ',';                //28
                        $res .= ($tc>1?$row['detenidoOff']:'') . ',';                //29
                        $res .= ($tc>1?$row['duracionOff']:'') . ',';                //30
                        $res .= ($tc>1?$row['duracionOn']:'') . ',';                //31                     
                        $res .= ($tc>1?$row['KilometrajeActual']:'') . ',';                //32
                        $res .= ($tc>1?$row['Tiempo']:'') . ',';                //33
                        $res .= ($tc>1?$row['alarmaPDI']:'') . ',';                //34
                        $res .= ($tc>1?$row['idPDICercano']:'') . ',';                //35
                        $res .= ($tc>1?utf8_encode($row['nombrePDICercano']):'') . ',';                //36
                        $res .= ($tc>1?$row['distanciaPDIC']:'') . ',';                //37
                        $res .= ($tc>1?$row['pdiCRadio']:'') . ',';                //38
                        $res .= ($tc>1?$row['pdiCImagen']:'') . ',';                //39
                        $res .= ($tc>1?$row['pdiCLat']:'') . ',';                //40
                        $res .= ($tc>1?$row['pdiCLon']:'') . ',';                //41
                        $res .= ($tc>1?$row['entrada1']:'') . ',';                //42
                        $res .= ($tc>1?$row['ignision2']:'') . ',';                //43
                        $res .= ($tc>1?$row['ltsCombustible1']:'') . ',';                //44
                        $res .= ($tc>1?$row['ltsCombustible2']:'') . ',';                //45
                        $res .= ($tc>1?$row['TipoGPS']:'') . '|';                       //46

                        $nextID = ($tc>1?$row['IdRegistro']:0);
                    }
                } while (sqlsrv_next_result($stmt));            
            }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            $salida['err'] = $err;
            $salida['totreg'] = $totreg;
            $salida['totcol'] = $tc;
            $salida['ultimoID'] = $nextID;
            $salida['oldID'] = $ultimoID;
            $salida['res'] = $res;
            $salida['sm'] = $sm;

            echo json_encode($salida);
        break;
        case "alertas":
			$err = 0;
            $res = '';
            $totreg = 0; $nextID = 0;
			$ultimoID = $_GET['ultimoID'];
            $mobile = 0;
            $mobileUltimoID = 0;
            if(isset($_GET['mobile'])) { $mobile = $_GET["mobile"]; }

            /*$lg_idUsuario = 57;//46;//33;//46;
            $lg_idRaiz = 31;//28; //9;//28;
            $lg_Administrador = 1;*/

            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "p_alarmas_listar ?,?,?";
            $params = array($lg_idUsuario,$ultimoID,$mobile);
			$stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);


            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
	            do {
	            	$tc = sqlsrv_num_fields($stmt);
	                while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                        if($totreg == 0) { $mobileUltimoID =  ($tc>1?$row['Id']:0); }
	                    $totreg++;
                        $velocidad = $row['Velocidad'];
	                    $res .= $row['Id'] . ',' . $row['Fecha Alarma'] . ',' . utf8_encode($row['Tipo Alarma']) . ',' . utf8_encode($row['Nombre Dispositivo']) . ',';
	                    $res .= $row['Latitud'] . "," . $row['Longitud'] . "," . $velocidad . "," . $row['Orientacion'] . "," . $row['idAlarma'] . '|';
	                    $nextID = ($tc>1?$row['Id']:0);
	                }
	            } while (sqlsrv_next_result($stmt));            
			}
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            if($mobile == 1) { $nextID = $mobileUltimoID; }

            $salida['err'] = $err;
            $salida['totreg'] = $totreg;
            $salida['ultimoID'] = $nextID;
            $salida['res'] = $res;

            echo json_encode($salida);
        break;
        case "alertas_v2":
            $err = 0;
            $res = '';
            $primerID = 0;
            $totreg = 0; $nextID = 0;
            $ultimoID = $_GET['ultimoID'];
            $totAlarmas = 0;

            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            //$sql = "p_alarmas_listar ?,?,?";
            //$params = array($lg_idUsuario,$ultimoID,0);
            $sql = "p_alarmas_listar_v2 ?,?";
            $params = array($lg_idUsuario,$ultimoID);
            $stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                do {
                    $tc = sqlsrv_num_fields($stmt);
                    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                        $totreg++;
                        if($totreg == 1) { $primerID = $row['id']; }
                        $totAlarmas = $row['totReg'];
                        $nextID = $row['id'];
                        $res .= $row['id'] . ',';
                        $res .= $row['fecha'] . ',';
                        $res .= utf8_encode($row['tipoAlarma']) . ',';
                        $res .= utf8_encode($row['nombre']) . ',';
                        $res .= $row['lat'] . ",";
                        $res .= $row['lon'] . ",";
                        $res .= $row['velocidad'] . ",";
                        $res .= $row['orientacion'] . ',';
                        $res .= $row['idAlarma'] . '|';
                    }
                } while (sqlsrv_next_result($stmt));            
            }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            $salida['err'] = $err;
            $salida['totreg'] = $totreg;
            $salida['totalarmas'] = $totAlarmas;
            $salida['ultimoID'] = $nextID;
            $salida['primerID'] = $primerID;
            $salida['res'] = $res;

            echo json_encode($salida);
        break;
        case "p_reportes_alarmas":
            $err = 0;
            $res = '';
            $totreg = 0; $nextID = 0;
            $ultimoID = $_GET['ultimoID'];
            $mobile = 0;
            $mobileUltimoID = 0;
            if(isset($_GET['mobile'])) { $mobile = $_GET["mobile"]; }

            /*$lg_idUsuario = 57;//46;//33;//46;
            $lg_idRaiz = 31;//28; //9;//28;
            $lg_Administrador = 1;*/

            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "p_alarmas_listar ?,?,?";
            $params = array($lg_idUsuario,$ultimoID,$mobile);
            $stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);


            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                do {
                    $tc = sqlsrv_num_fields($stmt);
                    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                        if($totreg == 0) { $mobileUltimoID =  ($tc>1?$row['Id']:0); }
                        $totreg++;
                        $velocidad = $row['Velocidad'];
                        
                        $res .= $row['Id'] . ',';
                        $res .= $row['Fecha Alarma'] . ',';
                        $res .= utf8_encode($row['Tipo Alarma']) . ',';
                        $res .= utf8_encode($row['Nombre Dispositivo']) . ',';
                        $res .= $row['Latitud'] . ",";
                        $res .= $row['Longitud'] . ",";
                        $res .= $velocidad . ",";
                        $res .= $row['Orientacion'] . ',';
                        $res .= $row['chofer'] . '|';
                        $nextID = ($tc>1?$row['Id']:0);
                    }
                } while (sqlsrv_next_result($stmt));            
            }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            if($mobile == 1) { $nextID = $mobileUltimoID; }

            $salida['err'] = $err;
            $salida['totreg'] = $totreg;
            $salida['ultimoID'] = $nextID;
            $salida['res'] = $res;

            echo json_encode($salida);
        break;
        case "historial":
			$err = 0;
            $res = '';
            $totreg = 0;
			$dispositivo = $_GET['nomDisp'];
			$fechaIni = $_GET['fechaIni'];
			$fechaFin = $_GET['fechaFin'];
			$tipo = $_GET['tipo'];
			$limite = $_GET['limite'];
			$motorBusqueda = $_GET['motorBusqueda'];
			$intervalo = 1;//$_GET['intervalo'];
	
            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "p_historial ?,?,?,?,?,?,?,?,?";
            $params = array($lg_idRaiz,$dispositivo,$fechaIni,$fechaFin,$tipo,$limite,$motorBusqueda,$intervalo,$lg_verTodos);
			$stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);


            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
	            do {
	            	$tc = sqlsrv_num_fields($stmt);
	                while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
	                    $totreg++;

                        $velocidad = $row['Velocidad'];


                        $res .= $row['Fecha'] . ',';                   //0
                        $res .= utf8_encode($row['Posicion']) . ',';                //1
                        $res .= utf8_encode($row['Nombre']) . ',';
                        $res .= $row['Tipo'] . ',';
                        $res .= $row['Motor'] . ',';
                        $res .= $row['Latitud'] . ',';                 //5
                        $res .= $row['Longitud'] . ',';                
                        $res .= utf8_encode($row['PDI']) . ',';
                        $res .= $row['Dir'] . ',';
                        $res .= $row['Orientacion'] . ',';
                        $res .= $velocidad . ',';               //10
                        $res .= $row['Combustible1'] . ',';
                        $res .= $row['Combustible2'] . ',';
                        $res .= $row['Temperatura'] . ',';
                        $res .= $row['Direccion'] . ',';
                        $res .= $row['Kilometros'] . ',';  //15
                        $res .= $row['Tiempo'] . ',';  //16
                        $res .= $row['bateria'] . ',';    //17
                        $res .= $row['idAlarma'] . ',';  //18
                        $res .= $row['detenido'] . ',';  //19
                        $res .= $row['duracionOff'] . ',';  //20
                        $res .= $row['duracionOn'] . ',';  //21
                        $res .= $row['idxFecha'] . ',';  //22
                        $res .= $row['ltsCombustible1'] . ',';  //23
                        $res .= $row['ltsCombustible2'] . ',';  //24
                        $res .= utf8_encode($row['ltsCombustible2']) . '|';  //24

	                }
	            } while (sqlsrv_next_result($stmt));            
			}
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            $salida['err'] = $err;
            $salida['totreg'] = $totreg;
            //$salida['ultimoID'] = $nextID;
            $salida['res'] = $res;

            echo json_encode($salida);
        break;
        case "p_reportes_historial":
            $err = 0;
            $res = '';
            $totreg = 0;
            $dispositivo = $_GET['unidad'];
            $fechaIni = $_GET['fechaIni'];
            $fechaFin = $_GET['fechaFin'];
            $tipo = $_GET['tipo'];
            $limite = $_GET['limite'];
            $motorBusqueda = $_GET['motorBusqueda'];
            if($motorBusqueda == '0') {$motorBusqueda = '';}
    
            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "p_reportes_historial ?,?,?,?,?,?,?";
            $params = array($lg_idRaiz,$dispositivo,$fechaIni,$fechaFin,$limite,$motorBusqueda,$lg_verTodos);
            $stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                do {
                    $tc = sqlsrv_num_fields($stmt);
                    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                        $totreg++;

                        $velocidad = $row['Velocidad'];


                        $res .= $row['Fecha'] . ',';                   //0
                        $res .= utf8_encode($row['Posicion']) . ',';                //1
                        $res .= utf8_encode($row['Nombre']) . ',';
                        $res .= $row['Tipo'] . ',';
                        $res .= $row['Motor'] . ',';
                        $res .= $row['Latitud'] . ',';                 //5
                        $res .= $row['Longitud'] . ',';                
                        $res .= utf8_encode($row['PDI']) . ',';
                        $res .= $row['Dir'] . ',';
                        $res .= $row['Orientacion'] . ',';
                        $res .= $velocidad . ',';               //10
                        $res .= $row['Combustible1'] . ',';
                        $res .= $row['Combustible2'] . ',';
                        $res .= $row['Temperatura'] . ',';
                        $res .= $row['Direccion'] . ',';
                        $res .= $row['Kilometros'] . ',';  //15
                        $res .= $row['Tiempo'] . ',';  //16
                        $res .= $row['bateria'] . ',';    //17
                        $res .= $row['idAlarma'] . ',';  //18
                        $res .= $row['detenido'] . ',';  //19
                        $res .= $row['duracionOff'] . ',';  //20
                        $res .= $row['duracionOn'] . ',';  //21
                        $res .= $row['idxFecha'] . ',';  //22
                        $res .= $row['ltsCombustible1'] . ',';  //23
                        $res .= $row['ltsCombustible2'] . ',';  //24
                        $res .= utf8_encode($row['chofer']) . ',';  //25
                        $res .= utf8_encode($row['Geocerca']) . '|';  //26
                    }
                } while (sqlsrv_next_result($stmt));            
            }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            $salida['err'] = $err;
            $salida['totreg'] = $totreg;
            //$salida['ultimoID'] = $nextID;
            $salida['res'] = $res;

            echo json_encode($salida);
        break;
        case "p_ultimoReporte":
            $err = 0;
            $res = '';
            $totreg = 0;
    
            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "p_ultimaPosicion ?";
            $params = array($lg_idRaiz);
            $stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                do {
                    $tc = sqlsrv_num_fields($stmt);
                    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                        $totreg++;

                        $res .= utf8_encode($row['NombreCorto']) . ',';                //0
                        $res .= $row['fecha'] . ',';                   //1
                        $res .= $row['LocationX'] . ',';
                        $res .= $row['LocationY'] . ',';
                        $res .= $row['Velocidad'] . ',';                 //4
                        $res .= $row['Direccion'] . ',';                //5
                        $res .= $row['StatusMotor'] . ',';                //6
                        $res .= $row['odometro'] . ',';                //7
                        $res .= $row['horometro'] . ',';                //8
                        $res .= $row['detenido'] . ',';                //9
                        $res .= utf8_encode($row['Marca']) . ',';       //10
                        $res .= utf8_encode($row['Modelo']) . ',';       //11
                        $res .= utf8_encode($row['Anio']) . ',';       //12
                        $res .= utf8_encode($row['placa']) . ',';       //13
                        $res .= utf8_encode($row['noSerie']) . ',';       //14
                        $res .= utf8_encode($row['Detalles']) . ',';       //15
                        $res .= utf8_encode($row['RazonSocial']) . ',';       //16
                        $res .= $row['fechaUTC'] . ',';                   //17
                        $res .= $row['pdi'] . ',';                   //18
                        $res .= $row['distanciaPDI'] . ',';       //19
                        $res .= $row['idReg'] . ',';       //20
                        $res .= utf8_encode($row['wsDir']) . ',';       //21
                        $res .= $row['actDir'] . '|';       //22
                    }
                } while (sqlsrv_next_result($stmt));            
            }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            $salida['err'] = $err;
            $salida['totreg'] = $totreg;
            //$salida['ultimoID'] = $nextID;
            $salida['res'] = $res;

            echo json_encode($salida);
        break;
        case "p_reportes_horasTrabajadas_w_horario":
            $err = 0;
            $res = '';
            $totreg = 0;

            $dispositivo = $_GET['unidad'];
            $fechaIni = $_GET['fechaIni'];
            $fechaFin = $_GET['fechaFin'];
            $horaIni = $_GET['horaIni'];
            $horaFin = $_GET['horaFin'];
            $fueraHorario = $_GET['fueraHorario'];

            $horaIni = substr($horaIni,0,2) . ':' . substr($horaIni,2,2);
            $horaFin = substr($horaFin,0,2) . ':' . substr($horaFin,2,2);

            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            if($fueraHorario == 0){
                $sql = "p_reportes_horasTrabajadas_w_horario ?,?,?,?,?,?";
            } else {
                $sql = "p_reportes_horasTrabajadas_w_horario_v1 ?,?,?,?,?,?";
            }
            $params = array($lg_idRaiz,$dispositivo,$fechaIni,$fechaFin,$horaIni,$horaFin);
            $stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                do {
                    $tc = sqlsrv_num_fields($stmt);
                    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                        $totreg++;
                        $res .= $row['id1'] . ',';                   //0
                        $res .= $row['idxFecha1'] . ',';                   //0
                        $res .= $row['motor1'] . ',';                   //0
                        $res .= $row['locx1'] . ',';                   //0
                        $res .= $row['locy1'] . ',';                   //0
                        $res .= $row['hor1'] . ',';                   //0
                        $res .= $row['id2'] . ',';                   //0
                        $res .= $row['idxFecha2'] . ',';                   //0
                        $res .= $row['motor2'] . ',';                   //0
                        $res .= $row['locx2'] . ',';                   //0
                        $res .= $row['locy2'] . ',';                   //0
                        $res .= $row['hor2'] . ',';                   //0
                        $res .= $row['mins'] . ',';                   //0
                        $res .= $row['hormins'] . '|';                   //0
                    }
                } while (sqlsrv_next_result($stmt));            
            }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            $salida['err'] = $err;
            $salida['totreg'] = $totreg;
            //$salida['ultimoID'] = $nextID;
            $salida['res'] = $res;

            echo json_encode($salida);
        break;
        case "historial_file":
            $err = 0;
            $res = '';
            $res2 = '';
            $totreg = 0;
            $odoIni = 0;
            $kms = 0;
            $horIni = 0;
            $hrs = 0;
            $dispositivo = $_GET['nomDisp'];
            $fechaIni = $_GET['fechaIni'];
            $fechaFin = $_GET['fechaFin'];
            $tipo = $_GET['tipo'];
            $limite = $_GET['limite'];
            $motorBusqueda = $_GET['motorBusqueda'];
            $intervalo = 1;//$_GET['intervalo'];
    
            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "p_historial ?,?,?,?,?,?,?,?,?";
            $params = array($lg_idRaiz,$dispositivo,$fechaIni,$fechaFin,$tipo,$limite,$motorBusqueda,$intervalo,$lg_verTodos);
            $stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {                
                $archivo = date("YmdHis") . "_Reporte.csv";
                $file = fopen("../tmpReportes/" . $archivo, "w");
                //$file = fopen($archivo, "w");

                do {
                    $tc = sqlsrv_num_fields($stmt);
                    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                        $totreg++;

                        if($totreg == 1) { $odoIni = $row['Kilometros'] ; }
                        else { $kms = intval($row['Kilometros'] - $odoIni); }

                        if($totreg == 1) { $horIni = $row['Tiempo'] ; }
                        else { $hrs = intval($row['Tiempo'] - $horIni); }

                        $velocidad = $row['Velocidad'] . 'Kms/h';

                        $combustible1 = $row['Combustible1'] . "% (" . $row['ltsCombustible1'] . " Lts)";
                        $combustible2 = $row['Combustible2'] . "% (" . $row['ltsCombustible2'] . " Lts)";
                        if($row['Combustible1'] == '0') { $combustible1 = '0%'; }
                        if($row['Combustible2'] == '0') { $combustible2 = '0%'; }

                        $res .= utf8_encode($row['Posicion']) . ',';                //1
                        $res .= $row['Fecha'] . ',';                   //0
                        $res .= $row['Motor'] . ',';
                        //$res .= utf8_encode($row['Nombre']) . ',';
                        $res .= $row['Tipo'] . ',';
                        $res .= $velocidad . ',';               //10
                        $res .= convertirHrsMins($row['detenido']) . ',';  //19
                        $res .= utf8_encode($row['PDI']) . ',';
                        $res .= $row['Dir'] . ',';
                        $res .= $row['Orientacion'] . ',';
                        $res .= $combustible1 . ',';
                        $res .= $combustible2 . ',';
                        $res .= $row['Temperatura'] . ',';
                        $res .= $row['Latitud'] . ',';                 //5
                        $res .= $row['Longitud'] . ',';                
                        $res .= $row['bateria'] . ',';    //17
                        $res .= $row['Kilometros'] . ',';  //15
                        $res .= $kms . ',';  //15
                        $res .= $row['Tiempo'] . ',';  //16
                        $res .= $hrs . ',';  //16
                        $res .= ' ,';  //16
                        ///$res .= '=hipervinculo("http://maps.google.com/maps?q=' + $row['Latitud'] + '%2c' + $row['Longitud'] + '")'  . PHP_EOL;
                        $res .= '=hipervinculo("http://maps.google.com/maps?q=' . $row['Latitud'] . '%2c' . $row['Longitud'] . '")'  . PHP_EOL;
                        //$res .= $row['Direccion'] . ',';


                        //$res .= $row['idAlarma'] . ',';  //18
                        //$res .= $row['duracionOff'] . ',';  //20
                        //$res .= $row['duracionOn'] . ',';  //21
                        //$res .= $row['idxFecha'] . ',';  //22
                        //$res .= $row['ltsCombustible1'] . ',';  //23
                        //$res .= $row['ltsCombustible2'] . PHP_EOL;  //24
                    }
                } while (sqlsrv_next_result($stmt));            
            }

            $res2 .= "REPORTE DE HISTORIAL" . PHP_EOL;
            $res2 .= "De:," . dateToXls($fechaIni) . PHP_EOL;
            $res2 .= "Hasta:," . dateToXls($fechaFin) . PHP_EOL . PHP_EOL;
            $res2 .= "DISPOSITIVO" . PHP_EOL;
            $res2 .= "Nombre:," . $dispositivo . PHP_EOL;
            $res2 .= $kms . " Kms. Recorridos" . PHP_EOL;
            $res2 .= $hrs . " Hrs. Trabajadas" . PHP_EOL . PHP_EOL;

            $res2 .= "POSICION";
            $res2 .= ",FECHA";
            $res2 .= ",MOTOR";
            $res2 .= ",TIPO";
            $res2 .= ",VELOCIDAD";
            $res2 .= ",DETENIDO";
            $res2 .= ",P.INT.";
            $res2 .= ",DIR.";
            $res2 .= ",ORIENTACION";
            $res2 .= ",FUEL 1";
            $res2 .= ",FUEL 2";
            $res2 .= ",TEMP";
            $res2 .= ",LATITUD";
            $res2 .= ",LONGITUD";
            $res2 .= ",BATERIA";
            $res2 .= ",ODO";
            $res2 .= ",KMS";
            $res2 .= ",HOR";
            $res2 .= ",HRS";
            $res2 .= ",DIRECCION";
            $res2 .= ",MAPA"  . PHP_EOL;

            $res = $res2 . $res;   
            
            fwrite($file, $res);
            //fwrite($file, "Otra más" . PHP_EOL);
            fclose($file);

            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            $salida['err'] = $err;
            $salida['totreg'] = $totreg;
            $salida['archivo'] = $archivo;
            //$salida['ultimoID'] = $nextID;
            $salida['res'] = "Se ha generado correctamente el archivo";

            echo json_encode($salida);
        break;
        case 'alarmasEliminar':
        	$err = 0;
            $res = '';
			
			$seleccionados = $_GET['sels'];
            
            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "NV_EliminarAlarmas ?,?";
            $params = array($seleccionados, $lg_idRaiz);
			$stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);


            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
	            $res = "Eliminados Correctamente";
			}
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            $salida['err'] = $err;
            $salida['res'] = $res;

            echo json_encode($salida);
        break;
        case 'ejecutarComando':
        	$err = 0; $tc = 0; $res = ''; $tt = ''; $ultimoID = -1; $rPuerto=1;
			//Parametros-----------------------
            $dispositivo = $_GET['imei'];
            $comando = $_GET['cmd'];
            $cmd1 = $_GET['cmd'];
            $rPuerto = 0; $rComando = ''; $rDelimitador=0;

            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "NV_Dispositivos_Comandos ?,?";
            $params = array($dispositivo, $comando);
            
			$stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
	            do {
	            	$tc = sqlsrv_num_fields($stmt);
	            	if($tc > 0) {
		                while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
		                    $rPuerto = $row['Puerto'];
		                    $rComando = utf8_encode($row['Comando']);
		                    $rDelimitador = $row['Delimitador'];
		                }
	            	}
	            } while (sqlsrv_next_result($stmt));            
			}
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            //echo "<hr>";
            //echo "puerto: " . $rPuerto;
            //echo "<br>puerto: " . $rComando;
            //echo "<br>deliminatador: " . $rDelimitador;
            //echo "<br>err: " . $err;
            //echo "<br>tc: " . $tc;
            //echo "<hr>";

            //$res = $rPuerto . " " . $rComando . " " . $rDelimitador;

            
            $res = 'Comando enviado correctamente';

            if($err == 0 && $tc > 0) {
            	$port = $rPuerto;
            	$comando = $rComando . chr($rDelimitador);

	            //Temporal, solo para los puertos 9195 de UDP
                if($port == 9195 || $port == 8888) {
                    //$fp = stream_socket_client("udp://192.168.0.104:" . $port, $errno, $errstr);
                    $fp = stream_socket_client("udp://" . $address . ":" . $port, $errno, $errstr);
                    if (!$fp) {
                        $res = "ERROR: $errno - $errstr";
                    } else {
                        fwrite($fp, $comando);
                        //echo fread($fp, 26);
                        fclose($fp);
                    }
                    //$address = '192.168.0.104';
                } else {
                    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
                    if ($socket === false) $err = 1;
                    if (socket_connect($socket, $address, $port) === false) { $err = 2; }
                    socket_write($socket, $comando, strlen($comando));
                    $out = socket_read($socket, 2048);
                    if (trim($out) == "Imei no localizado") {
                        $err=3;
                        $res=$out;
                    } else { 
                        $res =$out;
                        //Aqui cambiamos la bandera de status motor.
                        if($port == 7073 || 4842){
                            $ultimoID = actualizarStatusMotor($dispositivo, $cmd1);
                        }
                    }
                    socket_close($socket);
                }
            }

            $salida['err'] = $err;
            $salida['totcol'] = $tc;
            $salida['puerto'] = $port;
            $salida['comando'] = $comando;
            $salida['ultimoID'] = $ultimoID;
            $salida['res'] = $res;

            echo json_encode($salida);        	
        break;
        case 'verGeocerca':
            $err = 0; $res = ''; $totreg = 0; $nextID = 0; $tc=0;

            //Parametros-----------------------
            $dispositivo = $_GET['imei'];

            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "Dispositivo_geocerca_ver ?";
            $params = array($dispositivo);
            
            $stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);


            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                do {
                    $tc = sqlsrv_num_fields($stmt);
                    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                        $totreg++;
                        $res .= $row['geocerca'];
                    }
                } while (sqlsrv_next_result($stmt));
            }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            $salida['err'] = $err;
            $salida['totreg'] = $totreg;
            $salida['totcol'] = $tc;
            $salida['res'] = $res;

            echo json_encode($salida);
        break;
        case "geocercas":
            $err = 0; $res = ''; $totreg = 0; $tc=0;
    
            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "p_geocercas_administrar ?";
            $params = array($lg_idRaiz);
            $stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                do {
                    $tc = sqlsrv_num_fields($stmt);
                    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                        $totreg++;
                        $res .= $row['Id'] . '|';
                        $res .= utf8_encode($row['Nombre Geocerca']) . '|';
                        $res .= utf8_encode($row['Comentarios']) . '|';
                        $res .= $row['Fecha'] . '|';
                        $res .= $row['Geocerca'] . '|';
                        $res .= $row['idRaiz'] . ';';
                    }
                } while (sqlsrv_next_result($stmt));
            }
            
            /*sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);*/

            $salida['err'] = $err;
            $salida['totreg'] = $totreg;
            //$salida['ultimoID'] = $nextID;
            $salida['res'] = $res;

            echo json_encode($salida);
        break;
        case "recolecciones":
            $err = 0; $res = ''; $totreg = 0; $tc=0;
    
            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "p_recolecciones_administrar ?";
            $params = array($lg_idRaiz);
            $stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                do {
                    $tc = sqlsrv_num_fields($stmt);
                    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                        $totreg++;
                        $res .= $row['id'] . '|';
                        $res .= utf8_encode($row['nombre']) . '|';
                        $res .= utf8_encode($row['comentarios']) . '|';
                        $res .= $row['fecha'] . '|';
                        $res .= $row['recoleccion'] . '|';
                        $res .= $row['idraiz'] . ';';
                    }
                } while (sqlsrv_next_result($stmt));
            }
            
            /*sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);*/

            $salida['err'] = $err;
            $salida['totreg'] = $totreg;
            //$salida['ultimoID'] = $nextID;
            $salida['res'] = $res;

            echo json_encode($salida);
        break;
        case "guardarGeocerca":
            $err = 0; $res = ''; $totreg = 1; $tc=0;
            $id = $_POST['txtGeoId'];
            $nombre = $lg_idRaiz . "_" . $_POST['txtGeoNombre'];
            $comentarios = $_POST['txtGeoComent'];
            $datos = $_POST['txtGeoDatos'];
            $nuevo = $_POST['nuevo'];
            $alertaVelocidad = 0;
            $maxVelocidad = 'MAX';

            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            //EXEC NV_ActualizarGeocerca '" & txtId.Text & "','" & PuntosInvertidos & "','" & Puntos
            
            if($nuevo == 1) {
                $sql = "NV_Geocercas_Guardar ?,?,?,?,?,?";
                $params = array($lg_idRaiz,$nombre,$datos,$comentarios,$alertaVelocidad,$maxVelocidad);
            } else { 
                $sql = "NV_Geocercas_Actualizar ?,?,?,?,?";
                $params = array($id,$datos,$comentarios,$alertaVelocidad,$maxVelocidad);
            }
            
            $stmt = sqlsrv_query($conn, $sql, $params);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
            }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            $salida['err'] = $err;
            $salida['totreg'] = $totreg;
            $salida['res'] = $res;

            echo json_encode($salida);
        break;
        case "guardarRecoleccion":
            $err = 0; $res = ''; $totreg = 1; $tc=0;
            $id = $_POST['txtRecId'];
            $nombre = $_POST['txtRecNombre'];
            $comentarios = $_POST['txtRecComent'];
            $datos = $_POST['txtRecDatos'];
            if($id == '') { $id=0; }

            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            //EXEC NV_ActualizarGeocerca '" & txtId.Text & "','" & PuntosInvertidos & "','" & Puntos
            
            $sql = "EXEC p_recoleccion_guardar ?,?,?,?,?";
            $params = array($id, $lg_idRaiz,$nombre,$datos,$comentarios);
            
            $stmt = sqlsrv_query($conn, $sql, $params);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
            }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            $salida['err'] = $err;
            $salida['totreg'] = $totreg;
            $salida['res'] = $res;

            echo json_encode($salida);
        break;
        case 'geocercasEliminar':
            $err = 0;
            $res = '';
            
            $seleccionados = $_GET['sels'];
            //NV_Geocercas_Eliminar] @Ids VARCHAR(MAX),@Raiz INT as
            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "NV_Geocercas_Eliminar ?,?";
            $params = array($seleccionados, $lg_idRaiz);
            $stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                $res = "Eliminados Correctamente";
            }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            $salida['err'] = $err;
            $salida['res'] = $res;

            echo json_encode($salida);        
        break;
        case 'guardarAsignarGeocerca':
            $err = 0;
            $res = '';

            $idDispositivo = $_GET['idDispositivo'];
            $idGeocerca = $_GET['idGeocerca'];
            $alarmaGeocerca = $_GET['alarmaGeocerca'];
            $ApagadoGeocerca = $_GET['apagadoGeocerca'];
            
            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "p_guardarAsignarGeocerca_p1 ?,?,?,?,?,?";
            $params = array($lg_idRaiz,$idDispositivo,$idGeocerca,$alarmaGeocerca,$ApagadoGeocerca,$lg_verTodos);
            $stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                $res = "Almacenado Correctamente";
            }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            $salida['err'] = $err;
            $salida['res'] = $res;

            echo json_encode($salida);        
        break;
        case 'datosUsuario':
            $err = 0; $res = ''; $totreg = 0; $tc=0;

            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            //EXEC NV_ActualizarGeocerca '" & txtId.Text & "','" & PuntosInvertidos & "','" & Puntos
            
            $sql = "NV_Usuarios_ObtenerDatos ?";
            $params = array($lg_usuario);
            $stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                do {
                    $tc = sqlsrv_num_fields($stmt);
                    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                        $totreg++;
                        $_SESSION['DU1'] = $row['ID Usuario'];
                        $_SESSION['DU2'] = $row['Tipo Usuario'];
                        $_SESSION['DU3'] = $row['ID Raiz'];
                        $_SESSION['DU4'] = $row['Administrador'];
                        $_SESSION['DU5'] = $row['Tipo Cliente'];
                        $_SESSION['DU6'] = $row['Link'];
                        $_SESSION['DU7'] = $row['Logo'];
                        $_SESSION['DU8'] = $row['Titulo'];
                        $_SESSION['DU9'] = $row['Usuario'];
                        $_SESSION['DU10'] = $row['Nombre'];
                        $_SESSION['DU11'] = $row['Apellido'];
                        $_SESSION['DU12'] = $row['Razon Social'];
                        $_SESSION['DU13'] = $row['Fecha Alta'];
                        $_SESSION['DU14'] = $row['Movil'];
                        $_SESSION['DU15'] = $row['Correo Electronico'];
                        $_SESSION['DU16'] = $row['Notificacion Panico'];
                        $_SESSION['DU17'] = $row['Notificacion Fuente'];
                        $_SESSION['DU18'] = $row['Notificacion Geocerca'];
                        $_SESSION['DU19'] = $row['Notificacion Velocidad'];
                        $_SESSION['DU20'] = $row['Permiso Emergencia'];
                        $_SESSION['DU21'] = $row['Permiso Motor'];
                        $_SESSION['DU22'] = $row['Permiso Historial'];
                        $_SESSION['DU23'] = $row['Permiso Mensaje'];
                        $_SESSION['DU24'] = $row['Permiso Beep'];
                        $_SESSION['DU25'] = $row['Permiso Posicion'];
                        $_SESSION['DU26'] = $row['Permiso Armar'];
                        $_SESSION['DU27'] = $row['Permiso Fotografia'];
                        $_SESSION['DU28'] = $row['Permiso Administrar Geocerca'];
                        $_SESSION['DU29'] = $row['Permiso Velocidad'];
                        $_SESSION['DU30'] = $row['Permiso Movimiento'];
                        $_SESSION['DU31'] = $row['Permiso Administrar Usuarios'];
                        $_SESSION['DU32'] = $row['Permiso Administrar Dispositivos'];
                        $_SESSION['DU33'] = $row['Permiso Administrar Choferes'];
                        $_SESSION['DU34'] = $row['Permiso Administrar Puntos'];
                        $_SESSION['DU35'] = $row['Permiso Rutas'];
                        $_SESSION['DU36'] = $row['Permiso Velocidad Sin Intervalos'];
                        $_SESSION['DU37'] = $row['Usuario Email'];
                        $_SESSION['DU38'] = $row['Password Email'];
                        $_SESSION['DU39'] = $row['Puerto Email'];
                        $_SESSION['DU40'] = $row['SSL Email'];
                        $_SESSION['DU41'] = $row['Servidor Email'];
                        $_SESSION['DU42'] = $row['Nombre Alarma'];
                        $_SESSION['DU43'] = $row['Sonido Activado'];
                        $_SESSION['DU44'] = $row['Estilo Sistema'];
                        $_SESSION['DU45'] = $row['Mostrar Geocerca'];
                        $_SESSION['DU46'] = $row['Mostrar StreetView'];
                        $_SESSION['DU47'] = $row['Voz Activada'];
                        $_SESSION['DU48'] = $row['Timer'];
                        /*$res .= $row['ID Usuario'] . ',';
                        $res .= $row['Tipo Usuario'] . ',';
                        $res .= $row['ID Raiz'] . ',';
                        $res .= $row['Administrador'] . ',';
                        $res .= $row['Tipo Cliente'] . ',';
                        $res .= $row['Link'] . ',';
                        $res .= $row['Logo'] . ',';
                        $res .= $row['Titulo'] . ',';
                        $res .= $row['Usuario'] . ',';
                        $res .= $row['Nombre'] . ',';
                        $res .= $row['Apellido'] . ',';
                        $res .= $row['Razon Social'] . ',';
                        $res .= $row['Fecha Alta'] . ',';
                        $res .= $row['Movil'] . ',';
                        $res .= $row['Correo Electronico'] . ',';
                        $res .= $row['Notificacion Panico'] . ',';
                        $res .= $row['Notificacion Fuente'] . ',';
                        $res .= $row['Notificacion Geocerca'] . ',';
                        $res .= $row['Notificacion Velocidad'] . ',';
                        $res .= $row['Permiso Emergencia'] . ',';
                        $res .= $row['Permiso Motor'] . ',';
                        $res .= $row['Permiso Historial'] . ',';
                        $res .= $row['Permiso Mensaje'] . ',';
                        $res .= $row['Permiso Beep'] . ',';
                        $res .= $row['Permiso Posicion'] . ',';
                        $res .= $row['Permiso Armar'] . ',';
                        $res .= $row['Permiso Fotografia'] . ',';
                        $res .= $row['Permiso Administrar Geocerca'] . ',';
                        $res .= $row['Permiso Velocidad'] . ',';
                        $res .= $row['Permiso Movimiento'] . ',';
                        $res .= $row['Permiso Administrar Usuarios'] . ',';
                        $res .= $row['Permiso Administrar Dispositivos'] . ',';
                        $res .= $row['Permiso Administrar Choferes'] . ',';
                        $res .= $row['Permiso Administrar Puntos'] . ',';
                        $res .= $row['Permiso Rutas'] . ',';
                        $res .= $row['Permiso Velocidad Sin Intervalos'] . ',';
                        $res .= $row['Usuario Email'] . ',';
                        $res .= $row['Password Email'] . ',';
                        $res .= $row['Puerto Email'] . ',';
                        $res .= $row['SSL Email'] . ',';
                        $res .= $row['Servidor Email'] . ',';
                        $res .= $row['Nombre Alarma'] . ',';
                        $res .= $row['Sonido Activado'] . ',';
                        $res .= $row['Estilo Sistema'] . ',';
                        $res .= $row['Mostrar Geocerca'] . ',';
                        $res .= $row['Mostrar StreetView'] . ',';
                        $res .= $row['Voz Activada'] . ',';
                        $res .= $row['Timer'] . ',';*/
                    }
                } while (sqlsrv_next_result($stmt));
                $res = "Cargado";
            }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            $salida['err'] = $err;
            $salida['totreg'] = $totreg;
            $salida['res'] = $res;

            echo json_encode($salida);
        break;
        case 'datosUsuarioGral':
            if(leerDatosUsuario($lg_usuario) == 1){
                $res = '';
                $res .= $_SESSION['DU12'] . ','; //Razon Social
                $res .= $lg_usuario . ',';         //Usuario
                $res .= $_SESSION['DU10'] . ','; //Nombre
                $res .= $_SESSION['DU11'] . ','; //Apellido
                $res .= $_SESSION['DU14'] . ','; //Movil
                $res .= $_SESSION['DU13'] . ','; //Fecha Alta
                $res .= $_SESSION['DU15'] . ','; //Correo Electronico

                $res .= $_SESSION['DU37'] . ','; //Email App
                //$res .= $_SESSION['DU38'] . ','; //Pass App
                $res .= $_SESSION['DU41'] . ','; //Servidor
                $res .= $_SESSION['DU39'] . ','; //Puerto
                $res .= $_SESSION['DU40'] . ','; //SSL
                
                $res .= $_SESSION['DU43'] . ','; //Sonido Activado
                $res .= $_SESSION['DU42'] . ','; //Nombre Alarma

                $res .= $_SESSION['DU45'] . ','; //MGeocerca
                $res .= $_SESSION['DU46'] . ','; //MStreetView

                $res .= $_SESSION['DU47'] . ','; //Reproducir Voz

                $res .= $_SESSION['DU48'] . ','; //Timer
            }
        
            $salida['err'] = 0;
            $salida['res'] = $res;
            echo json_encode($salida);
        break;
        case 'datosUsuario_guardar':
            $err = 0;
            $res = '';
            $nombre = $_GET['txtDUNombre'];  $apellido = $_GET['txtDUApellido'];  $movil = $_GET['txtDUMovil'];
            $correo = $_GET['txtDUEmail'];  $nPanico = 1;  $nFuente = 1;  $nVelocidad = 1;  $nGeocerca = 1;  
            if(isset($_GET['chkDUMGeocerca'])) { $mostrarGeocerca = 1; } else { $mostrarGeocerca = 0; };
            if(isset($_GET['chkDUSonidoActivado'])) { $sonidoActivado = 1; } else { $sonidoActivado = 0; };
            $nombreAlarma = $_GET['txtDUNombreAlarma'];
            if(isset($_GET['chkDUStreetView'])) { $mostrarStreetView = 1; } else { $mostrarStreetView = 0; };
            if(isset($_GET['chkDUReproducirVoz'])) { $vozActivada = 1; } else { $vozActivada = 0; };
            $valorTimer = $_GET['txtDUTimer'];

            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "NV_Usuarios_GuardarDatos ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?";
            //@Usuario,@Nombre,@Apellido @Movil,@Correo,@Npanico,@NFuente,@NVelocidad,@NGeocerca,@MostrarGeocerca,@SonidoActivado,@NombreAlarma,@MostrarStreetView,@VozActivada,@ValorTimer
            $params = array($lg_usuario, $nombre, $apellido, $movil, $correo, $nPanico, $nFuente, $nVelocidad, $nGeocerca, $mostrarGeocerca, $sonidoActivado, $nombreAlarma, $mostrarStreetView, $vozActivada, $valorTimer);
            $stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                $_SESSION['DU10'] = $nombre;
                $_SESSION['DU11'] = $apellido;
                $_SESSION['DU14'] = $movil;
                $_SESSION['DU15'] = $correo;
                $_SESSION['DU42'] = $nombreAlarma;
                $_SESSION['DU43'] = $sonidoActivado;
                $_SESSION['DU45'] = $mostrarGeocerca;
                $_SESSION['DU46'] = $mostrarStreetView;
                $_SESSION['DU47'] = $vozActivada;
                $_SESSION['DU48'] = $valorTimer;
                //if(leerDatosUsuario($lg_usuario) == 1) {

                    $res = "Guardado Correctamente";
                //}
            }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            $salida['err'] = $err;
            $salida['res'] = $res;

            echo json_encode($salida);  
        break;
        case 'datosDispositivo':
            $err = 0; $res = ''; $totreg = 0; $tc=0;
            $imei = $_GET['imei'];

            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            //EXEC NV_ActualizarGeocerca '" & txtId.Text & "','" & PuntosInvertidos & "','" & Puntos
            
            $sql = "p_Dispositivo_Info ?";
            $params = array($imei);
            $stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                do {
                    $tc = sqlsrv_num_fields($stmt);
                    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                        $totreg++;
                        $res .= utf8_encode($row['Nombre Dispositivo']) . '|'; //0
                        $res .= $row['Tipo Dispositivo'] . '|';                 //1
                        $res .= utf8_encode($row['Razon Social']) . '|';                     //2
                        $res .= $row['Velocidad Maxima'] . '|';                 //3
                        $res .= $row['Alarma ACC'] . '|';           
                        $res .= $row['Alarma Panico'] . '|';                    //5
                        $res .= $row['Alarma Bateria'] . '|';
                        $res .= $row['Alarma Velocidad'] . '|';                 //7
                        $res .= $row['Alarma Geocerca'] . '|';
                        $res .= $row['Alarma PDI'] . '|';                       //9
                        $res .= $row['Apagado Geocerca'] . '|';                 //10
                        $res .= utf8_encode($row['Nombre Geocerca']) . '|';
                        $res .= $row['Desbloqueo Temporal'] . '|';              //12
                        $res .= utf8_encode($row['Nombre Ruta']) . '|';
                        $res .= $row['Apagado Movimiento'] . '|';               //14
                        $res .= utf8_encode($row['marca']) . '|';
                        $res .= utf8_encode($row['modelo']) . '|';
                        $res .= utf8_encode($row['anio']) . '|';
                        $res .= utf8_encode($row['placa']) . '|';
                        $res .= utf8_encode($row['noserie']) . '|';             //19
                        $res .= utf8_encode($row['tipoVehiculo']) . '|';             //20
                        $res .= utf8_encode($row['chofer']) . '|';             //21
                        $res .= $row['alarmaDetenido'] . '|';             //22
                        $res .= $row['alarmaDetenidoMins'] . '|';             //23
                        $res .= $row['alarmaDetenidoRepetir'] ."|";            //24
                        $res .= $row['IdRuta'] ."|";               //25
                        $res .= $row['alarmaRuta'] . "|";               //26
                        $res .= $row['alarmaPrimerOn'] . "|";
                        $res .= $row['alarmaOnOff'] . "|";
                        $res .= $row['alarmaOFLun'] . "|";
                        $res .= $row['alarmaOFMar'] . "|";                  //30
                        $res .= $row['alarmaOFMie'] . "|";
                        $res .= $row['alarmaOFJue'] . "|";
                        $res .= $row['alarmaOFVie'] . "|";
                        $res .= $row['alarmaOFSab'] . "|";
                        $res .= $row['alarmaOFDom'] . "|";                        //35
                        $res .= $row['alarmaDetenidoFueraPdi'] . "|";                        //36
                        $res .= $row['alarmaNivelBateria'];                        //37
                    }
                } while (sqlsrv_next_result($stmt));
            }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            $salida['err'] = $err;
            $salida['totreg'] = $totreg;
            $salida['res'] = $res;

            echo json_encode($salida);
        break;
        case "tiposDispositivos":
            $err = 0; $res = ''; $totreg = 0; $tc=0;
    
            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "NV_TiposDispositivos_Administrar";
            //$params = array($lg_idRaiz);
            $stmt = sqlsrv_query($conn, $sql);
            //var_dump($conn, $sql);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                do {
                    $tc = sqlsrv_num_fields($stmt);
                    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                        $totreg++;
                        $res .= $row['Id'] . '|';
                        $res .= utf8_encode($row['Tipo']) . ';';
                    }
                } while (sqlsrv_next_result($stmt));
            }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            $salida['err'] = $err;
            $salida['totreg'] = $totreg;
            //$salida['ultimoID'] = $nextID;
            $salida['res'] = $res;

            echo json_encode($salida);
        break;
        case "rutas":
            $err = 0; $res = ''; $totreg = 0; $tc=0;
    
            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "NV_Rutas_Administrar ?";
            $params = array($lg_idRaiz);
            $stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                do {
                    $tc = sqlsrv_num_fields($stmt);
                    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                        $totreg++;
                        $res .= $row['Id'] . '|';
                        $res .= utf8_encode($row['Nombre']) . ';';
                    }
                } while (sqlsrv_next_result($stmt));
            }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            $salida['err'] = $err;
            $salida['totreg'] = $totreg;
            //$salida['ultimoID'] = $nextID;
            $salida['res'] = $res;

            echo json_encode($salida);
        break;
        case 'datosDispositivo_guardar1':
            $err = 0;
            $res = '';
            
            $imei = $_GET['txtInfoDispImei'];
            $nombre = $_GET['txtInfoDispNombre'];
            $chofer = $_GET['txtInfoDispChofer'];
            $tipoDispositivo = $_GET['cmbInfoDispTipoDisp'];

            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "p_Dispositivo_Guardar ?,?,?,?";
            //@Dispositivo,@Nombre,@Tipo,@Panico,@Bateria,@Velocidad,@Geocerca,@Apagado,@VelocidadMax,@NombreGeocerca,@Desbloqueotemporal,@NombreRuta,@ApagadoMovimiento,@ACC,@PDI
            $params = array($imei, $nombre, $tipoDispositivo, $chofer);
            $stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                    $res = "Guardado Correctamente";
            }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);



            $salida['err'] = $err;
            $salida['res'] = $res;

            echo json_encode($salida);  
        break;
        case 'datosDispositivo_guardar2':
            $err = 0;
            $res = '';
            $imei = $_GET['txtInfoDispImei'];
            $velocidad = $_GET['txtInfoDispVelocidadMax'];
            
            $alarmaDetenido = $_GET['chkInfoDispAlarmaDetenido'];
            $alarmaDetenidoMins = $_GET['txtInfoDispAlarmaDetenidoMins'];
            $alarmaDetenidoRepetir = $_GET['chkInfoDispAlarmaDetenidoRepetir'];
            $alarmaRutas = $_GET['chkInfoDispAlarmaRutas'];
            $optRuta = $_GET['optInfoDispRutas'];
            $alarmaPDI = $_GET['chkInfoDispAlarmaPDI'];
            

            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "p_Dispositivo_Guardar_Conf ?,?,?,?,?,?,?,?";
            $params = array($imei, $velocidad,$alarmaDetenido,$alarmaDetenidoMins,$alarmaDetenidoRepetir,$alarmaRutas,$optRuta,$alarmaPDI);
            $stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                    $res = "Guardado Correctamente";
            }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);



            $salida['err'] = $err;
            $salida['res'] = $res;

            echo json_encode($salida);  
        break;
        case 'datosDispositivo_guardar3':
            $err = 0;
            $res = '';
            $imei = $_GET['imei'];
            $campo = $_GET['campo'];            
            $val1 = $_GET['val1'];
            $val2 = $_GET['val2'];
            $val3 = $_GET['val3'];
            $val4 = $_GET['val4'];
            $val5 = $_GET['val5'];
            $val6 = $_GET['val6'];
            $val7 = $_GET['val7'];
            $val8 = $_GET['val8'];
            

            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "p_Dispositivo_Guardar_Conf3 ?,?,?,?,?,?,?,?,?,?,?";
            $params = array($lg_idRaiz,$imei,$campo,$val1,$val2,$val3,$val4,$val5,$val6,$val7,$val8);
            $stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                    $res = "Guardado Correctamente";
            }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);



            $salida['err'] = $err;
            $salida['res'] = $res;

            echo json_encode($salida);  
        break;        
        case 'datosDispositivo_guardar':
            $err = 0;
            $res = '';
            
            $imei = $_GET['txtCDImei'];
            $nombre = $_GET['txtCDNombre'];
            $tipoDispositivo = $_GET['txtCDTipoDisp'];
            if(isset($_GET['chkCDBPanico'])) { $panico = 1; } else { $panico = 0; };
            if(isset($_GET['chkCDBateria'])) { $bateria = 1; } else { $bateria = 0; };
            if(isset($_GET['chkCDVelocidad'])) { $velocidad = 1; } else { $velocidad = 0; };
            if(isset($_GET['chkCDGeocerca'])) { $geocerca = 1; } else { $geocerca = 0; };
            if(isset($_GET['chkCDMotorGeocerca'])) { $geocercaApagado = 1; } else { $geocercaApagado = 0; };
            $velocidadMax = $_GET['txtCDVelocidad'];
            $nomGeocerca = $_GET['tmpNomGeocerca'];        
            if(isset($_GET['chkCDTemporal'])) { $temporal = 1; } else { $temporal = 0; };
            $nomRuta = $_GET['tmpNomRuta'];
            if(isset($_GET['chkCDMovimiento'])) { $movimiento = 1; } else { $movimiento = 0; };
            if(isset($_GET['chkCDACC'])) { $ACC = 1; } else { $ACC = 0; };
            if(isset($_GET['chkCDPDI'])) { $PDI = 1; } else { $PDI = 0; };


            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "NV_Dispositivo_Guardar ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?";
            //@Dispositivo,@Nombre,@Tipo,@Panico,@Bateria,@Velocidad,@Geocerca,@Apagado,@VelocidadMax,@NombreGeocerca,@Desbloqueotemporal,@NombreRuta,@ApagadoMovimiento,@ACC,@PDI
            $params = array($imei, $nombre, $tipoDispositivo, $panico, $bateria, $velocidad, $geocerca, $geocercaApagado, $velocidadMax, $nomGeocerca, $temporal, $nomRuta, $movimiento, $ACC, $PDI);
            $stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                    $res = "Guardado Correctamente";
            }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);



            $salida['err'] = $err;
            $salida['res'] = $res;

            echo json_encode($salida);  
        break;
        case "guardarPuntoInteres":
            $err = 0; $res = ''; $totreg = 0; $tc=0;

            $id = $_GET['txtPIntId'];
            $nombre = utf8_decode($_GET['txtPIntNombre']);
            $comentarios = utf8_decode($_GET['txtPIntComent']);
            $datos = $_GET['txtPIntDatos'];
            $nuevo = $_GET['nuevo'];
            $imagen=$_GET['optPDIImg'];

            $datos = explode(",", $datos);

            $lat = $datos[0];
            $long = $datos[1];
            $radio = $datos[2];


            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            //EXEC NV_ActualizarGeocerca '" & txtId.Text & "','" & PuntosInvertidos & "','" & Puntos
            
            if($nuevo == 1) {
                $sql = "NV_PDI_Agregar ?,?,?,?,?,?,?";
                $params = array($lg_idRaiz,$nombre,$comentarios,$lat,$long,$radio,$imagen);
            } else { 
                $sql = "NV_PDI_Actualizar ?,?,?,?,?,?,?";
                $params = array($lg_idRaiz,$id,$lat,$long,$radio,$imagen,$comentarios);
            }
            

            $stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                do {
                    $tc = sqlsrv_num_fields($stmt);
                    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                        $totreg++;
                        $res .= $row['RESULTADO'];
                    }
                } while (sqlsrv_next_result($stmt));
            }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            if(trim($res) == '1' || trim($res) == 1 ) { $res = 'Almacenado correctamente'; }

            $salida['err'] = $err;
            $salida['totreg'] = $totreg;
            $salida['res'] = $res;

            echo json_encode($salida);
        break;
        case "puntoInteres":
            $err = 0; $res = ''; $totreg = 0; $tc=0;
    
            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "NV_PDI_Buscar ?";
            $params = array($lg_idRaiz);
            $stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                do {
                    $tc = sqlsrv_num_fields($stmt);
                    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                        $totreg++;
                        $res .= $row['Id'] . '|';
                        $res .= utf8_encode($row['Nombre']) . '|';
                        $res .= utf8_encode($row['Comentarios']) . '|';
                        $res .= $row['Latitud'] . '|';
                        $res .= $row['Longitud'] . '|';
                        $res .= $row['Radio'] . '|';
                        $res .= utf8_encode($row['Imagen']) . ';';
                        //$res .= $row['fechaCreacion'] . ';';
                    }
                } while (sqlsrv_next_result($stmt));
            }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            $salida['err'] = $err;
            $salida['totreg'] = $totreg;
            //$salida['ultimoID'] = $nextID;
            $salida['res'] = $res;

            echo json_encode($salida);
        break;
        case 'puntoInteresEliminar':
            $err = 0;
            $res = '';
            
            $seleccionados = $_GET['sels'];
            //NV_Geocercas_Eliminar] @Ids VARCHAR(MAX),@Raiz INT as
            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "NV_PDI_Eliminar ?,?";
            $params = array($seleccionados, $lg_idRaiz);
            $stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                $res = "Eliminados Correctamente";
            }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            $salida['err'] = $err;
            $salida['res'] = $res;

            echo json_encode($salida);        
        break;
        case 'avisos':
            $err = 0; $res = ''; $totreg = 0; $tc=0;
            $bienvenida = 0;
            $urgen1 = 0;
            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            
            $sql = "p_avisos ?";
            $params = array($lg_idUsuario);
            $stmt = sqlsrv_query($conn, $sql, $params);
            

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                do {
                    $tc = sqlsrv_num_fields($stmt);
                    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                        $totreg++;
                        $bienvenida = $row['bienvenida'];
                        $urgen1 = $row['urgen1'];
                    }
                } while (sqlsrv_next_result($stmt));
                $res = 1;
            } else { $res = 0; }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);
            
            $salida['totreg'] = $totreg;
            $salida['err'] = $err;
            $salida['aVisitaGuiada'] = $bienvenida;
            $salida['aUrgen1'] = $urgen1;
            $salida['res'] = $sql;

            echo json_encode($salida);
        break;
        case 'marcarAviso':
            $err = 0; $res = ''; $totreg = 0; $tc=0;
            $campo = $_GET["campo"];
            $valor = $_GET["valor"];
            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            
            $sql = "p_marcarAviso ?,?,?";
            $params = array($lg_idUsuario,$campo,$valor);
            $stmt = sqlsrv_query($conn, $sql, $params);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            $salida['err'] = $err;

            echo json_encode($salida);        
        break;
        case 'guardarUsuario':
            $err = 0;
            $ret = 0;
            $existe = 0;
            $nombre = $_GET["txtUserNombre"];
            $apellido = $_GET["txtUserApellido"];
            $email = $_GET["txtUserEmail"];
            $usuario = $_GET["txtUserUsuario"];
            $pass1 = $_GET["txtUserPass"];

            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "p_usuario_guardar ?,?,?,?,?,?,?";
            
            $params = array( 
                array($lg_idRaiz, SQLSRV_PARAM_IN),
                array($nombre, SQLSRV_PARAM_IN),
                array($apellido, SQLSRV_PARAM_IN),
                array($email, SQLSRV_PARAM_IN),
                array($usuario, SQLSRV_PARAM_IN),
                array($pass1, SQLSRV_PARAM_IN),
                array($ret, SQLSRV_PARAM_INOUT)
            );

            $stmt = sqlsrv_query($conn, $sql, $params);
            if($stmt === false ){
                $err = 1;
            }else{
                sqlsrv_next_result($stmt);
                $existe = $ret;
            }


            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            //$lg_idRaiz

            $salida['err'] = $err;
            $salida['existe'] = $existe;
            echo json_encode($salida);
        break;
        case "leerUsuarios":
            $err = 0; $res = ''; $totreg = 0; $tc=0;
    
            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "p_usuario_listar ?";
            $params = array($lg_idRaiz);
            $stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                do {
                    $tc = sqlsrv_num_fields($stmt);
                    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                        $totreg++;
                        $res .= $row['Id'] . '|';
                        $res .= utf8_encode($row['Usuario']) . '|';
                        $res .= utf8_encode($row['Nombre']) . '|';
                        $res .= utf8_encode($row['Apellido']) . '|';
                        $res .= utf8_encode($row['CorreoElectronico']) . ';';
                    }
                } while (sqlsrv_next_result($stmt));
            }
            
            /*sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);*/

            $salida['err'] = $err;
            $salida['totreg'] = $totreg;
            //$salida['ultimoID'] = $nextID;
            $salida['res'] = $res;

            echo json_encode($salida);
        break;
        case 'usuarioEliminar':
            $err = 0;
            $res = '';
            
            $seleccionados = $_GET['sels'];
            //NV_Geocercas_Eliminar] @Ids VARCHAR(MAX),@Raiz INT as
            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "p_usuario_eliminar ?,?";
            $params = array($lg_idRaiz,$seleccionados);
            $stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                $res = "Eliminados Correctamente";
            }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            $salida['err'] = $err;
            $salida['res'] = $res;

            echo json_encode($salida);        
        break;
        case 'usuarioDispositivos':
            $err = 0;
            $res = '';
            $totreg = 0;
            $usuario = $_GET['selUsu'];

            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "p_usuario_dispositivos ?,?";
            $params = array($lg_idRaiz, $usuario);
            $stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                do {
                    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                        $totreg++;
                        //$res .= $row['Nombre'] . ',' . $row['Descripcion'] . $row['Id'] . ',' . $row['Modelo'] .  '|';
                        $res .= utf8_encode($row['Id']) 
                        . ',' . utf8_encode($row['NombreCorto']) 
                        . ',' . $row['asignado'] . '|';
                    }
                } while (sqlsrv_next_result($stmt));            
            }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            $salida['err'] = $err;
            $salida['totreg'] = $totreg;
            $salida['res'] = $res;

            echo json_encode($salida);
        break;
        case 'usuarioDispositivoAsignar':
            $err = 0;
            $res = '';
            
            $idDispositivo = $_GET['idDispositivo'];
            $usuario = $_GET['usuario'];
            $asignar = $_GET['asignar'];
            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "p_usuario_disp_asignar ?,?,?,?";
            $params = array($lg_idRaiz, $idDispositivo,$usuario,$asignar);
            $stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                $res = "Realizado Correctamente";
            }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            $salida['err'] = $err;
            $salida['res'] = $res;

            echo json_encode($salida);        
        break;
        case "usuarioPermisosLeer":
            $err = 0; $res = ''; $totreg = 0; $tc=0;
            $usuario = $_GET["usuario"];
            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "p_usuario_permisos_leer ?,?";
            $params = array($lg_idRaiz,$usuario);
            $stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                do {
                    $tc = sqlsrv_num_fields($stmt);
                    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                        $totreg++;
                        $res .= $row['PPosicion'] . '|';
                        $res .= $row['PDetenerMotor'] . '|';
                        $res .= $row['PHistorial'] . '|';
                        $res .= $row['PAUsuarios'] . '|';
                        $res .= $row['PADispositivos'] . '|';
                        $res .= $row['PAGeocercas'] . '|';
                        $res .= $row['PAPuntos'] . '|';
                        $res .= $row['PARutas'] . '';
                    }
                } while (sqlsrv_next_result($stmt));
            }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            $salida['err'] = $err;
            $salida['totreg'] = $totreg;
            //$salida['ultimoID'] = $nextID;
            $salida['res'] = $res;

            echo json_encode($salida);
        break;
        case 'guardarUsuarioChkPermisos':
            $err = 0;
            $res = '';
            
            $usuario = $_GET['usuario'];
            $PPosicion = $_GET['chkPPosicion'];
            $PDetenerMotor = $_GET['chkPDetenerMotor'];
            $PHistorial = $_GET['chkPHistorial'];
            $PAUsuarios = $_GET['chkPAUsuarios'];
            $PADispositivos = $_GET['chkPADispositivos'];
            $PAGeocercas = $_GET['chkPAGeocercas'];
            $PAPDI = $_GET['chkPAPDI'];
            $PARutas = $_GET['chkPARutas'];
            
            

            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "p_usuario_permisos_editar ?,?,?,?,?,?,?,?,?,?";
            $params = array($lg_idRaiz,$usuario,$PPosicion,$PDetenerMotor,$PHistorial,$PAUsuarios,$PADispositivos,$PAGeocercas,$PAPDI,$PARutas);
            $stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                $res = "Realizado Correctamente";
            }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            $salida['err'] = $err;
            $salida['res'] = $res;

            echo json_encode($salida);        
        break;
        case "usuarioOpcionesLeer":
            $err = 0; $res = ''; $totreg = 0;
            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "p_usuario_opciones_leer ?,?";
            $params = array($lg_idRaiz,$lg_idUsuario);
            $stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                do {
                    $tc = sqlsrv_num_fields($stmt);
                    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                        $totreg++;
                        $res .= $row['sonidoActivado'] . '|';
                        $res .= $row['mostrarInfo'] . '|';
                        $res .= $row['animarDisp'] . '|';
                        $res .= $row['rutaNoLineal'] . '|';
                        $res .= $row['leyendaPDI'] . '|';
                        $res .= $row['tipoMapa'] . '';
                    }
                } while (sqlsrv_next_result($stmt));
            }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            $salida['err'] = $err;
            $salida['totreg'] = $totreg;
            $salida['res'] = $res;

            echo json_encode($salida);
        break;
        case "usuarioOpcionesEditar":
            $err = 0; $res = '';
            $campo = $_GET["campo"];
            $valor=  $_GET["valor"];
            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "p_usuario_opciones_editar ?,?,?,?";
            $params = array($lg_idRaiz,$lg_idUsuario,$campo,$valor);
            $stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                if($campo == 'tipoMapa') { $_SESSION['tipoMapa'] = $valor; }
                $res = "Realizado Correctamente";
            }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            $salida['err'] = $err;
            $salida['res'] = $res;

            echo json_encode($salida);     
        break;
        case 'verReporteHoras':
            $err = 0; $res = ''; $totreg = 0;
            $unidad = $_GET["unidad"];
            $fechaIni = $_GET["fechaIni"];
            $fechaFin = $_GET["fechaFin"];
            $tipo = $_GET["tipo"];
            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "p_reportes_horas ?,?,?,?,?,?";
            $params = array($lg_idRaiz,$lg_idUsuario,$tipo,$unidad,$fechaIni,$fechaFin);
            $stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                do {
                    $tc = sqlsrv_num_fields($stmt);
                    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                        $totreg++;
                        $res .= $row['imei'] . '|';
                        $res .= $row['fecha'] . '|';
                        $res .= $row['mins'] . ';';
                    }
                } while (sqlsrv_next_result($stmt));
            }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            $salida['err'] = $err;
            $salida['totreg'] = $totreg;
            $salida['res'] = $res;

            echo json_encode($salida);
        break;
        case 'verReporteTemp':
            $err = 0; $res = ''; $totreg = 0;
            $unidad = $_GET["unidad"];
            $fechaIni = $_GET["fechaIni"];
            $fechaFin = $_GET["fechaFin"];
            $tipo = $_GET["tipo"];
            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "p_reportes_temperatura ?,?,?,?,?,?";
            $params = array($lg_idRaiz,$lg_idUsuario,$tipo,$unidad,$fechaIni,$fechaFin);
            $stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                do {
                    $tc = sqlsrv_num_fields($stmt);
                    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                        $totreg++;
                        $res .= $row['imei'] . '|';
                        $res .= $row['fecha'] . '|';
                        $res .= $row['temperatura'] . '|';
                        $res .= $row['locationx'] . '|';
                        $res .= $row['locationy'] . ';';
                    }
                } while (sqlsrv_next_result($stmt));
            }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            $salida['err'] = $err;
            $salida['totreg'] = $totreg;
            $salida['res'] = $res;

            echo json_encode($salida);
        break;
        case 'p_reportes_temperatura1':
            $err = 0; $res = ''; $totreg = 0;
            $unidad = $_GET["unidad"];
            $fechaIni = $_GET["fechaIni"];
            $fechaFin = $_GET["fechaFin"];
            $tipo = $_GET["tipo"];
            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "p_reportes_temperatura1 ?,?,?,?,?,?";
            $params = array($lg_idRaiz,$lg_idUsuario,$tipo,$unidad,$fechaIni,$fechaFin);
            $stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                do {
                    $tc = sqlsrv_num_fields($stmt);
                    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                        $totreg++;
                        $res .= $row['imei'] . '|';
                        $res .= $row['fecha'] . '|';
                        $res .= $row['temperatura'] . '|';
                        $res .= $row['temperaturaf'] . '|';
                        $res .= $row['statusMotor'] . '|';
                        $res .= $row['velocidad'] . '|';
                        $res .= $row['coorx'] . '|';
                        $res .= $row['coory'] . ';';
                    }
                } while (sqlsrv_next_result($stmt));
            }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            $salida['err'] = $err;
            $salida['totreg'] = $totreg;
            $salida['res'] = $res;

            echo json_encode($salida);
        break;
        case 'verReporteKms':
            $err = 0; $res = ''; $totreg = 0;
            $unidad = $_GET["unidad"];
            $fechaIni = $_GET["fechaIni"];
            $fechaFin = $_GET["fechaFin"];
            $tipo = $_GET["tipo"];
            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "p_reportes_Kms ?,?,?,?,?,?";
            $params = array($lg_idRaiz,$lg_idUsuario,$tipo,$unidad,$fechaIni,$fechaFin);
            $stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                do {
                    $tc = sqlsrv_num_fields($stmt);
                    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                        $totreg++;
                        $res .= $row['imei'] . '|';
                        $res .= $row['fecha'] . '|';
                        $res .= $row['kms'] . ';';
                    }
                } while (sqlsrv_next_result($stmt));
            }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            $salida['err'] = $err;
            $salida['totreg'] = $totreg;
            $salida['res'] = $res;

            echo json_encode($salida);
        break;
        case 'verReporteFuel':
            $err = 0; $res = ''; $totreg = 0;
            $unidad = $_GET["unidad"];
            $fechaIni = $_GET["fechaIni"];
            $fechaFin = $_GET["fechaFin"];
            $tipo = $_GET["tipo"];
            $tanque = 1; if(isset($_GET["tanque"])) { $tanque = $_GET["tanque"]; } else { $tanque = 1; }
            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "p_reportes_fuel ?,?,?,?,?,?,?";
            $params = array($lg_idRaiz,$lg_idUsuario,$tipo,$unidad,$fechaIni,$fechaFin,$tanque);
            $stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                do {
                    $tc = sqlsrv_num_fields($stmt);
                    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                        $totreg++;
                        $res .= $row['imei'] . '|';
                        $res .= $row['fecha'] . '|';
                        $res .= $row['combustible'] . '|';
                        $res .= $row['statusMotor'] . '|';
                        $res .= $row['velocidad'] . '|';
                        $res .= $row['alarma'] . ';';
                    }
                } while (sqlsrv_next_result($stmt));
            }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            $salida['err'] = $err;
            $salida['totreg'] = $totreg;
            $salida['res'] = $res;

            echo json_encode($salida);
        break;
        case 'p_reportes_combustible':
            $err = 0; $res = ''; $totreg = 0;
            $unidad = $_GET["unidad"];
            $fechaIni = $_GET["fechaIni"];
            $fechaFin = $_GET["fechaFin"];
            $tipo = $_GET["tipo"];
            $tanque = 1; if(isset($_GET["tanque"])) { $tanque = $_GET["tanque"]; } else { $tanque = 1; }
            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "p_reportes_combustible ?,?,?,?,?,?,?";
            $params = array($lg_idRaiz,$lg_idUsuario,$tipo,$unidad,$fechaIni,$fechaFin,$tanque);
            $stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                do {
                    $tc = sqlsrv_num_fields($stmt);
                    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                        $totreg++;
                        $res .= $row['imei'] . '|';
                        $res .= $row['fecha'] . '|';
                        $res .= $row['combustible'] . '|';
                        $res .= $row['ltsCombustible'] . '|';
                        $res .= $row['statusMotor'] . '|';
                        $res .= $row['velocidad'] . '|';
                        $res .= $row['alarma'] . '|';
                        $res .= $row['coorx'] . '|';
                        $res .= $row['coory'] . '|';
                        $res .= $row['odo'] . ';';
                    }
                } while (sqlsrv_next_result($stmt));
            }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            $salida['err'] = $err;
            $salida['totreg'] = $totreg;
            $salida['res'] = $res;

            echo json_encode($salida);
        break;   
        case 'p_reportes_combustible3':
            $err = 0; $res = ''; $totreg = 0;
            $unidad = $_GET["unidad"];
            $fechaIni = $_GET["fechaIni"];
            $fechaFin = $_GET["fechaFin"];
            $tipo = $_GET["tipo"];
            $tanque = 1; if(isset($_GET["tanque"])) { $tanque = $_GET["tanque"]; } else { $tanque = 1; }
            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "p_reportes_combustible3 ?,?,?,?,?,?,?";
            $params = array($lg_idRaiz,$lg_idUsuario,$tipo,$unidad,$fechaIni,$fechaFin,$tanque);
            $stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                do {
                    $tc = sqlsrv_num_fields($stmt);
                    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                        $totreg++;
                        $res .= $row['imei'] . '|';
                        $res .= $row['fecha'] . '|';
                        $res .= $row['combustible'] . '|';
                        $res .= $row['ltsCombustible'] . '|';
                        $res .= $row['statusMotor'] . '|';
                        $res .= $row['velocidad'] . '|';
                        $res .= $row['alarma'] . '|';
                        $res .= $row['coorx'] . '|';
                        $res .= $row['coory'] . '|';
                        $res .= $row['odo'] . ';';
                    }
                } while (sqlsrv_next_result($stmt));
            }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            $salida['err'] = $err;
            $salida['totreg'] = $totreg;
            $salida['res'] = $res;

            echo json_encode($salida);
        break;
        case 'p_reportes_combustible4':
            $err = 0; $res = ''; $totreg = 0;
            $unidad = $_GET["unidad"];
            $fechaIni = $_GET["fechaIni"];
            $fechaFin = $_GET["fechaFin"];
            $tipo = $_GET["tipo"];
            $tanque = 1; if(isset($_GET["tanque"])) { $tanque = $_GET["tanque"]; } else { $tanque = 1; }
            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "p_reportes_combustible4 ?,?,?,?,?,?,?";
            $params = array($lg_idRaiz,$lg_idUsuario,$tipo,$unidad,$fechaIni,$fechaFin,$tanque);
            $stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                do {
                    $tc = sqlsrv_num_fields($stmt);
                    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                        $totreg++;
                        $res .= $row['imei'] . '|';
                        $res .= $row['fecha'] . '|';
                        $res .= $row['combustible'] . '|';
                        $res .= $row['ltsCombustible'] . '|';
                        $res .= $row['statusMotor'] . '|';
                        $res .= $row['velocidad'] . '|';
                        $res .= $row['alarma'] . '|';
                        $res .= $row['coorx'] . '|';
                        $res .= $row['coory'] . '|';
                        $res .= $row['odo'] . ';';
                    }
                } while (sqlsrv_next_result($stmt));
            }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            $salida['err'] = $err;
            $salida['totreg'] = $totreg;
            $salida['res'] = $res;

            echo json_encode($salida);
        break;
        case 'p_reportes_combustible5':
            $err = 0; $res = ''; $totreg = 0;
            $unidad = $_GET["unidad"];
            $fechaIni = $_GET["fechaIni"];
            $fechaFin = $_GET["fechaFin"];
            $tipo = $_GET["tipo"];
            $tanque = 1; if(isset($_GET["tanque"])) { $tanque = $_GET["tanque"]; } else { $tanque = 1; }
            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "p_reportes_combustible5 ?,?,?,?,?,?,?";
            $params = array($lg_idRaiz,$lg_idUsuario,$tipo,$unidad,$fechaIni,$fechaFin,$tanque);
            $stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                do {
                    $tc = sqlsrv_num_fields($stmt);
                    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                        $totreg++;
                        $res .= $row['imei'] . '|';
                        $res .= $row['fecha'] . '|';
                        $res .= $row['combustible'] . '|';
                        $res .= $row['ltsCombustible'] . '|';
                        $res .= $row['statusMotor'] . '|';
                        $res .= $row['velocidad'] . '|';
                        $res .= $row['alarma'] . '|';
                        $res .= $row['coorx'] . '|';
                        $res .= $row['coory'] . '|';
                        $res .= $row['odo'] . ';';
                    }
                } while (sqlsrv_next_result($stmt));
            }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            $salida['err'] = $err;
            $salida['totreg'] = $totreg;
            $salida['res'] = $res;

            echo json_encode($salida);
        break;        
        case "rutasGuardar":
            $err = 0; $res = '';
            $id = intval($_POST['txtRId']);
            $nombre = utf8_decode($_POST['txtRNombre']);
            $origen = utf8_decode($_POST['txtROrigen']);
            $destino = utf8_decode($_POST['txtRDestino']);
            $tolerancia = $_POST['optRTolerancia'];
            $kilometros = $_POST['txtRKms'];
            $tiempo = $_POST['txtRTiempo'];
            $noPeajes = $_POST['chkRNoPeajes'];
            $noPista = $_POST['chkRNoPista'];
            $puntos = $_POST['txtRPuntos'];
            $hitos = $_POST['txtRHitos'];
            $coorXIni = $_POST['txtRCoorXIni'];
            $coorYIni = $_POST['txtRCoorYIni'];
            $coorXFin = $_POST['txtRCoorXFin'];
            $coorYFin = $_POST['txtRCoorYFin'];

            $puntos = str_replace(" ","", $puntos);
            $puntos = str_replace(")("," ", $puntos);
            $puntos = str_replace("(","", $puntos);
            $puntos = str_replace(")","", $puntos);
            $puntos = invertirPuntos($puntos);

            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "p_rutas_guardar ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?";
            $params = array($lg_idRaiz,$id,$nombre,$origen,$destino,$coorXIni,$coorYIni,$coorXFin,$coorYFin,$hitos,$puntos,$tolerancia,$kilometros,$tiempo,$noPeajes,$noPista);
            
            $stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            $salida['err'] = $err;
            $salida['res'] = $res;

            echo json_encode($salida);
        break;
        case "listarRutas":
            $err = 0; $res = ''; $totreg = 0; $tc=0;
    
            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "p_rutas_listar ?";
            $params = array($lg_idRaiz);
            $stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                do {
                    $tc = sqlsrv_num_fields($stmt);
                    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                        $totreg++;
                        $res .= $row['id'] . '|';                           //0
                        $res .= utf8_encode($row['nombreRuta']) . '|';
                        $res .= utf8_encode($row['origen']) . '|';
                        $res .= utf8_encode($row['destino']) . '|';
                        $res .= $row['hitos'] . '|';
                        $res .= revertirPuntos($row['waypoints']) . '|';                    //5
                        $res .= $row['coorxi'] . '|';
                        $res .= $row['cooryi'] . '|';
                        $res .= $row['coorxf'] . '|';
                        $res .= $row['cooryf'] . '|';
                        $res .= $row['tolerancia'] . '|';                   //10
                        $res .= utf8_encode($row['kilometros']) . '|';
                        $res .= utf8_encode($row['tiempo']) . '|';
                        $res .= $row['noPeajes'] . '|';
                        $res .= $row['noPista'] . ';';                      //14
                    }
                } while (sqlsrv_next_result($stmt));
            }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            $salida['err'] = $err;
            $salida['totreg'] = $totreg;
            //$salida['ultimoID'] = $nextID;
            $salida['res'] = $res;

            echo json_encode($salida);
        break;
        case 'rutasEliminar':
            $err = 0;
            $res = '';
            
            $seleccionados = $_GET['sels'];
            //NV_Geocercas_Eliminar] @Ids VARCHAR(MAX),@Raiz INT as
            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "p_rutas_eliminar ?,?";
            $params = array($lg_idRaiz, $seleccionados);
            $stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                $res = "Eliminados Correctamente";
            }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            $salida['err'] = $err;
            $salida['res'] = $res;

            echo json_encode($salida);        
        break;
        case 'guardarRutaAsignarRuta':
            $err = 0;
            $res = '';

            $idDispositivo = $_GET['idDispositivo'];
            $idRuta = $_GET['idRuta'];
            $alarmaRuta = $_GET['alarmaRuta'];
            
            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "p_rutas_asignar ?,?,?,?";
            $params = array($lg_idRaiz,$idDispositivo,$idRuta,$alarmaRuta);
            $stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                $res = "Almacenado Correctamente";
            }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            $salida['err'] = $err;
            $salida['res'] = $res;

            echo json_encode($salida);        
        break;
        case "reportePdi":
            $err = 0;
            $res = '';
            $totreg = 0;
            $dispositivo = $_GET['nomDisp'];
            $fechaIni = $_GET['fechaIni'];
            $fechaFin = $_GET['fechaFin'];
    
            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "p_rptPDI ?,?,?,?";
            $params = array($lg_idRaiz,$dispositivo,$fechaIni,$fechaFin);
            $stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                do {
                    $tc = sqlsrv_num_fields($stmt);
                    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                        $totreg++;

                        $res .= $row['id'] . ',';                                   //0
                        $res .= utf8_encode($row['nombre']) . ',';
                        $res .= number_format($row['LocationX'],5,'.','') . ',';                 //2
                        $res .= number_format($row['LocationY'],5,'.','') . ',';                //3
                        $res .= $row['Velocidad'] . ',';                   //4
                        $res .= $row['Direccion'] . ',';
                        $res .= strtoupper($row['Orientacion']) . ',';              //6
                        $res .= $row['StatusMotor'] . ',';
                        $res .= $row['Fecha'] . ',';                   //8
                        $res .= $row['pdi'] . ',';                   //9
                        $res .= utf8_encode($row['nombrePDI']) . ',';                //10
                        $res .= $row['mins'] . ',';                 //11
                        $res .= $row['tipo'] . ',';                 //12
                        $res .= $row['pdiLat'] . ',';                 //13
                        $res .= $row['pdiLon'] . ',';                 //14
                        $res .= $row['pdiRadio'] . ',';                 //15
                        $res .= $row['pdiImagen'] . ',';                 //16
                        $res .= $row['pdiCercano'] . ',';                 //17
                        $res .= $row['nombrePdiCercano'] . ',';                 //18
                        $res .= $row['distancia'] . ',';                 //19
                        $res .= $row['pdiCRadio'] . ',';                 //20
                        $res .= $row['pdiCImagen'] . ',';                 //21
                        $res .= $row['pdiCLat'] . ',';                 //22
                        $res .= $row['pdiCLon'] . '|';                 //23
                    }
                } while (sqlsrv_next_result($stmt));            
            }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            $salida['err'] = $err;
            $salida['totreg'] = $totreg;
            //$salida['ultimoID'] = $nextID;
            $salida['res'] = $res;

            echo json_encode($salida);
        break;
        case "getRendimiento":
            $err = 0;
            $res = '';
            $totreg = 0;
            /*$dispositivo = $_GET['nomDisp'];
            $fechaIni = $_GET['fechaIni'];
            $fechaFin = $_GET['fechaFin'];
    
            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "p_rptPDI ?,?,?,?";
            $params = array($lg_idRaiz,$dispositivo,$fechaIni,$fechaFin);
            $stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                do {
                    $tc = sqlsrv_num_fields($stmt);
                    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                        $totreg++;

                        $res .= $row['id'] . ',';                                   //0
                        $res .= utf8_encode($row['nombre']) . ',';
                        $res .= number_format($row['LocationX'],5,'.','') . ',';                 //2
                        $res .= number_format($row['LocationY'],5,'.','') . ',';                //3
                        $res .= $row['Velocidad'] . ',';                   //4
                        $res .= $row['Direccion'] . ',';
                        $res .= strtoupper($row['Orientacion']) . ',';              //6
                        $res .= $row['StatusMotor'] . ',';
                        $res .= $row['Fecha'] . ',';                   //8
                        $res .= $row['pdi'] . ',';                   //9
                        $res .= utf8_encode($row['nombrePDI']) . ',';                //10
                        $res .= $row['mins'] . ',';                 //11
                        $res .= $row['tipo'] . ',';                 //12
                        $res .= $row['pdiLat'] . ',';                 //13
                        $res .= $row['pdiLon'] . ',';                 //14
                        $res .= $row['pdiRadio'] . ',';                 //15
                        $res .= $row['pdiImagen'] . ',';                 //16
                        $res .= $row['pdiCercano'] . ',';                 //17
                        $res .= $row['nombrePdiCercano'] . ',';                 //18
                        $res .= $row['distancia'] . ',';                 //19
                        $res .= $row['pdiCRadio'] . ',';                 //20
                        $res .= $row['pdiCImagen'] . ',';                 //21
                        $res .= $row['pdiCLat'] . ',';                 //22
                        $res .= $row['pdiCLon'] . '|';                 //23
                    }
                } while (sqlsrv_next_result($stmt));            
            }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);
*/
            $salida['err'] = $err;
            $salida['totreg'] = $totreg;
            //$salida['ultimoID'] = $nextID;
            $salida['res'] = $res;

            echo json_encode($salida);
        break;
        case 'guardarGrupos':
            $err = 0;
            $res = '';

            $unidades = $_GET['unidades'];
            $grupos = utf8_decode($_GET['grupos']);
            
            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "p_Grupos_save ?,?,?,?";
            $params = array($lg_idRaiz,$_SESSION['lgId'],$grupos,$unidades);
            $stmt = sqlsrv_query($conn, $sql, $params);
            //var_dump($conn, $sql);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                $res = "Almacenado Correctamente";
            }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            $salida['err'] = $err;
            $salida['res'] = $res;

            echo json_encode($salida);        
        break;
    }

    function actualizarStatusMotor($dispositivo, $comando){
        $ejecutar = 0; $valor = 0; $ultimoID = -1;
        switch($comando){
            case 'activarmotor': $ejecutar = 1; $valor = 0; break;
            case 'desactivarmotor': $ejecutar = 1; $valor = 1; break;
        }

        if($ejecutar == 1) {
            $ultimoID = 1;
            $err = 0; $res = ''; $totreg = 0; $tc=0;
            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            $sql = "UPDATE dispositivos SET BloqueoMotor=" . $valor . " WHERE imei='" . $dispositivo . "'" ;
            $stmt = sqlsrv_query($conn, $sql);
            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }
        }
        return $ultimoID;
    }

    function leerDatosUsuario($lg_usuario){
            $err = 0; $res = ''; $totreg = 0; $tc=0;
            $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
            
            $sql = "NV_Usuarios_ObtenerDatos ?";
            $params = array($lg_usuario);
            $stmt = sqlsrv_query($conn, $sql, $params);

            if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }

            if($err == 0) {
                do {
                    $tc = sqlsrv_num_fields($stmt);
                    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                        $totreg++;
                        $_SESSION['DU1'] = $row['ID Usuario'];
                        $_SESSION['DU2'] = $row['Tipo Usuario'];
                        $_SESSION['DU3'] = $row['ID Raiz'];
                        $_SESSION['DU4'] = $row['Administrador'];
                        $_SESSION['DU5'] = $row['Tipo Cliente'];
                        $_SESSION['DU6'] = $row['Link'];
                        $_SESSION['DU7'] = $row['Logo'];
                        $_SESSION['DU8'] = $row['Titulo'];
                        $_SESSION['DU9'] = $row['Usuario'];
                        $_SESSION['DU10'] = $row['Nombre'];
                        $_SESSION['DU11'] = $row['Apellido'];
                        $_SESSION['DU12'] = $row['Razon Social'];
                        $_SESSION['DU13'] = $row['Fecha Alta'];
                        $_SESSION['DU14'] = $row['Movil'];
                        $_SESSION['DU15'] = $row['Correo Electronico'];
                        $_SESSION['DU16'] = $row['Notificacion Panico'];
                        $_SESSION['DU17'] = $row['Notificacion Fuente'];
                        $_SESSION['DU18'] = $row['Notificacion Geocerca'];
                        $_SESSION['DU19'] = $row['Notificacion Velocidad'];
                        $_SESSION['DU20'] = $row['Permiso Emergencia'];
                        $_SESSION['DU21'] = $row['Permiso Motor'];
                        $_SESSION['DU22'] = $row['Permiso Historial'];
                        $_SESSION['DU23'] = $row['Permiso Mensaje'];
                        $_SESSION['DU24'] = $row['Permiso Beep'];
                        $_SESSION['DU25'] = $row['Permiso Posicion'];
                        $_SESSION['DU26'] = $row['Permiso Armar'];
                        $_SESSION['DU27'] = $row['Permiso Fotografia'];
                        $_SESSION['DU28'] = $row['Permiso Administrar Geocerca'];
                        $_SESSION['DU29'] = $row['Permiso Velocidad'];
                        $_SESSION['DU30'] = $row['Permiso Movimiento'];
                        $_SESSION['DU31'] = $row['Permiso Administrar Usuarios'];
                        $_SESSION['DU32'] = $row['Permiso Administrar Dispositivos'];
                        $_SESSION['DU33'] = $row['Permiso Administrar Choferes'];
                        $_SESSION['DU34'] = $row['Permiso Administrar Puntos'];
                        $_SESSION['DU35'] = $row['Permiso Rutas'];
                        $_SESSION['DU36'] = $row['Permiso Velocidad Sin Intervalos'];
                        $_SESSION['DU37'] = $row['Usuario Email'];
                        $_SESSION['DU38'] = $row['Password Email'];
                        $_SESSION['DU39'] = $row['Puerto Email'];
                        $_SESSION['DU40'] = $row['SSL Email'];
                        $_SESSION['DU41'] = $row['Servidor Email'];
                        $_SESSION['DU42'] = $row['Nombre Alarma'];
                        $_SESSION['DU43'] = $row['Sonido Activado'];
                        $_SESSION['DU44'] = $row['Estilo Sistema'];
                        $_SESSION['DU45'] = $row['Mostrar Geocerca'];
                        $_SESSION['DU46'] = $row['Mostrar StreetView'];
                        $_SESSION['DU47'] = $row['Voz Activada'];
                        $_SESSION['DU48'] = $row['Timer'];
                    }
                } while (sqlsrv_next_result($stmt));
                $res = 1;
            } else { $res = 0; }
            
            sqlsrv_free_stmt( $stmt);
            sqlsrv_close($conn);

            return $res;
    }

    function invertirPuntos($coors){
        $coordenadas = explode(" ", $coors);
        $ncoors = '';
        for($i=0; $i<count($coordenadas);$i++){
            $cr = explode(",",$coordenadas[$i]);
            $ncoors .= $cr[1] . " " . $cr[0] . ",";
        }
        return substr($ncoors,0,-1);
    }
    function revertirPuntos($coors){
        $coordenadas = explode(",", $coors);
        $ncoors = '';
        for($i=0; $i<count($coordenadas);$i++){
            $cr = explode(" ",$coordenadas[$i]);
            $ncoors .= $cr[1] . "," . $cr[0] . " ";
        }
        return rtrim($ncoors);
    }

    function guardarLogLogin($idUsuario,$origen,$so,$navegador,$nav_version,$agent,$cli_IP,$error,$usuario){
        $err = 0;
        $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
        $sql = "p_guardarLogLogin ?,?,?,?,?,?,?,?,?";
        $params = array($idUsuario,$usuario,$origen,$cli_IP,$so,$navegador,$nav_version,$agent,$error);
        $stmt = sqlsrv_query($conn, $sql, $params);
    }

    function tienePDI($raiz){
        $tiene=0;
        $err = 0;
        $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
        $sql = "select count(id) as res from pdi where raiz='" . $raiz . "'" ;
        $stmt = sqlsrv_query($conn, $sql);
        if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }
        $rows = sqlsrv_has_rows($stmt);
        if ($rows === true) {
            while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                //$idDispositivo = $row['idDispositivo'];
                $tiene = $row['res'];
            }

        } else { $tiene = 0; }  
        sqlsrv_free_stmt( $stmt);
        sqlsrv_close($conn);

        return $tiene;
    }

    function guardarPSID($idUsuario,$origen,$so,$navegador,$nav_version,$agent,$cli_IP,$error,$usuario){
        $err = 0;
        $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
        $sql = "p_guardarLogLogin ?,?,?,?,?,?,?,?,?";
        $params = array($idUsuario,$usuario,$origen,$cli_IP,$so,$navegador,$nav_version,$agent,$error);
        $stmt = sqlsrv_query($conn, $sql, $params);
    }

    function encriptarEmail($email){
        $res = $email;
        $usuario = '';
        $arroba = strpos($email,"@");
        $prov = substr($email, $arroba, strlen($email));
        $encrip = '';
        if($arroba > 0) {
            $usuario = substr($email,0,$arroba);
            $usuario = substr($email, 0,3);
            for($i=3;$i<$arroba;$i++){
                $encrip = $encrip . "*";
            }
            $usuario = $usuario  . "" . $encrip . $prov;
        }

        return $usuario;
    }
    
  

    function guardarLogEnviarPass($texto,$srv_host,$correoElectronico,$origen,$cli_IP,$so,$navegador,$nav_version,$agent,$encontro){
        $err = 0;
        //if($srv_host == '') { $encontro = 1; }
        $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
        $sql = "p_guardarLogEnviarPass ?,?,?,?,?,?,?,?,?,?";
        $params = array($texto,$srv_host,$correoElectronico,$origen,$cli_IP,$so,$navegador,$nav_version,$agent,$encontro);
        $stmt = sqlsrv_query($conn, $sql, $params);
    }

    function guardarLogActualizarDatos($tipo,$idUsuario,$idRaiz,$origen,$cli_IP,$so,$navegador,$nav_version,$agent){
        $err = 0;
        //if($srv_host == '') { $encontro = 1; }
        $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
        $sql = "p_guardarLogActualizarDatos ?,?,?,?,?,?,?,?,?";
        $params = array($tipo,$idUsuario,$idRaiz,$origen,$cli_IP,$so,$navegador,$nav_version,$agent);
        $stmt = sqlsrv_query($conn, $sql, $params);
    }

function appAndroidVersion(){
        $version="";
        $err = 0;
        $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
        $sql = "select top 1 appAndroidVersion from configuraciones" ;
        $stmt = sqlsrv_query($conn, $sql);
        if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }
        $rows = sqlsrv_has_rows($stmt);
        if ($rows === true) {
            while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                //$idDispositivo = $row['idDispositivo'];
                $version = $row['appAndroidVersion'];
            }

        } else { $version = ""; }  
        sqlsrv_free_stmt( $stmt);
        sqlsrv_close($conn);

        return $version;
    }

    function getMapaUrl($raiz){
        $mapUrl="";
        $err = 0;
        $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
        $sql = "select top 1 mapUrl from configMapUrl where idRaiz=" . $raiz ;
        $stmt = sqlsrv_query($conn, $sql);
        if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }
        $rows = sqlsrv_has_rows($stmt);
        if ($rows === true) {
            while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                $mapUrl = $row['mapUrl'];
            }

        } else { $mapUrl = "http://www.redurbana.com.mx/openmap"; }  
        sqlsrv_free_stmt( $stmt);
        sqlsrv_close($conn);

        return $mapUrl;
    }
    function getApiGoogle($raiz){
        $apiGoogle="";
        $err = 0;
        $conn = conecta(); if($conn === false) {die( print_r(sqlsrv_errors(), true));}
        $sql = "select top 1 apiGoogle from configMapUrl where idRaiz=" . $raiz ;
        $stmt = sqlsrv_query($conn, $sql);
        if( $stmt === false ) { $err = 1; die( print_r( sqlsrv_errors(), true) ); }
        $rows = sqlsrv_has_rows($stmt);
        if ($rows === true) {
            while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                $apiGoogle = $row['apiGoogle'];
            }

        } else { $apiGoogle = ""; }  
        sqlsrv_free_stmt( $stmt);
        sqlsrv_close($conn);

        return $apiGoogle;
    }

    function dateToXls($fecha){
        $res = '';
        $anio = substr($fecha, 0,4);
        $mes = substr($fecha, 4,2);
        $dia = substr($fecha, 6,2);
        $hora = substr($fecha, 8,2);
        $min = substr($fecha, 10,2);
        $seg = substr($fecha, 12,2);
        return $dia . "/" . $mes . "/" . $dia . ' ' . $hora . ':' . $min . ':' . $seg;
    }

    function convertirHrsMins($tiempo){
        $res = '';
        if($tiempo =='0'){ $res = ''; }
        else {
            if($tiempo < 60) {
                if($tiempo == 1) {
                    $res = $tiempo . ' Min';
                } else {
                    $res = $tiempo . ' Mins';
                }
            } else {
                $hrs = intval($tiempo/60);
                $mins = $tiempo%60;
                $res = $hrs . ' Hrs ' . $mins . ' Mins';
            }
        }
        return $res;
    }
?>