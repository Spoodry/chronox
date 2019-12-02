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
    <title>Checkout</title>

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

    <!-- ##### Breadcumb Area Start ##### -->
    <div class="breadcumb_area bg-img" style="background-image: url(img/bg-img/breadcumb.jpg);">
        <div class="container h-100">
            <div class="row h-100 align-items-center">
                <div class="col-12">
                    <div class="page-title text-center">
                        <h2>Checkout</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ##### Breadcumb Area End ##### -->

    <!-- ##### Checkout Area Start ##### -->
    <div class="checkout_area section-padding-80">
        <div class="container">
            <div class="row">

                <div class="col-12 col-md-6">
                    <div class="checkout_details_area mt-50 clearfix">

                        <div class="cart-page-heading mb-30">
                            <h5>Billing Address</h5>
                        </div>

                        <form id="formPedido" action="#" method="post">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nombre">Nombre <span>*</span></label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" value="" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="last_name">Apellido <span>*</span></label>
                                    <input type="text" class="form-control" id="apellido" name="apellido" value="" required>
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="company">Empresa</label>
                                    <input type="text" class="form-control" id="empresa" name="empresa" value="">
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="country">País <span>*</span></label>
                                    <select class="w-100" id="pais" name="pais">
                                        <option value="mex">México</option>
                                        <option value="eua">Estados Unidos</option>
                                    </select>
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="street_address">Dirección <span>*</span></label>
                                    <input type="text" class="form-control" id="direccion" name="calle" value="" required>
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="numExterior">Número exterior <span>*</span></label>
                                    <input type="text" class="form-control" id="numExterior" name="numExterior" value="" required>
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="numInterior">Número interior</label>
                                    <input type="text" class="form-control" id="numInterior" name="numInterior" value="">
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="postcode">Código Postal <span>*</span></label>
                                    <input type="text" class="form-control" id="codigoPostal" name="codigoPostal" value="">
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="city">Ciudad <span>*</span></label>
                                    <input type="text" class="form-control" id="ciudad" name="ciudad" value="">
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="state">Estado <span>*</span></label>
                                    <input type="text" class="form-control" id="estado" name="estado" value="">
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="phone_number">Número de celular <span>*</span></label>
                                    <input type="number" class="form-control" id="celular" name="celular" min="0" value="">
                                </div>
                                <div class="col-12 mb-4">
                                    <label for="email_address">Email <span>*</span></label>
                                    <input type="email" class="form-control" id="email" name="correo" value="">
                                </div>

                                <div class="col-12">
                                    <div class="custom-control custom-checkbox d-block mb-2">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1">
                                        <label class="custom-control-label" for="customCheck1">términos y condiciones</label>
                                    </div>
                                    <div class="custom-control custom-checkbox d-block mb-2">
                                        <input type="checkbox" class="custom-control-input" id="customCheck2">
                                        <label class="custom-control-label" for="customCheck2">Crear cuenta</label>
                                    </div>
                                    <div class="custom-control custom-checkbox d-block">
                                        <input type="checkbox" class="custom-control-input" id="customCheck3">
                                        <label class="custom-control-label" for="customCheck3">Recibir información de nuevos productos</label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-5 ml-lg-auto">
                    <div class="order-details-confirmation">

                        <div class="cart-page-heading">
                            <h5>Tu pedido</h5>
                            <p>Detalles</p>
                        </div>

                        <ul class="order-details-form mb-4">
                            <li><span>Productos</span> <span>Total</span></li>
                            <div id="listaProductosCheckout"></div>
                            <li><span>Subtotal</span> <span id="subTotalChk"></span></li>
                            <li><span>Envío</span> <span>Free</span></li>
                            <li><span>Total</span> <span id="totalChk"></span></li>
                        </ul>

                        <div id="accordion" role="tablist" class="mb-4">
                            <div class="card">
                                <div class="card-header" role="tab" id="headingOne">
                                    <h6 class="mb-0">
                                        <a data-toggle="collapse" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne"><i class="fa fa-circle-o mr-3"></i>Paypal</a>
                                    </h6>
                                </div>

                                <div id="collapseOne" class="collapse" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion">
                                    <div class="card-body">
                                        <p></p>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" role="tab" id="headingTwo">
                                    <h6 class="mb-0">
                                        <a class="collapsed" data-toggle="collapse" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo"><i class="fa fa-circle-o mr-3"></i>cobro a la entrega</a>
                                    </h6>
                                </div>
                                <div id="collapseTwo" class="collapse" role="tabpanel" aria-labelledby="headingTwo" data-parent="#accordion">
                                    <div class="card-body">
                                        <p></p>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" role="tab" id="headingThree">
                                    <h6 class="mb-0">
                                        <a class="collapsed" data-toggle="collapse" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree"><i class="fa fa-circle-o mr-3"></i>tarjeta de crédito/débito</a>
                                    </h6>
                                </div>
                                <div id="collapseThree" class="collapse" role="tabpanel" aria-labelledby="headingThree" data-parent="#accordion">
                                    <div class="card-body">
                                        <p></p>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" role="tab" id="headingFour">
                                    <h6 class="mb-0">
                                        <a class="collapsed" data-toggle="collapse" href="#collapseFour" aria-expanded="true" aria-controls="collapseFour"><i class="fa fa-circle-o mr-3"></i>Transferencia bancaria</a>
                                    </h6>
                                </div>
                                <div id="collapseFour" class="collapse show" role="tabpanel" aria-labelledby="headingThree" data-parent="#accordion">
                                    <div class="card-body">
                                        <p></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <a href="#" class="btn essence-btn" id="btnConfirmarPedido">Confirmar Pedido</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ##### Checkout Area End ##### -->

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
    <script src="js/checkout.js"></script>

    <script>
        crearUsuarioTemp('<?php if(isset($_SESSION['IdUsuario'])) { echo "true"; } else { echo "false"; } ?>');
        var sessIdUsuario = <?php if(isset($_SESSION['IdUsuario'])) { echo $_SESSION['IdUsuario']; } else { echo -1; } ?>;
        var idCarrito;
        var productosCheck;
        obtenerIdCarrito();

    </script>

</body>

</html>