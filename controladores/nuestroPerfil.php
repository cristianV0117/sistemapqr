 <?php
/**
 *
 * @author Cristian Camilo Vasquez
 * Clase perfil
 * Se incluyen:
 * El archivo de auto carga de clases.
 * El archivo de clases query para la utilizacion del constructor de querys.
 * El archivo de clases encargada de retornar consultas simples.
 *
 */
include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/clases/clasesQuery.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/consultas/consultasCrud.php';
require $_SERVER['DOCUMENT_ROOT'] . '/autoRecarga.php';
use clases\SesionActualClase;
use clases\vista_clase;

class perfil {

	/**
	 * @property conexion null
	*/
	private $conexion;
	/**
	 * @property plantilla null
	*/
	private $plantilla;
	/**
	 * @property sesion null
	*/
	private $sesion;
	/**
	 * @property infoSesion null
	*/
	private $infoSesion;
	/**
	 * @property DB null
	*/
	private $DB;

	/**
	 *
	 * @property:$this->sesion Almacena el objeto SesionActualClase().
	 * Se verifica la @var:$_GET['usuario'] para saber si existe o no.
	 * Al existir @var:$_GET['usuario'] Se almacena en @property:$this->infoSesion el metodo obtenerArregloSesion().
	 * Se llama el @method:index($_GET['usuario']).
	 * Se verifica si existe @var:$_POST['idUsuario'].
	 * Al existir se instancia la clase clasesQuery() en @property:$this->DB.
	 * Se llama el @method:editarDatosPerfil($_POST).
	 */
	public function __construct() 
	{
		try 
		{
			$this->sesion = new SesionActualClase();
			if (isset($_GET['usuario'])) 
			{
				$this->infoSesion = $this->sesion->obtenerArregloSesion();
				$this->index($_GET['usuario']);
			} elseif ($_POST['idUsuario']) 
			{
				$this->DB = new clasesQuery(); 
				$this->editarDatosPerfil($_POST);
			}
		} catch (Exception $e) 
		{
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
		finally
		{
			$this->sesion = null;
		}
		
	}

	/**
	 *
	 * @method:index() Se encarga de llamar la vista perfil_vista y pasar sus respectivos datos.
	 * @var:$datos Contiene la informcacion alamcenada en @property:$this->infoSesion traida desde __construct().
	 * @method:index() ejecuta el llamado de la vista con el metodo @method:Vista_clase::(string vista,array(variables)).
	 *
	 */
	public function index($get) 
	{
		try 
		{
			$datos = $this->infoSesion;
			//El uso del llamado de la vista se almacena en $res.
			//En la vista usuarios_vista se usaran las variables enviadas.
			$res = Vista_clase::vista('perfil_vista',array(
				"sesion" => $datos
			));	
		} catch (Exception $e) 
		{
			die('Excepción capturada: '.$e->getMessage()."\n");
		}
	}

	/**
	 *
	 * @method:editarDatosPerfil(parametros) Se encarga de la edición de los datos del perfil del usuario activo en plataforma.
	 * @param:$post recibe $_POST enviados por el metodo __construct().
	 * @var:$idUsuario escapa los datos de la variable @var:$post['idUsuario'].
	 * @var:$retorno Almacena el response de la consulta hecha para la edición de los datos.
	 * @return:array retorna un arreglo el cual contiene el status booleano del response y un mensaje descriptivo.
	 *
	 */
	private function editarDatosPerfil($post) 
	{
		try 
		{
			$idUsuario = htmlentities(addslashes($post['idUsuario']));
			// Actualizacion de los datos del perfil del usuario activo.
			$this->DB->table('usuarios');
			$this->DB->where('id','=',$idUsuario);
			$retorno = $this->DB->update([
				'nombre_usuario'   => $post['nombreUsuario'],
				'primer_nombre'    => $post['primerNombre'],
				'segundo_nombre'   => $post['segundoNombre'],
				'primer_apellido'  => $post['primerApellido'],
				'segundo_apellido' => $post['segundoApellido'],
				'email' 		   => $post['emailUsuario'],
				'ciudad' 		   => $post['ciudadUsuario'],
				'documento' 	   => $post['documento']
			]);
			// Verificación de la variable $retorno.
			if($retorno == true)
			{
				if ($respuesta = $this->sesion->nuevoInfoSesion($post)) 
				{
					echo json_encode(array('status' => 1,'respuesta' => 'Se ha editado correctamente'));
				}
			}
			else
			{
				echo json_encode(array('status' => 0,'error' => 'Ha ocurrido un error al actualizar los datos'));
			}
		} catch (Exception $e) 
		{
			die('Excepción capturada: '.$e->getMessage()."\n");
		}
	}
}
/**
 * Se evalua si existe una sesion activa con @var:$_SESSION['bool'].
 * En @var:$obj se instancia la clase perfil().
 */
if (isset($_SESSION['bool'])) 
{
	$obj = new perfil();
} else 
{
	//Al lo existir $_SESSION['bool'] se hace una redireccion al index.
	header('location:../index.php');
}

