var xhr;
var datos = new Array();
var postAModificar = null;
var request;
var articulos;

window.onload = function() {
    localStorage.setItem('token', '');
    validarToken();
    $("#clave").keyup(function(event) {
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
        url:"/micomanda/servidor/login/",
        //url:"/servidor/login/",
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
        url:"/micomanda/servidor/api/"+tabla+"/",
        //url:"/servidor/api/"+tabla+"/",
        type:"GET",
        beforeSend: function(xhr) {
            xhr.setRequestHeader('token', localStorage.getItem('token'));
        },
        success:function(data) {
            $('#loading').hide();
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

function borrarArticulo(id){
    xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function(){
        if (this.readyState == 4 && this.status == 200) {
           cargarDatos();
        }
    };
    xhr.open("POST","http://localhost:3000/eliminar",true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.send(JSON.stringify({"collection":"posts","id": id}));
}

function agregarElemento() {
    if ($('#tabla').attr('name') == 'mesa') {
        agregarMesa();
    } else {
        $('.frm-' + $('#tabla').attr('name')).toggle();
    }
}

function agregarComanda() {
    $('.formulario').hide();
    $('#loading').show();
    $.ajax({
        url:"/micomanda/servidor/api/comanda/",
        //url:"/servidor/api/comanda/",
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
        success:function(data) {
            alert(data['respuesta']);
            $('#loading').hide();
            traerInfo('comanda');
        },
        error:function(data) {
            $('#loading').hide();
            var data = JSON.parse(data.responseText)
            alert(data['respuesta']);
        }
    });
}

function agregarMesa() {
    $('.formulario').hide();
    $('#loading').show();
    $.ajax({
        url:"/micomanda/servidor/api/mesa/",
        //url:"/servidor/api/mesa/",
        type:"POST",
        beforeSend: function(xhr) {
            xhr.setRequestHeader('token', localStorage.getItem('token'));
        },
        success:function(data) {
            $('#loading').hide();
            traerInfo('mesa');
        },
        error:function(data) {
            $('#loading').hide();
            var data = JSON.parse(data.responseText)
            alert(data['respuesta']);
        }
    });
}

function cargarTabla(data, tabla) {
    var table_content = '<thead><tr>';
    switch(tabla) {
        case 'mesa':
            table_content += '<th>ID</th><th>Codigo</th><th>Estado</th><th>Modificar</th><th>Borrar</th></tr></thead><tbody>';
            for (i in data) {
                table_content += "<tr><th>"+data[i].id+"</th><th>"+data[i].codigo+"</th><th>"+data[i].estado+"</th>"+
                "<th><input type='button' value='Modificar' onclick='modificarArticulo("+data[i].id+")'></th>"+
                "<th><input type='button' value='Eliminar' onclick='borrarArticulo("+data[i].id+")'></th></tr>";
            }
            table_content += '</tbody>';
            break;
        case 'comanda':
            table_content += '<th>ID</th><th>Codigo</th><th>Cliente</th><th>Mesa</th><th>Importe</th><th>Modificar</th><th>Borrar</th></tr></thead><tbody>';
            for (i in data) {
                var importe = data[i].importe == null ? "-" : "$ " + data[i].importe;
                table_content += "<tr><th>"+data[i].id+"</th><th>"+data[i].codigo+"</th><th>"+data[i].nombreCliente+"</th><th>"+data[i].idMesa+"</th><th>"+importe+"</th>"+
                "<th><input type='button' value='Modificar' onclick='modificarArticulo("+data[i].id+")'></th>"+
                "<th><input type='button' value='Eliminar' onclick='borrarArticulo("+data[i].id+")'></th></tr>";
            }
            table_content += '</tbody>';
            break;
        case 'pedido':
            table_content += '<th>ID</th><th>Comanda #</th><th>Sector</th><th>Descripcion</th><th>Estado</th><th>Ingresado</th><th>Empleado A Cargo</th><th>Estimado</th><th>Preparado</th><th>Modificar</th><th>Borrar</th></tr></thead><tbody>';
            for (i in data) {
                var estimacion = data[i].estimacion == null ? "-" : data[i].estimacion;
                var empleado = data[i].idEmpleado == null ? "-" : data[i].idEmpleado;
                var entregado = data[i].fechaEntregado == "0000-00-00 00:00:00" || data[i].fechaEntregado == null ? "-" : data[i].fechaEntregado;
                table_content += "<tr><th>"+data[i].id+"</th><th>"+data[i].idComanda+"</th><th>"+data[i].sector+"</th><th>"+data[i].descripcion+"</th><th>"+data[i].estado+"</th><th>"+data[i].fechaIngresado+
                "</th><th>"+empleado+"</th><th>"+estimacion+"</th><th>"+entregado+"</th>"+
                "<th><input type='button' value='Modificar' onclick='modificarArticulo("+data[i].id+")'></th>"+
                "<th><input type='button' value='Eliminar' onclick='borrarArticulo("+data[i].id+")'></th></tr>";
            }
            table_content += '</tbody>';
            break;
        case 'empleado':
            table_content += '<th>ID</th><th>Usuario</th><th>Clave</th><th>Estado</th><th>Sector</th>';
            if (data.length>0 && 'sueldo' in data[0]) {
                table_content += "<th>Sueldo</th>"
            }
            table_content += '<th>Modificar</th><th>Borrar</th></tr></thead><tbody>';
            for (i in data) {
                var sueldo = 'sueldo' in data[i] ? "<th>$ "+data[i].sueldo+"</th>" : ""
                table_content += "<tr><th>"+data[i].id+"</th><th>"+data[i].usuario+"</th><th>"+data[i].clave+"</th><th>"+data[i].estado+"</th><th>"+data[i].sector+"</th>"+sueldo+
                "<th><input type='button' value='Modificar' onclick='modificarArticulo("+data[i].id+")'></th>"+
                "<th><input type='button' value='Eliminar' onclick='borrarArticulo("+data[i].id+")'></th></tr>";
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

