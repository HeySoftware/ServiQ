/**
 * Esta funcion sera utilizada la mayoria de los scripts.
 * Sera incluida en ellos.
 */
function crearObjetoAJAX() {
	//Creamos el objeto de respuesta:
	var xmlhttp;

	if (window.XMLHttpRequest) {
		xmlhttp =new XMLHttpRequest();
	}else{
		xmlhttp =new ActiveXObject("Microsoft.XMLHTTP");
	}

	//Saber si el servidor se encuentra listo.
	xmlhttp.onreadystatechange = function(){
		if(ajaxRequest.readyState == 4){
			document.getElementById("myData").innerHTML=xmlhttp.responseText;
		}
	}
	return xmlhttp;
}
 
function request(op){
    var xhttp = new XMLHttpRequest();
    var op = String(op);
    xhttp.onreadystatechange = function(){
        if(xhttp.readyState == 4 && xhttp.status == 200){
            document.getElementById("todo").innerHTML = xhttp.responseText;
        }
    };
    var url = "operador.php?op=";
    var operacion= url.concat(op);
    xhttp.open("GET",operacion, true);
    xhttp.send();
}

function cantidad(op,id_car){
    var xhttp = new XMLHttpRequest();
    var id = "cantidad".concat(id_car);
    xhttp.onreadystatechange = function(){
        if(xhttp.readyState == 4 && xhttp.status == 200){
            document.getElementById(id).innerHTML = xhttp.responseText;
        }
    };
    var url = "operador.php?op=".concat(op);
    xhttp.open("GET",url, true);
    xhttp.send();
}

function agregarCarrito(id_pl_cd)
{
	var xhttp = new XMLHttpRequest();
	var url = "operador.php?op=aCar&&".concat(id_pl_cd);
	xhttp.open("GET",url,true);
	xhttp.send();
}

function eliminarCarrito(id_pl_cd)
{
	var xhttp = new XMLHttpRequest();
	var url = "operador.php?op=eliminarCarrito&&".concat(id_pl_cd);
	xhttp.onreadystatechange = function(){
        if(xhttp.readyState == 4 && xhttp.status == 200){
            document.getElementById("todo").innerHTML = xhttp.responseText;
        }
    };
	xhttp.open("GET",url,true);
	xhttp.send();
}