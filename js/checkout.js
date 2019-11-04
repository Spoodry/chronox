$("#btnConfirmarPedido").click(function() {
    confirmarPedido();
});

function confirmarPedido() {
    var informacionCompleta = true;

    if($("#nombre").val() == "")
        informacionCompleta = false;
    else if($("#apellido").val() == "")
        informacionCompleta = false;
    else if($("#direccion").val() == "")
        informacionCompleta = false;
    else if($("#codigoPostal").val() == "")
        informacionCompleta = false;
    else if($("#ciudad").val() == "")
        informacionCompleta = false;
    else if($("#estado").val() == "")
        informacionCompleta = false;
    else if($("#celular").val() == "")
        informacionCompleta = false;
    else if($("#email").val() == "")
        informacionCompleta = false;

    if(sessIdUsuario != -1) {
        if(informacionCompleta) {
            var fecha = new Date();
            var numPedido = "" + fecha.getFullYear() + fecha.getMonth() + fecha.getDate() + fecha.getHours() + fecha.getMinutes() + fecha.getSeconds();
            $.ajax({
                data: "opc=crearPedido&numPedido=" + numPedido + "&idUsuario=" + sessIdUsuario + "&idCarrito=" + idCarrito,
                type: "GET",
                dataType: "json",
                url: "files/procesar.php",
                success: function(data) {
                    if(data.err == 1) {
                        alert("error");
                    } else {
                        alert("N° Pedido #" + data.res['numPedido']);
                        location = "index.php";
                    }
                }
            });
        } else {
            alert("Información incompleta, llene los campos obligatorios");
        }
    }
}