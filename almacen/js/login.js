function iniciarSesion() {
    $.ajax({
        data: $.param({'opc' : 'login'}) + "&" + $("#formLogin").serialize(),
        type: "POST",
        dataType: "json",
        url: "files/nucleo.php",
        success: function(data) {
            if(data.err == 0) {
                alert("Correcto");
            } else {
                alert("Incorrecto");
            }
        },
        error: function(data) {

        }
    });
}