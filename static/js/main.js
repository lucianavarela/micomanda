var elementoAModificar = null;

window.onload = function() {
    localStorage.setItem('token', '');
    validarToken();
    //$.noConflict();
    $("#clave, #usuario").keyup(function(event) {
        if (event.keyCode === 13) {
            login();
        }
    });
}

function validarToken() {
    $('#mensaje').text("Please login!");
    localStorage.setItem('token', '');
    $('#login').show();
    $('#lista').hide();
    $('.formulario').hide();
    $('.login a').text('Login');
    $('#agregar').removeClass('empleado');
    $('#tabMetricas').css('display', 'none');
    $('#listaClientes').hide();
    elementoAModificar = null;
}

function login() {
    $.ajax({
        //url:"/micomanda/servidor/login/",
        url:"/servidor/login/",
        type:"POST",
        data: {
            'usuario': $('#login #usuario').val(),
            'clave': $('#login #clave').val()
        },
        success:function(data) {
            localStorage.setItem('token', data['token']);
            $('#loading').hide();
            $('#login').hide();
            $('#mensaje').text("Bienvenido");
            $('.login a').text(data['usuario']);
            if(data['sector'] == 'management') {
                $('#agregar').addClass('empleado');
                $('#tabMetricas').css('display', 'inline-block');
            }
        },
        error:function(data) {
            $('#loading').hide();
            var data = JSON.parse(data.responseText);
            alert(data['respuesta']);
        }
    });
}

function verEstadoPedidos(){
    var mesa = $('#menuClientes #mesa').val();
    var comanda = $('#menuClientes #comanda').val();
    if (mesa != '' && comanda != '') {
        $('#loading').show();
        $('#menuClientes .is-box').val('');
        $.ajax({
            //url:"/micomanda/servidor/api/comanda/"+mesa+"/"+comanda,
            url:"/servidor/api/comanda/"+mesa+"/"+comanda,
            type:"GET",
            success:function(data) {
                $('#loading').hide();
                if (data.length > 0) {
                    var table_content = '<thead><tr><th>Sector</th><th>Descripcion</th><th>Listo en</th></tr></thead><tbody>';
                    for (i in data) {
                        var estimacion = data[i].estimacion == null ? "-" : data[i].estimacion;
                        table_content += "<tr><th>"+data[i].sector+"</th><th>"+data[i].descripcion+"</th><th>"+estimacion+"</th>";
                    }
                    table_content += '</tbody>';
                    $('#listaClientes').show().find('#tabla-clientes').attr('name', tabla).html(table_content);
                } else {
                    alert("Su comanda no tiene pedidos ingresados");
                }
            },
            error:function(data) {
                $('#loading').hide();
                var data = JSON.parse(data.responseText)
                alert(data['respuesta']);
            }
        });
    }
}

function traerInfo(tabla) {
    elementoAModificar = null;
    var url = tabla == 'metrica'? 'empleado/metricas' : tabla;
    $('.formulario').hide();
    $('#loading').show();
    $.ajax({
        //url:"/micomanda/servidor/api/"+url+"/",
        url:"/servidor/api/"+url+"/",
        type:"GET",
        beforeSend: function(xhr) {
            xhr.setRequestHeader('token', localStorage.getItem('token'));
        },
        success:function(data) {
            $('#loading').hide();
            if ($('#agregar').hasClass(tabla)) {
                $('#agregar').show();
            } else {
                $('#agregar').hide();
            }
            $('#login').hide();
            cargarTabla(data, tabla);
        },
        error:function(data) {
            $('#loading').hide();
            var data = JSON.parse(data.responseText)
            alert(data['respuesta']);
        }
    });
}

function borrarElemento(id, tabla){
    $('#loading').show();
    $.ajax({
        //url:"/micomanda/servidor/api/"+tabla+"/"+id,
        url:"/servidor/api/"+tabla+"/"+id,
        type:"DELETE",
        contentType: 'application/json',
        beforeSend: function(xhr) {
            xhr.setRequestHeader('token', localStorage.getItem('token'));
        },
        complete:function(data) {
            var data = JSON.parse(data.responseText);
            alert(data['respuesta']);
        },
        success:function() {
            $('#loading').hide();
            traerInfo(tabla);
        },
        error:function() {
            $('#loading').hide();
        }
    });
}

function agregarElemento() {
    if ($('#tabla').attr('name') == 'mesa') {
        agregarMesa();
    } else {
        $('.frm-' + $('#tabla').attr('name') + ' .field').val('');
        $('.frm-' + $('#tabla').attr('name')).toggle();
    }
}

function agregarMesa() {
    $('.formulario').hide();
    $('#loading').show();
    $.ajax({
        //url:"/micomanda/servidor/api/mesa/",
        url:"/servidor/api/mesa/",
        type:"POST",
        beforeSend: function(xhr) {
            xhr.setRequestHeader('token', localStorage.getItem('token'));
        },
        complete:function(data) {
            var data = JSON.parse(data.responseText);
            alert(data['respuesta']);
        },
        success:function() {
            $('#loading').hide();
            traerInfo('mesa');
        },
        error:function() {
            $('#loading').hide();
        }
    });
}

function agregarComanda() {
    $('#loading').show();
    $.ajax({
        //url:"/micomanda/servidor/api/comanda/",
        url:"/servidor/api/comanda/",
        type:"POST",
        data: {
            'nombreCliente': $('#frmComanda #cliente').val(),
            'idMesa': $('#frmComanda #mesa').val(),
            'barra': $('#frmComanda #barra').val(),
            'cerveza': $('#frmComanda #cerveza').val(),
            'candy': $('#frmComanda #candy').val(),
            'cocina': $('#frmComanda #cocina').val(),
        },
        beforeSend: function(xhr) {
            xhr.setRequestHeader('token', localStorage.getItem('token'));
        },
        complete:function(data) {
            var data = JSON.parse(data.responseText);
            alert(data['respuesta']);
        },
        success:function() {
            $('#loading').hide();
            $('.formulario').hide();
            traerInfo('comanda');
        },
        error:function() {
            $('#loading').hide();
        }
    });
}

function subirFotoComanda(button) {
    var file = button.files[0];
    var formdata = new FormData();
    $('#loading').show();
    formdata.append("foto", file);
    formdata.append("codigoComanda", $(button).attr('id'));
    $.ajax({
        //url:"/micomanda/servidor/api/comanda/foto",
        url:"/servidor/api/comanda/foto",
        type: "POST",
        data: formdata,
        processData: false,
        contentType: false,
        beforeSend: function(xhr) {
            xhr.setRequestHeader('token', localStorage.getItem('token'));
        },
        complete:function(data) {
            var data = JSON.parse(data.responseText);
            alert(data['respuesta']);
        },
        success:function() {
            $('#loading').hide();
            traerInfo('comanda');
        },
        error:function() {
            $('#loading').hide();
        }
    });
}

function agregarEmpleado() {
    var method, id_for_update;
    if (elementoAModificar == null) {
        id_for_update = ''
        method = 'POST';
    } else {
        id_for_update = elementoAModificar;
        method = 'PUT';
    }

    $('#loading').show();
    $.ajax({
        //url:"/micomanda/servidor/api/empleado/"+id_for_update,
        url:"/servidor/api/empleado/"+id_for_update,
        type:method,
        data: {
            'usuario': $('#frmEmpleado #usuario').val(),
            'clave': $('#frmEmpleado #clave').val(),
            'sector': $('#frmEmpleado #sector').val(),
            'estado': $('#frmEmpleado #estado').val(),
            'sueldo': $('#frmEmpleado #sueldo').val()
        },
        beforeSend: function(xhr) {
            xhr.setRequestHeader('token', localStorage.getItem('token'));
        },
        complete:function(data) {
            var data = JSON.parse(data.responseText);
            alert(data['respuesta']);
        },
        success:function() {
            $('.formulario').hide();
            $('#loading').hide();
            traerInfo('empleado');
        },
        error:function() {
            $('#loading').hide();
        }
    });
}

function agregarEncuesta() {
    $('#loading').show();
    $.ajax({
        //url:"/micomanda/servidor/api/encuesta/",
        url:"/servidor/api/encuesta/",
        type:"POST",
        data: {
            'idComanda': $('#frmEncuesta #comanda').val(),
            'puntosMozo': $('#frmEncuesta #mozo').val(),
            'puntosMesa': $('#frmEncuesta #mesa').val(),
            'puntosRestaurante': $('#frmEncuesta #restaurante').val(),
            'puntosCocinero': $('#frmEncuesta #cocina').val(),
            'comentario': $('#frmEncuesta #comentario').val()
        },
        complete:function(data) {
            var data = JSON.parse(data.responseText);
            alert(data['respuesta']);
        },
        success:function() {
            $('.frm-encuesta .field').val('');
            $('.formulario').hide();
            $('#mensaje').text("Please login!");
            $('#loading').hide();
            $('#login').show();
        },
        error:function() {
            $('#loading').hide();
        }
    });
}

function cobrarComanda(codigoComanda) {
    do {
        var importe = prompt("Ingrese el importe a cobrar (decimales separados con un \".\")");
        var importe_parsed = parseFloat(importe);
        if (isNaN(importe_parsed)) {
            alert('Por favor ingrese un numero!');
        }
    } while (isNaN(importe_parsed));

    $('#loading').show();
    $.ajax({
        //url:"/micomanda/servidor/api/comanda/cobrar",
        url:"/servidor/api/comanda/cobrar",
        type:"POST",
        data: {
            'codigoComanda': codigoComanda,
            'importe': importe_parsed
        },
        beforeSend: function(xhr) {
            xhr.setRequestHeader('token', localStorage.getItem('token'));
        },
        complete:function(data) {
            var data = JSON.parse(data.responseText);
            alert(data['respuesta']);
        },
        success:function() {
            $('#loading').hide();
            traerInfo('comanda');
        },
        error:function() {
            $('#loading').hide();
        }
    });
}

function cerrarMesa(codigoMesa) {
    $('#loading').show();
    $.ajax({
        //url:"/micomanda/servidor/api/mesa/cerrar",
        url:"/servidor/api/mesa/cerrar",
        type:"POST",
        data: {
            'codigoMesa': codigoMesa
        },
        beforeSend: function(xhr) {
            xhr.setRequestHeader('token', localStorage.getItem('token'));
        },
        complete:function(data) {
            var data = JSON.parse(data.responseText);
            alert(data['respuesta']);
        },
        success:function() {
            $('#loading').hide();
            traerInfo('mesa');
        },
        error:function() {
            $('#loading').hide();
        }
    });
}

function entregarPedido(idPedido, estadoPedido) {
    $('#loading').show();
    if (estadoPedido == 'en preparación') {
        $.ajax({
            //url:"/micomanda/servidor/api/empleado/entregar_pedido",
            url:"/servidor/api/empleado/entregar_pedido",
            type:"POST",
            data: {
                'idPedido': idPedido
            },
            beforeSend: function(xhr) {
                xhr.setRequestHeader('token', localStorage.getItem('token'));
            },
            complete:function(data) {
                var data = JSON.parse(data.responseText);
                alert(data['respuesta']);
            },
            success:function() {
                $('#loading').hide();
                traerInfo('pedido');
            },
            error:function() {
                $('#loading').hide();
            }
        });
    } else if (estadoPedido == 'listo para servir') {
        $.ajax({
            //url:"/micomanda/servidor/api/pedido/entregar_pedido",
            url:"/servidor/api/pedido/entregar_pedido",
            type:"POST",
            data: {
                'idPedido': idPedido
            },
            beforeSend: function(xhr) {
                xhr.setRequestHeader('token', localStorage.getItem('token'));
            },
            complete:function(data) {
                var data = JSON.parse(data.responseText);
                alert(data['respuesta']);
            },
            success:function() {
                $('#loading').hide();
                traerInfo('pedido');
            },
            error:function() {
                $('#loading').hide();
            }
        });
    } else {
        alert("El pedido no está en estado de ser entregado");
    }
}

function tomarPedido(idPedido) {
    var estimacion = prompt('Ingrese su estimación en minutos');
    $('#loading').show();
    $.ajax({
        //url:"/micomanda/servidor/api/empleado/tomar_pedido",
        url:"/servidor/api/empleado/tomar_pedido",
        type:"POST",
        data: {
            'idPedido': idPedido,
            'estimacion': estimacion
        },
        beforeSend: function(xhr) {
            xhr.setRequestHeader('token', localStorage.getItem('token'));
        },
        complete:function(data) {
            var data = JSON.parse(data.responseText);
            alert(data['respuesta']);
        },
        success:function() {
            $('#loading').hide();
            traerInfo('pedido');
        },
        error:function() {
            $('#loading').hide();
        }
    });
}

function cancelarPedido(idPedido) {
    $('#loading').show();
    $.ajax({
        //url:"/micomanda/servidor/api/pedido/cancelar_pedido/"+idPedido,
        url:"/servidor/api/pedido/cancelar_pedido/"+idPedido,
        type:"POST",
        beforeSend: function(xhr) {
            xhr.setRequestHeader('token', localStorage.getItem('token'));
        },
        complete:function(data) {
            var data = JSON.parse(data.responseText);
            alert(data['respuesta']);
        },
        success:function() {
            $('#loading').hide();
            traerInfo('pedido');
        },
        error:function() {
            $('#loading').hide();
        }
    });
}

function deshabilitarEmpleado(idEmpleado) {
    $('#loading').show();
    $.ajax({
        //url:"/micomanda/servidor/api/empleado/deshabilitar_empleado",
        url:"/servidor/api/empleado/deshabilitar_empleado",
        type:"POST",
        data: {
            'idEmpleado': idEmpleado
        },
        beforeSend: function(xhr) {
            xhr.setRequestHeader('token', localStorage.getItem('token'));
        },
        complete:function(data) {
            var data = JSON.parse(data.responseText);
            alert(data['respuesta']);
        },
        success:function() {
            $('#loading').hide();
            traerInfo('empleado');
        },
        error:function() {
            $('#loading').hide();
        }
    });
}

function activarEmpleado(idEmpleado) {
    $('#loading').show();
    $.ajax({
        //url:"/micomanda/servidor/api/empleado/activar_empleado",
        url:"/servidor/api/empleado/activar_empleado",
        type:"POST",
        data: {
            'idEmpleado': idEmpleado
        },
        beforeSend: function(xhr) {
            xhr.setRequestHeader('token', localStorage.getItem('token'));
        },
        complete:function(data) {
            var data = JSON.parse(data.responseText);
            alert(data['respuesta']);
        },
        success:function() {
            $('#loading').hide();
            traerInfo('empleado');
        },
        error:function() {
            $('#loading').hide();
        }
    });
}

function realizarEncuesta() {
    $('#mensaje').text("Ingrese los puntajes del 1 al 10 (1 siendo muy bajo y 10 excelente)");
    $('#login').hide();
    $('#frmEncuesta').show();
}

function modificarEmpleado(id) {
    $('#loading').show();
    $.ajax({
        //url:"/micomanda/servidor/api/empleado/"+id,
        url:"/servidor/api/empleado/"+id,
        type:"GET",
        data: {
            'idEmpleado': id
        },
        beforeSend: function(xhr) {
            xhr.setRequestHeader('token', localStorage.getItem('token'));
        },
        success:function(data) {
            var empleado = data;
            $('#loading').hide();
            traerInfo('empleado');
            elementoAModificar = id;
            $('#frmEmpleado #usuario').val(empleado.usuario);
            $('#frmEmpleado #clave').val(empleado.clave);
            $('#frmEmpleado #sector').val(empleado.sector);
            $('#frmEmpleado #estado').val(empleado.estado);
            $('#frmEmpleado #sueldo').val(empleado.sueldo);
            $('.frm-empleado').toggle();
        },
        error:function(data) {
            var data = JSON.parse(data.responseText);
            alert(data['respuesta']);
            $('#loading').hide();
        }
    });
}

function cargarTabla(data, tabla) {
    switch(tabla) {
        case 'mesa':
            table_content = '<thead><tr><th>ID</th><th>Codigo</th><th>Estado</th><th colspan="3">Acciones</th></tr></thead><tbody>';
            for (i in data) {
                table_content += "<tr><th>"+data[i].id+"</th><th>"+data[i].codigo+"</th><th>"+data[i].estado+"</th>"+
                "<th class='boton-tabla' onclick='cerrarMesa(\""+data[i].codigo+"\")'>Cerrar</th>"+
                "<th class='boton-tabla' onclick='borrarElemento("+data[i].id+",\""+tabla+"\")'>Borrar</th></tr>";
            }
            table_content += '</tbody>';
            break;
        case 'comanda':
            table_content = '<thead><tr><th>ID</th><th>Codigo</th><th>Cliente</th><th>Mesa</th><th>Importe</th><th>Foto</th><th colspan="2">Acciones</th></tr></thead><tbody>';
            for (i in data) {
                var importe = data[i].importe == null ? "-" : "$ " + data[i].importe;
                if (data[i].foto == ""){
                    var foto = '<input type="file" class="update-foto" name="foto" id="'+data[i].codigo+'" onchange="subirFotoComanda(this)">';
                } else {
                    var foto = "<img class='foto-comanda' src=\"servidor/fotos/"+data[i].foto+"\">";
                }
                table_content += "<tr><th>"+data[i].id+"</th><th>"+data[i].codigo+"</th><th>"+data[i].nombreCliente+"</th><th>"+data[i].idMesa+"</th><th>"+importe+"</th>"+
                "<th>"+foto+"</th>"+
                "<th class='boton-tabla' onclick='cobrarComanda(\""+data[i].codigo+"\")'>Cobrar</th>"+
                "<th class='boton-tabla' onclick='borrarElemento("+data[i].id+",\""+tabla+"\")'>Borrar</th></tr>";
            }
            table_content += '</tbody>';
            break;
        case 'pedido':
            table_content = '<thead><tr><th>ID</th><th>Comanda</th><th>Sector</th><th>Descripcion</th><th>Estado</th><th>Ingresado</th><th>Empleado</th><th>Estimado</th><th>Preparado</th><th colspan="4">Acciones</th></tr></thead><tbody>';
            for (i in data) {
                var estimacion = data[i].estimacion == null ? "-" : data[i].estimacion;
                var empleado = data[i].idEmpleado == null ? "-" : data[i].idEmpleado;
                var entregado = data[i].fechaEntregado == "0000-00-00 00:00:00" || data[i].fechaEntregado == null ? "-" : data[i].fechaEntregado;
                table_content += "<tr><th>"+data[i].id+"</th><th>"+data[i].idComanda+"</th><th>"+data[i].sector+"</th><th>"+data[i].descripcion+"</th><th>"+data[i].estado+"</th><th>"+data[i].fechaIngresado+
                "</th><th>"+empleado+"</th><th>"+estimacion+"</th><th>"+entregado+"</th>"+
                "<th class='boton-tabla' onclick='tomarPedido("+data[i].id+")'>Tomar</th>"+
                "<th class='boton-tabla' onclick='entregarPedido("+data[i].id+", \""+data[i].estado+"\")'>Entregar</th>"+
                "<th class='boton-tabla' onclick='cancelarPedido("+data[i].id+")'>Cancelar</th>"+
                "<th class='boton-tabla' onclick='borrarElemento("+data[i].id+",\""+tabla+"\")'>Borrar</th></tr>";
            }
            table_content += '</tbody>';
            break;
        case 'empleado':
            table_content = '<thead><tr><th>ID</th><th>Usuario</th><th>Clave</th><th>Estado</th><th>Sector</th>';
            if (data.length>0 && 'sueldo' in data[0]) {
                table_content += "<th>Sueldo</th>"
            }
            table_content += '<th>Cant. Logs</th><th colspan="4">Acciones</th></tr></thead><tbody>';
            for (i in data) {
                var sueldo = 'sueldo' in data[i] ? "<th>$ "+data[i].sueldo+"</th>" : ""
                table_content += "<tr><th>"+data[i].id+"</th><th>"+data[i].usuario+"</th><th>"+data[i].clave+"</th><th>"+data[i].estado+"</th><th>"+data[i].sector+"</th>"+sueldo+"<th>"+data[i].cantidad+"</th>"+
                "<th class='boton-tabla' onclick='deshabilitarEmpleado("+data[i].id+")'>Deshabilitar</th>"+
                "<th class='boton-tabla' onclick='activarEmpleado("+data[i].id+")'>Activar</th>"+
                "<th class='boton-tabla' onclick='modificarEmpleado("+data[i].id+")'>Modificar</th>"+
                "<th class='boton-tabla' onclick='borrarElemento("+data[i].id+",\""+tabla+"\")'>Borrar</th></tr>";
            }
            table_content += '</tbody>';
            break;
        case 'log':
            table_content = '<thead><tr><th>ID</th><th>Empleado</th><th>Fecha</th><th>Acción Realizada</th></tr></thead><tbody class="tabla-min">';
            for (i in data) {
                table_content += "<tr><th>"+data[i].id+"</th><th>"+data[i].idEmpleado+"</th><th>"+data[i].fecha+"</th><th>"+data[i].accion+"</th></tr>";
            }
            table_content += '</tbody>';
            break;
        case 'encuesta':
            table_content = '<thead><tr><th>ID</th><th>Comanda</th><th>Puntaje Mozo</th><th>Puntaje Mesa</th><th>Puntaje Restaurante</th><th>Puntaje Cocineros</th><th>Comentarios</th><th colspan="1">Acciones</th></tr></thead><tbody>';
            for (i in data) {
                table_content += "<tr><th>"+data[i].id+"</th><th>"+data[i].idComanda+"</th><th>"+data[i].puntosMozo+"</th><th>"+data[i].puntosMesa+
                "</th><th>"+data[i].puntosRestaurante+"</th><th>"+data[i].puntosCocinero+"</th><th>"+data[i].comentario+"</th>"+
                "<th class='boton-tabla' onclick='borrarElemento("+data[i].id+",\""+tabla+"\")'>Borrar</th></tr>";
            }
            table_content += '</tbody>';
            break;
        case 'metrica':
            table_content = '<thead><tr><th>Reporte</th><th>Resultado</th></tr></thead><tbody>';
            for (i in data) {
                table_content+= "<tr><th>"+i+"</th><th>";
                if (data[i].constructor === Array) {
                    for (j in data[i]) {
                        table_content += JSON.stringify(data[i][j]) + '<br>';
                    }
                } else {
                    table_content += JSON.stringify(data[i]);
                }
                table_content+="</th></tr>";
            }
            break;
        default:
            table_content += "</tr></thead><tbody></tbody>";
            break;
    }
    $('#mensaje').text(tabla.toUpperCase() + "S");
    $('#lista').show().find('#tabla').attr('name', tabla).html(table_content);
}