function buscar() {
    if($("#txtCadena").val() == '') {
        data = 'opc=obtenerEquipos';
    } else {
        data = "opc=busqueda&cadena=" + $("#txtCadena").val() + "&tipo=" + $("#rdTipo").val();
    }

    var cargando = '<td colspan="7" class="text-center"><div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only">Loading...</span></div></td>';

    $("#tableContenido").html(cargando);

    $.ajax({
        data: data,
        type: "GET",
        dataType: "json",
        url: "files/nucleo.php",
        success: function(data) {
            if(data.err == 1) {
                console.log(data.err);
            } else {
                $("#tableContenido").empty();

                $("#tEquipos").DataTable().destroy();

                $("#tableContenido").empty();

                var table = $("#tEquipos").DataTable({
                    "autoWidth": false,
                    "ordering": false,
                    "searching": false,
                    "language": {
                        "url": "files/Spanish.json"
                    }
                });

                $.each(data.res, function(index, array) {
                    var btnEditar = "<a href=\"pdf/documentos/historial.php?idEquipo=" + array['id'] + "\" class=\"btn btn-outline-warning mb-1 mr-1\" title=\"Historial\"><i class=\"fa fa-history fa-sm fa-fw\"></i></a>";
                    var btnBaja = "<button class=\"btn btn-outline-danger mb-1 mr-1\" title=\"Baja\" onclick=\"bajaEquipo(" + array['id'] + ")\"><i class=\"fa fa-minus fa-sm fa-fw\"></i></button>";
                    var btnAgrAditamento = "<button class=\"btn btn-outline-success mb-1 mr-1\" title=\"Agregar Aditamento\" onclick=\"nuevoAditamento(" + array['id'] + ")\"><i class=\"fa fa-plus fa-sm fa-fw\"></i></button>";
                    var botones = "<div style=\"text-align:center;\">" + btnEditar + btnBaja + btnAgrAditamento + "</div>";

                    table.row.add([
                        array['Serie'],
                        array['Marca'],
                        array['Modelo'],
                        array['Tipo'],
                        array['Asignacion'],
                        array['Economico'],
                        botones
                    ]).draw(true);
                });

            }
        }
    });
}

function activar(navItem) {
    $("#" + navItem).addClass("active");
}