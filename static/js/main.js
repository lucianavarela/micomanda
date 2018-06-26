var xhr;
var datos = new Array();
var postAModificar = null;
var request;
var articulos;

window.onload = function() {
    localStorage.setItem('token', '');
    validarToken();
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
            }
        },
        error:function(data) {
            $('#loading').hide();
            var data = JSON.parse(data.responseText);
            alert(data['respuesta']);
        }
    });
}

function traerInfo(tabla){
    $('.formulario').hide();
    $('#loading').show();
    $.ajax({
        //url:"/micomanda/servidor/api/"+tabla+"/",
        url:"/servidor/api/"+tabla+"/",
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

function modificarArticulo(id){
    postAModificar = id;
    var i = getPost(id);
    document.getElementById('titulo').value = datos[i].titulo;
    document.getElementById('articulo').value = datos[i].articulo;
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

function agregarEmpleado() {
    $('#loading').show();
    $.ajax({
        //url:"/micomanda/servidor/api/empleado/",
        url:"/servidor/api/empleado/",
        type:"POST",
        data: {
            'usuario': $('#frmEmpleado #usuario').val(),
            'clave': $('#frmEmpleado #clave').val(),
            'sector': $('#frmEmpleado #sector').val(),
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
        url:"/micomanda/servidor/api/encuesta/",
        //url:"/servidor/api/encuesta/",
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

function realizarEncuesta() {
    $('#mensaje').text("Ingrese los puntajes del 1 al 10 (1 siendo muy bajo y 10 excelente)");
    $('#login').hide();
    $('#frmEncuesta').show();
}

function cargarTabla(data, tabla) {
    var table_content = '<thead><tr>';
    switch(tabla) {
        case 'mesa':
            table_content += '<th>ID</th><th>Codigo</th><th>Estado</th><th colspan="2">Acciones</th></tr></thead><tbody>';
            for (i in data) {
                table_content += "<tr><th>"+data[i].id+"</th><th>"+data[i].codigo+"</th><th>"+data[i].estado+"</th>"+
                "<th class='boton-tabla' onclick='modificarArticulo("+data[i].id+")'>Modificar</th>"+
                "<th class='boton-tabla' onclick='borrarElemento("+data[i].id+",\""+tabla+"\")'>Borrar</th></tr>";
            }
            table_content += '</tbody>';
            break;
        case 'comanda':
            table_content += '<th>ID</th><th>Codigo</th><th>Cliente</th><th>Mesa</th><th>Importe</th><th colspan="2">Acciones</th></tr></thead><tbody>';
            for (i in data) {
                var importe = data[i].importe == null ? "-" : "$ " + data[i].importe;
                table_content += "<tr><th>"+data[i].id+"</th><th>"+data[i].codigo+"</th><th>"+data[i].nombreCliente+"</th><th>"+data[i].idMesa+"</th><th>"+importe+"</th>"+
                "<th class='boton-tabla' onclick='modificarArticulo("+data[i].id+")'>Modificar</th>"+
                "<th class='boton-tabla' onclick='borrarElemento("+data[i].id+",\""+tabla+"\")'>Borrar</th></tr>";
            }
            table_content += '</tbody>';
            break;
        case 'pedido':
            table_content += '<th>ID</th><th>Comanda</th><th>Sector</th><th>Descripcion</th><th>Estado</th><th>Ingresado</th><th>Empleado</th><th>Estimado</th><th>Preparado</th><th colspan="4">Acciones</th></tr></thead><tbody>';
            for (i in data) {
                var estimacion = data[i].estimacion == null ? "-" : data[i].estimacion;
                var empleado = data[i].idEmpleado == null ? "-" : data[i].idEmpleado;
                var entregado = data[i].fechaEntregado == "0000-00-00 00:00:00" || data[i].fechaEntregado == null ? "-" : data[i].fechaEntregado;
                table_content += "<tr><th>"+data[i].id+"</th><th>"+data[i].idComanda+"</th><th>"+data[i].sector+"</th><th>"+data[i].descripcion+"</th><th>"+data[i].estado+"</th><th>"+data[i].fechaIngresado+
                "</th><th>"+empleado+"</th><th>"+estimacion+"</th><th>"+entregado+"</th>"+
                "<th class='boton-tabla' onclick='tomarPedido("+data[i].id+")'>Tomar</th>"+
                "<th class='boton-tabla' onclick='entregarPedido("+data[i].id+", \""+data[i].estado+"\")'>Entregar</th>"+
                "<th class='boton-tabla' onclick='modificarArticulo("+data[i].id+")'>Modificar</th>"+
                "<th class='boton-tabla' onclick='borrarElemento("+data[i].id+",\""+tabla+"\")'>Borrar</th></tr>";
            }
            table_content += '</tbody>';
            break;
        case 'empleado':
            table_content += '<th>ID</th><th>Usuario</th><th>Clave</th><th>Estado</th><th>Sector</th>';
            if (data.length>0 && 'sueldo' in data[0]) {
                table_content += "<th>Sueldo</th>"
            }
            table_content += '<th colspan="2">Acciones</th></tr></thead><tbody>';
            for (i in data) {
                var sueldo = 'sueldo' in data[i] ? "<th>$ "+data[i].sueldo+"</th>" : ""
                table_content += "<tr><th>"+data[i].id+"</th><th>"+data[i].usuario+"</th><th>"+data[i].clave+"</th><th>"+data[i].estado+"</th><th>"+data[i].sector+"</th>"+sueldo+
                "<th class='boton-tabla' onclick='modificarArticulo("+data[i].id+")'>Modificar</th>"+
                "<th class='boton-tabla' onclick='borrarElemento("+data[i].id+",\""+tabla+"\")'>Borrar</th></tr>";
            }
            table_content += '</tbody>';
            break;
        case 'log':
            table_content += '<th>ID</th><th>Empleado</th><th>Fecha</th><th>Acción Realizada</th><th colspan="2">Acciones</th></tr></thead><tbody>';
            for (i in data) {
                table_content += "<tr><th>"+data[i].id+"</th><th>"+data[i].idEmpleado+"</th><th>"+data[i].fecha+"</th><th>"+data[i].accion+"</th>"+
                "<th class='boton-tabla' onclick='modificarArticulo("+data[i].id+")'>Modificar</th>"+
                "<th class='boton-tabla' onclick='borrarElemento("+data[i].id+",\""+tabla+"\")'>Borrar</th></tr>";
            }
            table_content += '</tbody>';
            break;
        case 'encuesta':
            table_content += '<th>ID</th><th>Comanda</th><th>Puntaje Mozo</th><th>Puntaje Mesa</th><th>Puntaje Restaurante</th><th>Puntaje Cocineros</th><th>Comentarios</th><th colspan="2">Acciones</th></tr></thead><tbody>';
            for (i in data) {
                table_content += "<tr><th>"+data[i].id+"</th><th>"+data[i].idComanda+"</th><th>"+data[i].puntosMozo+"</th><th>"+data[i].puntosMesa+
                "</th><th>"+data[i].puntosRestaurante+"</th><th>"+data[i].puntosCocinero+"</th><th>"+data[i].comentario+"</th>"+
                "<th class='boton-tabla' onclick='modificarArticulo("+data[i].id+")'>Modificar</th>"+
                "<th class='boton-tabla' onclick='borrarElemento("+data[i].id+",\""+tabla+"\")'>Borrar</th></tr>";
            }
            table_content += '</tbody>';
            break;
        default:
            table_content += "</tr></thead><tbody></tbody>";
            break;
    }
    $('#mensaje').text(tabla.toUpperCase() + "S");
    $('#lista').show().find('#tabla').attr('name', tabla).html(table_content);
}

