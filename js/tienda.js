$(".porMarca").click(function() {
    var idMarca = $(this).attr("value");
    obtenerProductosXMarca(idMarca);
});

function obtenerProductos() {
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

                var informacion = "";

                $.each(data.res, function(index, array) {
                    informacion += crearInfoProducto(array);    
                });

                $("#datosProductos").append(informacion);

                obtenerCantProductos();

            }
        }
    });
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

                $.each(data.res, function(index, array) {
                    informacion += crearInfoProducto(array);    
                });

                $("#datosProductos").append(informacion);

                obtenerCantProductosXMarca(idMarca);

            }
        }
    });
}

function crearInfoProducto(array) {
    var informacion = "";

    informacion += "<div class=\"col-12 col-sm-6 col-lg-4\"><div class=\"single-product-wrapper\">";
    informacion += "<div class=\"product-img\"><img src=\"img/product-img/" + array['nombreImagen'] + "-1-B.jpg\" alt=\"\">";
    if(array['cantImagenes'] > 1)
        informacion += "<img class=\"hover-img\" src=\"img/product-img/" + array['nombreImagen'] + "-2-B.jpg\" alt=\"\">";
    informacion += "<div class=\"product-favourite\">";
    informacion += "<a href=\"#\" class=\"favme fa fa-heart\"></a></div></div><div class=\"product-description\">";
    informacion += "<span>" + array['marca'] + "</span><a href=\"single-product-details.html\">";
    informacion += "<h6>" + array['producto'] + "</h6></a><p class=\"product-price\">";
    informacion += "$" + array['precio'] + "</p><div class=\"hover-content\">";
    informacion += "<div class=\"add-to-cart-btn\"><a href=\"#\" class=\"btn essence-btn\">añadir al carrito</a>";
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