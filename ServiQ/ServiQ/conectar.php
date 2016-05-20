<?php
	//$myDao;

	$link = mysql_connect('localhost', 'al342460', 'bdatos');

	if (!$link)
	{
		die('Could not connect:' .mysql_error());
	}

	include("clases/Dao.php");
	$myDao = new Dao();

	$arreglo = $myDao->consultaTabla("id_pe,fecha_hora","pedido","status like ('ESPERA')");
	$n = count($arreglo);
	$j = 0;
	$vector;
	$hora =(int)date("H");
	$minutos = (int)date("i");
	$segundos = (int)date("s");
	
	$tiempo = $hora*3600+$minutos*60+$segundos;
	
	for($i=0;$i<$n;$i++)
	{
		$fecha_hora = $arreglo[$i]["fecha_hora"];
		$idPedido = $arreglo[$i]["id_pe"];
		$hora_pedido = (int)substr($fecha_hora, 11, 2);
		$minutos_pedido = (int)substr($fecha_hora, 14, 2);
		$segundos_pedido = (int)substr($fecha_hora, 17, 2);
		
		$tiempo_del_pedido = $hora_pedido*3600 + $minutos_pedido*60 + $segundos_pedido;
		
		$comparacion = $tiempo - $tiempo_del_pedido;
		
		if($comparacion >= 120)
		{
			$vector[$j] = $idPedido; 
			$j += 1;
		}
	}
	
	for ($z = 0; $z < $j; $z++)
	{
		$id_pe = $vector[$z];
		$myDao->updateData("pedido", "id_pe = $id_pe","status = 'PENDIENTE'");
	}

?>