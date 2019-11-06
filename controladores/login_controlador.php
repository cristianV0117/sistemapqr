<?php
/**
 *
 * @author Cristian Camilo Vasquez
 * Clase logeo
 * Se incluyen:
 * El archivo del historial de ingresos y salidas.
 * El archivo de clases query para la utilizacion del constructor de querys.
 *
 */
include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/clases/historial_ingreso_salida.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/clases/clasesQuery.php';

class logeo 
{
	/**
	 * @property nombreUsuario null
	*/
	private $nombreUsuario;
	/**
	 * @property contrasena null
	*/
	private $contrasena;
	/**
	 * @property tipo null
	*/
	private $tipo;
	/**
	 * @property his null
	*/
	private $his;
	/**
	 * @property his null
	*/
	private $sesion;
	/**
	 * @property DB null
	*/
	private $DB;

	/**
 	 *
 	 * @param:$postlogeo Almacena el nombre de usuario y la contraseña que se van a verificar.
 	 * @property:$this->DB Es un objeto de clasesQuery(). Para el envio de consultas.
 	 * @property:$this->hi Es un objeto de HistorialIngresoSalida() Para el historial de ingresos y salidas a la plataforma.
 	 * @property:$this->nombreUsuario almacena la variable @var:$postlogeo['nombreUsuario'].
 	 * @property:$this->contrasena almacena la variable @var:$postlogeo['contrasena'].
 	 * @property:$this->tipo almacena la variable @var:$postlogeo['tipo'].
 	 * Se filtra por @property:$this->tipo utilizando un switch case
 	 * Al verificar $this->tipo se llama @method:$this->verificacionDeLogeo($this->nombreUsuario,$this->contrasena,$this->tipo) en caso de que sea inserción.
 	 *
 	 */
	function __construct($postlogeo) 
	{
		$this->DB = new clasesQuery();
		$this->his = new HistorialIngresoSalida();
		$this->nombreUsuario = htmlentities(addslashes($postlogeo['nombreUsuario']));
		$this->contrasena = htmlentities(addslashes($postlogeo['contrasena']));
		$this->tipo = $postlogeo['tipo'];
		// Verificación de la propiedad $this->tipo.
		switch ($this->tipo) 
		{
			case 'insercion':
				$this->verificacionDeLogeo($this->nombreUsuario, $this->contrasena, $this->tipo);
				break;
			default:
			break;
		}
	}

	/**
     *
     * @method:verificacionDeLogeo(parametros) Se encarga de verificar el logeo del usuario a partir de la petición.
     * @param:$nombreUsuario.
     * @param:$contrasena.
     * @param:$tipo.
     * Al verificar si es correcto el nombre_usuario se almacena la contraseña del registro en @var:$contrasenaBase.
     * Se realiza un password_verify(@property:$this->contrasena,@var:$contrasenaBase).
     * @method:$this->asignarSesion($datos) Se llama el metodo para la asignación de valores de sesion.
     * @return:array retorna un arreglo el cual contiene el status booleano del response y un mensaje descriptivo.
     * Se envia el registro del ingreso a la plataforma al @method:ingreso_salida(@property:$this->sesion,'descripcion').
     *
     */
	private function verificacionDeLogeo($nombreUsuario, $contrasena, $tipo) 
	{
		try 
		{
			$this->DB->table('usuarios');
			$this->DB->where('nombre_usuario','=',$nombreUsuario);
			$this->DB->select('*');
			// Se almacena los datos en $tmp.
			$tmp = $this->DB->get();
			// Se realiza la verificacion
			if($tmp != 1)
			{
				//Se desencoda la variable $tmp;
				$tmp = json_decode($tmp,true);
				$contrasenaBase = $tmp[0]['contrasena'];
				$datos = $tmp[0];
				if (password_verify($this->contrasena,$contrasenaBase)) 
				{
					$sesion = $this->asignarSesion($datos);
					if($sesion == true)
					{
						echo json_encode(array('status' => 1,'respuesta' => 'Se ha registrado correctamente'));
						$this->his->ingreso_salida($this->sesion,'ingreso a la plataforma');
					}
				}
				else
				{
					echo json_encode(array('status' => 0,'error' => 'Email o contraseña incorrectos'));
				}
			}
			else
			{
				echo json_encode(array('status' => 0,'error' => 'Email o contraseña incorrectos'));
			}
		} catch (Exception $e) {
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		} finally {
			$this->DB = null;
		}
	}

	/**
     *
     * @method:asignarSesion(parametro) Se encarga de la asignación de las variables de sesion con los datos del usuario enviados.
     * @param:$datos se almacenan los datos del usuario al momento de ingresar el logeo a la plataforma.
     * @property:$this->sesion almacena la variable de sesion @var:$_SESSION['idUsuario'].
     * @return:booleano.
     *
     */
	private function asignarSesion($datos)
	{
		try 
		{
			// Se inicia sesion con session_start().
			session_name('PQRUSER');
			// Se da un tiempo de vida a la variable de sesion de 28800.
			session_start(['cookie_lifetime' => 28800]);
			$_SESSION['idUsuario'] 		 = $datos['id'];
			$_SESSION['nombreUsuario'] 	 = $datos['nombre_usuario'];
			$_SESSION['primerNombre'] 	 = $datos['primer_nombre'];
			$_SESSION['segundoNombre']   = $datos['segundo_nombre'];
			$_SESSION['primerApellido']  = $datos['primer_apellido'];
			$_SESSION['segundoApellido'] = $datos['segundo_apellido'];
			$_SESSION['emailUsuario'] 	 = $datos['email'];
			$_SESSION['ciudadUsuario']   = $datos['ciudad'];
			$_SESSION['documento'] 		 = $datos['documento'];
			$_SESSION['distritoUsuario'] = $datos['distrito'];
			$_SESSION['areaUsuario'] 	 = $datos['area'];
			$_SESSION['rolUsuario'] 	 = $datos['rol'];
			$_SESSION['statusUsuario']   = $datos['status'];
			$_SESSION['bool'] = true;
			// Se almacena el id del usuario en $this->sesion.
			$this->sesion = $_SESSION['idUsuario'];
			return true;
		} catch (Exception $e) 
		{
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}
}
// Se instancia la clase logeo($_POST).
new logeo($_POST);