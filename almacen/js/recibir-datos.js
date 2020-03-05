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
            alert('No hay conexión con el servidor')
        }
    }); 
}