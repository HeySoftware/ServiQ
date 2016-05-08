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

//Funcion de prueba:
/**
function updateProfile() {

	//Creamos nuestro objeto AJAX:
	var xmlhttp = crearObjetoAJAX();

	//Creamos y enviamos nuestra data al server a traves del objeto.
	//xmlhttp.open("GET", "nuestroCodigo.php", true);
	//xmlhttp.send();
**/


 
}
