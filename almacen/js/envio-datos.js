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
                alert('Se ha dado de alta el equipo');
            } else {
                alert('Error al dar de alta el equipo');
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