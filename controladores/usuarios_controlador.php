<?php
/**
 *
 * @author Cristian Camilo Vasquez
 * Clase usuarios
 * Se incluyen:
 * El archivo de auto carga de clases.
 * El archivo de clases query para la utilizacion del constructor de querys. 
 *
 */
include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/clases/clasesQuery.php';
require $_SERVER['DOCUMENT_ROOT'] . '/autoRecarga.php';
use clases\Vista_clase;

class usuarios 
{
	/**
	 * @property plantilla null
	*/
	private $plantilla;
	/**
	 * @property conexion null
	*/
	private $conexion;
	/**
	 * @property consultas null
	*/
	private $consultas;
	/**
	 * @property querys null
	*/
	private $querys;
	/**
	 * @property DB null
	*/
	private $DB;
	/**
	 * @property usuarios null
	*/
	private $usuarios;
	/**
	 * @property areas null
	*/
	private $areas;
	/**
	 * @property distritos null
	*/
	private $distritos;
	/**
	 * @property roles null
	*/
	private $roles;
	/**
	 * @property idActivo null
	*/
	private $idActivo;
	
	/**
	 *
	 * Se inicia a @property:$this->idActivo con la variable de sesion idUsuario.
	 * Se verifica el campo @var:tipo de la variable global post.
	 * Se evalua para llamar los metodos @method:insertarUsuarios(), @method:edicionUsuario(), @method:eliminarUsuario(), @method:seguridadUsuario().
	 * Se evalua si existe la variable get @var:infousuario.
	 * Al evaluarse infousuario se llama el metodo @method:informacionGeneralUsuario() el cual obtiene la información de los usuarios.
	 * Se evalua si existe la variable get @var:seguridadUsuario.
	 * Al evaluarse seguridadUsuario se llama el metodo de vista de cambio de contraseña y se envia el id del usuario como argumento.
	 * Si no se cumple ninguna de las opciones se llama al @method:index() por defecto.
	 *
	 */
	function __construct() 
	{
		$this->idActivo = $_SESSION['idUsuario'];
		if (!empty($_POST)) 
		{
			switch ($_POST['tipo']) 
			{
				case 'insercion':
					$this->insertarUsuarios($_POST);
					break;
				case 'edicion':
					$this->edicionUsuario($_POST);
					break;
				case 'eliminacion':
					$this->eliminarUsuario($_POST);
					break;
				case 'seguridadUsuario':
					$this->seguridadUsuario($_POST);
					break;
				default:
					# code...
					break;
			}
		} 
		elseif (isset($_GET['infousuario'])) 
		{
			//Se incluye las clases globales de consultas consultasCrud y querys.
			include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/consultas/consultasCrud.php';
			include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/clases/plantilla_clase.php';
			include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/consultas/querys.php';
			$this->informacionGeneralUsuario($_GET['infousuario']);
		}
		elseif (isset($_GET['seguridadUsuario'])) 
		{
			$this->seguridadUsuarioIndex($_GET);
		}
		else
		{
			$this->index();
		}
	}

	/**
	 *
	 * @property:$this->DB se inicia null.
	 * @method:DBinstancia() incia el objeto clasesQuery() en @property:$this->DB.
	 *
	 */
	private function DBinstancia()
	{
		try 
		{
			$this->DB = NULL;
			$this->DB = new clasesQuery();
		} catch (Exception $e) 
		{
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}

	/**
	 *
	 * @var:$_SESSION['idUsuario'].
	 * @method:$this->DBinstancia se inicia.
	 * @property:$this->idActivo se almacena en @var:$idUsuario.
	 * @property:$this->usuarios se obtiene de la consulta a usuarios.
	 * @property:$this->areas se obtiene de la consulta a areas.
	 * @property:$this->distritos se obtiene de la consulta de distritos.
	 * @property:$this->roles se obtiene de la consulta de los roles.
	 * @method:obtencionDedatos() identifica areas, distritos, roles y usuarios
	 * Para la obtención de datos se requiere filtrarlo por el rol del usuario activo, dependiendo del rol la consulta será distinta.
	 * 
	 */
	private function obtencionDedatos()
	{
		try 
		{
			// Se identifican los usuarios, se retorna a $this->usuarios.

			$idUsuario = $this->idActivo;
			$this->DBinstancia();
			if ($_SESSION['idUsuario'] == 1) 
			{
				$this->DB->table('usuarios');
				$this->DB->where('id','<>',$idUsuario);
				$this->DB->andWhere([
					['id','<>',1],
					['id','<>',2],
					['status','=',1]
				]);
				$this->DB->orderBy('id','DESC');
				$this->DB->select('*');
				$this->usuarios = $this->DB->get();
			}
			elseif ($_SESSION['rolUsuario'] == 6) 
			{
				$this->DB->table('usuarios');
				$this->DB->where('id','<>',$idUsuario);
				$this->DB->andWhere([
					['id','<>',1],
					['status','=',1],
					['id','<>',2],
					['rol','=',6]
				]);
				$this->DB->orWhere([
					['rol','=',7],
					['rol','=',8]
				]);
				$this->DB->orderBy('id','DESC');
				$this->DB->select('*');
				$this->usuarios = $this->DB->get();
			}
			else
			{
				$this->DB->table('usuarios');
				$this->DB->where('id','<>',$idUsuario);
				$this->DB->andWhere([
					['id','<>',1],
					['area','<>',0],
					['id','<>',2],
					['status','=',1]
				]);
				$this->DB->orderBy('id','DESC');
				$this->DB->select('*');
				$this->usuarios = $this->DB->get();
			}

			// Se identifican las areas, se retorna a $this->areas.

			$this->DBinstancia();
			if ($idUsuario == 1) 
			{
				$this->DB->table('areas');
				$this->DB->where('status','=',1);
				$this->DB->orderBy('id','DESC');
				$this->DB->select('*');
				$this->areas = $this->DB->get();
			}
			elseif ($_SESSION['rolUsuario'] == 6 || $_SESSION['rolUsuario'] == 8) 
			{
				$this->DB->table('areas');
				$this->DB->where('status','=',1);
				$this->DB->andWhere([
					['id','=',0],
					['status','=',1]
				]);
				$this->DB->orderBy('id','DESC');
				$this->DB->select('*');
				$this->areas = $this->DB->get();
			}
			else
			{
				$this->DB->table('areas');
				$this->DB->where('status','=',1);
				$this->DB->andWhere([
					['id','<>',0],
					['status','=',1]
				]);
				$this->DB->orderBy('id','DESC');
				$this->DB->select('*');
				$this->areas = $this->DB->get();
			}

			// Se identifican los distritos, se retorna a $this->distritos.

			$this->DBinstancia();
			if ($_SESSION['rolUsuario'] == 6 || $_SESSION['rolUsuario'] == 8) 
			{
				$this->DB->table('distritos');
				$this->DB->where('status','=',1);
				$this->DB->andWhere([
					['id','=',0]
				]);
				$this->DB->orderBy('id','DESC');
				$this->DB->select('*');
				$this->distritos = $this->DB->get();
			}
			else
			{
				$this->DB->table('distritos');
				$this->DB->where('status','=',1);
				$this->DB->orderBy('id','DESC');
				$this->DB->select('*');
				$this->distritos = $this->DB->get();
			}

			// Se identifican los roles, se retorna a $this->roles.

			$this->DBinstancia();
			if ($_SESSION['idUsuario'] == 1) 
			{
				$this->DB->table('roles');
				$this->DB->select('*');
				$this->roles = $this->DB->get();
			}
			elseif ($_SESSION['rolUsuario'] == 6 || $_SESSION['rolUsuario'] == 8) 
			{
				$this->DB->table('roles');
				$this->DB->where('id','<>',1);
				$this->DB->andWhere([
					['id','<>',2],
					['id','<>',3],
					['id','<>',4],
					['id','<>',5]
				]);
				$this->DB->select('*');
				$this->roles = $this->DB->get();
			}
			else
			{
				$this->DB->table('roles');
				$this->DB->where('id','<>',6);
				$this->DB->andWhere([
					['id','<>',7],
					['id','<>',8]
				]);
				$this->DB->select('*');
				$this->roles = $this->DB->get();
			}		
		} catch (Exception $e) 
		{
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}

	 /**
	 * 
	 * se declaran las variables:
	 * se llama el metodo @method:obtencionDedatos().
	 * @var:$distritos almacena a @property:$this->distritos.
	 * @var:$areas almacena a @property:$this->areas.
	 * @var:$roles almacena a @property:$this->roles.
	 * @var:$usuarios almacena a @property:$this->usuarios.
	 * @method:index() ejecuta el llamado de la vista con el metodo @method:Vista_clase::(string vista,array(variables)).
	 *
	 */
	public function index()
	{
		try 
		{
			$this->obtencionDedatos();
			$distritos = $this->distritos;
			$areas = $this->areas;
			$roles = $this->roles;
			$usuarios = $this->usuarios;
			//El uso del llamado de la vista se almacena en $res.
			//En la vista usuarios_vista se usaran las variables enviadas.
			$res = Vista_clase::vista('usuarios_vista',array(
				"distritos" => $distritos,
				"areas" 	=> $areas,
				"roles" 	=> $roles,
				"usuarios"  => $usuarios
			));	
		} catch (Exception $e) 
		{
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}

	/**
	 * 
	 * @method:insertarUsuarios() Realiza la inserción de los datos a la tabla usuarios en Base de Datos.
	 * @method:DBinstancia se incia para el uso del @property:$this->DB.
	 * @param:$post recibe $_POST enviados por el metodo __construct().
	 * @return:array retorna un arreglo el cual contiene el status booleano del response y un mensaje descriptivo.
	 *
	 */
	private function insertarUsuarios($post) {
		try {
			$this->DBinstancia();
			// En $contrasena se almacena el encriptado con el uso de password_hash(variable,PASSWORD_DEFAULT) el $post['contrasena'].
			$contrasena = password_hash($post['contrasena'], PASSWORD_DEFAULT);
			$this->DB->table('usuarios');
			$respuesta = $this->DB->insert([
				'nombre_usuario'   => $post['nombredeusuario'],
				'primer_nombre'    => $post['primernombre'],
				'segundo_nombre'   => $post['segundonombre'],
				'primer_apellido'  => $post['primerapellido'],
				'segundo_apellido' => $post['segundoapellido'],
				'email'            => $post['email'],
				'ciudad'           => $post['ciudad'],
				'documento'        => $post['documento'],
				'distrito' 		   => $post['distrito'],
				'area' 			   => $post['area'],
				'rol' 			   => $post['rol'],
				'status' 		   => 1,
				'contrasena'	   => $contrasena
			]);
			// Verificación de la variable $respuesta.
			if($respuesta == true)
			{
				echo json_encode(array('status' => 1,'respuesta' => 'Se ha registrado correctamente'));
			}
			else
			{
				echo json_encode(array('status' => 0,'error' => 'Ha ocurrido un error al registrar el usuario'));
			}
		} catch (Exception $e) 
		{
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		} finally {
			$this->conexion = null;
		}
	}

	/**
	 *
	 * @method:eliminarUsuario() Realiza la eliminación de un usuario de la tabla usuarios recibiendo como parametro el id del usuario.
	 * El usuario no se elimina completamente, se cambia su status y se deshabilida. 
	 * @method:DBinstancia se incia para el uso del @property:$this->DB.
	 * @var:$idUsuario almacena la variable escapada $post['idUsuario'].
	 * @param:$post recibe $_POST enviados por el metodo __construct().
	 * @return:array retorna un arreglo el cual contiene el status booleano del response y un mensaje descriptivo.
	 *
	 */
	private function eliminarUsuario($post) 
	{
		try 
		{
			$this->DBinstancia();
			$idUsuario = htmlentities(addslashes($post['idUsuario']));
			$this->DB->table('usuarios');
			$this->DB->where('id','=',$idUsuario);
			// En la eliminacion de usuario se cambia el status.
			$respuesta = $this->DB->update([
				'status' => 0
			]);
			// Verificación de la variable $respuesta.
			if($respuesta == true)
			{
				echo json_encode(array('status' => 1,'respuesta' => 'Se ha eliminado correctamente'));
			}
			else
			{
				echo json_encode(array('status' => 0,'error' => 'Ha ocurrido un error al eliminar el usuario'));
			}	
		} catch (Exception $e) 
		{
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}

	/**
	 *
	 * @method:informacionGeneralUsuario(parametro) Se encarga de la visualizacion del usuario seleccionado.
	 * @param:$get recibe $_GET['infousuario'] enviados por el metodo __construct().
	 * @var:$idUsuarioSelect almacena la el parametro $get el cual se desencoda con la función base64_decode(variable).
	 * @method:DBinstancia se incia para el uso del @property:$this->DB. 
	 * @var:$datosUsuario almacena la informacion del usuario.
     * @method:obtencionDedatos().
	 * Se ejecuta el llamado de la vista con el metodo @method:Vista_clase::(string vista,array(variables)).
	 *
	 */
	public function informacionGeneralUsuario($get) 
	{
		try 
		{
			$idUsuarioSelect = base64_decode($get);
			$this->DBinstancia();
			$this->DB->table('usuarios');
			$this->DB->join([
				["distritos","usuarios.distrito","=","distritos.id"],
				["areas","usuarios.area","=","areas.id"],
				["roles","usuarios.rol","=","roles.id"]
			]);
			$this->DB->where('usuarios.id','=',$idUsuarioSelect);
			$this->DB->select(
					'usuarios.id,
					usuarios.nombre_usuario,
					usuarios.primer_nombre,
					usuarios.segundo_nombre,
					usuarios.primer_apellido,
					usuarios.segundo_apellido,
					usuarios.email,
					usuarios.ciudad,
					usuarios.documento,
					distritos.nombre as distrito,
					areas.nombre as area,
					roles.tipo'
				);
			// Se obtiene la información del usuario.
			$datosUsuario = $this->DB->get();
			$this->obtencionDedatos();
			$res = Vista_clase::vista('info_individual_usuario_vista',array(
				"infoUsuario" => $datosUsuario,
				"area"       => $this->areas,
				"distritos"   => $this->distritos,
				"roles"       => $this->roles
			));
		} catch (Exception $e) 
		{
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}

	/**
	 *
	 * @method:edicionUsuario(parametro) Se encarga de la edición del usuario seleccionado.
	 * @param:$post recibe $_POST enviados por el metodo __construct().
	 * @method:DBinstancia se incia para el uso del @property:$this->DB.
	 * @var:$idUsuario almacena la variable $post['idUsuario'].
	 * @return:array retorna un arreglo el cual contiene el status booleano del response y un mensaje descriptivo. 
	 *
	 */
	private function edicionUsuario($post) 
	{
		try 
		{
			$this->DBinstancia();
			$idUsuario = htmlentities(addslashes($post['idUsuario']));
			$this->DB->table('usuarios');
			$this->DB->where('id','=',$idUsuario);
			$respuesta = $this->DB->update([
				'nombre_usuario'   => $post['nombreUsuario'],
				'primer_nombre'    => $post['primerNombre'], 
				'segundo_nombre'   => $post['segundoNombre'],
				'primer_apellido'  => $post['primerApellido'], 
				'segundo_apellido' => $post['segundoApellido'],
				'email'            => $post['email'],
				'ciudad'           => $post['ciudad'],
				'documento'        => $post['documento'],
				'distrito'         => $post['distrito'],
				'area'             => $post['area'],
				'rol'              => $post['rol']
			]);
			// Verificación de la variable $respuesta.
			if($respuesta == true)
			{
				echo json_encode(array('status' => 1,'respuesta' => 'Se ha editado correctamente'));
			}
			else
			{
				echo json_encode(array('status' => 0,'error' => 'Ha ocurrido un error al editar el usuario'));
			}	
		} catch (Exception $e) 
		{
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}

	/**
	 *
	 * @method:seguridadUsuarioIndex(parametro) Se encarga de la visualizacion del cambio de contraseña.
	 * @var:$idUsuario almacena la el parametro $get el cual se desencoda con la función base64_decode(variable).
	 * Se ejecuta el llamado de la vista con el metodo @method:Vista_clase::(string vista,array(variable)).
	 *
	 */
	public function seguridadUsuarioIndex($get)
	{
		try
		{
			$idUsuario = base64_decode($get['seguridadUsuario']);
			// Se envia el id del usuario.s
			Vista_clase::vista('seguridad_usuario_vista',array(
				"idUsuario" => $idUsuario
			));
		}
		catch (Exception $e)
		{
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}

	/**
	 *
	 * @method:seguridadUsuario(parametro) Se encarga de la edición de la contraseña.
	 * @param:$post recibe $_POST enviados por el metodo __construct().
	 * @var:$contrasena se almacena el encriptado con el uso de password_hash(variable,PASSWORD_DEFAULT) el $post['contrasena'].
	 * @var:$idUsuario almacena la variable $post['idUsuario'].
	 * @method:DBinstancia se incia para el uso del @property:$this->DB.
	 * @return:array retorna un arreglo el cual contiene el status booleano del response y un mensaje descriptivo.
	 *
	 */
	public function seguridadUsuario($post)
	{
		try 
		{
			$contrasena = password_hash($post['contrasena'], PASSWORD_DEFAULT);
			$idUsuario = $post['idUsuario'];
			$this->DBinstancia();
			$this->DB->table('usuarios');
			$this->DB->where('id','=',$idUsuario);
			$respuesta = $this->DB->update([
				'contrasena' => $contrasena
			]);
			// Verificación de la variable $respuesta.
			if($respuesta == true)
			{
				echo json_encode(array('status' => 1,'respuesta' => '<div class="alert alert-success" role="alert">Se ha editado correctamente!</div>'));
			}
			else
			{
				echo json_encode(array('status' => 0,'error' => '<div class="alert alert-danger" role="alert">Ha ocurrido un error!</div>'));
			}	
		} catch (Exception $e) 
		{
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}

}
/**
 * Se utiliza $_SESSION['bool'] para verificar si existe una sesion iniciada.
*/
if (isset($_SESSION['bool'])) 
{
	/**
	 * Se instancia el objeto usuarios() y  se almacena en @var:$obj.
	*/
	$obj = new usuarios();
} else {
	header('location:../index.php');
}

