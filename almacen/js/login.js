function iniciarSesion() {
    $.ajax({
        data: $.param({'opc' : 'login'}) + "&" + $("#formLogin").serialize(),
        type: "POST",
        dataType: "json",
        url: "files/nucleo.php",
        success: function(data) {
            if(data.err == 0) {
                window.location = 'index.php';
            } else {
                alert("Incorrecto");
            }
        },
        error: function(data) {

        }
    });
}

$("#txtUsuario").keypress(function(e) {
    if(e.which == 13) {
        iniciarSesion();
    }
});

$("#txtClave").keypress(function(e) {
    if(e.which == 13) {
        iniciarSesion();
    }
});