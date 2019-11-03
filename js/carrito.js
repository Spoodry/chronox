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
                    actualizarCarrito();
                }
            }
        });
    }
}

function actualizarCarrito() {
    $.ajax({
        data: "opc=obtenerProductosEnCarrito&idCarrito=" + idCarrito,
        type: "GET",
        dataType: "json",
        url: "files/procesar.php",
        success: function(data) {
            if(data.err == 1) {
                alert("error");
            } else {
                var productos = "";
                $.each(data.res, function(index, array) {
                    productos += "<div class=\"single-cart-item\"><a href=\"#\" class=\"product-image\">";
                    var extensionImagen = "jpg";
                    if(array['marca'] == "Huawei" || array['marca'] == "Omega")
                        extensionImagen = "png";
                    productos += "<img src=\"img/product-img/" + array['nombreImagen'] + "-1-B." + extensionImagen + "\" class=\"cart-thumb\" alt=\"\">";
                    productos += "<div class=\"cart-item-desc\"><span class=\"product-remove\"><i class=\"fa fa-close\" aria-hidden=\"true\" value=\"" + array['id'] + "\">";
                    productos += "</i></span><span class=\"badge\">" + array['marca'] + "</span><h6>" + array['nombre'] + "</h6>";
                    productos += "<p class=\"color\">Color: " + array['color'] + "</p>";
                    productos += "<p class=\"price\">$" + array['precio'].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "</p></div></a></div>";
                });

                $("#listaCarrito").html(productos);

                obtenerCantCarrito()
            }
        }
    });
}

function obtenerCantCarrito() {
    $.ajax({
        data: "opc=cantProductosCarrito&idCarrito=" + idCarrito,
        type: "GET",
        dataType: "json",
        url: "files/procesar.php",
        success: function(data) {
            if(data.err == 1) {
                alert("error");
            } else {
                console.log("cantProductosCarrito: " + data.res['cantProductosCarrito']);
                $("#spHCantCarrito").html(data.res['cantProductosCarrito']);
                $("#spRCantCarrito").html(data.res['cantProductosCarrito']);
            }
        }
    });
}