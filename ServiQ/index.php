<?php
ob_start();
?>
<html lang="en">

<head>

<meta charset="utf-8">
<link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
<link rel="icon" href="img/favicon.ico" type="image/x-icon">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Sistema de pedidos.">
<meta name="author" content="HeySoftware!">

<meta http-equiv="Content-Type" charset="UTF-8" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<title>ServiQ</title>

<!-- Bootstrap Core CSS -->
<link href="css/bootstrap.min.css" rel="stylesheet">

<!-- Custom CSS -->
<link href="css/business-casual.css" rel="stylesheet">

<!-- Fonts -->
<link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800" rel="stylesheet" type="text/css">
<link href="http://fonts.googleapis.com/css?family=Josefin+Slab:100,300,400,600,700,100italic,300italic,400italic,600italic,700italic" rel="stylesheet" type="text/css">

</head>

<body>

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/scripts.js"></script>
    
	<div class="brand">ServiQ</div>
    <div class="address-bar">UABC Sauzal</div>
	
	<!-- Seccion principal? :O -->
	<div class="container">		
		<!-- Aqui comienza el php-->	
		<?php
			ini_set('display_errors', 1);
			//Se incluye la clase administrador, En el administrador se definen todas las operaciones.
			include("clases/Adm.php");
			
			//Se crea una instancia del administrador.
			$objAdm= new Adm();
			session_start();
			//La operacion por default esta vacia.
			if(isset($_GET["op"]))
			{
				$op = $_GET["op"];
				$objAdm->doGet($op);
			}
			if(isset($_POST["op"]))
			{
				$op = $_POST["op"];
				$objAdm->doGet($op);
			}
			//$op="";          
			
			//Se "Cacha el valor de la operacion"
			//Ojo si se manda la operacion a traves de un formulario y a la vez por url, tiene prioridad la del formulario.
			/*if(isset($_GET["op"]))
				$op=$_GET["op"];
			if(isset($_POST["op"]))
				$op=$_POST["op"];*/
			
			//Si hay usuarios, se debe iniciar sesion.
			
			
			//Operaciones en las que no estas dentro del sistema, pero no se quiere mostrar la forma de Iniciar Sesion.
			/*if($op == "regForm" || $op == "regConf" || $op == "auth" || $op == "lgOut" || $op == "cod")
			{
				//Aqui se manda la operacion al adm. En realidad solo es para los casos que se encuentran en el if.
				$objAdm->doGet($op);
			}
			else
			{*/
				//Aqui se verifica si existe una sesion.
				if(isset($_SESSION["user"]))
				{
					//Si hay sesion, se permite el acceso al sistema.
					$usu = $_SESSION["user"];
					//(Esto se puede mejorar) lo que hace este if es:
					//Si la operacion no es vacia, de todos modos muestre el menu de navegacion.
					$objAdm->navBar(); 
					//Aqui se mandan las operaciones al adm.
					//El funcionamiento del sistema se encuentra aqui.
					//$objAdm->doGet($op); 
					?><div id="todo"><?php $objAdm->doGet(""); ?></div><?php
				}
				else
				{
					if(!isset($op))
					{
						//Si no hay una sesion existente, lo unico que se muestra es la forma de inicio de sesion.
						$objAdm->logInForm();
					}
				}
			//}	
		?>
	
	</div>
	
	
	<!-- Hace posible el funcionamiento de JavaScript -->
	<!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Script to Activate the Carousel -->
    <script>
    $('.carousel').carousel({
        interval: 5000 //changes the speed
    })
    </script>

    <script>
    	function modify_qty(val) {
    		var qty = document.getElementById('qty').value;
    		var new_qty = parseInt(qty,10) + val;
    
		    if (new_qty < 0) {
		        new_qty = 0;
		    }
		    
		    document.getElementById('qty').value = new_qty;
		    return new_qty;
		}
    </script>

</body>
</html>
<?php
ob_end_flush();
?>