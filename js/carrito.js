function addToCarrito(idProducto) {
    console.log($(idProducto).attr("value") + " IdUsuario: " +sessIdUsuario);
    if(sessIdUsuario != -1) {
        $.ajax({
            data: "opc=addToCarrito&idProducto=" + $(idProducto).attr("value") + "&idUsuario=" + sessIdUsuario,
            type: "GET",
            dataType: "json",
            url: "files/procesar.php",
            success: function(data) {
                if(data.err == 1) {
                    alert("error");
                } else {
                    alert("Añadido al carrito exitosamente #" + data.res['id']);
                }
            }
        });
    }
}

function actualizarCarrito() {
    
}