<?php
    session_name('chronox');
    session_start();
    date_default_timezone_set('America/Monterrey');
    if(isset($_GET['tipo'])) { $tipo = $_GET['tipo']; } else { $tipo = "todo"; }
    if(isset($_GET['opc'])) { $opcion = $_GET['opc']; } else { $opcion = ""; }
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
    <title>Shop</title>

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
                        <h2 id="tiendaTitulo"></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ##### Breadcumb Area End ##### -->

    <!-- ##### Shop Grid Area Start ##### -->
    <section class="shop_grid_area section-padding-80">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-4 col-lg-3">
                    <div class="shop_sidebar_area">

                        <!-- ##### Single Widget ##### -->
                        <div class="widget catagory mb-50">
                            <!-- Widget Title -->
                            <h6 class="widget-title mb-30">Categorías</h6>

                            <!--  Catagories  -->
                            <div class="catagories-menu">
                                <ul id="menu-content2" class="menu-content collapse show">
                                    <!-- Single Item -->
                                    <li data-toggle="collapse" data-target="#marcas">
                                        <a href="#">marcas</a>
                                        <ul class="sub-menu collapse show" id="marcas">
                                            <li><a href="#" onclick="obtenerProductos('todo','')">Todas</a></li>
                                            <li><a class="porMarca" href="#" value="1">Casio</a></li>
                                            <li><a class="porMarca" href="#" value="2">Rolex</a></li>
                                            <li><a class="porMarca" href="#" value="3">Seiko</a></li>
                                            <li><a class="porMarca" href="#" value="4">Omega</a></li>
                                            <li><a class="porMarca" href="#" value="5">Swatch</a></li>
                                            <li><a class="porMarca" href="#" value="6">Soxy</a></li>
                                            <li><a class="porMarca" href="#" value="7">Huawei</a></li>
                                        </ul>
                                    </li>
                                    <!-- Single Item -->
                                    <li data-toggle="collapse" data-target="#tipoReloj" class="collapsed">
                                        <a href="#">tipos</a>
                                        <ul class="sub-menu collapse" id="tipoReloj">
                                            <li><a href="#" onclick="obtenerProductos('todo','')">Todos</a></li>
                                            <li><a class="porTipoReloj" href="#" value="1">Analógico</a></li>
                                            <li><a class="porTipoReloj" href="#" value="2">Digital</a></li>
                                            <li><a class="porTipoReloj" href="#" value="3">Smartwatch</a></li>
                                        </ul>
                                    </li>
                                    <!-- Single Item -->
                                    <li data-toggle="collapse" data-target="#accessories" class="collapsed">
                                        <a href="#">público</a>
                                        <ul class="sub-menu collapse" id="accessories">
                                            <li><a href="#">Todos</a></li>
                                            <li><a class="porTipoPublico" href="#" value="1">Hombres</a></li>
                                            <li><a class="porTipoPublico" href="#" value="2">Mujeres</a></li>
                                            <li><a class="porTipoPublico" href="#" value="3">Niños</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- ##### Single Widget ##### -->
                        <div class="widget price mb-50">
                            <!-- Widget Title -->
                            <h6 class="widget-title mb-30">Filter by</h6>
                            <!-- Widget Title 2 -->
                            <p class="widget-title2 mb-30">Price</p>

                            <div class="widget-desc">
                                <div class="slider-range">
                                    <div data-min="49" data-max="360" data-unit="$" class="slider-range-price ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" data-value-min="49" data-value-max="360" data-label-result="Range:">
                                        <div class="ui-slider-range ui-widget-header ui-corner-all"></div>
                                        <span class="ui-slider-handle ui-state-default ui-corner-all" tabindex="0"></span>
                                        <span class="ui-slider-handle ui-state-default ui-corner-all" tabindex="0"></span>
                                    </div>
                                    <div class="range-price">Range: $49.00 - $360.00</div>
                                </div>
                            </div>
                        </div>

                        <!-- ##### Single Widget ##### -->
                        <div class="widget color mb-50">
                            <!-- Widget Title 2 -->
                            <p class="widget-title2 mb-30">Color</p>
                            <div class="widget-desc">
                                <ul class="d-flex" id="lsColores">
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-12 col-md-8 col-lg-9">
                    <div class="shop_grid_product_area">
                        <div class="row">
                            <div class="col-12">
                                <div class="product-topbar d-flex align-items-center justify-content-between">
                                    <!-- Total Products -->
                                    <div class="total-products">
                                        <p id="cantProductos"></p>
                                    </div>
                                    <!-- Sorting -->
                                    <div class="product-sorting d-flex">
                                        <p>Sort by:</p>
                                        <form action="#" method="get">
                                            <select name="select" id="sortByselect">
                                                <option value="value">Highest Rated</option>
                                                <option value="value">Newest</option>
                                                <option value="value">Price: $$ - $</option>
                                                <option value="value">Price: $ - $$</option>
                                            </select>
                                            <input type="submit" class="d-none" value="">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row" id="datosProductos">

                        </div>

                    </div>
                    <!-- Pagination -->
                    <nav aria-label="navigation">
                        <ul class="pagination mt-50 mb-70">
                            <li class="page-item"><a class="page-link" href="#"><i class="fa fa-angle-left"></i></a></li>
                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#"><i class="fa fa-angle-right"></i></a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <!-- ##### Shop Grid Area End ##### -->

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
    <!-- Classy Nav js -->
    <script src="js/classy-nav.min.js"></script>
    <!-- Active js -->
    <script src="js/active.js"></script>

    <script src="js/tienda.js"></script>

    <script src="js/carrito.js"></script>

    <script src="js/datos.js"></script>

    <script>
        obtenerProductos("<?php echo $tipo; ?>", <?php echo $opcion; ?>);
        var sessIdUsuario = <?php if(isset($_SESSION['IdUsuario'])) { echo $_SESSION['IdUsuario']; } else { echo -1; } ?>;
        var idCarrito;
        obtenerIdCarrito();
        obtenerColores();
    </script>

</body>

</html>