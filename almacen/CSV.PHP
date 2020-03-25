<?php
    require 'vendor/autoload.php';

    use PhpOffice\PhpSpreadsheet\IOFactory;
    use PhpOffice\PhpSpreadsheet\Spreadsheet;

    include('files/conexion.php');

    date_default_timezone_set('America/Monterrey');

    $tabla = $_GET['tabla'];
    
    $link = Conectar();

    $query = '';
    switch($tabla) {
        case 0:
            $nomArchivo = "equipos_";
            $query = "SELECT e.id, Serie, Marca, Modelo, Tipo, CONCAT_WS(' - ', e.Asignacion, u.nomUsuario) AS Asignacion, Economico, Imagen, estatus FROM equipos AS e LEFT JOIN usuarios AS u ON e.Asignacion = u.idUsuario ORDER BY id";
            break;
        case 1:
            $nomArchivo = "aditamentos_";
            $query = "SELECT a.id, a.idAditamento, CONCAT_WS(' - ', u.idUsuario, u.nomUsuario) AS Asignacion, CONCAT_WS(' - ', TipoAditamento, ta.Aditamento) AS TipoAditamento, Tipo FROM aditamentos AS a INNER JOIN tipoaditamentos AS ta ON a.TipoAditamento = ta.IdAditamento INNER JOIN usuarios AS u ON a.idAsignacion = u.id";
            break;
        case 2:
            $nomArchivo = "usuarios_";
            $query = "SELECT u.id, idUsuario, tu.descripcion AS tipoUsuario, nomUsuario, usuario FROM usuarios AS u INNER JOIN tiposUsuarios AS tu ON u.idTipoUsuario = tu.id";
            break;
        case 3:
            $nomArchivo = "movimientos_equipos_";
            $query = "SELECT me.id, CONCAT_WS(' - ', u.idUsuario, u.nomUsuario) AS usuario, fecha, CONCAT_WS(' ', e.Marca, e.Modelo) AS equipo, CONCAT(a.idAditamento, ' - ' , ta.Aditamento, ' ', a.Tipo) AS aditamento, tm.nombre AS tipoMovimiento, me.Serie, query FROM movimientosEquipos AS me LEFT JOIN usuarios AS u ON me.idUsuario = u.id LEFT JOIN equipos AS e ON me.idEquipo = e.id LEFT JOIN aditamentos AS a ON me.idAditamento = a.id LEFT JOIN tipoaditamentos AS ta ON a.TipoAditamento = ta.IdAditamento INNER JOIN tiposMovimientos AS tm ON me.idTipoMovimiento = tm.id ORDER BY me.id";
            break;

    }
    $nomArchivo .= date('YmdHi');

    $stmt = $link->prepare($query);

    if($stmt->execute()) {
        $rowsDatos = mysqli_fetch_all($stmt->get_result(), MYSQLI_ASSOC);

        $spreadsheet = new Spreadsheet();

        $spreadsheet->getProperties()->setCreator('Juan Pablo Altamirano Flores');

        for($i = 0; $i < count($rowsDatos); $i++) {
            $indexs = array_keys($rowsDatos[$i]);

            if($i == 0) {
                for($k = 0; $k < count($indexs); $k++) {
                    $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow($k + 1, 1, $indexs[$k]);
                }
            }
            $rowsDatos[$i] = array_map('utf8_encode', $rowsDatos[$i]);

            for($k = 0; $k < count($indexs); $k++) {
                $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow($k + 1, $i + 2, $rowsDatos[$i][$indexs[$k]]);
            }
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $nomArchivo . '.csv"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Csv'); 
        $writer->setUseBOM(true);
        $writer->save('php://output');
        exit;

    }

?>