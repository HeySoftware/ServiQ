<?php
	/*
	*	Esta clase se encarga de la interfaz con el usuario.
	*	Presenta la informacion procesada. No deberia realizar consultas.
	*/
	class Gui
	{
		/*
		*	Esta funcion muestra las opciones del menu para ordenar. Tambien la opcion de cerrar sesion.
		*/
		public function navBar($categorias,$carrito,$bandeja,$validarSudo=0) 
		{
			?>
			<nav class="navbar navbar-default" role="navigation">
        		<div class="container">
            		<!-- Brand and toggle get grouped for better mobile display -->
            		<div class="navbar-header">
                		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    		<span class="sr-only">Toggle navigation</span>
                    		<span class="icon-bar"></span>
                    		<span class="icon-bar"></span>
                    		<span class="icon-bar"></span>
                		</button>
                		<!-- navbar-brand is hidden on larger screens, but visible when the menu is collapsed -->
                		<a class="navbar-brand" href="index.php">ServiQ</a>
            		</div>
            	<!-- Collect the nav links, forms, and other content for toggling -->
            	<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                	<ul class="nav navbar-nav">                 	
                    	<li>
                    		<a href="#" onclick="request('')">Comida del Dia</a>
                        	<!--<a href="index.php">Comida del dia</a>-->
                    	</li>
                    	<?php
                    		$n = count($categorias);
                    		if($n != 0)
                    		{
                    		?>
                    			<li role="presentation" class="dropdown">
	        					<a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
	        					Menu
	        					<span class="caret"></span>
								<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
									<?php
										for($i=0;$i<$n;$i++)
										{
											$categoria = $categorias[$i]["id_ct"];
											$nombre = $categorias[$i]["categoria"];
											echo "<li><a href=\"#\" onclick=\"request('vMenu&&id_ct=$categoria')\">$nombre</a></li>";
										}
									?>
								</ul>
	                    		</li>
                    		<?php } ?>					
						<li role="presentation" class="dropdown">
        					<a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
        					Ver
        					<span class="caret"></span>
							<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
								<li>
									<?php
										if(isset($_SESSION["adm"]))
			                    		{
											//Anairene.- Gestionar Admins
											if($validarSudo==1)
											{
                    							echo "<a href=\"#\" onclick=\"request('consulAdm')\">Admins</a>";
												// Moxis
                    							echo "<a href=\"#\" onclick=\"request('recarga')\">Recarga</a>";
                    							//Moxis
											}
											//Fin Anairene.- Gestionar Admins
			                    			echo "<a href=\"#\" onclick=\"request('vPed')\">Pedidos</a>";
			                    		}
										else
										{
			                    			?>
			                    			<a href="#" onclick="request('vPCliente')">Mis Pedidos</a>
			                    			<a href="#" onclick="request('showFav')">Mis Favoritos</a>
			                    			<a href="#" onclick="request('vBEntrada')">Correo <span class="badge badge-success"><?php echo $bandeja;?></span></a>
			                    			<?php
			                    		}
			                    	?>
								</li>
								<?php
									if(!isset($_SESSION["adm"]))
									{
										?>
										<li>
											<a href="#" onclick="request('vCar')">Carrito <span class="badge"><?php echo $carrito; ?></span></a>
										</li>
										<?php
									}
								?>
							</ul>
							
                    	</li>
						
                    	<li>
                        	<a href="index.php?op=lgOut">Cerrar Sesion</a>
                    	</li>
                	</ul>
            	</div>
            	<!-- /.navbar-collapse -->
        	</div>
        	<!-- /.container -->
    		</nav>
    	<?php
		}
		
		public function logInForm()
		{
			?>
				<div class="panel panel-default col-lg-6 col-lg-offset-3">
						<div class="panel-title text-center"><h3>Iniciar Sesion</h3></div>
						<div class="panel-body">
								<form action="index.php?op=auth" method="POST" class="form-horizontal">
								
									<div class="form-group">
										<label for="correo_login" class="col-sm-2 control-label">Correo</label>
										<div class="col-sm-8">
											<input type="email" class="form-control" name="correo" placeholder="correo@uabc.edu.mx">
										</div>
									</div>
									
									<div class="form-group">
										<label for="correo_login" class="col-sm-2 control-label">Contrase&ntilde;a</label>
										<div class="col-sm-8">
											<input type="password" class="form-control" name="clave">
										</div>
									</div>
									<div class="form-group">
										<div class="col-sm-offset-2 col-xs-8">
											
											<button type="submit" class="btn btn-success">Entrar</button>
											
										</div>

									</div>

									<input type="hidden" name="adm" value=0> 

							</form>
							<a class="btn btn-primary" href="index.php?op=regForm" role="button">Registrarse</a>
						</div>
				</div>
			<?php
		}
		
		public function logInFormAdm()
		{
			?>
			<div class="container">
				<div class="panel panel-default col-lg-6 col-lg-offset-3">
						<div class="panel-title text-center"><h3>Iniciar Sesion</h3></div>
						<div class="panel-body">
								<form action="index.php?op=auth" method="POST" class="form-horizontal">
								
									<div class="form-group">
										<label for="correo_login" class="col-sm-2 control-label">Usuario</label>
										<div class="col-sm-8">
											<input type="text" class="form-control" name="correo">
										</div>
									</div>
									
									<div class="form-group">
										<label for="correo_login" class="col-sm-2 control-label">Contrase&ntilde;a</label>
										<div class="col-sm-8">
											<input type="password" class="form-control" name="clave">
										</div>
									</div>
									<div class="form-group">
										<div class="col-sm-offset-2 col-xs-8">
											<button type="submit" class="btn btn-success">Entrar</button>
										</div>
									</div>

									<input type="hidden" name="adm" value=1> 

								</form>
						</div>
				</div>
			</div>
			<?php
		}
		
		
		// ANAIRENE.- REGISTRO DE CLIENTE 16/Abril/2016
		public function formaRegistro()
		{
			?>
			<div class="container">
				<div class="panel panel-default col-lg-8 col-lg-offset-2">
					<div class="panel-title text-center"><h3>Registrarse</h3></div>
					<div class ="panel-body">
						<form action="index.php?op=regConf" method="POST" class="form-horizontal">
						
							<div class="form-group">
								<label for="correo_registro" class="col-sm-2 control-label">Correo</label>
								<div class="col-sm-8">
									<input type="email" class="form-control" name="cliente[correo]" placeholder="correo@uabc.edu.mx">
								</div>
							</div>
							
							<div class="form-group">
								<label for="correo_password" class="col-sm-2 control-label">Contrase&ntilde;a</label>
								<div class="col-sm-8">
									<input type="password" class="form-control" name="cliente[clave]" placeholder="6 digitos">
								</div>
							</div>
							
							<div class="form-group">
								<label for="correo_password" class="col-sm-2 control-label">Confirma contrase&ntilde;a</label>
								<div class="col-sm-8">
									<input type="password" class="form-control" name="cliente[clave2]">
								</div>
							</div>
							
							<div class="form-group">
								<div class="col-sm-offset-2 col-xs-8">
									<button type="submit" class="btn btn-success">Aceptar</button>
									<a class="btn btn-default" href=index.php role="button">Regresar</a>
								</div>
							</div>
					
						</form>
					</div>
				</div>
			</div>
			<?php
		}
		//FIN ANAIRENE.- REGISTRO DE CLIENTE 
		
		//Anairene .- Gestionar Admins
		
		/**
         *	Funcion para ingresar datos de un nuevo administrador. 
         * @return None
         */
		public function formaAnadirAdm()
		{
			?>
			<div class="container">
				<div class="panel panel-default col-lg-8 col-lg-offset-2">
					<div class="panel-title text-center"><h3>Nuevo Administrador</h3></div>
					<div class ="panel-body">
						<form action="index.php?op=saveAdm" method="POST" class="form-horizontal">
						
							<div class="form-group">
								<label for="correo_registro" class="col-sm-2 control-label">Nombre Completo</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" name="admin[nombre]" placeholder="">
								</div>
							</div>
							
							<div class="form-group">
								<label for="correo_registro" class="col-sm-2 control-label">Correo</label>
								<div class="col-sm-8">
									<input type="email" class="form-control" name="admin[correo]" placeholder="adm@correo.com">
								</div>
							</div>
                            
                            <div class="form-group">
								<label for="correo_registro" class="col-sm-2 control-label">Direccion</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" name="admin[direccion]" placeholder="Calle   Numero   Colonia">
								</div>
							</div>
                            
                            <div class="form-group">
								<label for="correo_registro" class="col-sm-2 control-label">Telefono</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" name="admin[telefono]" placeholder="(000) 000-00-00">
								</div>
							</div>
                            
                            <div class="form-group">
								<label for="correo_registro" class="col-sm-2 control-label">Usuario</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" name="admin[usuario]">
								</div>
							</div>
                            
                            <div class="form-group">
								<label for="correo_password" class="col-sm-2 control-label">Contrase&ntilde;a</label>
								<div class="col-sm-8">
									<input type="password" class="form-control" name="admin[clave]" placeholder="6 d&iacute;gitos">
								</div>
							</div>
                            <div class="form-group">
								<label for="correo_password" class="col-sm-2 control-label">Confirmar Contrase&ntilde;a</label>
								<div class="col-sm-8">
									<input type="password" class="form-control" name="admin[clave2]" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-2 col-xs-8">
									<button type="submit" class="btn btn-success">A&ntilde;adir</button>
									<a class="btn btn-default" href=index.php?op=consulAdm role="button">Regresar</a>
								</div>
							</div>
					
						</form>
					</div>
				</div>
			</div>
			<?php
		}
		/**
         *	Funcion para ver la lista de administradores. 
		 *	En caso de estar vacio muestra un mensaje. 
         *	@param $admins, Matriz que contiene los datos de los administradores. 
         * @return None
         */
		public function formaConsulAdm($admins)
		{
		
			$cantidad = count($admins);
			if ($cantidad == 0)
			{
				?>
				<div class="alert alert-danger" role="alert">
				<h3>No hay cuentas de Administrador.</h3><br/>
				<a class="btn btn-default" href=index.php role="button">Regresar</a>
				</div>
				<?php
			}
			else
			{
				?>
				<div class="panel panel-default">
				<div class="table-responsive">
				<table class="table table-hover">
				<tr><th>ID</th><th>NOMBRE</th><th>USUARIO</th><th align="right"><a class="btn btn-warning" href=index.php?op=anadirAdm role="button"><strong>A&Ntilde;ADIR ADMIN</strong></a></th></tr>
				<?php
				for ($i=0;$i<$cantidad;$i++)
				{
					$id_ad = $admins[$i]["id_ad"];
					echo "<tr><td>".$admins[$i]["id_ad"]."</td>";
					echo "<td>".$admins[$i]["nombre"]."</td>";
					echo "<td>".$admins[$i]["usuario"]."</td>";
					echo "<td align=\"right\"><a class=\"btn btn-success\" href=\"index.php?op=verAdm&&id_ad=$id_ad\" role=\"button\">Ver</a> <a class=\"btn btn-primary\" href=\"index.php?op=modifAdm&&id_ad=$id_ad\" role=\"button\">Modificar</a> <a class=\"btn btn-danger\" href=\"index.php?op=bajaAdm&&id_ad=$id_ad\" role=\"button\">Baja</a></td>";
					echo "</tr>";
				}
				echo "</table>";
				echo "</div></div>";
			}
			
		}
		/**
         *	Funcion para modificar un administrador existente.  
         *	@param $infoadm, Arreglo que contiene los datos del administrador que se modificara. 
         * 	@return None
         */
		public function formaModifAdm($infoadm)
		{
			$nombre = $infoadm[0]["nombre"];
			$correo = $infoadm[0]["correo"];
			$direccion = $infoadm[0]["direccion"];
			$telefono = $infoadm[0]["telefono"];
			$usuario = $infoadm[0]["usuario"];
			$clave = $infoadm[0]["clave"];
			$id_ad = $infoadm[0]["id_ad"];
			?>
			<div class="container">
				<div class="panel panel-default col-lg-8 col-lg-offset-2">
					<div class="panel-title text-center"><h3>Modificar Admin </h3></div>
					<div class ="panel-body">
						<form action="index.php?op=savemodifAdm" method="POST" class="form-horizontal">
						<?php
							echo "<div class=\"form-group\">";
							echo "<label for=\"correo_registro\" class=\"col-sm-2 control-label\">Nombre Completo</label>";
							echo "<div class=\"col-sm-8\">";
								echo "<input type=\"text\" class=\"form-control\" name=\"admin[nombre]\" value=\"$nombre\">";
								echo "</div>";
							echo "</div>";
							
							echo "<div class=\"form-group\">";
							echo "<label for=\"correo_registro\" class=\"col-sm-2 control-label\">Correo</label>";
							echo "<div class=\"col-sm-8\">";
							echo "<input type=\"email\" class=\"form-control\" name=\"admin[correo]\" value=\"$correo\">";
							echo "</div>";
							echo "</div>";
							
							echo "<div class=\"form-group\">";
							echo "<label for=\"correo_registro\" class=\"col-sm-2 control-label\">Direccion</label>";
							echo "<div class=\"col-sm-8\">";
							echo "<input type=\"text\" class=\"form-control\" name=\"admin[direccion]\" value=\"$direccion\">";
							echo "</div>";
							echo "</div>";
                            
							echo "<div class=\"form-group\">";
							echo "<label for=\"correo_registro\" class=\"col-sm-2 control-label\">Telefono</label>";
							echo "<div class=\"col-sm-8\">";
							echo "<input type=\"text\" class=\"form-control\" name=\"admin[telefono]\" value=\"$telefono\">";
							echo "</div>";
							echo "</div>";
                            
							echo "<div class=\"form-group\">";
							echo "<label for=\"correo_registro\" class=\"col-sm-2 control-label\">Usuario</label>";
							echo "<div class=\"col-sm-8\">";
							echo "<input type=\"text\" class=\"form-control\" name=\"admin[usuario]\" value=\"$usuario\" readonly=\"readonly\">";
							echo "</div>";
							echo "</div>";
                            
                            echo "<div class=\"form-group\">";
							echo "<label for=\"correo_registro\" class=\"col-sm-2 control-label\">Contrase&ntilde;a</label>";
							echo "<div class=\"col-sm-8\">";
							echo "<input type=\"password\" class=\"form-control\" name=\"admin[clave]\" value=\"$clave\" readonly=\"readonly\">";
							echo "</div>";
							echo "</div>";
                            
							
							echo  "<input type=\"hidden\" name=\"admin[id_ad]\" value=\" $id_ad\">";
                            
							echo "<div class=\"form-group\">";
							echo "<div class=\"col-sm-offset-2 col-xs-8\">";
								echo "<button type=\"submit\" class=\"btn btn-success\">Guardar</button>";
								echo "<a class=\"btn btn-default\" href=\"index.php?op=consulAdm\" role=\"button\">Regresar</a>";
								echo "</div>";
							echo "</div>";
					
						echo "</form>";
					echo "</div>"; 
				echo "</div>";
			echo "</div>";
		}
		
		/**
         *	Funcion para validar que se desea dar de baja un admin.  
         *	@param $id_ad, id del administrador que se dara de baja. 
		 *	@param $nombre, nombre del administrador que se dara de baja.
         * 	@return None
         */
		public function validarBajaAdm($id_ad,$nombre)
		{

			echo "<div class=\"panel panel-default col-lg-6 col-lg-offset-3\">";
			echo "<div class=\"table-responsive\">";
			echo "<table class=\"table table-hover\">";
				echo "<tr><th colspan='4' >¿Seguro(a) que desea dar de baja a $nombre ?</th></tr>";
				echo "<tr><th> </th><th> </th><th><a class=\"btn btn-default\" href=\"index.php?op=saveBajaAdm&&id_ad=$id_ad\" role=\"button\">  Aceptar  </a></th><th><a class=\"btn btn-default\" href=\"index.php?op=consulAdm\" role=\"button\">  cancelar </a></th></tr>";
			echo "</table></div></div>";

		}
		
		/**
         *	Funcion para ver los detalles de un administrador.  
         *	@param $infoadm, Arreglo con los datos del administrador. 
         * 	@return None
         */
		public function verAdm($infoadm)
		{
			$nombre = $infoadm[0]["nombre"];
			$correo = $infoadm[0]["correo"];
			$direccion = $infoadm[0]["direccion"];
			$telefono = $infoadm[0]["telefono"];
			$usuario = $infoadm[0]["usuario"];
			$clave = $infoadm[0]["clave"];
			$id_ad = $infoadm[0]["id_ad"];
			?>
            <div class="container">
				<div class="panel panel-default col-lg-8 col-lg-offset-2">
					<div class="panel-title text-center"><h3>Detalles de Admin </h3></div>
					<div class ="panel-body">
						<form action="index.php?op=consulAdm" method="POST" class="form-horizontal">
						
						<?php
							echo "<div class=\"form-group\">";
							echo "<label for=\"correo_registro\" class=\"col-sm-2 control-label\">Nombre Completo</label>";
							echo "<div class=\"col-sm-8\">";
								echo "<label for=\"correo_registro\" class=\"form-control\">$nombre</label>";
								echo "</div>";
							echo "</div>";
							
							echo "<div class=\"form-group\">";
							echo "<label for=\"correo_registro\" class=\"col-sm-2 control-label\">Correo</label>";
							echo "<div class=\"col-sm-8\">";
							echo "<label for=\"correo_registro\" class=\"form-control\">$correo</label>";
							echo "</div>";
							echo "</div>";
							
							echo "<div class=\"form-group\">";
							echo "<label for=\"correo_registro\" class=\"col-sm-2 control-label\">Direccion</label>";
							echo "<div class=\"col-sm-8\">";
							echo "<label for=\"correo_registro\" class=\"form-control\">$direccion</label>";
							echo "</div>";
							echo "</div>";
                            
							echo "<div class=\"form-group\">";
							echo "<label for=\"correo_registro\" class=\"col-sm-2 control-label\">Telefono</label>";
							echo "<div class=\"col-sm-8\">";
							echo "<label for=\"correo_registro\" class=\"form-control\">$telefono</label>";
							echo "</div>";
							echo "</div>";
                            
							echo "<div class=\"form-group\">";
							echo "<label for=\"correo_registro\" class=\"col-sm-2 control-label\">Usuario</label>";
							echo "<div class=\"col-sm-8\">";
							echo "<label for=\"correo_registro\" class=\"form-control\">$usuario</label>";
							echo "</div>";
							echo "</div>";
                            
							
							echo "<div class=\"form-group\">";
							echo "<div class=\"col-sm-offset-2 col-xs-8\">";
								echo "<button type=\"submit\" class=\"btn btn-default\">Regresar</button>";
								echo "</div>";
							echo "</div>";
					
						echo "</form>";
					echo "</div>"; 
				echo "</div>";
			echo "</div>";
		}
		
		
		//Fin Anairene .- Gestionar Clientes
		
		public function mensaje($num)
		{
			?><div class="alert alert-success" role="alert"><?php
			//ANAIRENE ISHIHARRA.-MENSAJE 16/Abril/2016
			if($num == 1)
			{
				echo "<h3>Has confirmado tu cuenta correctamente.</h3>";
				header("Refresh: 2;index.php");				
			}
			elseif($num == 2)
			{
				echo "<h3>Tu cuenta ha sido registrada, sin embargo, esta requiere que la confirmes desde el email que ingresaste en el registro</h3>";
				header("Refresh: 4;index.php");
			}
			elseif($num == 3)
			{
				echo "<h3>Tu pedido se ha realizado con exito.</h3>";
				header("Refresh: 3;index.php");
			}
			elseif($num == 4)
			{
				echo "<h3>Tu pedido ha entrado a la cola de pedidos. Por lo tanto no puede ser cancelada</h3>";
			}
			elseif($num == 10)
			{
				?>
					<h3>Se ha guardado la informaci&oacute;n de tu carrito con &eacute;xito.</h3>
				<?php
				header("Refresh: 4; index.php");
			}
			elseif($num == 210)
			{
				echo "<h3>El platillo se ha agregado con exito.</h3>";
				header("Refresh: 3; index.php?op=vCar");
			}
			//FIN ANAIRENE
			// Anairene.- Gestionar Admins
			elseif($num == 5)
			{
				echo "<h3>Registro de Administrador exitoso.</h3>";
				header("Refresh: 3;index.php?op=consulAdm");
			}
			elseif($num == 6)
			{
				echo "<h3>Se ha modificado la informacion del Administrador exitosamente.</h3>";
				header("Refresh: 3;index.php?op=consulAdm");
			}
			elseif($num == 7)
			{
				echo "<h3>Baja exitosa.</h3>";
				header("Refresh: 3;index.php?op=consulAdm");
			}
			//Moxis recarga
			elseif($num == 646)
			{
				echo "<h3>Recarga exitosa.</h3>";
				header("Refresh: 3;index.php?op=recarga");
			}
			// fin gestionar Admins
			?></div><?php
		}
		
		public function error($no)
		{	?><div class="alert alert-danger" role="alert"><?php
			echo "<h3>Error numero $no</h3>";
			if($no == 1)
			{
				echo "<h3>Correo o contrase&ntilde;a incorrectos.</h3>";
			}
			//ANAIRENE ISHIHARA.- ERRORES 16/Abril/2016
			elseif($no == 101)
			{
				echo "Ingrese su correo electr&oacute;nico UABC.";
			}
			elseif($no == 202)
			{
				echo "Ingrese una contrase&ntilde;a.";
			}
			//Anairene 
			elseif($no == 203)
			{
				echo "Ingrese una contrase&ntilde;a de m&aacute;ximo 6 d&iacute;gitos.";
			}
			// fin Anairene 
			elseif($no == 210)
			{
				echo "El Carrito est&aacute; vac&iacute;o.";
			}
			elseif($no == 303)
			{
				echo "Confirme su contrase&ntilde;a.";
			}
			elseif($no == 505)
			{
				echo "Esta cuenta ya fue confirmada.";
			}
			elseif($no == 707)
			{
				echo "Lo siento, ya existe una cuenta con el correo UABC proporcionado.";
			}
			elseif($no == 404)
			{
				echo "Saldo insuficiente, puede realizar una recarga en la cafeter&iacute;a.";
			}
			elseif($no == 606)
			{
				echo "No se ha podido realizar tu pedido.";
			}

			//Anairene .- Gestionar Admins
			elseif($no == 607)
			{
				echo "Ingresa el nombre del Administrador.";
			}
			elseif($no == 608)
			{
				echo "Ingresa el correo del Administrador.";
			}
			elseif($no == 609)
			{
				echo "Ingresa un usuario.";
			}
			elseif($no == 610)
			{
				echo "Ingresa una clave.";
			}
			elseif($no == 611)
			{
				echo "Ingresa la direccion del Administrador.";
			}
			elseif($no == 612)
			{
				echo "Ingresa un telefono del Administrador.";
			}
			elseif($no == 613)
			{
				echo "No se ha podido realizar el registro.";
			}
			elseif($no == 614)
			{
				echo "El usuario o correo ingresado ya existe, por favor intenta con otro.";
			}
			elseif($no == 614)
			{
				echo "El correo ingresado ya existe, por favor intenta con otro.";
			}
			elseif($no == 616)
			{
				echo "No se a podido actualizar la informacion del Adminstrador.";
			}
			
			elseif($no == 617)
			{
				echo "No se a podido dar de baja al Administrador.";
			}
			// fin gestionar Admins
			// FIN ANAIRENE
			//Moxis recarga
			elseif($no == 619)
			{
				echo "Ingrese un usuario valido.";
			}
			elseif($no == 90)
			{
				echo "Seleccione una cantidad.";
			}
			else
			{
				echo "Error inesperado";
			}
			header("Refresh: 6;index.php");
			?></div><?php
		}
		
		/**
         *	Funcion para ver la lista de pedidos. Muestra una tabla con la lista de pedidos pendientes.
		 *	En caso de estar vacio muestra un mensaje. 
         *	@param $pedidos, Matriz que contiene los datos de los pedidos. 
		 *	@param $nombres, Arreglo con los nombres de los platillos.
         * @return None
         */
		public function verPedidos($pedidos,$nombres,$clientes)
		{
			$cantidad = count($pedidos);
			if ($cantidad == 0)
			{
				?>
				<div class="alert alert-danger" role="alert">
				<h3>No hay pedidos pendientes.</h3><br/>
				<a class="btn btn-default" href=index.php role="button">Regresar</a>
				</div>
				<?php
			}
			else
			{
				?>
				<div class="panel panel-default">
				<div class="table-responsive">
				<table class="table table-hover">
				<tr><th>PEDIDO</th><th>CLIENTE</th><th>PLATILLO</th><th>FECHA/HORA</th><th>ESTADO</th><th>DESCRIPCION</th></tr>
				<?php
				for ($i=0;$i<$cantidad;$i++)
				{
					$id_pe = $pedidos[$i]["id_pe"];
					echo "<tr><td>".$pedidos[$i]["id_pe"]."</td><td>".$clientes[$i]["correo"]."</td>";
					if(isset($nombres[$i]["platillo"]))
					{
						echo "<td>".$nombres[$i]["platillo"]."</td>";
					}
					else
					{
						echo "<td>".$nombres[$i]["p_fuerte"]."</td>";
					}
					echo "<td>".$pedidos[$i]["fecha_hora"]."</td><td>".$pedidos[$i]["status"]."</td><td>".$pedidos[$i]["descripcion"]."</td><td><a class=\"btn btn-success\" href=\"index.php?op=cEsteNotif&&id_pe=$id_pe&&msj=2\" role=\"button\">Listo</a></td></tr>";
				}
				echo "</table>";
				echo "</div></div>";
			}
		}
		
		public function verMenu($nombre_cat,$platillos,$saldo2)
		{
			//INICIO EUNICE VER SALDO
			if(!isset($_SESSION["adm"]))
			{
			?>
			<div class="panel panel-default col-md-12">
				<div class="panel-body">
				<div class= "row">
				<div class= "col-md-offset-4 col-md-4" style="text-align: center;">
					<?php
						echo "<h2>$nombre_cat</h2>";
					?>
				</div>
					<div class= "col-md-4"  style="text-align:right;"><h3><FONT FACE="VERDANA" SIZE=4 style="color:#5bc0de;">Saldo: $<?php echo $saldo2; ?></FONT>
					</h3></div>
					</div>
				</div>
			</div>
			<?php
			}
			else
			{
				?>
				<div class="panel panel-default">
					<div class="panel-heading" style="text-align:center">
			
				<div class= "row">
				<div class= "col-md-12">
					<?php
						echo "<h2>$nombre_cat</h2>";
					?>
				</div>
				</div>
				
				</div>
			</div>
			<?php
			}
			//FIN EUNICE 

			$n = count($platillos);
			for($i=0;$i<$n;$i++)
			{
				//Anairene.- Puse el if para validar que se muestren todos para adm y solo los de status 1 para clientes.
				if(((!isset($_SESSION["adm"])) && ($platillos[$i]["status"] == 1)) || (isset($_SESSION["adm"])))
				{
				$nombre = $platillos[$i]["platillo"];
				$tiempo = $platillos[$i]["duracion"];
				$precio = $platillos[$i]["precio"];
				$ingrediente = $platillos[$i]["ingrediente"];
				$imagen = $platillos[$i]["imagen"];
				$id_pl = $platillos[$i]["id_pl"];
				$status = $platillos[$i]["status"];

				?>
				<div class="panel panle-default">
				<div class="panel-heading"><h3><?php echo $nombre; ?></h3></div>
				<div class="panel-body">
				<div class="row"> 
	                    
	                <div class="col-md-2">
	                <?php
	                	echo "<img class=\"img-responsive img-border img-left\" src=\"img/imagenes/$imagen.jpg\" width=\"75px\" alt=\"\">";
	                ?>
	                </div>

	                
	                

	                <div class="col-md-4">
	                    <?php
	                    	echo "<h4>Precio: $ $precio </h4>";	                    
	                    ?>
	                </div>

	                <div class="col-md-4">
	                    <?php
	                    	echo "<h4>Duracion: $tiempo mins</h4>";	                    
	                    ?>
	                </div>
	            </div>
	            <div class="row">
	                <div class="col-md-12">
	                    <?php
	                    	echo "<h4>Ingredientes: $ingrediente</h4>";         
	                    ?>
	                </div>
	           	</div>
	            <div class="row"> 
	                <div class="col-md-6">
	                    <?php
	                    	if(isset($_SESSION["adm"]))
	                    	{
	                    		$this->activarDesactivarPlatillo($id_pl,$status);	
	                    	}
	                    	else
	                    	{
	                    		echo "<button type=\"button\" class=\"btn btn-success\" data-toggle=\"modal\" data-target=\"#aCar\" onclick=\"agregarCarrito('id_pl=$id_pl')\">Agregar a Carrito</button>";
	                    	}
	                    ?>
	                </div> 
			    </div>
			    </div>
			    </div>

			    <div class="modal fade" id="aCar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				  <div class="modal-dialog" role="document">
				    <div class="modal-content">
				      <div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				        <h4 class="modal-title" id="myModalLabel">Has Agregado al Carrito</h4>
				      </div>
				      <div class="modal-body">
				        Puedes ver tu carrito en la pesta&ntilde;a Ver/Carrito.
				      </div>
				      <div class="modal-footer">
				        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				      </div>
				    </div>
				  </div>
				</div>
				<?php
				}
			}
		}
		
		//INICIO MOXXIS, INSERTAR LOS PEDIDOS A LA TABLA

		public function realizarPedido($datosPlatillo,$ingredientes,$idUser,$tipo)
		{
			if ($tipo == true)
			{
			$precio = $datosPlatillo[0]["precio"];
			$idPlatillo = $datosPlatillo[0]["id_pl"];
			$tipo2 = 1;
			}
			else
			{
			$precio = $datosPlatillo[0]["precio"];
			$idPlatillo = $datosPlatillo[0]["id_cd"];	
			$tipo2 = 0;
			}
			?>
				<div class="panel panel-default">
					<div class="panel-heading"><h3 style="text-align:center">Pedido</h3></div>
					<div class ="panel-body">
						<form action="index.php?op=envPedido&&idUser=$idUser" method="POST" class="form-horizontal">
							<?php
							echo  "<input type=\"hidden\" name=\"pedido[idUser]\" value=\" $idUser\">";
							echo "<input type=\"hidden\" name=\"pedido[id_pl]\" value=\" $idPlatillo\">";
							echo "<input type=\"hidden\" name=\"pedido[precio]\" value=\" $precio\">";
							echo "<input type=\"hidden\" name=\"pedido[tipo2]\" value=\" $tipo2\">"
							?>

							<div class="row">
								<label  class="col-sm-2 control-label">Platillo</label>
								<div class="col-sm-8">
									<?php 
									if($tipo == true)
									{ 
									?>	
									<?php echo "<p>".$datosPlatillo[0]["platillo"]."</p>"; ?>
									<?php 
									} 
									else
									{ ?>
									<?php echo "<tr><td>".$datosPlatillo[0]["p_fuerte"]."</td><td>"; ?>	
									<?php
									} ?>
								</div>
							</div>

							<div class="row">
								<label  class="col-sm-2 control-label">Ingredientes</label>
								<div class="col-sm-8">
									<p>
									<?php
									if($tipo == true)
									{	
										$cantidad = count($ingredientes);
										for ($i=0;$i<$cantidad;$i++)
										{
											echo $ingredientes[$i]["ingrediente"]." "; 
										}
									}
									else
									{
										echo $ingredientes[0]["ingredientes"]." ";
									}
									?>
									</p>
								</div>
							</div>
							
							<div class="row">
								<label  class="col-sm-2 control-label">DescripciÃ³n</label>
								<div class="col-sm-8">
									<p><textarea class="form-control" rows="3" name="pedido[desc]"></textarea></p>
								</div>
							</div>
							

							<div class="row">
								<label  class="col-sm-2 control-label">Precio</label>
								<div class="col-sm-8">
									<?php echo "<p>$".$datosPlatillo[0]["precio"]."</p>"; ?>
								</div>
							</div>
							
							<div class="row">
								<label class="col-sm-2 control-label">Tiempo de preparacion</label>
								<div class="col-sm-8">
									<?php echo "<p>".$datosPlatillo[0]["duracion"]." Minutos</p>"; ?>
								</div>
							</div>
							
							<div class="form-group">
								<div class="col-sm-offset-2 col-xs-8">
									<button type="submit" class="btn btn-default">Enviar pedido</button>
								</div>
							</div>
					
						</form>
					</div>
				
			</div>

			<?php
		}
		//FIN MOXXIS,

		
		public function verCDia($cDia,$saldo2)
		{
			//INICIO EUNICE VER SALDO
			
			if(!isset($_SESSION["adm"]))
			{
			?>

			<div class="panel panel-default col-md-12">
				<div class="panel-body">
				<div class= "row">
				<div class= "col-md-offset-4 col-md-4" style="text-align:center;">
					<?php
						echo "<h2>Comida Del Dia</h2>";
					?>
				</div>
					<div class= "col-md-4"  style="text-align:right;"><h3><FONT FACE="VERDANA" SIZE=4 style="color:#5bc0de;">Saldo: $<?php echo $saldo2; ?></FONT>
					</h3></div>
					</div>
				</div>
			</div>

			<?php
			}
			else
			{
				?>
				<div class="panel panel-default col-md-12" style="text-align:center">
				<div class="panel-body">
				<div class= "row">
				<div class= "col-md-12">
					<?php
						echo "<h2>Comida Del Dia</h2>";
					?>
				</div>
				</div>
				</div>
				</div>
			<?php
			}
			//FIN EUNICE 


			$n = count($cDia);
			for($i=0;$i<$n;$i++)
			{	
				//Anairene.- Puse el if para validar que se muestren todos para adm y solo los de status 1 para clientes.
				if(((!isset($_SESSION["adm"])) && ($cDia[$i]["status"] == 1)) || (isset($_SESSION["adm"])))
				{
				$id_cd = $cDia[$i]["id_cd"];
				$p_fuerte = $cDia[$i]["p_fuerte"];
				$p_chico = $cDia[$i]["p_chico"];
				$bebida = $cDia[$i]["bebida"];
				$precio = $cDia[$i]["precio"];
				$ingredientes = $cDia[$i]["ingredientes"];
				$p_entrada = $cDia[$i]["p_entrada"];
				$imagen = $cDia[$i]["imagen"];
				$status = $cDia[$i]["status"];

				?>
				<div class="panel panle-default">
				<div class="panel-heading"><h3><?php echo $p_fuerte; ?></h3></div>
				<div class="panel-body">

				<div class="row"> 
	                
					
					 <div class="col-md-2">
	                <?php
	                	echo "<img class=\"img-responsive img-border img-left\" src=\"img/imagenes/$imagen.jpg\"  width=\"75px\" alt=\"\">"; 
	                ?>
	                </div>
					
					<div class="col-md-4">
	                    <?php
	                    	echo "<h4>Precio: $ $precio </h4>";	                    
	                    ?>
	                </div>
					
					
					<div class="col-md-6">
	                    <?php
	                    	echo "<h4>Platillo Fuerte: $p_fuerte </h4>";	                    
	                    ?>
	                </div>
	            </div>

	            <div class="row">
	                <div class="col-md-4">
	                    <?php
	                    	echo "<h4>Platillo Chico: $p_chico </h4>";	                    
	                    ?>
	                </div>

	                <div class="col-md-4">
	                    <?php
	                    	echo "<h4>Platillo de Entrada: $p_entrada </h4>";	                    
	                    ?>
	                </div>

	                <div class="col-md-4">
	                    <?php
	                    	echo "<h4>Bebida: $bebida </h4>";	                    
	                    ?>
	                </div>
	            </div>
	                
	            <div class="row">
	                <div class="col-md-12">
	                    <?php
	                    	echo "<h4>Ingredientes: $ingredientes</h4>";	                     
	                    ?>
	                </div>
	            </div>
	            <div class="row">
	                <div class="col-md-6">
	                    <?php
	                    	if(isset($_SESSION["adm"]))
	                    	{
	                    		$this->activarDesactivarCDia($id_cd,$status);	
	                    	}
	                    	else
	                    	{
	                    		echo "<button type=\"button\" class=\"btn btn-success\" data-toggle=\"modal\" data-target=\"#aCar\" onclick=\"agregarCarrito('id_cd=$id_cd')\">Agregar a Carrito</button>";
	                    	}
	                    ?>
	                </div> 
			    </div>
			    </div>
			    </div>

			    <div class="modal fade" id="aCar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				  <div class="modal-dialog" role="document">
				    <div class="modal-content">
				      <div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				        <h4 class="modal-title" id="myModalLabel">Has Agregado al Carrito</h4>
				      </div>
				      <div class="modal-body">
				        Puedes ver tu carrito en la pesta&ntilde;a Ver/Carrito.
				      </div>
				      <div class="modal-footer">
				        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				      </div>
				    </div>
				  </div>
				</div>
				<?php
				}
			}
		}
		/**
         *	Funcion para ver la lista de pedidos. Muestra una tabla con la lista de pedidos en espera, pendientes y listos del cliente.
		 *	En caso de estar vacio muestra un mensaje. 
         *	@param $pedidos, Matriz que contiene los datos de los pedidos. 
		 *	@param $nombres, Arreglo con los nombres de los platillos.
		 *	@param $likes    Arreglo con los likes de los platillos.
         * @return None
         */
		public function verPCliente($favoritos,$pedidos,$nombres,$likes)
		{
			$cantidad = count($pedidos);
			if ($cantidad == 0)
			{
				?>
				<div class="alert alert-danger" role="alert">
				<h3>No tienes pedidos pendientes.</h3><br/>
				<a class="btn btn-default" href=index.php role="button">Regresar</a>
				</div>
				<?php
			}
			else
			{
				?>
				<div class="panel panel-default">
				<div class="table-responsive">
				<table class="table table-hover">
				<tr><th>PEDIDO</th><th>PLATILLO</th><th>FECHA/HORA</th><th>ESTADO</th><th>DESCRIPCION</th><th>FAVORITO</th></tr>
				<?php
				for ($i=0;$i<$cantidad;$i++)
				{
					$id_pe = $pedidos[$i]["id_pe"];
					echo "<tr><td>".$pedidos[$i]["id_pe"]."</td>";
					if(isset($nombres[$i]["platillo"]))
					{
						echo "<td>".$nombres[$i]["platillo"]."</td>";
					}
					else
					{
						echo "<td>".$nombres[$i]["p_fuerte"]."</td>";
					}
					echo "<td>".$pedidos[$i]["fecha_hora"]."</td><td>".$pedidos[$i]["status"]."</td><td>".$pedidos[$i]["descripcion"]."</td>";
					if($pedidos[$i]["status"] == "ESPERA")
					{
						echo "<td><a class=\"btn btn-danger\" href=\"index.php?op=cPed&&id_pe=$id_pe&&msj=3\" role=\"button\">Cancelar</a></td></tr>";
					}
					else if ($pedidos[$i]["status"] == "ENTREGADO") 
					{
						$id = $pedidos[$i]["id_pl"];

						if ( $this->checarLikes($likes, $id) ) 
						{
							echo "<td></td></tr>";
						} 
						else 	
						{
							?>
								<form action="index.php?op=addLike" method="POST">
									<?
									echo "<td><button type=\"submit\" name=\"like\" value=\"$id_pe\"class=\"btn btn-success center-block\"><span class=\"glyphicon glyphicon-thumbs-up\"></span></button></td></tr>";
									?>
								</form>
							<?
						}
					}
					else
					{
						////FAVORITOS!!!!!
						$n=count($favoritos);
						$z=0;
						for($x=0; $x<$n; $x++)
						{
							if($id_pe==$favoritos[$x]["id_pe"])
							{
								$z=1;
							}
						}
						
						if($z==1)  //==true
						{
							echo "<td><a class=\"btn btn-primary glyphicon glyphicon-star\" href=\"index.php?op=delFav&&id_pe=$id_pe&&l=1\" role=\"button\" Title=\"Quitar favorito\"></a></td>";
						}
						else
						{
							echo "<td><a class=\" btn btn-default glyphicon glyphicon-star-empty\" href=\"index.php?op=addFav&&id_pe=$id_pe\" role=\"button\" Title=\"Agregar favorito\"></a></td>"; 
						}
						/////
						echo "<td></td></tr>";
					}
				}
				echo "</table>";
				echo "</div></div>";
			}
		}
		/**
		 * Funcion necesaria para ver si un pedido tienen algun like del cliente
		 * @param  array $likes      Todos los likes de los platillos
		 * @param  int 	 $idPlatillo El platillo que se desea ver si tiene ya un like del cliente
		 * @return bool              Regresa un booleano para checar si se encuentra dentro de los likes del cliente.
		 */
		private function checarLikes($likes, $idPlatillo)
		{
			foreach ($likes as $key => $value) 
			{
				if ( in_array($idPlatillo, $value) )
				{
					return TRUE;
				}
			}
			return FALSE;
		}
		/**
         *	Funcion para ver la lista de notificaciones.
		 *	En caso de estar vacio muestra un mensaje. 
		 *	@param $notificaciones, Matriz informacion de las notificaciones.
         * @return None
         */
		public function verBandejaEntrada($notificaciones)
		{
			$cantidad = count($notificaciones);
			if ($cantidad == 0)
			{
				?>
				<div class="alert alert-danger" role="alert">
				<h3>No tienes notificaciones disponibles.<h3><br/>
				<a class="btn btn-default" href=index.php role="button">Regresar</a>
				</div>
				<?php
			}
			else
			{
				?>
				<div class="panel panel-default col-md-10 col-md-offset-1">
				<div class="table-responsive">
				<table class="table table-hover">
				<tr><th>ID</th><th>FECHA/HORA</th><th>TITULO</th><th>VER</th><th>ELIMINAR</th></tr>
				<?php

				for ($i=0;$i<$cantidad;$i++)
				{
					
					$id_no = $notificaciones[$i]["id_no"];
					$ver = ".ver".$i;
					$eliminar = ".eliminar".$i;
					echo "<tr><td>".$id_no."</td><td>".$notificaciones[$i]["fecha_hora"]."</td><td>".$notificaciones[$i]["titulo"]."</td></td>";
					
					echo "<td><button class=\"btn btn-success\" data-title=\"Ver\" data-toggle=\"modal\" data-target=\"$ver\"><span class=\"glyphicon glyphicon-eye-open\"></span></button></td>";
					
					echo "<td><button class=\"btn btn-danger\" data-title=\"Eliminar\" data-toggle=\"modal\" data-target=\"$eliminar\"><span class=\"glyphicon glyphicon-trash\"></span></button></td>";
					
					$modal_ver = "modal fade ver".$i;
					$eliminar = "modal fade eliminar".$i;
					echo "<div class=\"$modal_ver\" role=\"dialog\">";
					?>
					    <div class="modal-dialog">
					      	<div class="modal-content">
					        	<div class="modal-header">
					          		<h3 class="modal-title"> 
					          			<?php echo $notificaciones[$i]["titulo"] ?> 
					          		</h3>
					        	</div>
					        	<div class="modal-body">
					        		<div class = "alert alert-success">
					        			<?php echo $notificaciones[$i]["descripcion"] ?>
					        		</div>
					        	</div>
					        	<div class="modal-footer">
					        	<?php
					          		echo "<a href=\"index.php?op=cEstNotif&&id_no=$id_no\" type=\"button\" class=\"btn btn-success\">Cerrar</a>";
					        	?>
					        	</div>
					      	</div>  
					    </div>
					</div>
					<?php
					echo "<div class=\"$eliminar\" role=\"dialog\" aria-labelledby=\"confirmDeleteLabel\" aria-hidden=\"true\">"; 
					?>
						<div class="modal-dialog">
					    	<div class="modal-content">
					     		<div class="modal-header">
					        		<h3 class="modal-title">Eliminar notificac&oacute;n</h3>
					      		</div>
					      		<div class="modal-body alert alert-danger">
					        		Estas seguro de continuar y eliminar la notificaci&oacute;n, esta acci&oacute;n no se podr&aacute; deshacer.
					      		</div>
					      		<div class="modal-footer">
					        		<a href="" type="button" class="btn btn-default" data-dismiss="modal">Cancelar</a>
					        		<?php 
					        			echo "<a href=\"index.php?op=eNotif&&id_no=$id_no\" type=\"button\" class=\"btn btn-danger\" id=\"confirm\">Eliminar</a>";
					        		?>
					      		</div>
					    	</div>
					 	</div>
					</div>
					<?php
				}
				echo "</table>";
				echo "</div></div>";
			}
		}
		//Moxis recarga
		/**
		 *Formato para realizar una recarga, no tiene parametros y envia el arreglo con los datos ingresados.	
		*/
		public function realizarRecarga()
		{
			?>
			<div class="panel panel-default">
					<div class="panel-heading"><h3 style="text-align:center">Recargas</h3></div>
					<div class ="panel-body">
						<form action="index.php?op=enviarRecarga" method="POST" class="form-horizontal">
						<div class="row">
								<label  class="col-sm-2 control-label">Usuario: </label>
								<div class="col-sm-8">
									<input type="text" name="recarga[usuario]" size="30" placeholder="example@uabc.mx">
								</div>
						</div>


						<div class="row">
						<label  class="col-sm-2 control-label" >Cantidad: </label>
							<div class="col-sm-8">
								<select class="combobox" name="recarga[cantidadCB]" class="form-control">
									  <option></option>
									  <option value="20">20$</option>
									  <option value="50">50$</option>
									  <option value="100">100$</option>
									  <option value="200">200$</option>
									  <option value="500">500$</option>
									  
								</select>
							</div>
						</div>

						<div class="form-group">
								<div class="col-sm-offset-2 col-xs-8">
									<button type="submit" class="btn btn-default">Realizar recarga</button>
								</div>
						</div>

						</form>
					</div>
				
			</div>
			<?php 
		}
		
		/*
		*	Esta por partes, porque se quiere respetar el model MVC... sino
		*	seria mas entendible...
		*	Primera parte, aqui es el titulo y el comienzo del form del carrito.
		*/
		public function verCarritoA()
		{
			?>
				<form action="index.php?op=gCar" method="POST" class="form-inline">
				<div class="panel panel-default">
					<div class="panel-heading">
							<div style="text-align: center;">
								<h2>Carrito</h2>
							</div>
					</div>
				</div>
			<?php
		}

		/*
		*	El carrito, aqui se muestra la informacion de cada platillo de forma
		*	individual.
		*
		*	@param $datosPlatillo	matriz con la informacion del platillo.
		*	@param $descripcion 	Texto que se almacena en los textarea.
		*	@param $id_car 			Se utiliza como indice unico, muy util.
		*	@param $tipo 			Si es true, es tipo platillo normal.
		*	@param $cantidad 		Cantidad del platillo.
		*	@param $saldo 			Saldo del cliente.
		*/
		public function verCarritoB($datosPlatillo,$descripcion,$id_car,$tipo,$cantidad,$saldo)
		{
			$precio = $datosPlatillo[0]["precio"];
			if ($tipo == true)
			{	
				$idPlatillo = $datosPlatillo[0]["id_pl"];
				$tipo2 = 1;
			}
			else
			{
				$idPlatillo = $datosPlatillo[0]["id_cd"];
				$tipo2 = 0;
			}

			// A la matriz pedido se le asigna como primer indice el id_car
			// Es porque no se repite, y es mas facil acceder a la informacion.
			$pedido_id_car = "pedido[$id_car][\"id_car\"]";
			echo "<input type=\"hidden\" class=\"form-control\" value=\"$id_car\">";

			?>
				<div class="panel panel-default">
					<div class="panel-heading"><h3 class="pull-left">
						<?php 
							if($tipo == true)
							{ 
								
								echo $datosPlatillo[0]["platillo"];
							} 
							else
							{ 
								echo $datosPlatillo[0]["p_fuerte"]; 	
							} 
						?>
					</h3>
					<!-- Eliminar del Carrito -->
					<?php
					echo "<button type=\"button\" onclick=\"eliminarCarrito('id_car=$id_car')\" class=\"btn btn-secondary pull-right\" style=\"color:black\"><span class=\"glyphicon glyphicon-remove\" aria-hidden=\"true\"></span></button>";
					?>
	    			<div class="clearfix"></div>

					</div>
					<div class ="panel-body">

							<div class="row">
								<label  class="col-sm-2 control-label">Ingredientes</label>
								<div class="col-sm-8">
									<p>
									<?php
									if($tipo == true)
									{	
										echo $datosPlatillo[0]["ingrediente"];
									}
									else
									{
										echo $datosPlatillo[0]["ingredientes"];
									}
									?>
									</p>
								</div>
							</div>
							
							<div class="row">
								<label  class="col-sm-2 control-label">Descripci&oacute;n</label>
								<div class="col-sm-8">
								<?php
								// Descripcion del pedido.
									$pedido_desc = "pedido[$id_car][desc]";
									echo "<p><textarea class=\"form-control\" rows=\"3\" cols=\"100\" name=\"$pedido_desc\">$descripcion</textarea></p>";
								?>
								</div>
							</div>
							

							<div class="row">
								<label  class="col-sm-2 control-label">Precio</label>
								<div class="col-sm-8">
									<?php
									$precio = intval($datosPlatillo[0]["precio"]);
									echo "<p>$".$precio."</p>"; 
									?>
								</div>
							</div>
							
							<div class="row">
								<label class="col-sm-2 control-label">Tiempo de preparacion</label>
								<div class="col-sm-4">
									<?php echo "<p>".$datosPlatillo[0]["duracion"]." Minutos</p>"; ?>
								</div>

								<label class="col-sm-2 control-label">Cantidad</label>

								<div class="col-sm-4">
								<?php
									$this->modificarCantidad($cantidad,$saldo,$precio,$id_car);
								?>
								</div>
							</div>
					</div>
				</div>
			<?
		}							

		/*
		* Tercera parte, solo se muestra el total y se cierra el form.
		*	@param $total 	Total a pagar.
		*/
		public function verCarritoC($total)
		{
			?>				
			<div class="panel panel-default">
				<div class="panel-heading" style="text-align: center">
						<h3> Total: $<?php echo $total; ?> </h3>
					</div>
				<div class="panel-body" style="text-align: center">
					<input class="form-control" name="boton_guardar" type="submit" value="Guardar">
					<input class="form-control" name="boton_enviar" type="submit" value="Guardar&Enviar">	
					</form>
				</div>
			</div>
			<?php
		}

		///GABY///

		/**
         * Obtiene el id del usuario y del platillo, para almacenarlos en la tabla de favoritos. Y regresa a ver los pedidos del cliente.
         *	@param $id_cl 	El id del cliente.
         *  @param $favoritos Contienetoda la información pertenecciente a cada elemento de la tabla favoritos.
         *	@param $platillos Contiene el nombre de todos los platillos y comidas que han sido marcad@s como favoritos
         *	@param $descripciones Contiene la descripción previamente almacenada, de cada pedido.
         */
		public function mostrarFavoritos($id_cl, $favoritos, $platillos, $descripciones)
		{
			?>
			<div class="panel panel-default col-md-12">
				<div class="panel-body" style="text-align:center">
					<?php
						echo "<h2>Favoritos</h2>";
					?>
				</div>
			</div>
			<?php

			$n=count($favoritos);
			if($n==0)
			{
				?>
				<div class="panel panel-default col-md-12">
					<div class="panel-body" style="text-align:left">
						<?php
							echo "<strong>No ha agregado favoritos</strong>";
						?>
					</div>
				</div>
				<?php
			}
			else
			{
				?>
				<div class="panel panel-default col-md-12">
				<div class="table-responsive">
				<table class="table table-hover">
				<tr><th>PEDIDO</th><th>PLATILLO</th><th>DESCRIPCION</th></tr> 
				<?php
				for($i=0; $i<$n; $i++)
				{

					$platillo = $platillos[$i][0];
				    $descrip= $descripciones[$i][0];

				    echo "<tr><td>".$favoritos[$i]["id_pe"]."</td><td>".$platillo."</td><td>".$descrip."</td>";

				    $id_pe=$favoritos[$i]["id_pe"];
				    echo "<td><a class=\"btn btn-primary btn glyphicon glyphicon-star\" href=\"index.php?op=delFav&&id_pe=$id_pe&&id_cl=$id_cl\" Title=\"Quitar favorito\"></a></td>";

				    echo "<td><button class=\"btn btn-success \" href=index.php?op=pFav&&id_pe=$id_pe role=\"button\">Agregar a Carrito</a></td></tr>";  

				}
				echo "</table>";
				echo "</div></div>";
			}

		}
		/// FIN GABY ///
		

		public function modificarCantidad($cantidad,$saldo,$precio,$id_car)
		{ 
			$id = "cantidad".$id_car;
			echo "<p id=\"$id\">";
			if($cantidad == 1)
			{
				echo "<button type=\"button\" class=\"btn btn-success disabled\"> - </button> ";
			}
			else
			{
				echo "<button type=\"button\" onclick=\"cantidad('dCan&&id_car=$id_car','$id_car')\" class=\"btn btn-success\"> - </button>";
			}
			echo " $cantidad ";
			if($saldo >= $precio)
			{
			echo " <button type=\"button\" onclick=\"cantidad('aCan&&id_car=$id_car','$id_car')\" class=\"btn btn-success\"> + </button>";
			}
			else
			{
				echo " <button type=\"button\" class=\"btn btn-success disabled\"> + </button>";
			}
			?></p><?php
		}

		public function activarDesactivarPlatillo($id_pl,$status)
		{
			$id_activar = "activar".$id_pl;
    		echo "<div id=\"$id_activar\">";
    		echo "<button class=\"btn btn-primary\" onclick=\"request('mPl&&id_pl=$id_pl')\">Modificar</button>";
    		if($status == 1)
    		{
    			echo "<button class=\"btn btn-success\" onclick=\"activarDesactivar('desactivarPl&&id_pl=$id_pl','$id_activar')\">Activado</button>";
    		}
    		else
    		{
    			echo "<button class=\"btn btn-danger\" onclick=\"activarDesactivar('activarPl&&id_pl=$id_pl','$id_activar')\">Desactivado</button>";
    		}
    		echo "</div>";
		}

		public function activarDesactivarCDia($id_cd,$status)
		{
			$id_activar = "activar".$id_cd;
    		echo "<div id=\"$id_activar\">";
    		echo "<button class=\"btn btn-primary\" onclick=\"request('mPl&&id_cd=$id_cd')\">Modificar</button>";
    		if($status == 1)
    		{
    			echo "<button class=\"btn btn-success\" onclick=\"activarDesactivar('desactivarPl&&id_cd=$id_cd','$id_activar')\">Activado</button>";
    		}
    		else
    		{
    			echo "<button class=\"btn btn-danger\" onclick=\"activarDesactivar('activarPl&&id_cd=$id_cd','$id_activar')\">Desactivado</button>";
    		}
    		echo "</div>";
		}
	}
?>
