var xhr;
var datos = new Array();
var postAModificar = null;
var request;
var articulos;

window.onload = function() {
    cargarBienvenida();
}

function getPost (id) {
    if (datos.length > 0) {
        for (i in datos) {
            if (datos[i].id == id) {
                return i;
            }
        }
    }
}

function cargarBienvenida(){
    $.ajax({
        url:"/micomanda/",
        type:"GET",
        success:function(data) {
            console.log(data);
            $('#mensaje').text(data);
        }
    })
}

function traerInfo(tabla){
    $.ajax({
        url:"/"+tabla+"/",
        type:"GET",
        success:function(data) {
            console.log(data);
            cargarTabla(data, tabla);
        }
    })
}

function cargarTabla(data, tabla) {
    var table_content = '';
    for (i in data) {
        table_content += "<tr><th>"+data[i].id+"</th><th>"+data[i].codigo+"</th><th>"+data[i].estado+
        "</th><th><input type='button' value='Modificar' onclick='modificarArticulo("+data[i].id+
        ")'></th><th><input type='button' value='Eliminar' onclick='borrarArticulo("+data[i].id+")'></th></tr>";
    }
    $('#lista').show();
    $('#lista h2').text(tabla);
    $('#lista tbody').empty().html(table_content);
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

function guardarPost() {
    if (postAModificar === null && document.getElementById('titulo').value != '' && document.getElementById('articulo').value != '') {
        document.getElementById('loading').style.display = 'block';
        data = {
            "titulo": document.getElementById('titulo').value,
            "articulo": document.getElementById('articulo').value,
            "mas": "#",
            "collection": "posts"
        }
        xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function(){
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById('loading').style.display = 'none';
                document.getElementById('titulo').value = '';
                document.getElementById('articulo').value = '';
                var resp = JSON.parse(this.response);
                datos = resp.data;
                cargarTabla(resp.data);
            }
        };
        xhr.open("POST","http://localhost:3000/agregar",true);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.send(JSON.stringify(data));
    } else if (postAModificar != null) {
        document.getElementById('loading').style.display = 'block';
        var i = getPost(postAModificar);
        data = {
            "titulo": document.getElementById('titulo').value,
            "articulo": document.getElementById('articulo').value,
            "mas": datos[i].mas,
            "collection": "posts",
            "id": postAModificar,
            "active" : datos[i].active,
            "created_dttm" : datos[i].created_dttm
        }
        xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function(){
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById('loading').style.display = 'none';
                document.getElementById('titulo').value = '';
                document.getElementById('articulo').value = '';
                postAModificar = null;
                cargarDatos();
            }
        };
        xhr.open("POST","http://localhost:3000/modificar",true);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.send(JSON.stringify(data));
    }
}

function popularBarraNavegadora() {
    var html_articles = '';
    if (Object.keys(datos).length) {
        for (i in datos) {
            if (datos[i].active) {
                html_articles += '<li><a href="/#'+ datos[i].id +'">'+ datos[i].titulo +'</a></li>';
            }
        }
    }
    if (html_articles == '') {
        html_articles += '<li><a href="/admin.html"><b>Carge Articulos Aqui!</b></a></li>';
    }
    document.getElementsByTagName('aside')[0].getElementsByTagName('ul')[0].innerHTML = html_articles;
}