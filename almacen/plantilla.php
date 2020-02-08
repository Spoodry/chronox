<?php

include 'files/conexion.php';
$asignacion=$_GET['asignacion'];

$link = Conectar();

$stmt = $link->prepare('SELECT * FROM equipos WHERE Asignacion=?');
$stmt->bind_param("s", $asignacion);

if($stmt->execute()) {
	$rows = mysqli_fetch_all($stmt->get_result(), MYSQLI_ASSOC);
}

include('pdf/tcpdf.php');

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

$tabla = "<table border=\"2\"><tr><th>Serie</th><th>Marca</th><th>Modelo</th><th>Tipo</th><th>Asignacion</th><th>Economico</th><th>Imagen</th></tr>";
for($i = 0; $i < count($rows); $i++) {
	$tabla .= "<tr><td>" . $rows[$i]['Serie'] . "</td>";
	$tabla .= "<td>" . $rows[$i]['Marca'] . "</td>";
	$tabla .= "<td>" . $rows[$i]['Modelo'] . "</td>";
	$tabla .= "<td>" . $rows[$i]['Tipo'] . "</td>";
	$tabla .= "<td>" . $rows[$i]['Asignacion'] . "</td>";
	$tabla .= "<td>" . $rows[$i]['Economico'] . "</td>";
	if($rows[$i]['Imagen'] != null) {
		$rows[$i]['Imagen'] = "<img src=\"imagenes/" . $rows[$i]['Imagen'] . "\" width=\"100px\" height=\"100px\">";
	}
	$tabla .= "<td>" . $rows[$i]['Imagen'] . "</td></tr>";
}
$tabla .= "</table>";

$html = <<<HTML
	$tabla
HTML;
$pdf->writeHTML($html, true, 0, true, 0);
//$pdf->Output($nombre, 'I');
// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('example_001.pdf', 'FI');
?>