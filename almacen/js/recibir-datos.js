function obtenerTiposEquipo() {
    $.ajax({
        data: $.param({'opc' : 'obtenerTiposEquipos'}),
        type: 'GET',
        dataType: 'json',
        url: 'files/nucleo.php',
        success: function(data) {
            if(data.err == 0) {
                var opciones = '<option value="-1"></option>';
                $.each(data.res, function(index, array) {
                    opciones += '<option value="' + array['IdTipo'] + '">' + array['NomEquipo'] + '</option>';
                });

                $("#slTipoEquipo").html(opciones);
            } else {
                alert('Tipos de equipo no cargados');
            }
        },
        error: function(data) {
            alert('No hay conexión con el servidor')
        }
    }); 
}

function obtenerUsuariosAsignacion() {
    $.ajax({
        data: $.param({'opc' : 'obtenerUsuariosAsignacion'}),
        type: 'GET',
        dataType: 'json',
        url: 'files/nucleo.php',
        success: function(data) {
            if(data.err == 0) {
                var opciones = '<option value="-1"></option>';
                $.each(data.res, function(index, array) {
                    opciones += '<option value="' + array['idUsuario'] + '">' + array['nomUsuario'] + '</option>'
                });

                $("#slAsign").html(opciones);
            } else {
                alert('Usuarios no cargados');
            }
        },
        error: function(data) {
            alert('No hay conexión con el servidor')
        }
    }); 
}

function obtenerTiposAditamentos() {
    $.ajax({
        data: $.param({'opc' : 'obtenerTiposAditamentos'}),
        type: 'GET',
        dataType: 'json',
        url: 'files/nucleo.php',
        success: function(data) {
            if(data.err == 0) {
                var opciones = '<option value="-1"></option>';
                $.each(data.res, function(index, array) {
                    opciones += '<option value="' + array['IdAditamento'] + '">' + array['Aditamento'] + '</option>'
                });

                $("#slTiposAdit").html(opciones);
            } else {
                alert('Usuarios no cargados');
            }
        },
        error: function(data) {
            alert('No hay conexión con el servidor');
        }
    }); 
}

function obtenerUsuarios() {
    $.ajax({
        data: $.param({'opc' : 'obtenerUsuarios'}),
        type: 'GET',
        dataType: 'json',
        url: 'files/nucleo.php',
        success: function(data) {
            if(data.err == 0) {
                $("#tbodyUsuarios").empty();

                var table = $("#tUsuarios").DataTable({
                    "language": {
                        "url": "files/Spanish.json"
                    }
                });

                $.each(data.res, function(index, array) {

                    var btnXML = '<a href="XML.php?idUsuario=' + array['idUsuario'] + '" class="btn btn-outline-success mb-1 mr-1" title="XML"><i class="fa fa-file-code fa-sm fa-fw"></i></a>';
                    var btnCSV = '<a href="pdf/historial.php?idEquipo=' + array['id'] + '" class="btn btn-outline-info mb-1 mr-1" title="CSV"><i class="fa fa-file-csv fa-sm fa-fw"></i></a>';
                    var btns = '<div style="text-align:center;">' + btnXML + btnCSV + '</div>';
                    table.row.add([
                        array['idUsuario'],
                        array['nomUsuario'],
                        array['usuario'],
                        array['tipoUsuario'],
                        btns
                    ]).draw(true);
                });
            } else {

            }
        }
    });
}