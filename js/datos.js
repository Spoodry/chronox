function crearUsuarioTemp(hayUsuario) {
    console.log(hayUsuario);
    if(hayUsuario == 'false') {
        var fecha = new Date();
        var nombreUsuario = "temp" + fecha.getFullYear() + fecha.getMonth() + fecha.getDate() + fecha.getHours() + fecha.getMinutes() + fecha.getSeconds();
        $.ajax({
            data: "opc=crearUsuarioTemp&usuario=" + nombreUsuario,
            type: "GET",
            dataType: "json",
            url: "files/procesar.php",
            success: function(data) {
                if(data.err == 1) {
                    alert("error");
                } else {
                }
            }
        });
    }
}

function obtenerIdCarrito() {
    $.ajax({
        data: "opc=obtenerIdCarrito&idUsuario=" + sessIdUsuario,
        type: "GET",
        dataType: "json",
        url: "files/procesar.php",
        success: function(data) {
            if(data.err == 1) {
                alert("error");
            } else {
                idCarrito = data.res['idCarrito'];
                actualizarCarrito();
            }
        }
    });
}