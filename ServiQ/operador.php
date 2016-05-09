<?php
	include("clases/Adm.php");
			
	//Se crea una instancia del administrador.
	$objAdm= new Adm();
	//La operacion por default esta vacia.
	
	$op="";          
	
	//Se "Cacha el valor de la operacion"
	//Ojo si se manda la operacion a traves de un formulario y a la vez por url, tiene prioridad la del formulario.
	if(isset($_GET["op"]))
		$op=$_GET["op"];
	if(isset($_POST["op"]))
		$op=$_POST["op"];

	$objAdm->doGet($op);
?>