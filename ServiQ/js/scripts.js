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