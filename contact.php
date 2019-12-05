<?php
    session_name('chronox');
    session_start();
    date_default_timezone_set('America/Monterrey');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- The above 4 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <!-- Title  -->
    <title>Contact</title>

    <!-- Favicon  -->
    <link rel="icon" href="img/core-img/favicon.ico">

    <!-- Core Style CSS -->
    <link rel="stylesheet" href="css/core-style.css">
    <link rel="stylesheet" href="style.css">

</head>

<body>
    <!-- ##### Header Area Start ##### -->
    <?php
        include('header-area.php');
    ?>
    <!-- ##### Header Area End ##### -->

    <!-- ##### Right Side Cart Area ##### -->
    <?php 
        include('right-side-cart-area.php');
    ?>
    <!-- ##### Right Side Cart End ##### -->


    <div class="contact-area d-flex align-items-center">

        <div class="google-map">
            <div id="googleMap"></div>
        </div>

        <div class="contact-info">
            <h2>¿Cómo encontrarnos?</h2>

            <div class="contact-address mt-50">
                <p><span>Dirección:</span> Iturbide #145 Zona Centro Altamira, Tamaulipas, MX</p>
                <p><span>Teléfono:</span> +52 (833)-447-13-96</p>
                <p><a href="mailto:chronox.me@gmail.com">chronox.me@gmail.com</a></p>
            </div>
        </div>

    </div>

    <!-- ##### Footer Area Start ##### -->
    <?php
        include('footer-area.php');
    ?>
    <!-- ##### Footer Area End ##### -->

    <!-- jQuery (Necessary for All JavaScript Plugins) -->
    <script src="js/jquery/jquery-2.2.4.min.js"></script>
    <!-- Popper js -->
    <script src="js/popper.min.js"></script>
    <!-- Bootstrap js -->
    <script src="js/bootstrap.min.js"></script>
    <!-- Plugins js -->
    <script src="js/plugins.js"></script>
    <script src="js/sweetalert2/sweetalert2.all.js"></script>
    <!-- Classy Nav js -->
    <script src="js/classy-nav.min.js"></script>
    <!-- Active js -->
    <script src="js/active.js"></script>
    <script src="js/datos.js"></script>
    <script src="js/carrito.js"></script>
    <!-- Google Maps -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAwuyLRa1uKNtbgx6xAJVmWy-zADgegA2s"></script>
    <script src="js/map-active.js"></script>

    <script>
        crearUsuarioTemp('<?php if(isset($_SESSION['IdUsuario'])) { echo "true"; } else { echo "false"; } ?>');
        var sessIdUsuario = <?php if(isset($_SESSION['IdUsuario'])) { echo $_SESSION['IdUsuario']; } else { echo -1; } ?>;
        var idCarrito;
        obtenerIdCarrito();
    </script>

</body>

</html>