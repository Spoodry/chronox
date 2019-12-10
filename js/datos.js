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
                    location.reload();
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

function obtenerInfoProducto(idProducto) {
    if(idProducto != -1) {
        $.ajax({
            data: "opc=obtenerInfoProducto&idProducto=" + idProducto,
            type: "GET",
            dataType: "json",
            url: "files/procesar.php",
            success: function(data) {
                if(data.err == 1) {
                    alert("error");
                } else {
                    console.log(data.res);
                    datos = data.res[0];
                    $("#marca").html(datos['marca']);
                    $("#nombreProducto").html(datos['nombre']);
                    $("#precio").html("$" + datos['precio'].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));

                    var carrusel = "";
                    console.log(datos['cantImagenes']);
                    for (let i = 1; i <= datos['cantImagenes']; i++) {
                        var extensionImagen = "jpg";
                        carrusel += "<img src=\"img/product-img/" + datos['nombreImagen'] + "-" + i + "-B." + extensionImagen + "\" alt=\"\">";
                        //$("#imgCarrusel").trigger('add.owl.carousel', [carrusel]).trigger('refresh.owl.carousel');
                    }

                    var canLoop = true;

                    if(datos['cantImagenes'] == 1)
                        canLoop = false;

                    //add.owl.carousel();
                    $("#imgCarrusel").html(carrusel);

                    $('.product_thumbnail_slides').owlCarousel({
                        items: 1,
                        margin: 0,
                        loop: canLoop,
                        nav: canLoop,
                        navText: ["<img src='img/core-img/long-arrow-left.svg' alt=''>", "<img src='img/core-img/long-arrow-right.svg' alt=''>"],
                        dots: false,
                        autoplay: true,
                        autoplayTimeout: 5000,
                        smartSpeed: 1000
                    });

                    $("#descripcion").html(datos['descripcion']);

                    document.title = datos['nombre'];

                }
            }
        });
    } else {
        location = "index.php";
    }
}