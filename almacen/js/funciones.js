function buscar() {
    console.log("cadena=" + $("#txtCadena") + "&tipo=" + $("input[name=rdTipo]:checked").val());
    $.ajax({
        data: "opc=busqueda&cadena=" + $("#txtCadena").val() + "&tipo=" + $("input[name=rdTipo]:checked").val(),
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
                    informacion += "<tr><td>" + array['Serie'] + "</td>";
                    informacion += "<td>" + array['Marca'] + "</td>";
                    informacion += "<td>" + array['Modelo'] + "</td>";
                    informacion += "<td>" + array['Tipo'] + "</td>";
                    informacion += "<td>" + array['Asignacion'] + "</td>";
                    informacion += "<td>" + array['Economico'] + "</td></tr>";
                });

                $("#tableContenido").append(informacion);
            }
        }
    });
}