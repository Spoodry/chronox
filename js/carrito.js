$(".add-carrito-index").click(function() {
    addToCarrito(this);
});

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

function eliminarDelCarrito(idProductoCarrito) {
    if(sessIdUsuario != -1) {
        $.ajax({
            data: "opc=eliminarDelCarrito&idProductoCarrito=" + $(idProductoCarrito).attr("value"),
            type: "GET",
            dataType: "json",
            url: "files/procesar.php",
            success: function(data) {
                if(data.err == 1) {
                    alert("error");
                } else {
                    alert("Producto eliminado del carrito");
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
                var datosProductos = "";
                $.each(data.res, function(index, array) {
                    productos += "<div class=\"single-cart-item\"><a href=\"#\" class=\"product-image\">";
                    var extensionImagen = "jpg";
                    productos += "<img src=\"img/product-img/" + array['nombreImagen'] + "-1-B." + extensionImagen + "\" class=\"cart-thumb\" alt=\"\">";
                    productos += "<div class=\"cart-item-desc\"><span class=\"product-remove\"><i class=\"fa fa-close eliminar-to-carrito\" aria-hidden=\"true\" value=\"" + array['id'] + "\">";
                    productos += "</i></span><span class=\"badge\">" + array['marca'] + "</span><h6>" + array['nombre'] + "</h6>";
                    productos += "<p class=\"color\">Color: " + array['color'] + "</p>";
                    productos += "<p class=\"price\">$" + array['precio'].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "</p></div></a></div>";
                    datosProductos += "<li><span>" + array['nombre'] + "</span> <span>$" + array['precio'].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "</span></li>";
                });

                $("#listaCarrito").html(productos);
                $("#listaProductosCheckout").html(datosProductos);

                $(".eliminar-to-carrito").click(function() {
                    eliminarDelCarrito(this);
                });

                obtenerCantCarrito();

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

                if(data.res['cantProductosCarrito'] != 0) {
                    $("#listaCarrito").removeClass("bg-danger");
                    resumenCarrito(data.res['cantProductosCarrito']);
                } else {
                    ZeroProdutosEnCarrito();
                }

            }
        }
    });
}

function ZeroProdutosEnCarrito() {
    $("#cartResumen").html("<h2>No hay productos en carrito :(<h2>");
    $("#listaCarrito").addClass("bg-danger");
}

function resumenCarrito(cantidad) {
    $.ajax({
        data: "opc=obtenerCarrito&idCarrito=" + idCarrito,
        type: "GET",
        dataType: "json",
        url: "files/procesar.php",
        success: function(data) {
            if(data.err == 1) {
                alert("error");
            } else {
                var informacion = "<h2>Resumen</h2><ul class=\"summary-table\">";
                informacion += "<li><span>cantidad de productos:</span><span>" + cantidad + "</span></li>";
                informacion += "<li><span>subtotal:</span> <span>$" + data.res['total'].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "</span></li>"
                informacion += "</ul><div class=\"checkout-btn mt-100\">";
                informacion += "<a href=\"checkout.php\" class=\"btn essence-btn\">check out</a></div>"
                
                $("#cartResumen").html(informacion);
                $("#subTotalChk").html("$" + data.res['total'].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                $("#totalChk").html("$" + data.res['total'].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            }
        }
    });
}