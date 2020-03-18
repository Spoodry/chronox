<?php
    include('files/conexion.php');

    if(isset($_GET['idUsuario'])) {
        $idUsuario = $_GET['idUsuario'];
        header("Content-disposition: attachment; filename=$idUsuario.xml");
        header("Content-type: application/xml");
        $link = Conectar();

        $stmt = $link->prepare('SELECT u.id, idUsuario, idTipoUsuario, tu.descripcion AS tipoUsuario, nomUsuario, usuario FROM usuarios AS u INNER JOIN tiposUsuarios AS tu ON u.idTipoUsuario = tu.id WHERE idUsuario = ?;');
        $stmt->bind_param('s', $idUsuario);

        $nomArchivo = $idUsuario . ".xml";

        $xml = new XMLWriter();
        $xml->openURI('php://output');
        $xml->setIndent(true);
        $xml->setIndentString("\t");
        $xml->startDocument('1.0', 'utf-8');

        if($stmt->execute()) {
            $row = mysqli_fetch_array($stmt->get_result(), MYSQLI_ASSOC);
            $row = array_map("utf8_encode", $row);

            $xml->startElement('usuario');
            $xml->writeAttribute('id', $row['idUsuario']);
            $xml->writeAttribute('tipo', $row['tipoUsuario']);
            $xml->writeAttribute('nombre', $row['usuario']);
            $xml->text("\n\t" . $row['nomUsuario'] . "\n");

            $stmt->close();

            $xml->startElement('equipos');
            
            $stmt = $link->prepare('SELECT id, Serie, Marca, Modelo, Tipo, te.NomEquipo AS tipoEquipo, Asignacion, Economico, estatus FROM equipos AS e INNER JOIN tipoequipo AS te ON e.Tipo = te.IdTipo WHERE estatus = 1 AND Asignacion = ?;');
            $stmt->bind_param('s', $idUsuario);

            if($stmt->execute()) {
                $rowsEquipos = mysqli_fetch_all($stmt->get_result(), MYSQLI_ASSOC);                

                $stmt->close();

                for($i = 0; $i < count($rowsEquipos); $i++) {
                    $rowsEquipos[$i] = array_map("utf8_encode", $rowsEquipos[$i]);

                    $xml->startElement('equipo');
                    $xml->writeAttribute('serie', $rowsEquipos[$i]['Serie']);
                    $xml->writeAttribute('tipo', $rowsEquipos[$i]['tipoEquipo']);
                    $xml->startElement('marca');
                    $xml->text("\n\t\t\t\t" . $rowsEquipos[$i]['Marca'] . "\n\t\t\t");
                    $xml->endElement();
                    $xml->startElement('modelo');
                    $xml->text("\n\t\t\t\t" . $rowsEquipos[$i]['Modelo'] . "\n\t\t\t");
                    $xml->endElement();
                    $xml->startElement('economico');
                    $xml->text("\n\t\t\t\t" . $rowsEquipos[$i]['Economico'] . "\n\t\t\t");
                    $xml->endElement();

                    $stmt = $link->prepare('SELECT id, a.idAditamento, TipoAditamento, ta.Aditamento AS nomTipoAditamento, Tipo FROM aditamentos AS a INNER JOIN tipoaditamentos AS ta ON a.TipoAditamento = ta.IdAditamento WHERE idAsignacion = ?;');
                    $stmt->bind_param('i', $rowsEquipos[$i]['id']);

                    if($stmt->execute()) {
                        $rowsAditamentos = mysqli_fetch_all($stmt->get_result(), MYSQLI_ASSOC);

                        if(count($rowsAditamentos) != 0) {
                            $xml->startElement('aditamentos');

                            for($k = 0; $k < count($rowsAditamentos); $k++) {
                                $rowsAditamentos[$k] = array_map("utf8_encode", $rowsAditamentos[$k]);

                                $xml->startElement('aditamento');
                                $xml->writeAttribute('tipo', $rowsAditamentos[$k]['nomTipoAditamento']);
                                $xml->text("\n\t\t\t\t\t" . $rowsAditamentos[$k]['Tipo'] . "\n\t\t\t\t");
                                $xml->endElement();
                            }

                            $xml->endElement();
                        }
                    }

                    $xml->endElement();

                    $stmt->close();

                }

            }

            $xml->endElement();

            $xml->endElement();
            $xml->endDocument();
        }

    }
        
?>