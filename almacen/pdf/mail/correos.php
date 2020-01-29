<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
$html = '';
include("correo1.php");
/* CONFIGURACIÓN DEL SERVIDOR DE CORREO*/
$srv_host = "mail.appsolalsa.com";
$srv_username = "atencionaclientes@appsolalsa.com";
$srv_pwd = "atencionaclientes2019.";
$srv_port = 465;
$srv_ssl = "ssl";
$srv_nombre = "Nombre de quien se lo envia";

/* DATOS DEL CLIENTE*/
$emailEmpresa = "Empresa";
$id = "1";
$unidad = "Unidad";
$fecha = "01/09/2019";
$motor = "encendido";
$alerta = "Alerta 1";
$velocidad = "100";
$orientacion = "Orientacion";
$coorx = "24.2365";
$coory = "-94.5265";
$bateria = "100%";

include("correo1.php");


//$datos["Unidad", "Latitud", "Longitud"];
/*
    http://localhost/correos/correos.php?key=jk45hdyski&opc=correo1&
    nombre=Pruebas ABG&correo=begasoft.cel@gmail.com&asunto=Informacion de Posicion&
    datos=TH 101,22.4004669189453,-97.9164276123047^TH 102,22.3995971679688,-97.9169158935547
    ^TH 103,22.3996925354004,-97.9169158935547
*/
/*$texto = $_POST["datos"];
$dato1 = explode("^", $texto);
$datos = array();
for ($i=0; $i<=count($dato1) - 1; $i++) {
    $dato2 = explode(",", $dato1[$i]);
    array_push($datos, array($dato2[0],$dato2[1],$dato2[2],$dato2[3]));
}*/


$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
try {
    //Server settings
    $mail->SMTPDebug = 0;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = $srv_host;          // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = $srv_username; // SMTP username
    $mail->Password = $srv_pwd;                   // SMTP password
    $mail->SMTPSecure = $srv_ssl;                            // Enable TLS encryption, `ssl` also accepted
    //$mail->Port = 26;                                    // TCP port to connect to
    $mail->Port = $srv_port;                                    // TCP port to connect to
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
    //Recipients
    $mail->setFrom($srv_username, $srv_nombre);
    for($i=0; $i <= count($correos) - 1; $i++){
        $mail->addAddress($correos[$i], '');     // Add a recipient
    }
    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $asunto;
    $mail->Body    = $html;
    //$mail->AddAttachment('path_to_pdf', $name = 'Name_for_pdf',  $encoding = 'base64', $type = 'application/pdf');
    $mail->send();
    $res = 'El mensaje ha sido Enviado';
} catch (Exception $e) {
    $res = 'Error al enviar el mensaje. Error: ', $mail->ErrorInfo;
}

?>