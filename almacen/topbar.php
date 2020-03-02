<nav class="navbar fixed-top navbar-expand-md mb-4 navbar-dark bg-dark">
    <a class="navbar-brand font-weight-light text-white" style="cursor: default;">Almacen</a>
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
        </ul>
        <label class="text-primary font-weight-bold mt-2 mr-4"><?php echo $_SESSION['NomUsuario'];?></label>
        <div class="form-inline my-2 my-lg-0">
            <button class="btn btn-outline-danger my-2 my-sm-0" onclick="window.location='login.php';"><i class="fa fa-sign-out-alt"></i></button>
        </div>
    </div>
</nav>