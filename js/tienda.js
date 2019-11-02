$(".porMarca").click(function() {
    var idMarca = $(this).attr("value");
    obtenerProductosXMarca(idMarca);
});

$(".porTipoPublico").click(function() {
    var idTipoPublico = $(this).attr("value");
    obtenerProductosXTipoPublico(idTipoPublico);
});

function obtenerProductos(tipo, opcion) {
    if(tipo == "todo") {
        console.log("entra todo");
        $.ajax({
            data: "opc=obtenerProductos",
            type: "GET",
            dataType: "json",
            url: "files/procesar.php",
            success: function(data) {
                if(data.err == 1) {
                    alert("error");
                } else {
                    console.log(data.res);
                    $("#datosProductos").empty();
                    $("#tiendaTitulo").html("relojes");

                    var informacion = "";

                    $.each(data.res, function(index, array) {
                        informacion += crearInfoProducto(array);    
                    });

                    $("#datosProductos").append(informacion);

                    obtenerCantProductos();

                    $(".add-to-carrito").click(function() {
                        addToCarrito(this);
                    });

                }
            }
        });
    } else if(tipo == "marca") {
        obtenerProductosXMarca(opcion);
    } else if(tipo == "tipoPublico") {
        obtenerProductosXTipoPublico(opcion);
    }
    
}

function obtenerProductosXMarca(idMarca) {
    $.ajax({
        data: "opc=obtenerProductosXMarca&idMarca=" + idMarca,
        type: "GET",
        dataType: "json",
        url: "files/procesar.php",
        success: function(data) {
            if(data.err == 1) {
                alert("error");
            } else {
                console.log(data.res);
                $("#datosProductos").empty();

                var informacion = "";
                var marca = "";

                $.each(data.res, function(index, array) {
                    informacion += crearInfoProducto(array);    
                    marca = array['marca'];
                });

                $("#tiendaTitulo").html(marca);
                $("#datosProductos").append(informacion);

                obtenerCantProductosXMarca(idMarca);

                $(".add-to-carrito").click(function() {
                    addToCarrito(this);
                });

            }
        }
    });
}

function obtenerProductosXTipoPublico(idTipoPublico) {
    $.ajax({
        data: "opc=obtenerProductosXTipoPublico&idTipoPublico=" + idTipoPublico,
        type: "GET",
        dataType: "json",
        url: "files/procesar.php",
        success: function(data) {
            if(data.err == 1) {
                alert("error");
            } else {
                console.log(data.res);
                $("#datosProductos").empty();

                var informacion = "";
                var tipoPublico = "";

                $.each(data.res, function(index, array) {
                    informacion += crearInfoProducto(array);    
                    tipoPublico = array['tipoPublico'];
                });

                $("#tiendaTitulo").html(tipoPublico);
                $("#datosProductos").append(informacion);

                obtenerCantProductosXTipoPublico(idTipoPublico);

                $(".add-to-carrito").click(function() {
                    addToCarrito(this);
                });

            }
        }
    });
}

function crearInfoProducto(array) {
    var informacion = "";

    informacion += "<div class=\"col-12 col-sm-6 col-lg-4\"><div class=\"single-product-wrapper\">";
    var extensionImagen="jpg";
    if(array['marca'] == "Huawei" || array['marca'] == "Omega")
        extensionImagen="png";
    informacion += "<div class=\"product-img\"><img class=\"\" src=\"img/product-img/" + array['nombreImagen'] + "-1-B." + extensionImagen + "\" alt=\"\">";
    if(array['cantImagenes'] > 1)
        informacion += "<img class=\"hover-img\" src=\"img/product-img/" + array['nombreImagen'] + "-2-B." + extensionImagen + "\" alt=\"\">";
    informacion += "<div class=\"product-favourite\">";
    informacion += "<a href=\"#\" class=\"favme fa fa-heart\"></a></div></div><div class=\"product-description\">";
    informacion += "<span>" + array['marca'] + "</span><a href=\"single-product-details.html\">";
    informacion += "<h6>" + array['producto'] + "</h6></a><p class=\"product-price\">";
    informacion += "$" + array['precio'] + "</p><div class=\"hover-content\">";
    informacion += "<div class=\"add-to-cart-btn\"><a class=\"btn essence-btn add-to-carrito\" value=\"" + array['id'] + "\" style=\"color: white;\">añadir al carrito</a>";
    informacion += "</div></div></div></div></div>";

    return informacion;
}

function obtenerCantProductos() {
    $.ajax({
        data: "opc=cantProductos",
        type: "GET",
        dataType: "json",
        url: "files/procesar.php",
        success: function(data) {
            if(data.err == 1) {
                alert("error");
            } else {
                console.log(data.res);

                cantidadProductos = data.res['cantProductos'];
                console.log(cantidadProductos);

                $("#cantProductos").empty();

                informacion = "<span>" + cantidadProductos + "</span>";
                if(cantidadProductos == 1) {
                    informacion += " producto encontrado";
                } else {
                    informacion += " productos encontrados";
                }

                $("#cantProductos").append(informacion);

            }
        }
    });
}

function obtenerCantProductosXMarca(idMarca) {
    $.ajax({
        data: "opc=cantProductosXMarca&idMarca=" + idMarca,
        type: "GET",
        dataType: "json",
        url: "files/procesar.php",
        success: function(data) {
            if(data.err == 1) {
                alert("error");
            } else {
                console.log(data.res);

                cantidadProductos = data.res['cantProductos'];
                console.log(cantidadProductos);

                $("#cantProductos").empty();

                informacion = "<span>" + cantidadProductos + "</span>";
                if(cantidadProductos == 1) {
                    informacion += " producto encontrado";
                } else {
                    informacion += " productos encontrados";
                }

                $("#cantProductos").append(informacion);

            }
        }
    });
}

function obtenerCantProductosXTipoPublico(idTipoPublico) {
    $.ajax({
        data: "opc=cantProductosXTipoPublico&idTipoPublico=" + idTipoPublico,
        type: "GET",
        dataType: "json",
        url: "files/procesar.php",
        success: function(data) {
            if(data.err == 1) {
                alert("error");
            } else {
                console.log(data.res);

                cantidadProductos = data.res['cantProductos'];
                console.log(cantidadProductos);

                $("#cantProductos").empty();

                informacion = "<span>" + cantidadProductos + "</span>";
                if(cantidadProductos == 1) {
                    informacion += " producto encontrado";
                } else {
                    informacion += " productos encontrados";
                }

                $("#cantProductos").append(informacion);

            }
        }
    });
}