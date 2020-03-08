<?php
include '../files/conexion.php';

$link = Conectar();

$idEquipo = $_GET['idEquipo'];

$stmt = $link->prepare('CALL proc_obtenerDatosEquipo(?);');
$stmt->bind_param('i', $idEquipo);

if($stmt->execute()) {
	$equipo = mysqli_fetch_array($stmt->get_result(), MYSQLI_ASSOC);
	
	$equipo['Serie'] = utf8_encode($equipo['Serie']);
	$equipo['Marca'] = utf8_encode($equipo['Marca']);
	$equipo['Modelo'] = utf8_encode($equipo['Modelo']);
	$equipo['Tipo'] = utf8_encode($equipo['Tipo']);
	$equipo['Asignacion'] = utf8_encode($equipo['Asignacion']);
	$equipo['Economico'] = utf8_encode($equipo['Economico']);
	$equipo['Imagen'] = utf8_encode($equipo['Imagen']);

	$stmt->close();

	$stmt = $link->prepare('CALL proc_obtenerHistorial(?);');
	$stmt->bind_param('i', $idEquipo);

	if($stmt->execute()) {
		$movimientos = mysqli_fetch_all($stmt->get_result(), MYSQLI_ASSOC);

		for($i = 0; $i < count($movimientos); $i++) {
			$movimientos[$i]['nomUsuario'] = utf8_encode($movimientos[$i]['nomUsuario']);
			$movimientos[$i]['Aditamento'] = utf8_encode($movimientos[$i]['Aditamento']);
			$movimientos[$i]['descAditamento'] = utf8_encode($movimientos[$i]['descAditamento']);
			$movimientos[$i]['tipoMovimiento'] = utf8_encode($movimientos[$i]['tipoMovimiento']);
			$movimientos[$i]['Serie'] = utf8_encode($movimientos[$i]['Serie']);
		}

	} else {
		$err = 1;
		echo $link->error;
	}

} else {
	$err = 1;
	echo $link->error;
}

$stmt->close();

include('tcpdf.php');

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set default header data
//$pdf->SetHeaderData('../img/logo.png', 45, 'Cotizaciones ', 'by servicio.a.clientes@solalsa.com');

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
//$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(12, 12, 12);
//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
//$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 0);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
// set font
$pdf->SetFont('times', '', 12);
// Call before the addPage() method
$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false);
//Funcion necesaria
$pdf->AddPage();

$serie = $equipo['Serie'];
$marca = $equipo['Marca'];
$modelo = $equipo['Modelo'];
$tipo = $equipo['Tipo'];
$asignacion = $equipo['Asignacion'];
$economico = $equipo['Economico'];
$imagen = $equipo['Imagen'];

$nomPDF = "HIST-$serie";
$pdf->setTitle($nomPDF);

$historial = "<ul>";
for($i = 0; $i < count($movimientos); $i++) {
	$fecha = date("d-m-Y", strtotime($movimientos[$i]['fecha']));
	$hora = date("g:i A", strtotime($movimientos[$i]['fecha']));

	$historial .= "<li>$fecha $hora - ";
	switch($movimientos[$i]['idTipoMovimiento']) {
		case 1:		//alta
			$historial .= "Se ha dado de alta ";
			break;
		case 2:		//baja
			$historial .= "Se ha dado de baja ";
			break;
		case 3:		//actualización
			$historial .= "Se ha actualizado ";
			break;
		case 4:		//alta aditamento
			$historial .= "Se ha dado de alta el aditamento ";
			break;
	}

	$usuario = $movimientos[$i]['nomUsuario'];
	$aditamento = $movimientos[$i]['Aditamento'];
	$descAditamento = $movimientos[$i]['descAditamento'];

	if($movimientos[$i]['idTipoMovimiento'] == 4) {
		$historial .= "$aditamento/$descAditamento por el usuario $usuario.</li>";
	} else {
		$historial .= "el equipo $marca $modelo con serie $serie por el usuario $usuario.</li>";	
	}
}
$historial .= "</ul>";

$tabla = <<<EOD
	<table border="2" cellpadding="5">
		<tr>
			<td width="70%">
				<strong>Serie: </strong>$serie
				<strong>Marca: </strong>$marca
				<strong>Modelo: </strong>$modelo<br />
				<strong>Tipo: </strong>$tipo<br />
				<strong>Asignacion: </strong>$asignacion<br />
				<strong>Economico: </strong>$economico
			</td>
			<td width="30%" align="center">
				<img src="../imagenes/$imagen" width="150px" height="100px">
			</td>
		</tr>
	</table> <br /> <br />
	<table border="2">
		<tr>
			<td>
				$historial
			</td>
		</tr>
	</table>
EOD;

$enters = str_repeat("<br />", 25);
$html = <<<HTML
	<h1 align="center">Historial</h1>
	$tabla
	$enters
	<h3 align="center">_____________________________________</h3>
	<h3 align="center">Firma</h3>
HTML;
$pdf->writeHTML($html, true, 0, true, 0);
//$pdf->Output($nombre, 'I');
// ---------------------------------------------------------
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output($nomPDF . '.pdf', 'I');
?>