<?php
	class Adm
	{
		//Variable para la clase Dao
		var $myDao;
		//Variable para la clase Gui
		var $myGui;
		
		/**
         *	Clase ADM Se encarga de controlar a las clases Gui y Dao, se encarga de manipular la informacion.
         * @return None
         */
		public function __construct()
		{
			//Se incluyen las clases para poderlas usar.
			include("clases/Dao.php");
			include("clases/Gui.php");
			
			//Se crea un objeto de la clase Dao. Esta clase es la que se comunica con la base de datos.
			$this->myDao= new Dao();
			//Esta clase se encarga de mostrar informacion en pantalla.
			$this->myGui= new Gui();			
		}
		
		/**
         *	Muestra la pagina de inicio, despues de haber hecho login.
         * @return None
         */
		 public function inicio()
		 {
			 $this->verCDia();
		 }
		 
		 /**
		 * Barra de navegacion.
		 */
		public function navBar()
		{
			//Modificacion Anairene.- Gestionar Admins
			if(isset($_SESSION["adm"]))
			{
				$categorias = $this->myDao->consultaTabla("*","categoria");
				$admin = $_SESSION["user"];
				$sudo = $this->myDao->consultaTabla("sudo_su","administrador","usuario='".$admin."'");
				$this->myGui->navBar($categorias,"","",$sudo[0]["sudo_su"]);
			}
			else
			{
				$categorias = $this->myDao->consultaTabla("*","platillo p,categoria c","p.id_ct = c.id_ct and status=1 group by categoria");
				$id_cl = $this->getIdUser();
				$carrito = $this->myDao->consultaTabla("*","carrito","id_cl = $id_cl");
				$bandeja = $this->myDao->consultaTabla("*","notificacion","id_cl = $id_cl and estado = 0");
				$n = count($carrito);
				$m = count($bandeja);
				$this->myGui->navBar($categorias,$n,$m);
			}
			//Fin Modificacion Anairene.- Gestionar Admins
			
		}
		
		/**
         *	Muestra la forma de Iniciar Sesion, en este caso como cliente.
         * @return None
         */
		public function logInForm()
		{
			$this->myGui->logInForm();
		}
		/**
         *	Muestra la forma de Iniciar Sesion, en este caso como administrador.
         * @return None
         */
		public function logInFormAdm()
		{
			$this->myGui->logInFormAdm();
		}
		/**
         *	Cierra sesion y redireccion a la pagina de inicio.
         * @return None
         */
		public function logOut()
		{
			session_start();
			if(isset($_SESSION["adm"]))
			{
				session_destroy();
				header("Location: administrator.php");
			}
			else
			{
				session_destroy();
				header("Location: index.php");
			}	
		}
		
		/**
         *	Verifica que los datos escritos en la forma de iniciar sesion sean validos.
         * @return None
         */
		public function loginAutentificacion()
		{
			//Se almacena el correo que escribio el usuario, si es administrador, en realidad es usuario
			//pero para hacer la autentificacion mas facil se dejo el nombre correo.
			$correo = $_POST["correo"];
			//Se almacena la clave que escribio el usuario.
			$clave = $_POST["clave"];
			//Se almacena si la forma en la que se escribieron los datos fue de administrador o no.
			// 1 si es administrador, 0 si no lo es.
			$adm = $_POST["adm"];
			if($adm != 1)
			{
				//Si no es administrador se autentifica como usuario comun.
				$error = $this->myDao->auth($correo,$clave);
			}
			else{
				//Si es administrador, se autentifica de la misma forma que el usuario comun.
				//pero con un parametro extra.
				$error = $this->myDao->auth($correo,$clave,true);
			}
			
			//Si el usuario y la clave no coinciden, aqui se atrapa el error.
			if($error != 1)
			{
				//Se manda un mensaje de error.
				$this->myGui->error(1);
			}
			else
			{
				//Recarga la pagina.
				header("Location: index.php");
			}
		}
		
		// ANAIRENE ISHIHARA.- REGISTRO DE CLIENTES 16/Abril/2016
		public function formaRegistro()
		{
			//se le pide al gui que muestre la forma de registro de cliente
			$this->myGui->formaRegistro();
		}
						
		/**
         *	Valida que los datos se hayan ingresado correctamente, para regstrarlo
		 * en la tabla de registrotemp y enviar el correo de confirmacion.
         * @return None
         */				
		public function confirmarCliente()// Validando datos de registro
		{
			// Cachamos los datos del registro.
			$cliente = $_POST["cliente"];
			// verificamos que no haya dejado el campo de correo vacio
			if ($cliente["correo"] == "")
			{	
				//si asi fue le enviamos mensaje de error.
				$this->myGui->error(101);
				header("Refresh: 2; index.php?op=regForm");
			}
			//Anairene
			elseif (strlen($cliente["clave"]) > 6) 
			{
				$this->myGui->error(203);		
				header("Refresh: 2; index.php?op=regForm");
			}
			//Fin Anairene
			elseif($cliente["clave"] == "")
			{
				$this->myGui->error(202);		
				header("Refresh: 2; index.php?op=regForm");				
			}
			elseif ($cliente["clave"] != $cliente["clave2"])
			{
				$this->myGui->error(303); 
				header("Refresh: 2; index.php?op=regForm");
			}
			elseif (!strpos($cliente["correo"],"@uabc.edu.mx")) 
			{ 
				$this->myGui->error(101);  
				header("Refresh: 2; index.php?op=regForm");
			}
			else 
			{ 
				//preparamos datos para llamar la funcion consultaTabla, pues queremos saber si existen
				//en la tabla cliente registros con el mismo correo ingresado.
				$columna = "*";
				$condicion = "correo = '".$cliente["correo"]."'";
				//revisamos si existen registros repetidos en la tabla cliente
				$clienteRepetido = $this->myDao->consultaTabla($columna, "cliente", $condicion);
				//revisamos tambien si existen registros repetidos en la tabla registrotemp
				$clienteRepetidoDos = $this->myDao->consultaTabla($columna, "registrotemp", $condicion);
				// verificamos si la consulta envio registros que coincidieran.
				if((count($clienteRepetido) == 0) && (count($clienteRepetidoDos) == 0))
				{
					// si no fue asi, enviamos los datos para que sean transladados de la tabla registrotemp a cliente.
					$error = $this->myDao->regConfirm($cliente);
					if($error != 0)
					{
						$this->myGui->mensaje(2);
					}
					else
					{
						$this->myGui->error(0);
					}
				}
				else
				{		// en caso de que si se encontraran coincidencias, se envia el mensaje de error al cliente.
						$this->myGui->error(707);
				}
			}
		}
		
		public function confirm()
		{
			$codigo = $_GET["codigo"];
			//le pide al dao que confirme si existe el codigo
			$error = $this->myDao->confirm($codigo);
			if($error != 1)
			{
				$this->myGui->error($error);
				header("Refresh: 2; index.php");
			}
			else{
				$this->myGui->mensaje($error);
			}
		}
		
		/**
         *	Manda llamar la funcion "consultaTabla", consulta el nombre de los pedidos.
         *	Y envia la informacion de los pedidos a la funcion verPedidos
         * @return None
         */
		public function verPedidos()
		{
			$columna = "id_pe,id_cl,id_pl,id_cd,fecha_hora,status,descripcion";
			$condicion = "status = 'PENDIENTE'";
			$pedidos = $this->myDao->consultaTabla($columna,"pedido",$condicion);
			$nombres = array();
			$clientes = array();
			for($i = 0; $i < count($pedidos);$i++)
			{
				$id_pl = $pedidos[$i]["id_pl"]; 
				$id_cd = $pedidos[$i]["id_cd"];
				if($id_cd != NULL)
				{
					$condicion = "id_cd = $id_cd";
					$nombre = $this->myDao->consultaTabla("p_fuerte","cDia",$condicion);
				}
				else
				{
					$condicion = "id_pl = $id_pl";
					$nombre = $this->myDao->consultaTabla("platillo","platillo",$condicion);
				}
				$id_cl = $pedidos[$i]["id_cl"];
				$cliente = $this->myDao->consultaTabla("correo","cliente","id_cl = $id_cl");
				array_push($nombres, $nombre[0]);
				array_push($clientes, $cliente[0]);
			}
			$this->myGui->verPedidos($pedidos,$nombres,$clientes);
		}
		/**
          * Modifica el estado del pedido en la cola de prioridad. 
          * Cambia el estado, de "PENDIENTE" a "LISTO".
          * @return <type>
          */
		public function cambiarEstado()
		 {
			$id_pe = $_GET["id_pe"];
			$this->myDao->updateData("pedido","id_pe = $id_pe", "status = 'LISTO'");
			$this->verPedidos();
		 }
		/**
          * Envia la notificacion de que el pedido ya está listo al cliente.
          * Manda un mensaje a la bandeja del cliente.
          * @param $id_pe es el id del pedido.
          * @param $id_cl es el id del cliente al que se mandara la notificacion
          * @param $msj es el tipo de notificacion que se va a enviar
          * @return <type>
          */
		 public function mandarNotificacion($id_pe,$id_cl=0,$msj)
		 {
			$columnas = "id_cl,titulo,descripcion";
			if($msj == 3)
			{
				$id_cl = $this->myDao->consultaTabla("id_cl","pedido","id_pe=$id_pe");
				$id_cl = $id_cl[0][0];
				$valores = "$id_cl,'PEDIDO CANCELADO','Tu pedido ha sido cancelado satisfactoriamente.'";
				$this->myDao->insertarEnTabla("notificacion",$columnas,$valores);
			}
			elseif($msj == 2)
			{
				$id_cl = $this->myDao->consultaTabla("id_cl","pedido","id_pe=$id_pe");
				$id_cl = $id_cl[0][0];
				$valores = "$id_cl,'PEDIDO LISTO','Tu pedido es el n&uacute;mero: $id_pe y ya se encuentra listo, ya puedes pasar a recogerlo a la cafeter&iacute;a.'";
				$this->myDao->insertarEnTabla("notificacion",$columnas,$valores);
			}
			elseif ($msj == 1)
			{
				$valores = "$id_cl,'PEDIDO PENDIENTE','Tu pedido ya entr&oacute; a la lista de pedidos, estar&aacute; listo en unos momentos.'";
				$this->myDao->insertarEnTabla("notificacion",$columnas,$valores);
			}
		 }
		
		// FIN DE LA PARTE DE REGISTRO DE CLIENTES
		
		public function verMenu()
		{
			/**INICIO EUNICE
			*recauda la informacion para poder sacar el saldo
			* de la tabla cliente
			*/

			$user = $_SESSION["user"];
			$id_cl = $this->getIdUser();
			$columna = "id_cl,correo,clave,status,recurrencia,saldo";
			$condicion = "id_cl = $id_cl";;
		    if(!isset($_SESSION["adm"]))
			{
			$saldo = $this->myDao->consultaTabla("*","cliente",$condicion);
			}
			if(isset($saldo[0]["saldo"]))
			{
				$saldo2 = $saldo[0]["saldo"];
			}
		    else
		    {
		    	$saldo2 = 0;
		    }
		    //FIN EUNICE


			$id_ct = $_GET["id_ct"];
			$categoria = $this->myDao->consultaTabla("*","categoria","id_ct=$id_ct");
			$nombre_cat = $categoria[0]["categoria"];
			$platillos = $this->myDao->consultaTabla("*","platillo","id_ct = $id_ct");
			$this->myGui->verMenu($nombre_cat,$platillos,$saldo2);

		}
		
		public function verCDia()
		{
			$user = $_SESSION["user"];
			$id_cl = $this->getIdUser();
			$columna = "id_cl,correo,clave,status,recurrencia,saldo";
			$condicion = "id_cl = $id_cl";
			if(!isset($_SESSION["adm"]))
			{
			$saldo = $this->myDao->consultaTabla("*","cliente",$condicion);
			}
			if(isset($saldo[0]["saldo"]))
			{
				$saldo2 = $saldo[0]["saldo"];
			}
		    else
		    {
		    	$saldo2 = 0;
		    }
			$cDia = $this->myDao->consultaTabla("*","cDia","");
			$this->myGui->verCDia($cDia,$saldo2);


		}

		// INICIO MOXXIS
		
		public function realizarPedido()
		{
			
			if(isset ($_GET["id_pl"]))
			{
				$idPlatillo = $_GET["id_pl"];
				$usuario = $_SESSION["user"];
				$condicionUsuario = "correo='$usuario'";
				$datosUsuario = $this -> myDao -> consultaTabla("*","cliente",$condicionUsuario);
				$idUser = $datosUsuario[0]["id_cl"];
				$condicion = "id_pl = $idPlatillo";
				$ingredientes = $this -> myDao -> consultaTabla("ingrediente","platillo",$condicion);
				$datosPlatillo = $this -> myDao -> consultaTabla("*","platillo",$condicion);
				$this -> myGui -> realizarPedido($datosPlatillo,$ingredientes,$idUser,true);
			}
			else
			{
				$idPlatillo = $_GET["id_cd"];
				$usuario = $_SESSION["user"];
				$condicionUsuario = "correo='$usuario'";
				$datosUsuario = $this -> myDao -> consultaTabla("*","cliente",$condicionUsuario);
				$idUser = $datosUsuario[0]["id_cl"];
				$condicion = "id_cd = $idPlatillo";
				$ingredientes = $this -> myDao -> consultaTabla("ingredientes","cDia",$condicion);
				$datosPlatillo = $this -> myDao -> consultaTabla("*","cDia",$condicion);
				$this -> myGui -> realizarPedido($datosPlatillo,$ingredientes,$idUser,false);

			}
		}

		
		public function enviarPedido()
		{	
			$pedido = $_POST["pedido"];
			$idUser = $pedido["idUser"];
			$precio = $pedido["precio"];
			$tipo2 = $pedido["tipo2"];
			$condicion = "id_cl=".$idUser;
			$infoUsu = $this->myDao->consultaTabla("*","cliente",$condicion);
			$saldo = $infoUsu[0]["saldo"];
			if($saldo >= $precio)
			{
				if ($tipo2 == 1)
				{
				$idPlatillo = $pedido["id_pl"];
				$valores = "$idUser,$idPlatillo,'$pedido[desc]',$precio";
				$columnas = "id_cl,id_pl,descripcion,total";
				$error =$this -> myDao -> insertarEnTabla("pedido",$columnas,$valores);
				}
				else
				{				
				$idPlatillo = $pedido["id_pl"];
				$valores = "$idUser,$idPlatillo,'$pedido[desc]',$precio";
				$columnas = "id_cl,id_cd,descripcion,total";
				$error =$this -> myDao -> insertarEnTabla("pedido",$columnas,$valores);
				}
				
				if($error == 1)
				{
					$condicion = "id_cl=".$idUser;
					$nSaldo = $saldo - $precio;
					$set = "saldo=".$nSaldo;
					$this->myDao->updateData("cliente",$condicion,$set);
					$this->myGui->mensaje(3);
				}
				else
				{
					$this->myGui->error(606);
				}
				
			}
			else
			{
				$this->myGui->error(404);
			}

		}

		// FIN MOXXIS
		/**
         *	Manda llamar la funcion "consultaTabla", consulta el nombre de los pedidos.
         *	Y envia la informacion de los pedidos a la funcion verPCliente de la GUI.
         * @return None
         */
		public function verPedidosCliente()
		{
			$user = $_SESSION["user"];
			$id_cl = $this->myDao->consultaTabla("id_cl","cliente","correo = '$user'");
			$id_cl = $id_cl[0][0];
			$columna = "id_pe,id_pl,id_cd,fecha_hora,status,descripcion";
			$condicion = "id_cl = $id_cl";
			$pedidos = $this->myDao->consultaTabla($columna,"pedido",$condicion);
			$nombres = array();
			for($i = 0; $i < count($pedidos);$i++)
			{
				$id_pl = $pedidos[$i]["id_pl"]; 
				$id_cd = $pedidos[$i]["id_cd"];
				if($id_cd != NULL)
				{
					$condicion = "id_cd = $id_cd";
					$nombre = $this->myDao->consultaTabla("p_fuerte","cDia",$condicion);
				}
				else
				{
					$condicion = "id_pl = $id_pl";
					$nombre = $this->myDao->consultaTabla("platillo","platillo",$condicion);
				}
				array_push($nombres, $nombre[0]);
			}
			$pedidos = array_reverse($pedidos);
			$favoritos= $this->myDao->consultaTabla("id_pe", "favoritos","id_cl=$id_cl");
			$pedidosConLikes = $this->myDao->consultaTabla("id_pl","likes","id_cl = $id_cl");
			$this->myGui->verPCliente($favoritos,$pedidos,$nombres, $pedidosConLikes);
		}
		
		/**
         * Cancela el pedido y lo elimina de la base de datos.
         * @return None
         */
		public function cancelarPedido()
		{
			$id_pe = $_GET["id_pe"];
			$condicionArbitraria = "id_pe=".$id_pe;			
			$confirm = $this->myDao->consultaTabla("status","pedido",$condicionArbitraria);
			if ( $confirm[0]["status"] == 'PENDIENTE' )
			{
				$this->myGui->mensaje(4);
			}
			else
			{
				$condicion = "id_pe=".$id_pe;
				$info = $this->myDao->consultaTabla("*","pedido",$condicion);
				$total = $info[0]["total"];
			
				$usuario = $info[0]["id_cl"];
				$condicion = "id_cl=".$usuario;
				$infoUsu = $this->myDao->consultaTabla("*","cliente",$condicion);
				$saldo = $infoUsu[0]["saldo"];
				$nSaldo = $saldo + $total;
				$set = "saldo=".$nSaldo;
				$this->myDao->updateData("cliente",$condicion,$set);

				$this->myDao->deleteData("pedido", "id_pe = $id_pe");
			}
			$this->verPedidosCliente();
		}
		/**
          * Cambia el estado de la notificacion a visto. 
          * Manda llamar la funcion del dao "updateData".
          * @return <type>
          */
		public function cambiarEstadoNotif()
		{
			$id_no = $_GET["id_no"];
			$condicion = "id_no = $id_no";
			$this->myDao->updateData("notificacion", $condicion, "estado = 1");
			$this->verBandejaEntrada();
		}
		/**
          * Elimina la notificacion de la base de datos. 
          * Manda llamar la funcion del dao "deleteData".
          * @return <type>
          */

		public function eliminarNotificacion()
		{
			$id_no = $_GET["id_no"];
			$this->myDao->deleteData("notificacion","id_no = $id_no");
			$this->verBandejaEntrada();
		}
		/**
         *	Manda llamar la funcion "consultaTabla", consulta la informacion de las notificaciones.
         *	Y envia la informacion a la funcion verBandejaEntrada de la GUI.
         * @return None
         */
		public function verBandejaEntrada()
		{
			$user = $_SESSION["user"];
			$id_cl = $this->myDao->consultaTabla("id_cl","cliente","correo = '$user'");
			$id_cl = $id_cl[0][0];
			$columna = "id_no,titulo,estado,fecha_hora,descripcion";
			$condicion = "id_cl = $id_cl";
			$notificaciones = $this->myDao->consultaTabla($columna,"notificacion",$condicion);
			$notificaciones = array_reverse($notificaciones);
			$this->myGui->verBandejaEntrada($notificaciones);
		}
		
		/**
		*	Esta funcion agrega al carrito.
		*	No debe agregar un platillo o comida del dia que ya se encuentre dentro.
		**/
		public function agregarCarrito($id_cd_pl="",$desc="",$tipo="")
		{
			$id_cl = $this->getIdUser();

			//Se consigue el saldo a partir de la informacion del cliente.
			$info_cliente = $this->myDao->consultaTabla("*","cliente","id_cl = $id_cl");
			$saldo = intval($info_cliente[0]["saldo"]);

			//Cuando se llama esta funcion, se manda id_pl o id_cd.
			// Se verifica si se trata de un platillo o de una comida del dia.
			// ya que se procesan de forma diferente.
			if(isset($_GET["id_pl"])or($tipo=="platillo"))
			{
				// Si se mando el id_pl por el url aqui se atrapa.
				if(isset($_GET["id_pl"])) 
				{
					$id_pl=$_GET["id_pl"];
				}
				//Si no, entonces lo obtiene del argumento de la función
				else
				{
					$id_pl=$id_cd_pl;
				}

				// Se consigue la informacion del platillo para conseguir el precio.
				$info_platillo = $this->myDao->consultaTabla("*","platillo","id_pl = $id_pl");
				$precio = intval($info_platillo[0]["precio"]);

				// Se compara si el cliente tiene saldo suficiente para
				// Agregar al carrito.
				// En cuanto se agrega se resta del saldo.
				if($saldo >= $precio)
				{
					// Se pregunta si el platillo ya estaba en el carrito.
					$platillo_en_carrito = $this->myDao->consultaTabla("*","carrito","id_cl = $id_cl and id_pl = $id_pl");
					$dentro_del_carrito = count($platillo_en_carrito);

					// Si no se encuentra dentro del carrito, se inserta en la tabla carrito.
					// Si ya se encuentra dentro, se aumenta en 1 la cantidad del platillo.
					if($dentro_del_carrito == 0)
					{
						//$this->myDao->insertarEnTabla("carrito","id_cl,id_pl","$id_cl,$id_pl");
						////FAVORITOS!!!!!!!!!!!
							if($desc!="")  /// isset($_GET["desc"])
							{
								//$desc=$_GET["desc"];
								$this->myDao->insertarEnTabla("carrito","id_cl,id_pl,descripcion","$id_cl,$id_pl,'$desc'");
							}
							else
							{
								$this->myDao->insertarEnTabla("carrito","id_cl,id_pl","$id_cl,$id_pl");
							}
							//////////!!!!!!!!!!!!!!!!
					}
					else
					{
						// Primero se consigue la cantidad que tiene almacenada el platillo esta en el carrito.
						$cantidad = intval($platillo_en_carrito[0]["cantidad"]) + 1;
						$this->myDao->updateData("carrito","id_cl = $id_cl and id_pl = $id_pl","cantidad = $cantidad");
					}

					// Aqui se actualiza el saldo del cliente.
					// Se hace aqui para que los calculos sean mas faciles.

					$n_saldo = $saldo - $precio;
					$this->myDao->updateData("cliente","id_cl = $id_cl","saldo = $n_saldo");

					// Mensaje de exito.
					//$this->myGui->mensaje(210);
				}
				else
				{
					// Saldo insuficiente.
					//$this->myGui->error(404);
				}	
			}
			// Si el platillo es de tipo comida del dia, se hace esto.
			elseif(isset($_GET["id_cd"])or($tipo=="comida"))
			{
				// Igual, se consigue el precio de la comida del dia.
				if(isset($_GET["id_cd"])) 
				{
					$id_cd = $_GET["id_cd"];
				}
				//Si no, entonces lo obtiene del argumento de la función
				else
				{
					$id_cd=$id_cd_pl;
				}
				//


				$info_platillo = $this->myDao->consultaTabla("*","cDia","id_cd = $id_cd");
				$precio = intval($info_platillo[0]["precio"]);

				// Verifica si el saldo es suficiente.
				// Lo mismo que arriba.
				if($saldo >= $precio)
				{
					$platillo_en_carrito = $this->myDao->consultaTabla("*","carrito","id_cl = $id_cl and id_cd = $id_cd");
					$dentro_del_carrito = count($platillo_en_carrito);
					if($dentro_del_carrito == 0)
					{
						$this->myDao->insertarEnTabla("carrito","id_cl,id_cd","$id_cl,$id_cd");
					}
					else
					{
						$cantidad = intval($platillo_en_carrito[0]["cantidad"]) + 1;
						$this->myDao->updateData("carrito","id_cl = $id_cl and id_cd = $id_cd","cantidad = $cantidad");
					}
					$n_saldo = $saldo - $precio;
					$this->myDao->updateData("cliente","id_cl = $id_cl","saldo = $n_saldo");
					//$this->myGui->mensaje(210);
				}
				else
				{
					//$this->myGui->error(404);
				}
			}
		}

		/*
		*	Ver carrito, es complicado, esta dividido en 3 partes.
		*	Desde aqui, se puede guardar una descripcion para cada platillo.
		*	Desde aqui, se hace una orden.
		*/
		public function verCarrito()
		{
			// Primero se consigue el id_cl y la informacion del carrito.
			$id_cl = $this->getIdUser();
			$carrito = $this->myDao->consultaTabla("*","carrito","id_cl = $id_cl");
			$n = count($carrito);

			// Si el carrito esta vacio se muestra un mensaje.
			if($n == 0)
			{
				$this->myGui->error(210);
			}
			// sino, empieza lo bueno.
			else
			{
				// Se manda llamar la primera parte del carrito.
				// Es el titulo y el inicio del form.
				$this->myGui->verCarritoA();

				// Se consigue el saldo.
				$info_cliente = $this->myDao->consultaTabla("*","cliente","id_cl = $id_cl");
				$saldo = intval($info_cliente[0]["saldo"]);

				// Se inicia el total igual a 0, despues de calcula.
				$total = $this->calcularTotal();

				// Este for recorre cada elemento del carrito.
				for($i=0;$i<$n;$i++)
				{
					// Se verifica si es de tipo platillo o comida del dia.
					if(isset($carrito[$i]["id_pl"]))
					{
						// Si es tipo platillo, se obtiene el id del platillo.
						// junto con toda su informacion.
						$id_pl = $carrito[$i]["id_pl"];
						$datosPlatillo = $this->myDao->consultaTabla("*","platillo","id_pl = $id_pl");
						$descripcion = $carrito[$i]["descripcion"];
						$cantidad = $carrito[$i]["cantidad"];
						$id_car = $carrito[$i]["id_car"];

						// Aqui se obtiene el precio del platillo y 
						// se le agrega al total.
						// Como el for cuenta el numero de elementos en el carrito.
						// No considera la cantidad de cada platillo.
						// Entonces se multiplica el precio por la cantidad.
						$precio = $datosPlatillo[0]["precio"];
						//$total = $total + intval($precio)*intval($cantidad);

						// Segunda parte del Carrito
						// Aqui se manda toda la informacion del platillo
						// Y se muestra en una seccion individual.
						$this->myGui->verCarritoB($datosPlatillo,$descripcion,$id_car,true,$cantidad,$saldo);
					}
					else
					{
						// Si es de tipo comida del dia.
						// igual, se consigue la informacion y se manda al GUI
						// Ademas de calcular el total.
						$id_cd = $carrito[$i]["id_cd"];
						$datosPlatillo = $this->myDao->consultaTabla("*","cDia","id_cd = $id_cd");
						$descripcion = $carrito[$i]["descripcion"];
						$cantidad = $carrito[$i]["cantidad"];
						$id_car = $carrito[$i]["id_car"];

						$precio = $datosPlatillo[0]["precio"];
						//$total = $total + intval($precio)*intval($cantidad);

						$this->myGui->verCarritoB($datosPlatillo,$descripcion,$id_car,false,$cantidad,$saldo);
					}				
				}
				// Tercera parte!
				// Aqui se muestra el total a pagar y la opcion de guardar
				// o enviar, tambien se cierra el form.
				$this->myGui->verCarritoC($total);
			}
		}

		/*
		*	Bastante interesante, No solo guarda la descripcion de
		*	Cada platillo en el carrito, sino tambien, envia los platillos
		*	A la lista de espera.
		*/
		public function guardarCarrito()
		{
			//Como mandar la OP por Get no es seguro...
			// Primero se verifica si existe el pedido.
			if(isset($_POST["pedido"]))
			{
				// El form de ver carrito, utiliza una MATRIZ llamada pedido.
				// El primer indice coincide con el id del carrito.
				// luego sera explicado.
				$pedido = $_POST["pedido"];

				// Se consigue la informacion del carrito.
				$id_cl = $this->getIdUser();
				$carrito = $this->myDao->consultaTabla("*","carrito","id_cl = $id_cl");
				$n = count($carrito);

				// Como hice 2 botones de submit para el mismo form...
				// Primero se verifica que boton se presiono.
				if(isset($_POST['boton_guardar']) || isset($_POST['boton_enviar']))
				{
					// Se recorren los platillos del carrito.
					for($i=0;$i<$n;$i++)
					{
						// Como el primer indice de la MATRIZ pedido hace referencia
						// al id del carrito.
						// Se consigue el id del carrito y la cantidad.
						$id_car = $carrito[$i]["id_car"];
						$cantidad = intval($carrito[$i]["cantidad"]);

						// Bueno, esto es seguridad... es por si se tienen 2 ventanas
						// abiertas, si en una se agrega al carrito y en otra se da guardar.
						// podria provocar un error.
						// Primero se verifica que exista la descripcion.
						// Si no existe... no hay nada que guardar.
						if(isset($pedido[$id_car]["desc"]))
						{
							// Y asi de facil se hace un update a la tabla carrito
							// y ya se tiene la nueva descripcion.
							$descripcion = $pedido[$id_car]["desc"];
							$this->myDao->updateData("carrito","id_car = $id_car","descripcion = '$descripcion'");

							// Ahora, si ya guardada la descripcion, ya se puede enviar.
							// Se verifica si fue presionado el boton enviar.
							if(isset($_POST['boton_enviar']))
							{
								// Ahora se verifica si el tipo del platillo.
								if(isset($carrito[$i]["id_pl"]))
								{
									// Se consigue el precio, para luego ponerlo en la tabla de pedido.
									$id_pl = $carrito[$i]["id_pl"];
									$info_platillo = $this->myDao->consultaTabla("*","platillo","id_pl = $id_pl");
									$precio = $info_platillo[0]["precio"];

									// Ahora se hara un pedido por cada cantidad
									// almacenada en la columna del carrito.
									// Ejemplo: 5 chilaquiles
									// mismo platillo, 5 distintos pedidos.
									// Por desgracia tendran la misma descripcion.
									for($j=0;$j<$cantidad;$j++)
									{
										$this->enviarCarrito($precio,$id_pl,$descripcion,1);
									}
								}
								else
								{
									// Igual que el anterior pero para comida del dia.
									$id_cd = $carrito[$i]["id_cd"];
									$info_platillo = $this->myDao->consultaTabla("*","cDia","id_cd = $id_cd");
									$precio = $info_platillo[0]["precio"];
									for($j=0;$j<$cantidad;$j++)
									{
										$this->enviarCarrito($precio,$id_cd,$descripcion,2);
									}
								}
							}
						}
					}

					// Aqui se elige el mensaje, si se envio o solo se guardo.
					if(isset($_POST['boton_enviar']))
					{
						?><script type="text/javascript">
							alert("Enviado");
						</script><?php
						header("Refresh: 4; index.php");
					}
					else
					{
						?><script type="text/javascript">alert("Guardado");</script><?php
						header("Refresh: 4; index.php");
					}
				}
			}
			else
			{
				// Si es un chistosito que mando la op gCar por url...
				// Pues nomas lo redirecciona al index... jue jue jue
				header("Location: index.php");
			}
		}
		
		/*
		*	Es lo mismo que enviarPedido... solo que tiene parametros.
		*/
		public function enviarCarrito($precio,$id_pl_cd,$descripcion,$tipo)
		{
			// Primero se obtiene el id del cliente.
			$id_cl = $this->getIdUser();

			// Si es de tipo 1 significa que es un platillo normal, comun y corriente.
			if ($tipo == 1)
			{
				// De igual forma el atributo se llama id_pl_cd... no importa es solo un numero.
				$idPlatillo = $id_pl_cd;
				$valores = "$id_cl,$idPlatillo,'$descripcion',$precio";

				// Aqui es donde importa la columna en la que se ponga.
				$columnas = "id_cl,id_pl,descripcion,total";
				// Se inserta en la tabla pedido 
				$error =$this -> myDao -> insertarEnTabla("pedido",$columnas,$valores);
			}
			else
			{	
				// Igual pero para comida del dia.	
				$idPlatillo = $id_pl_cd;
				$valores = "$id_cl,$idPlatillo,'$descripcion',$precio";
				// Aqui esta la diferencia.. id_cd
				$columnas = "id_cl,id_cd,descripcion,total";
				$error =$this -> myDao -> insertarEnTabla("pedido",$columnas,$valores);
			}
			// Se vacia el carrito, de todos modos ya le cobre al cliente
			// cuando agrego el platillo al carrito.
			$this->myDao->deleteData("carrito","id_cl = $id_cl");
		}
		
		/*
		*	Bueno, esto solo aumenta en 1 la cantidad del platillo que se
		*	encuentre en el carrito.
		*/
		public function agregarCantidad()
		{
			// Pues se manda el id del carrito por url... por facilidad.
			$id_car = $_GET["id_car"];
			$id_cl = $this->getIdUser();
			// de igual forma se verifica que el carrito pertenezca al
			// cliente que tiene la sesion iniciada.
			// Entonces es seguro :D
			// Se obtiene la informacion del carrito.
			$info_carrito = $this->myDao->consultaTabla("*","carrito","id_car = $id_car and id_cl = $id_cl");

			//La informacion del carrito podria ser 0, dado el caso
			// de un chistosito que puso una op aCan y un id carrito
			// que no le corespondiera.
			if(count($info_carrito) != 0)
			{
				// Se aumenta la cantidad en 1.
				$cantidad = intval($info_carrito[0]["cantidad"]) + 1;
				// Se verifica el tipo de platillo para obtener el precio.
				if(isset($info_carrito[0]["id_pl"]))
				{
					$id_pl = $info_carrito[0]["id_pl"];
					$info_platillo = $this->myDao->consultaTabla("*","platillo","id_pl = $id_pl");
					$precio = $info_platillo[0]["precio"];
				}
				else
				{
					$id_cd = $info_carrito[0]["id_cd"];
					$info_platillo = $this->myDao->consultaTabla("*","cDia","id_cd = $id_cd");
					$precio = $info_platillo[0]["precio"];
				}
				// Una vez que se obtuvo el precio del platillo.
				// Se obtiene el saldo del cliente.
				$info_cliente = $this->myDao->consultaTabla("*","cliente","id_cl = $id_cl");
				$saldo = intval($info_cliente[0]["saldo"]);

				// y si el saldo es mayor o igual al precio del platillo.
				// Ahora si se actualiza la base de datos.
				if($saldo >= $precio)
				{
					// Se calcula el nuevo saldo.
					$saldo = $saldo - intval($precio);
					// Se actualiza el saldo del cliente.
					$this->myDao->updateData("cliente","id_cl = $id_cl","saldo = $saldo");
					// Se actualiza la cantidad del platillo.
					$this->myDao->updateData("carrito","id_car = $id_car","cantidad = $cantidad");
				}
			}
			$this->verCarrito();
		}
		/*
		* Lo mismo que aumentarCantidad solo que se resta en 1.
		*/
		public function disminuirCantidad()
		{
			// Igual, contra chistositos.
			$id_car = $_GET["id_car"];
			$id_cl = $this->getIdUser();
			$info_carrito = $this->myDao->consultaTabla("*","carrito","id_car = $id_car and id_cl = $id_cl");
			
			// Si no hay nada en el carrito, pues fue un chistosito.
			if(count($info_carrito) != 0)
			{	
				// la cantidad se disminuye en 1.
				$cantidad = intval($info_carrito[0]["cantidad"]) - 1;

				// Si la cantidad es 0, no tiene porque disminuir y no se hace nada.
				if($cantidad != 0)
				{
					if(isset($info_carrito[0]["id_pl"]))
					{
						$id_pl = $info_carrito[0]["id_pl"];
						$info_platillo = $this->myDao->consultaTabla("*","platillo","id_pl = $id_pl");
						$precio = $info_platillo[0]["precio"];
					}
					else
					{
						$id_cd = $info_carrito[0]["id_cd"];
						$info_platillo = $this->myDao->consultaTabla("*","cDia","id_cd = $id_cd");
						$precio = $info_platillo[0]["precio"];
					}
					$info_cliente = $this->myDao->consultaTabla("*","cliente","id_cl = $id_cl");

					// Se le regresa el saldo al cliente.
					$saldo = intval($info_cliente[0]["saldo"]) + intval($precio);
					
					// Se actualiza la base de datos.
					$this->myDao->updateData("cliente","id_cl = $id_cl","saldo = $saldo");
					$this->myDao->updateData("carrito","id_car = $id_car","cantidad = $cantidad");
				}
			}
			$this->verCarrito();
		}

		/* 
		*	Siempre se necesita el id_cl.
		*	@return id_cl
		*/
		public function getIdUser()
		{
			if(!isset($_SESSION["adm"]))
			{
				$user = $_SESSION["user"];
				$info_user = $this->myDao->consultaTabla("*","cliente","correo = '$user'");
				return $info_user[0]["id_cl"];
			}
		}
		
		//Anairene.- Gestionar Admins
		
		/**
         * Manda al gui que muestre la forma para añadir a un nuevo admin.
         * @return None
         */
		public function formaAnadirAdm()
		{
			$this->myGui->formaAnadirAdm();
		}
		
		/**
         * Se validan los datos ingresados para añadir admin y le manda al dao que lo guarde en la bd.
         * @return None
         */
		public function guardarAdm()
		{
			$admin=$_POST["admin"];
			
			if($admin["nombre"] == "")
			{
				$this->myGui->error(607);
				header("Refresh: 3;index.php?op=anadirAdm");
			}
			elseif($admin["correo"] == "")
			{
				$this->myGui->error(608);
				header("Refresh: 3;index.php?op=anadirAdm");
			}
			elseif($admin["usuario"] == "")
			{
				$this->myGui->error(609);
				header("Refresh: 3;index.php?op=anadirAdm");
			}
			//Anairene
			elseif (strlen($admin["clave"]) > 6) 
			{
				$this->myGui->error(203);		
				header("Refresh: 3;index.php?op=anadirAdm");
			}
			//Fin Anairene
			elseif($admin["clave"] == "")
			{
				$this->myGui->error(610);
				header("Refresh: 3;index.php?op=anadirAdm");
			}
			elseif($admin["clave"] != $admin["clave2"])
			{
				$this->myGui->error(303);
				header("Refresh: 3;index.php?op=anadirAdm");
			}
			elseif($admin["direccion"] == "")
			{
				$this->myGui->error(611);
				header("Refresh: 3;index.php?op=anadirAdm");
			}
			elseif($admin["telefono"] == "")
			{
				$this->myGui->error(612);
				header("Refresh: 3;index.php?op=anadirAdm");
			}
			else
			{
				//Verificamos que no haya usuarios de administrador iguales o correos repetidos.
				$adminRepetido = $this->myDao->consultaTabla("*","administrador","usuario='".$admin["usuario"]."'");
				$correoRepetido = $this->myDao->consultaTabla("*","administrador","correo='".$admin["correo"]."'");
				if((count($adminRepetido)== 0) && (count($correoRepetido)== 0))
				{
					//En caso de que no suceda, mandaos al dao a que inserte el nuevo registro.
					$columnas = "nombre, correo, usuario, clave, direccion, telefono";
					$valores = "'".strtoupper($admin["nombre"])."','".$admin["correo"]."','".$admin["usuario"]."','".$admin["clave"]."','".$admin["direccion"]."','".$admin["telefono"]."'";
					$error = $this->myDao->insertarEnTabla("administrador", $columnas, $valores);
					//Verificamos si se realizo la insercion con exito
					if($error == 1)
					{
						// si asi fue, mandamos mensaje de exito.
						//$this->myGui->mensaje(5);
						?>
							<script>
								request('msj&&no=5','todo');
							</script>
						<?php
					}
					else
					{
						//En caso contrario, mandamos mensaje de error.
						//$this->myGui->error(613);
						?>
							<script>
								request('err&&no=613','todo');
							</script>
						<?php
						//header("Refresh: 3;index.php?op=consulAdm");
					}
				}
				else
				{
					// En caso de encontrar usuarios o correos iguales, mandamos mensaje de error.
					//$this->myGui->error(614);
					?>
						<script>
							request('err&&no=614','todo');
						</script>
					<?php
					//header("Refresh: 3;index.php?op=anadirAdm");
				}
			}
		}
		
		/**
         * Se manda a mostrar la tabla con los administradores registrados, siempre y cuando se encuentren
		 * activos y que no sean super administradores.
         * @return None
         */
		public function formaConsulAdm()
		{
			$admins = $this->myDao->consultaTabla("*","administrador","sudo_su=0 and estatus='A'");
			$this->myGui->formaConsulAdm($admins);
		}
		
		/**
         * Se manda al gui que muestre la informacion del administrador que se desea modificar, en formato editable
		 * para que se puedan actualizar los datos.
         * @return None
         */
		public function formaModifAdm()
		{
			$id_ad = $_GET["id_ad"];	
			$infoadm = $this->myDao->consultaTabla("*","administrador","id_ad=".$id_ad);
			$this->myGui->formaModifAdm($infoadm);
		}
		
		/**
         * Se validan los datos que se desean actualizar y se manda al dao a que los guarde en la base de datos.
         * @return None
         */
		public function guardarModifAdm()
		{
			$admin = $_POST["admin"];
			$id_ad=$admin["id_ad"];
			if($admin["nombre"] == "")
			{
				$this->myGui->error(607);
				header("Refresh: 3;index.php?op=modifAdm&&id_ad=$id_ad");
			}
			elseif($admin["correo"] == "")
			{
				$this->myGui->error(608);
				header("Refresh: 3;index.php?op=modifAdm&&id_ad=$id_ad");
			}
			elseif($admin["direccion"] == "")
			{
				$this->myGui->error(611);
				header("Refresh: 3;index.php?op=modifAdm&&id_ad=$id_ad");
			}
			elseif($admin["telefono"] == "")
			{
				$this->myGui->error(612);
				header("Refresh: 3;index.php?op=modifAdm&&id_ad=$id_ad");
			}
			else
			{
				//Verificamos que no estamos actualizando un correo repetido.
				$condicion = "id_ad != '$admin[id_ad]' and correo = '$admin[correo]'";
				$correoRepetido = $this->myDao->consultaTabla("*","administrador",$condicion);
				if((count($correoRepetido)== 0))
				{
					//En caso de que no sea asi, se actualiza el registro en la base de datos.
					$set ="nombre='".strtoupper($admin["nombre"])."',correo='$admin[correo]',direccion='$admin[direccion]',telefono='$admin[telefono]'";
					$condicion = "id_ad ='$admin[id_ad]'";
					$error = $this->myDao->updateData("administrador",$condicion,$set);
					//Verificamos que no se regresara error en la insercion 
					if($error == 1)
					{
						//Si no fue asi, se manda mensaje de actualizacion exitosa.
						//$this->myGui->mensaje(6);
						?>
							<script>
								request('msj&&no=6','todo');
							</script>
						<?php
						//header("Refresh: 3;index.php?op=consulAdm");
					}
					else
					{
						//En caso contrario, se manda mensaje de error.
						//$this->myGui->error(616);
						?>
							<script>
								request('err&&no=616','todo');
							</script>
						<?php
						//header("Refresh: 3;index.php?op=consulAdm");
					}
				}
				else
				{
					//En caso de que se este actualizando un correo repetido, se mandara un mensaje de error.
					//$this->myGui->error(615);
					?>
						<script>
							request('err&&no=615','todo');
						</script>
					<?php
					//header("Refresh: 3;index.php?op=modifAdm&&$id_ad");
				}
			}
			
		}
		
		/**
         * Se manda al gui que muestre la solicitud de confirmacion de baja.
         * @return None
         */
		public function validarBajaAdm()
		{
			$id_ad = $_GET["id_ad"];
			$infoadm = $this->myDao->consultaTabla("*","administrador","id_ad=".$id_ad);
			$nombre = $infoadm[0]["nombre"];
			$this->myGui->validarBajaAdm($id_ad,$nombre);
		}
		
		/**
         * Se manda al dao a que de de baja el registro del administrador dado.
         * @return None
         */
		public function guardarBajaAdm()
		{
			$id_ad = $_GET["id_ad"];
			$set ="estatus = 'B'";
			$condicion = "id_ad =$id_ad";
			$error = $this->myDao->updateData("administrador",$condicion,$set);

			if($error == 1)
			{
				//$this->myGui->mensaje(7);
				?>
					<script>
						request('msj&&no=7','todo');
					</script>
				<?php
				//header("Refresh: 3;index.php?op=consulAdm");
			}
			else
			{
				//$this->myGui->error(617);
				?>
					<script>
						request('err&&no=617','todo');
					</script>
				<?php
				//header("Refresh: 3;index.php?op=consulAdm");
			}
		}
		
		/**
         * Se manda al dao a buscar la informacion del administrador que se selecciono, y al gui a que muestre la info.
         * @return None
         */
		public function verAdm()
		{
			$id_ad = $_GET["id_ad"];
			$infoadm = $this->myDao->consultaTabla("*","administrador","id_ad=".$id_ad);
			$this->myGui->verAdm($infoadm);
			
		}
		// Fin Anairene.- Gestionar Admins
		
		//Moxis recarga

		public function realizarRecarga()
		{
			$this -> myGui -> realizarRecarga();
		}

		public function enviarRecarga()
		{
			$datosRe = $_POST["recarga"];  // recibe los datos de la recarga
			$correo = $datosRe["usuario"];		//Correo del usuario
			$cantidadCB = $datosRe["cantidadCB"];   // cantidad a recargar
			$condicion = "correo = '$correo'";	
			$infoUsu = $this->myDao->consultaTabla("*","cliente",$condicion); //Saca la informacion del usuario.
			if ($cantidadCB == "")
			{
				?>
					<script>
						request('err&&no=90','todo');
					</script>
				<?php
			}
			elseif (count($infoUsu) == 0) // revisa que el usuario exista.
			{
				?>
					<script>
						request('err&&no=619','todo');
					</script>
				<?php
			}
			else
			{
				$saldoUsu = $infoUsu[0]['saldo']; // saca el saldo actual del usuario.
				$total = $saldoUsu + $cantidadCB;  // suma el saldo actual del usuario con el que se va a recargar.
				$condicion2 = "correo = '$correo'";
				$set = "saldo = $total";
				$this->myDao->updateData("cliente",$condicion,$set);
				?>
					<script>
						request('msj&&no=646','todo');
					</script>
				<?php
			}
			

		}

		//Anairene.- Baja de Platillos y Comida del Dia
		public function desactivarPl()
		{
			if(isset($_GET["id_cd"]))
			{
				$id_cd = $_GET["id_cd"];
				$set = "status = 0";
				$condicion = "id_cd = $id_cd";
				$this->myDao->updateData("cDia",$condicion,$set);
				$this->myGui->activarDesactivarCDia($id_cd,0);
				//header("Location: index.php");
			}
			elseif (isset($_GET["id_pl"])) 
			{
				$id_pl = $_GET["id_pl"];
				$set = "status = 0";
				$condicion = "id_pl = $id_pl";
				$this->myDao->updateData("platillo",$condicion,$set);
				$categoria = $this->myDao->consultaTabla("id_ct","platillo",$condicion);
				$id_ct = $categoria[0]["id_ct"];
				$this->myGui->activarDesactivarPlatillo($id_pl,0);
				//header("Location: index.php?op=vMenu&&id_ct=$id_ct");
			}
		}
		public function activarPl()
		{
			if(isset($_GET["id_cd"]))
			{
				$id_cd = $_GET["id_cd"];
				$set = "status = 1";
				$condicion = "id_cd = $id_cd";
				$this->myDao->updateData("cDia",$condicion,$set);
				$this->myGui->activarDesactivarCDia($id_cd,1);
				//header("Location: index.php");
			}
			elseif (isset($_GET["id_pl"])) 
			{
				$id_pl = $_GET["id_pl"];
				$set = "status = 1";
				$condicion = "id_pl = $id_pl";
				$this->myDao->updateData("platillo",$condicion,$set);
				$categoria = $this->myDao->consultaTabla("id_ct","platillo",$condicion);
				$id_ct = $categoria[0]["id_ct"];
				$this->myGui->activarDesactivarPlatillo($id_pl,1);
				//header("Location: index.php?op=vMenu&&id_ct=$id_ct");
			}
		}
		//Fin Baja de Platillos y Comida del Dia

		//Gaby _ Gestionar favoritos///
		/**
         * Obtiene el id del usuario y del platillo, para almacenarlos en la tabla de favoritos. Y regresa a ver los pedidos del cliente.
         * @return None
         */
		public function marcarFavoritos()
		{
			$id_pe= $_GET["id_pe"];
			$pedido= $this->myDao->consultaTabla("*", "pedido", "id_pe='$id_pe'");
			$id_cl = $this->getIdUser();
			$id_pe=$pedido[0]["id_pe"];
			
			$this->myDao->insertarEnTabla("favoritos", "id_cl, id_pe", "$id_cl, $id_pe");
			$this->verPedidosCliente();
		}


		/**
         * Muestra los favoritos del cliente, para lo cual debe consultar la información de cada favorito. La consulta será diferente dependiendo de si se trata de un platillo del menú o de una comida.
         * @return None
         */
		public function showFavorito()
		{
			$id_cl = $this->getIdUser();

			$favoritos= $this->myDao->consultaTabla("*", "favoritos", "id_cl=$id_cl");
			$n= count($favoritos);
			$platillos=array();
			$descripciones=array();

			for($i=0; $i<$n; $i++)
			{
				$id_pe=$favoritos[$i]["id_pe"];
				$infoPlatillo=$this->myDao->consultaTabla("*","pedido","id_pe=$id_pe");

				$id_pl = $infoPlatillo[0]["id_pl"]; 
				$id_cd = $infoPlatillo[0]["id_cd"];

				if($id_pl!=NULL)
				{
					$platillo = $this->myDao->consultaTabla("platillo","platillo","id_pl=$id_pl");
				}
				else
				{
					$platillo = $this->myDao->consultaTabla("p_fuerte","cDia","id_cd=$id_cd");
				}

				$desc=$this->myDao->consultaTabla("descripcion","pedido","id_pe=$id_pe"); 
				
				if($desc==NULL)
				{
					$descripcion= NULL;
				}
				else
				{
					$descripcion=$desc;
				}
				
				array_push($platillos, $platillo[0]);
				array_push($descripciones, $descripcion[0]);
			}
			$this->myGui->mostrarFavoritos($id_cl, $favoritos, $platillos, $descripciones);
		}

		/**
         * Obtiene el id del pedido a eliminar de la tabla de favoritos, al eliminarlo, redirecciona al usuario al sitio donde se encontraba.
         * @return None
         */
		public function eliminarFav()
		{
			$id_pe=$_GET["id_pe"];
			$id_cl = $this->getIdUser();
			$this->myDao->deleteData("favoritos", "id_pe=$id_pe");
			if($_GET["l"]==1)
			{
				header("Location: index.php?op=vPCliente");
			}
			else
			{
				header("Location: index.php?op=showFav");
			}
		}

		/**
         * Prepara la información que se enviará a agregar carrito. Esta información pertenece a un pedido que fue marcado como favorito. También envía la descripción.
         * @return None
         */
		public function pedirFav()
		{
			$id_pe=$_GET["id_pe"]; 
			
			$pedido=$this->myDao->consultaTabla("*","pedido","id_pe=$id_pe");
			$id_pl=$pedido[0]["id_pl"];
			$id_cd=$pedido[0]["id_cd"];
			$desc=$pedido[0]["descripcion"];
			if($id_pl!=NULL)
			{
				//header("Location: index.php?op=aCar&&id_pl=$id_pl&&desc=$desc");
				$id_cd_pl=$id_pl;
				$tipo="platillo";
			}
			else
			{
				//header("Location: index.php?op=aCar&&id_cd=$id_cd&&desc=$desc");
				$id_cd_pl=$id_cd;
				$tipo="comida";
			}
			$this->agregarCarrito($id_cd_pl,$desc,$tipo);
		}
		////FIN GABY
		///
		
		/**
		 * Agrega un like a un platillo.
		 */
		public function addLikePedido()
		{
			//Se consigue el id del pedido al cual se le quiere agregar el like
			$id_pe = $_POST["like"];
			$condicion = "id_pe=".$id_pe;
			//Se consulta la informacion del usuario desde su pedido.
			$informacionUsuario = $this->myDao->consultaTabla("id_cl, id_pl", "pedido", $condicion);
			$idCliente = $informacionUsuario[0]["id_cl"];
			$idPlatillo = $informacionUsuario[0]["id_pl"];
			//Se insertan los datos necesarios para agregar el like a la tabla.
			$this->myDao->insertarEnTabla("likes", "id_cl, id_pl", $idCliente.", ".$idPlatillo);
			?>
			<script type="text/javascript">
				alert("Se ha agreado el like");
				window.location.href="index.php?vPCliente"; 
			</script>
			<?
			//header("Location: index.php?op=vPCliente");

		}
		/**
		 * Elimina un platillo del carrito.
		 */
		public function eliminarCarrito()
		{
			//Se obtiene la informacion del carrito.
			$idCarrito = $_GET["id_car"];
			//Se obtiene su usuario.	
			$idUsuario = $this->getIdUser();
			$condicion = "id_cl=".$idUsuario." and id_car=".$idCarrito;
			//Se obtiene la informacion de los pedidos dentro del carrito.
			$informacionCarrito = $this->myDao->consultaTabla("*","carrito",$condicion);

			$total = 0;
			// Se calcula el precio que se le regresara
			// dependiendo si fue comida del dia o platillo regular.
			if ( isset( $informacionCarrito[0]["id_cd"] ) )
			{
				$id_cd = $informacionCarrito[0]["id_cd"];
		
				
				$cantidad = $informacionCarrito[0]["cantidad"];
				$precio = $this->myDao->consultaTabla("precio", "cDia", "id_cd = $id_cd");

				$precio = (int) $precio[0]["precio"];
				$cantidad = (int) $cantidad;
				$subTotal =  $precio * $cantidad;
				$total = $subTotal;
			}
			else
			{
				$id_pl = $informacionCarrito[0]["id_pl"];
				$cantidad = $informacionCarrito[0]["cantidad"];	

				$precio = (int) $precio[0]["precio"];
				$cantidad = (int) $cantidad;
				$subTotal =  $precio * $cantidad;
				$total = $subTotal;

			}
			//Se calcula el saldo que sera regresado.
			$saldo = $this->myDao->consultaTabla("saldo", "cliente", "id_cl=".$idUsuario);
			$saldoNuevo = (int)$saldo[0]["saldo"] + $total;
			//Se actualiza la base de datos:
			//1.>>>>>>> Se elimina el platillo
			//2.>>>>>>> Se agrega el saldo nuevo
			$this->myDao->updateData("cliente", "id_cl=".$idUsuario, "saldo=".$saldoNuevo);
			$this->myDao->deleteData("carrito", "id_car=".$idCarrito);

			$this->verCarrito();
		}

		public function mensaje()
		{
			$numero = $_GET["no"];
			$this->myGui->mensaje($numero);
		}

		public function error()
		{
			$numero = $_GET["no"];
			$this->myGui->error($numero);
		}
		//Calcula el total a pagar de los platillos que se encuentran en el carrito.
		public function calcularTotal()
		{
			$id_cl = $this->getIdUser();
			$carrito_info = $this->myDao->consultaTabla("*","carrito","id_cl=$id_cl");
			$n = count($carrito_info);
			$total = 0;
			for($i=0;$i<$n;$i++)
			{
				if(isset($carrito_info[$i]["id_pl"]))
				{
					$id_pl = $carrito_info[$i]["id_pl"];
					$platillo_info = $this->myDao->consultaTabla("*","platillo","id_pl=$id_pl");
				}
				else
				{
					$id_cd = $carrito_info[$i]["id_cd"];
					$platillo_info = $this->myDao->consultaTabla("*","cDia","id_cd=$id_cd");
				}
				$cantidad = $carrito_info[$i]["cantidad"];
				$precio = $platillo_info[0]["precio"];
				$total = $total + $precio*$cantidad;
			}
			return $total;
		}


		public function doGet($op)
		{
			//Anairene.- Gestionar Admins
			if(isset($_SESSION["adm"]))
			{
				$admin = $_SESSION["user"];
				$sudo = $this->myDao->consultaTabla("sudo_su","administrador","usuario='".$admin."'");
			}
			//Fin Gestionar Admins
			switch($op)
			{	
				//Anairene.- Baja de Platillos y Comida del Dia
				case "desactivarPl":
					$this->desactivarPl();
					break;
				case "activarPl":
					$this->activarPl();
					break;
				//Fin Baja de Platillos y Comida del Dia
					
				//moxis
				case "recarga":
					$this->realizarRecarga();
					break;
				case "enviarRecarga":
					$this->enviarRecarga();
					break;
				//moxis
				case "hPed":
					$this->realizarPedido();
					break;

				case "envPedido":
					$this->enviarPedido();
					break;

				case "":
					$this->inicio();
					break;
				
				case "auth":
					$this->loginAutentificacion();
					break;
				
				case "lgOut":
					$this->logOut();
					break;
				
				//Esto es solo un ejemplo 
				case "consulta":
					$this->consulta();
					break;
					
				
				// ANAIRENE ISHIHARA 16/Abril/2016
				case "regForm":
					$this->formaRegistro();
					break;
				case "regConf":
					$this->confirmarCliente();
					break;
					
				case "cod":
					$this->confirm();
					break;
				//FIN ANAIRENE ISHIHARA
				//Manuel Carrillo 16/04/2016 07:21
				
				case "vPed":
					$this->verPedidos();
					break;
				//Cambiar estado de notificacion
				case "cEstNotif":
					$this->cambiarEstadoNotif();
					break;
				//Cambiar estado de pedido(adm) y enviar notificacion
				case "cEsteNotif":
					$id_pe = $_GET["id_pe"];
					$msj = $_GET["msj"];
					$this->mandarNotificacion($id_pe,0,$msj);
					$this->cambiarEstado();
					break;
				case "vBEntrada":
					$this->verBandejaEntrada();
					break;
				case "eNotif":
					$this->eliminarNotificacion();
					break;

				case "vMenu":
					$this->verMenu();
					break;
				
				/*case "cDia":
					$this->verCDia();
					break;
					*/
					
				case "vPCliente":
					$this->verPedidosCliente();
					break;
		
				case "cPed":
					$id_pe = $_GET["id_pe"];
					$msj = $_GET["msj"];
					$this->mandarNotificacion($id_pe,0,$msj);
					$this->cancelarPedido();
					break;

				// Anairene.- Gestionar Admins
				case "anadirAdm":
					if(isset($_SESSION["adm"]))
					{
						if($sudo[0]["sudo_su"] == 1)
						{
							$this->formaAnadirAdm();
						}
						else
						{
							header("Location: index.php");
						}
					}
					else
					{
						header("Location: index.php");
					}
					break;

				case "saveAdm":
					if(isset($_SESSION["adm"]))
					{
						if($sudo[0]["sudo_su"] == 1)
						{
							$this->guardarAdm();
						}
						else
						{
							header("Location: index.php");
						}
					}
					else
					{
						header("Location: index.php");
					}
					break;

				case "consulAdm":
					if(isset($_SESSION["adm"]))
					{
						if($sudo[0]["sudo_su"] == 1)
						{
							$this->formaConsulAdm();
						}
						else
						{
							header("Location: index.php");
						}
					}
					else
					{
						header("Location: index.php");
					}
					break;

				case "bajaAdm":
					if(isset($_SESSION["adm"]))
					{
						if($sudo[0]["sudo_su"] == 1)
						{
							$this->validarBajaAdm();
						}
						else
						{
							header("Location: index.php");
						}
					}
					else
					{
						header("Location: index.php");
					}
					break;

				case "saveBajaAdm":
					if(isset($_SESSION["adm"]))
					{
						if($sudo[0]["sudo_su"] == 1)
						{
							$this->guardarBajaAdm();
						}
						else
						{
							header("Location: index.php");
						}
					}
					else
					{
						header("Location: index.php");
					}
					break;

				case "modifAdm":
					if(isset($_SESSION["adm"]))
					{
						if($sudo[0]["sudo_su"] == 1)
						{
							$this->formaModifAdm();
						}
						else
						{
							header("Location: index.php");
						}
					}
					else
					{
						header("Location: index.php");
					}
					break;

				case "savemodifAdm":
					if(isset($_SESSION["adm"]))
					{
						if($sudo[0]["sudo_su"] == 1)
						{
							$this->guardarModifAdm();
						}
						else
						{
							header("Location: index.php");
						}
					}
					else
					{
						header("Location: index.php");
					}
					break;

				case "verAdm":
					if(isset($_SESSION["adm"]))
					{
						if($sudo[0]["sudo_su"] == 1)
						{
							$this->verAdm();
						}
						else
						{
							header("Location: index.php");
						}
					}
					else
					{
						header("Location: index.php");
					}
					break;
				//fin Anairene.-Gestionar Admins
				
				//Inicio Carrito
				case "aCar":
					if(!isset($_SESSION["adm"]))
					{
						$this->agregarCarrito();
					}
					else
					{
						header("Location: index.php");
					}
					break;

				case "vCar":
					if(!isset($_SESSION["adm"]))
					{
						$this->verCarrito();
					}
					else
					{
						header("Location: index.php");
					}
					break;

				case "gCar":
					if(!isset($_SESSION["adm"]))
					{
						$this->guardarCarrito();
					}
					else
					{
						header("Location: index.php");
					}
					break;

				case "aCan":
					if(!isset($_SESSION["adm"]))
					{
						$this->agregarCantidad();
					}
					else
					{
						header("Location: index.php");
					}
					break;

				case "dCan":
					if(!isset($_SESSION["adm"]))
					{
						$this->disminuirCantidad();
					}
					else
					{
						header("Location: index.php");
					}
					break;
				//Fin carrito

				//Favoritos
				case "addFav":
					$this->marcarFavoritos();
					break;

				case "showFav":
					$this->showFavorito();
					break;

				case "delFav":
					$this->eliminarFav();
					break;

				case "pFav":
					$this->pedirFav();
					break;
				//FIN favoritos
				
				case "addLike":
					$this->addLikePedido();
					break;

				case "eliminarCarrito":
					$this->eliminarCarrito();
					break;

				case "msj":
					$this->mensaje();
					break;

				case "err":
					$this->error();
					break;
			}
		}
		
	}
?>
