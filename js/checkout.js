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
    else if($("#numExterior").val() == "")
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
            var datos = $("#formPedido").serialize() + "&" + $.param({'numPedido' : numPedido}) + "&" + $.param({'idUsuario' : sessIdUsuario}) + "&" + $.param({'idCarrito' : idCarrito}) + "&" + $.param({'opc' : 'crearPedido'});
            console.log(datos);

            $.ajax({
                data: datos,
                type: "POST",
                dataType: "json",
                url: "files/procesar.php",
                success: function(data) {
                    if(data.err == 1) {
                        Swal.fire("Error","No se creó el pedido","error");
                    } else {
                        Swal.fire("Pedido exitoso", "N° Pedido #" + data.res['numPedido'], "success").then((result) => {
                            if (result.value) {
                                location = "index.php";
                            }
                          });
                    }
                }
            });
        } else {
            Swal.fire("Información incompleta", "Llene los campos obligatorios", "warning");
        }
    }
}