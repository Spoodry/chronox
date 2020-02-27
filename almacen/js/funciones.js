function buscar() {
    if($("#txtCadena").val() == '') {
        data = 'opc=obtenerEquipos';
    } else {
        data = "opc=busqueda&cadena=" + $("#txtCadena").val() + "&tipo=" + $("#rdTipo").val();
    }
    $.ajax({
        data: data,
        type: "GET",
        dataType: "json",
        url: "files/nucleo.php",
        success: function(data) {
            if(data.err == 1) {
                console.log(data.err);
            } else {
                var informacion = "";
                $("#tablaCompleta").removeClass("d-none");
                $("#tableContenido").empty();
                $.each(data.res, function(index, array) {
                    var btnEditar = "<a href=\"pdf/historial.php?idEquipo=" + array['id'] + "\" class=\"btn btn-outline-warning mb-1 mr-1\" title=\"Historial\"><i class=\"fa fa-history fa-sm fa-fw\"></i></a>";
                    var btnBaja = "<button class=\"btn btn-outline-danger mb-1 mr-1\" title=\"Baja\" onclick=\"\"><i class=\"fa fa-minus fa-sm fa-fw\"></i></button>";
                    var botones = "<div style=\"text-align:center;\">" + btnEditar + btnBaja + "</div>";

                    informacion += "<tr><td>" + array['Serie'] + "</td>";
                    informacion += "<td>" + array['Marca'] + "</td>";
                    informacion += "<td>" + array['Modelo'] + "</td>";
                    informacion += "<td>" + array['Tipo'] + "</td>";
                    informacion += "<td>" + array['Asignacion'] + "</td>";
                    informacion += "<td>" + array['Economico'] + "</td>";
                    informacion += "<td>" + botones + "</td></tr>";
                });

                $("#tableContenido").append(informacion);
            }
        }
    });
}

function activar(navItem) {
    $("#" + navItem).addClass("active");
}