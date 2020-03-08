function altaEquipo() {
    Swal.fire({
        title: "Aviso", 
        text: "¿Desea dar de alta el equipo?",
        type: "info",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí',
        cancelButtonText: 'No'
    }).then((result) => {
        if(result.value) {
            var formData = new FormData(document.getElementById('formAltaEquipo'));
            formData.append('opc', 'altaEquipo');
            var infoCompleta = true;

            if($("#txtSerieAlta").val() == "") {
                infoCompleta = false;
            }
            if($("#txtMarcaAlta").val() == "") {
                infoCompleta = false;
            }
            if($("#txtModeloAlta").val() == "") {
                infoCompleta = false;
            }
            if($("#slTipoEquipo").val() == -1) {
                infoCompleta = false;
            }
            if($("#slAsign").val() == -1) {
                infoCompleta = false;
            }
            if($("#txtEconoAlta").val() == "") {
                infoCompleta = false;
            }

            if(infoCompleta) {
                $.ajax({
                    data: formData,
                    url: 'files/nucleo.php',
                    type: 'POST',
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        console.log(data);
                        if(data.err == 0) {
                            Swal.fire("Aviso", "Equipo dado de alta exitosamente", "success").then((result) => {
                                if(result.value) {
                                    Swal.fire({
                                        title: "Aviso", 
                                        text: "¿Desea ver el reporte de alta del equipo?",
                                        type: "info",
                                        showCancelButton: true,
                                        confirmButtonColor: '#3085d6',
                                        cancelButtonColor: '#d33',
                                        confirmButtonText: 'Sí',
                                        cancelButtonText: 'No'
                                    }).then((resultNot) => {
                                        if(resultNot.value) {
                                            var url = "entrada-inventario.php?idEquipo=" + data.res['id'];
                                            window.open(url, "_blank");
                                        }
                                        $("#formAltaEquipo").trigger('reset');
                                        $("#altaEquipoMod").modal("hide");
                                    });
                                }
                            });
                        } else {
                            Swal.fire("Aviso", "El equipo no pudo ser dado de alta", "error");
                        }
                    }
                });
            } else {
                Swal.fire("Aviso", "Datos incompletos", "warning");
            }
        }
    });
}

function bajaEquipo(idEquipo) {
    Swal.fire({
        title: "Aviso", 
        text: "¿Dar de baja el equipo?",
        type: "info",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí',
        cancelButtonText: 'No'
    }).then((result) => {
        if(result.value) {
            $.ajax({
                data: $.param({'opc' : 'eliminarEquipo'}) + "&" + $.param({'idEquipo' : idEquipo}),
                type: 'GET',
                dataType: 'json',
                url: 'files/nucleo.php',
                success: function(data) {
                    if(data.err == 0)
                        Swal.fire("Aviso", "Equipo dado de baja exitosamente", "success");
                    else
                        Swal.fire("Aviso", "El equipo no pudo ser dado de baja", "error");
                }
            });     
        }
    });
}

function nuevoAditamento(idEquipo) {
    $("#agrAditMod").modal("toggle");

    $("#btnAgregarAdit").unbind();
    $("#btnAgregarAdit").click(function() {
        agregarAditamento(idEquipo);
    });
}

function agregarAditamento(idEquipo) {
    var infoCompleta = true;

    if($("#slTiposAdit").val() == -1) {
        infoCompleta = false;
    }
    if($("#txtDescAdit").val() == "") {
        infoCompleta = false;
    }

    if(infoCompleta) {
        Swal.fire({
            title: "Aviso", 
            text: "¿Está seguro que quiere agregar un aditamento?",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí',
            cancelButtonText: 'No'
        }).then((result) => {
            if(result.value) {
                $.ajax({
                    data: $.param({'opc' : 'agregarAditamento'}) + "&" + $.param({'idAsignacion' : idEquipo}) + "&" + $("#formAgrAdit").serialize(),
                    type: 'GET',
                    dataType: 'json',
                    url: 'files/nucleo.php',
                    success: function(data) {
                        if(data.err == 0)
                            Swal.fire("Aviso", "Aditamento agregado exitosamente", "success").then((result) => {
                                if(result.value) {
                                    $("#formAgrAdit").trigger('reset');
                                    $("#agrAditMod").modal('hide');
                                }
                            });
                        else
                            Swal.fire("Aviso", "El aditamento no pudo agregarse", "error");
                    }
                });     
            }
        });
    } else {
        Swal.fire("Aviso", "Datos incompletos", "warning");
    }
}