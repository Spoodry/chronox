$("#formAltaEquipo").on('submit', function(e){
    e.preventDefault();
    var f = $(this);

    var formData = new FormData(document.getElementById('formAltaEquipo'));
    formData.append('opc', 'altaEquipo');
    $.ajax({
        data: formData,
        url: 'files/nucleo.php',
        type: 'POST',
        dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        success: function(data) {
            console.log(data);
            if(data.err == 0) {
                Swal.fire("Aviso", "Equipo dado de alta exitosamente", "success").then((result) => {
                    if(result.value) {
                        Swal.fire({
                            title: "Aviso", 
                            text: "¿Desea ver el reporte de alta del equipo?",
                            type: "info",
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Sí',
                            cancelButtonText: 'No'
                        }).then((resultNot) => {
                            if(resultNot.value) {
                                var url = "entrada-inventario.php?idEquipo=" + data.res['id'];
                                window.open(url, "_blank");
                            }
                            $("#formAltaEquipo").trigger('reset');
                            $("#altaEquipoMod").modal("hide");
                        });
                    }
                });
            } else {
                Swal.fire("Aviso", "El equipo no pudo ser dado de alta", "error");
            }
        }
    })
});

function bajaEquipo() {
    $.ajax({
        data: $.param({'opc' : 'eliminarEquipo'}) + "&" + $("#formBajaEquipo").serialize(),
        type: 'GET',
        dataType: 'json',
        url: 'files/nucleo.php',
        success: function(data) {
            if(data.err == 0)
                alert('Equipo dado de baja');
            else
                alert('Error al dar de baja el equipo');
        },
        error: function(data) {
            alert('No hay conexión con el servidor');
        }
    });
}