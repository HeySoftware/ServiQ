<?php
	class Dao
	{
		//En esta variable se almancena el objeto de la clase DBMysql.
		var $objDB;
		
		/*
		*	La clase Dao, se encarga de la interaccion con la base de datos.
		*	@return None
		*/
		public function __construct()
		{
			//Inicia la funcion conect.
			$this->conect();
		}
		
		/*
		*	Esta funcion crea un objeto de la clase DBMysql y la conecta a la base de datos.
		*/
		public function conect()
		{
			require_once("DBMysql.php");
			//En este directorio esta la informacion de la base de datos.
			include("/home/alumno/al342460/public_html/ServiQ/include/db/db.php");
			
			$this->objDB= new DBMysql();
			//Aqui se hace la verdadera conexion.
			$this->objDB->conect($db,$host,$user,$passwd);
		}
		
		/*
		*	Esta funcion realiza una consulta a la base de datos.
		*	Verifica que la clave y el usuario coincidan.
		*	@param $user, El correo de la uabc, en caso de ser administrador el usuario.
		*	@param $clave, La clave correspondiente al correo o usuario.
		*	@param $adm, por default es false, verifica que tipo de usuario es.
		*	@return int, si es 1 significa que se encontro al usuario y clave en la base de datos.
		*/
		public function auth($user,$clave,$adm=false)
		{	
			if($adm)
			{
				$query = "select id_ad from administrador where clave='$clave' and usuario='$user';";
				$res = $this->objDB->select($query);
				if($row = $this->objDB->getRow($res))
				{
					session_start();
					$_SESSION["user"]=$user;
					$_SESSION["pass"]=$clave;
					$_SESSION["adm"] = true;
					return 1;
				}
				else
				{
					return 0;
				}
			}
			else
			{
				$query = "select id_cl from cliente where clave='$clave' and correo='$user' and status=1;";
				$res = $this->objDB->select($query);
				if($row = $this->objDB->getRow($res))
				{
					session_start();
					$_SESSION["user"]=$user;
					$_SESSION["pass"]=$clave;
					return 1;
				}
				else
				{
					return 0;
				}
			}
			
		}
		/**
		 * Consulta las tablas de la base de datos.
		 * @param  string $columna   La columna que se desea obtener de la consulta.
		 * @param  string $tabla     La tabla de la cual se extraeran los datos.
		 * @param  string $condicion Condicion necesaria para obtener la informacion (Puede estar vacia, cuando no se necesita condicion).
		 * @return array             Regresa el arreglo con la informacion de la consulta.
		 */
		public function consultaTabla($columna, $tabla, $condicion="")
		{
			//Se preparan los argumentos de la consulta.
			//La cual es una instruccion para la base de datos.
			if($condicion != "")
			{
				$query = "SELECT ".$columna." FROM ".$tabla." WHERE ".$condicion.";";
			}
			else
			{
				$query = "SELECT ".$columna." FROM ".$tabla.";";
			}
			
			//Se manda la instruccion y regresa
			//La respuesta de la base de datos.
			$res = $this->objDB->select($query);

			//Se crea una vector donde se almacena la consulta.
			$consulta = array();

			//Se obtiene las columnas con la informacion 
			//Y se almacena en el arreglo.
			while( $row = $this->objDB->getRow($res) )
			{
				//Se almacena cada informacion
				//De las columnas.
				$consulta[] = $row;
			}
			
			//Regresa la consulta como un arreglo.
			return $consulta;

		}
		
		//ANAIRENE ISHIHARA.- REGISTRAR CLIENTE 16/Abril/2016
		public function regConfirm($cliente)//funcion que envia correo con la liga de confirmacion
		{
			
			$codigoverificacion = rand(0000000000,9999999999); // Conseguimos un codigo aleatorio de 10 digitos.
			$sql = "INSERT INTO registrotemp(correo,clave,codigo) VALUES ('$cliente[correo]','$cliente[clave]', '$codigoverificacion')";
			$correo = $cliente["correo"];
			if ($this->objDB->correoConfirm($sql));
				$headers = "From: ServiQ@noreplay.com"; 
				$mensaje = "Usted solicito un registro en Servi-Q, \n 
				Para confirmarlo debe hacer click en el siguiente enlace: \n 
				http://lcc.ens.uabc.mx/~al342460/ServiQ/index.php?op=cod&codigo=".$codigoverificacion; 
			if (!@mail("$correo","Confirmacion de registro en Servi-Q","$mensaje","$headers")) die ("No se pudo enviar el email de confirmacion."); 
				return 1; 
		}
		
		public function confirm($codigo)//le pide al dao que busque el codifo de verificacion para registrar a un usuario
		{
			$confirmar = $this->objDB->buscar($codigo);
			return $confirmar;
		}
		//FIN ANAIRENE REGISTRAR CLIENTE 16/Abril/2016
		/**
		 * Recibe una consulta de la base de datos
		 * Regresa 1 si fue con exito
		 * Regresa 0 si fallo
		 */
		private function confirmacion($id)
		{
			if ( $id == 0 )
			{
				//Si no fue exitosa.
				return -1;
			}	
			else
			{
				//Si fue exitosa
				return 1;
			}
		}
		/**
		 * Inserta un conjunto de datos dentro de una tabla.
		 * @param  string $nombreTabla Nombre de la tabla den donde se va a insertar.
		 * @param  string $columnas    Columnas donde se van a insertar las tablas.
		 * @param  string $valores     Valores a insertar dentro de la tabla.
		 * @return int    Regresa 1 si la insercion fue exitosa, de lo contrario regresa -1.
		 */
		public function insertarEnTabla($nombreTabla, $columnas, $valores)
		{

			/**
			 * Se prepara la insercion de informacion.
			 * @var string
			 */
			
			$sql = "INSERT INTO ".$nombreTabla." (".$columnas.") "."VALUES "." (".$valores.")".";";
			/**
			 * Es una confirmacion de insercion
			 * @var int
			 */
			
			$confirmacion = $this->objDB->insert($sql);
			
			/**
			 *	Se confirma si la informacion
			 *	fue insertada con exito.
			 */
			return $this->confirmacion($confirmacion);
		}

		/**
		 * Actualiza los datos de una tabla en la base de datos.
		 * @param  string $nombreTabla Nombre de la(s) tabla(s) donde se actualizaran el/los dato(s).
		 * @param  string $condicion   Condicion necesaria para actualizar el/los dato(s).
		 * @param  string $set         La condicion que tomara la actualizacion del dato(s).
		 * @return int                 Confirmacion de la actualizacion(Si es 1 es exitosa, Si es -1 fallo).
		 */
		public function updateData($nombreTabla, $condicion="", $set="")
		{
				/**
				 * La instruccion que actualiza los datos, se prepara.
				 * @var string
				 */
				
				$sql = "UPDATE ".$nombreTabla." SET ".$set." WHERE ".$condicion.";";
				/**
				 * Respuesta de la base de datos ante la insercion.
				 * @var int
				 */
				
				$idSql = $this->objDB->update($sql);

				//Regresa exito(1) o fallo(-1)
				return $this->confirmacion($idSql);
		}
		/**
		 * Borra la informacion de una tabla dada una condicion.
		 * @param  string $nombreTabla nombre de la tabla de la cual, se quieren borrar los datos.
		 * @param  string $condicion   Condicion necesaria para que se borren los datos.
		 * @return int 		           La base de datos regresa la validacion de la actualizacion.
		 */
		public function deleteData($nombreTabla, $condicion="")
		{
				/**
				 * Se prepara la eliminacion de los datos.
				 * @var string
				 */
				$sql = "DELETE "."FROM ".$nombreTabla." WHERE ".$condicion.";";

				/**
				 * Un estado de la base de datos al eliminar.
				 * Generalmente regresa exito, o regresa fallo, si la accion produjo algun error.
				 * @var Integer
				 */
				$idSql = $this->objDB->update($sql);
				//return
				return $this->confirmacion($idSql);
		}
	}
?>