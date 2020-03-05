<?php
    session_name('almacen');
    session_start();
    date_default_timezone_set('America/Monterrey');

    if(!isset($_SESSION['IdUsuario']) || $_SESSION['IdUsuario'] == -1) {
        header('location: login.php');
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Almacen</title>

    <link rel="icon" href="../img/core-img/favicon.ico">

    <!-- Core Style CSS -->
    <link rel="stylesheet" href="../css/core-style.css">
    <link rel="stylesheet" href="../style.css">

    <style>
        .table td {
            vertical-align: middle;
        }
    </style>

</head>
<body>
    <?php
        include('topbar.php');
    ?>
    <div class="container">
        <h1 class="h2 font-weight-light mb-2">Inventario</h1>
        
        <div class="text-right mb-4">
            <button class="btn btn-success" id="btnNuevoEquipo">
                <span class="icon text-white-50">
                    <i class="fa fa-plus"></i>
                </span>
                <span class="text">Nuevo Equipo</span>
            </button>
        </div>

        <div class="form-group row">
            <div class="col-xl-7 col-7">
                <input type="text" class="form-control" id="txtCadena">
            </div>
            <div class="col-xl-3 col-5">
                <select class="form-control" id="rdTipo">
                    <option value="Principio">Principio</option>
                    <option value="Medio">Medio</option>
                    <option value="Final">Final</option>
                </select>
            </div>
            
            <div class="col-xl-2 mt-xl-0 mt-3">
                <div class="text-xl-center text-center">
                    <button class="btn btn-primary btn-icon-split" onclick="buscar()">
                        <span class="icon text-white-50">
                            <i class="fa fa-search"></i>
                        </span>
                        <span class="text">Buscar</span>
                    </button>
                </div>
            </div>
        </div>
        
        <div class="table-responsive" id="tablaCompleta">
            <table class="table">
                <thead>
                    <tr>
                        <th>Serie</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Tipo</th>
                        <th>Asignación</th>
                        <th>Economico</th>
                        <th style="text-align: center;">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tableContenido">
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="altaEquipoMod" tabindex="-1" role="dialog" aria-labelledby="altaEquipoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="altaEquipoModalLabel">Alta de Equipo</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" enctype="multipart/form-data" id="formAltaEquipo">
                        <label class="h6 font-weight-light">Serie</label>
                        <input type = "text" class="form-control mb-2" id="txtSerieAlta" name = "serie">
                        <label class="h6 font-weight-light">Marca</label>
                        <input type = "text" class="form-control mb-2" id="txtMarcaAlta" name = "marca">
                        <label class="h6 font-weight-light">Modelo</label>
                        <input type = "text" class="form-control mb-2" id="txtModeloAlta" name="modelo">
                        <label class="h6 font-weight-light">Tipo</label>
                        <select name = "tipo" id="slTipoEquipo" class="form-control mb-2">
                        </select>
                        <label class="h6 font-weight-light">Asignación</label>
                        <select name="asignacion" id="slAsign" class="form-control mb-2">
                        </select>
                        <label class="h6 font-weight-light">Económico</label>
                        <input type = "text" class="form-control mb-2" id="txtEconoAlta" name = "economico">
                        <label class="h6 font-weight-light">Imagen</label>
                        <input type="file" class="form-control-file mb-2" id="fileImagenAlta" name="imagen">
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <button class="btn btn-success" id="btnAltaEquipo">
                        <span class="icon text-white-50">
                            <i class="fa fa-paper-plane"></i>
                        </span>
                        <span class="text">Enviar</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="agrAditMod" tabindex="-1" role="dialog" aria-labelledby="agregarAditamentoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="agregarAditamentoModalLabel">Agregar Aditamento</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label class="h6 font-weight-light">Tipo</label>
                    <select name="asignacion" id="slTiposAdit" class="form-control mb-2">
                    </select>
                    <label class="h6 font-weight-light">Descripción</label>
                    <input type="text" class="form-control">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <button class="btn btn-success" id="btnAltaEquipo">
                        <span class="icon text-white-50">
                            <i class="fa fa-paper-plane"></i>
                        </span>
                        <span class="text">Enviar</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery (Necessary for All JavaScript Plugins) -->
    <script src="../js/jquery/jquery-2.2.4.min.js"></script>
    <!-- Popper js -->
    <script src="../js/popper.min.js"></script>
    <!-- Bootstrap js -->
    <script src="../js/bootstrap.min.js"></script>
    <!-- Plugins js -->
    <script src="../js/plugins.js"></script>
    <!-- Classy Nav js -->
    <script src="../js/classy-nav.min.js"></script>
    <script src="https://kit.fontawesome.com/34336e5f41.js" crossorigin="anonymous"></script>

    <script src="js/sweetalert2/sweetalert2.all.min.js"></script>

    <script src="js/funciones.js"></script>

    <script src="js/recibir-datos.js"></script>

    <script src="js/envio-datos.js"></script>

    <script>
        activar('nvItemInv');
        buscar();

        obtenerTiposEquipo();
        obtenerUsuariosAsignacion();
        obtenerTiposAditamentos();

        $("#btnNuevoEquipo").click(function() {
            $("#altaEquipoMod").modal("toggle");
        });

        $("#btnAltaEquipo").click(function() {
            altaEquipo();
        });

    </script>

</body>
</html>