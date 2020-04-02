<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

$html = "";
switch($tipoCorreo) {
    case '':
        $mensaje = "Hola, te hemos enviado la hoja de servicio correspondiente al servicio con folio #$folio";
        include("plantilla-hoja-servicio.php");
        break;
}
/* CONFIGURACIÓN DEL SERVIDOR DE CORREO*/
$srv_host = "mail.chronox.me";
$srv_username = "master@chronox.me";
$srv_pwd = "Onmula08051607";
$srv_port = 465;
$srv_ssl = "ssl";
$srv_nombre = $_SESSION['NomUsuario'];

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
    $mail->CharSet = 'utf-8';
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
    //Recipients
    $mail->setFrom($srv_username, $srv_nombre);
    switch($tipoCorreo) {
        case 'estatusAutorizado':
            $mail->addAddress($correo, '');
            break;
        default:
            for($i = 0; $i <= count($correos) - 1; $i++){
                $mail->addAddress($correos[$i], '');     // Add a recipient
            }   
            break;
    }
    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $asunto;
    $mail->Body    = $html;
    switch($tipoCorreo) {
        case 'hojaServicio':
            $mail->AddAttachment('../temp/'.$archivo, $name = $archivo,  $encoding = 'base64', $type = 'application/pdf');
            break;
    }
    $mail->send();
    $res = 'El mensaje ha sido Enviado';
    switch($tipoCorreo) {
        case 'hojaServicio':
            unlink('../temp/' . $archivo);
            break;
    }

} catch (Exception $e) {
    $err = 1;
    $res = 'Error al enviar el mensaje. Error: ' . $mail->ErrorInfo;

    switch($tipoCorreo) {
        case 'hojaServicio':
            unlink('../temp/' . $archivo);
            break;
    }
}

?>