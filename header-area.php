<header class="header_area">
    <div class="classy-nav-container breakpoint-off d-flex align-items-center justify-content-between">
        <!-- Classy Menu -->
        <nav class="classy-navbar" id="essenceNav">
            <!-- Logo -->
            <a class="nav-brand" href="index.php"><img src="img/core-img/logo.png" alt=""></a>
            <!-- Navbar Toggler -->
            <div class="classy-navbar-toggler">
                <span class="navbarToggler"><span></span><span></span><span></span></span>
            </div>
            <!-- Menu -->
            <div class="classy-menu">
                <!-- close btn -->
                <div class="classycloseIcon">
                    <div class="cross-wrap"><span class="top"></span><span class="bottom"></span></div>
                </div>
                <!-- Nav Start -->
                <div class="classynav">
                    <ul>
                        <li><a href="#">Shop</a>
                            <div class="megamenu">
                                <ul class="single-mega cn-col-4">
                                    <li class="title">Marcas</li>
                                    <li><a href="shop.php?tipo=marca&opc=1">Casio</a></li>
                                    <li><a href="shop.php?tipo=marca&opc=2">Rolex</a></li>
                                    <li><a href="shop.php?tipo=marca&opc=3">Seiko</a></li>
                                    <li><a href="shop.php?tipo=marca&opc=4">Omega</a></li>
                                    <li><a href="shop.php?tipo=marca&opc=5">Swatch</a></li>
                                    <li><a href="shop.php?tipo=marca&opc=6">Soxy</a></li>
                                    <li><a href="shop.php?tipo=marca&opc=7">Huawei</a></li>
                                </ul>
                                <ul class="single-mega cn-col-4">
                                    <li class="title">Tipos</li>
                                    <li><a href="shop.php?tipo=tipoReloj&opc=1">Analógico</a></li>
                                    <li><a href="#">Digital</a></li>
                                    <li><a href="#">Smartwatch</a></li>
                                </ul>
                                <ul class="single-mega cn-col-4">
                                    <li class="title">Público</li>
                                    <li><a href="shop.php?tipo=tipoPublico&opc=1">Hombres</a></li>
                                    <li><a href="shop.php?tipo=tipoPublico&opc=2">Mujeres</a></li>
                                    <li><a href="shop.php?tipo=tipoPublico&opc=3">Niños</a></li>
                                </ul>
                                <div class="single-mega cn-col-4">
                                    <img src="img/bg-img/bg-6.jpg" alt="">
                                </div>
                            </div>
                        </li>
                        <li><a href="#">Pages</a>
                            <ul class="dropdown">
                                <li><a href="index.html">Home</a></li>
                                <li><a href="shop.html">Tienda</a></li>
                                <li><a href="single-product-details.php">Product Details</a></li>
                                <li><a href="checkout.html">Checkout</a></li>
                                <li><a href="blog.html">Blog</a></li>
                                <li><a href="single-blog.html">Single Blog</a></li>
                                <li><a href="regular-page.html">Regular Page</a></li>
                                <li><a href="contact.html">Contact</a></li>
                            </ul>
                        </li>
                        <li><a href="blog.html">Blog</a></li>
                        <li><a href="contact.html">Contact</a></li>
                    </ul>
                </div>
                <!-- Nav End -->
            </div>
        </nav>

        <!-- Header Meta Data -->
        <div class="header-meta d-flex clearfix justify-content-end">
            <!-- Search Area -->
            <div class="search-area">
                <form action="#" method="post">
                    <input type="search" name="search" id="headerSearch" placeholder="Type for search">
                    <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                </form>
            </div>
            <!-- Favourite Area -->
            <div class="favourite-area">
                <a href="#"><img src="img/core-img/heart.svg" alt=""></a>
            </div>
            <!-- User Login Info -->
            <div class="user-login-info">
                <a href="#"><img src="img/core-img/user.svg" alt=""><span><?php if(isset($_SESSION['IdUsuario'])) { echo $_SESSION['IdUsuario']; } else { echo '0'; } ?></span></a>
            </div>
            <!-- Cart Area -->
            <div class="cart-area">
                <a href="#" id="essenceCartBtn"><img src="img/core-img/bag.svg" alt=""> <span id="spHCantCarrito"></span></a>
            </div>
        </div>

    </div>
</header>