<html lang="en">

<head>

<meta charset="utf-8">
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
    
	<div class="brand">ServiQ</div>
    <div class="address-bar">UABC SAUZAL</div>
	
	<!-- lo unico que se muestra en esta pagina es una forma para iniciar sesion como administrador. -->
	<div class="container">		
		<!-- Aqui comienza el php-->	
		<?php
			ini_set('display_errors', 1);
			//Se incluye la clase administrador, En el administrador se definen todas las operaciones.
			include("clases/Adm.php");
			
			//Se crea una instancia del administrador.
			$objAdm= new Adm();
			
			//Se manda a llamar directamente la funcion logInFormAdm.
			$objAdm->logInFormAdm();
		?>
	</div>

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
</body>
</html>