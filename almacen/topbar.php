<nav class="navbar fixed-top navbar-expand-md mb-4 navbar-dark bg-danger">
    <a class="navbar-brand font-weight-light text-white">Almacen</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item" id="nvItemInv">
                <a class="nav-link" href="index.php">Inventario<span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item" id="nvItemInform">
                <a class="nav-link" href="buscarEquipos.php">Informe</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="formulario-equipo.php">Alta de Equipo</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="baja-equipo.php">Baja de Equipo</a>
            </li>
        </ul>
        <form class="form-inline my-2 my-lg-0">
            <button class="btn btn-outline-light my-2 my-sm-0" type="submit" onclick="window.location='login.php';">Cerrar Sesión</button>
        </form>
    </div>
</nav>