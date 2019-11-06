<?php
/**
 *
 * @author Cristian Camilo Vasquez
 * Clase distritos
 * Se incluyen:
 * El archivo de auto carga de clases.
 * El archivo de clases query para la utilizacion del constructor de querys.
 *
 */
include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/clases/clasesQuery.php';
require $_SERVER['DOCUMENT_ROOT'] . '/autoRecarga.php';
use clases\Vista_clase;

class distritos 
{

	/**
	 * @property conexion null
	*/
	private $conexion;
	/**
	 * @property nombrededistrito null
	*/
	private $nombrededistrito;
	/**
	 * @property descdistrito null
	*/
	private $descdistrito;
	/**
	 * @property DB null
	*/
	private $DB;

	/**
	 *
	 * @property:$this->DB Almacena el objeto clasesQuery().
	 * Se verifica la super global $_POST para saber si esta vacia o no.
	 * Al no estar vacia se filtra la variable @var:$_POST['tipo'].
	 * Dependiendo del tipo se llamará los metodos @method:$this->insertarDistritos($_POST) o @method:$this->eliminarDistrito($_POST).
	 * En caso contrario, si la super global llega vacia se llamara a @method:$this->index() como metodo por defecto.
	 *
	 */
	public function __construct() 
	{
		try
		{
			$this->DB = new clasesQuery();
			// Se verifica si la variable llega vacia.
			if (!empty($_POST)) 
			{
				switch ($_POST['tipo']) 
				{
					case 'insercion':
						$this->insertarDistritos($_POST);
						break;
					case 'eliminacion':
						$this->eliminarDistrito($_POST);
						break;
					case 'edicion':
						$this->editarDistrito($_POST);
						break;
					default:
						# code...
						break;
				}
			} elseif (isset($_GET['infodistri'])) {
				include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/consultas/consultasCrud.php';
				include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/clases/plantilla_clase.php';
				include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/consultas/querys.php';
				$this->informacionGeneralDistrito( base64_decode($_GET['infodistri']) );
			} else
			{
				$this->index();
			}
		} catch (Exception $e)
		{
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}

	/**
	 *
	 * @method:index() Se encarga de la obtención de datos de la tabla distritos y el llamado de la vista distritos_vista retornando sus respectivos datos.
	 * Dependiendo de la @var:$_SESSION['rolUsuario'] se ejecutara una consulta con distintos parametros.
	 * Los datos adquiridos se almacenaran en @var:$datos.
	 * @method:index() ejecuta el llamado de la vista con el metodo @method:Vista_clase::(string vista,array(variables)).
	 *
	 */
	public function index()
	{
		try 
		{
			// Las consultan dependeran del rol del usuario activo en la plataforma.
			if ($_SESSION['rolUsuario'] == 6 || $_SESSION['rolUsuario'] == 8) 
			{
				$this->DB->table('distritos');
				$this->DB->where('status','=',1);
				$this->DB->andWhere([
					['id','=',0]
				]);
				$this->DB->orderBy('id','DESC');
				$this->DB->select('*');
				$datos = $this->DB->get();
			}
			else
			{
				$this->DB->table('distritos');
				$this->DB->where('status','=',1);
				$this->DB->orderBy('id','DESC');
				$this->DB->select('*');
				$datos = $this->DB->get();
			}
			//El uso del llamado de la vista se almacena en $res.
			//En la vista usuarios_vista se usaran las variables enviadas.
			$res = Vista_clase::vista('distritos_vista',array(
				"distritos" => $datos
			));	
		} catch (Exception $e) 
		{
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}

	/**
	 *
	 * @method:insertarDistritos(parametros) Se encarga de insertar registros de distritos en la base de datos.
	 * @param:$post recibe $_POST enviados por el metodo __construct().
	 * @return:array retorna un arreglo el cual contiene el status booleano del response y un mensaje descriptivo.
	 *
	 */
	private function insertarDistritos($post) 
	{
		try 
		{
			// Insertar distritos.
			$this->DB->table('distritos');
			$retorno = $this->DB->insert([
				'nombre' => $post['nombrededistrito'],'descripcion' => $post['descdistrito'], 'status' => 1
			]);
			// Se evalua la variable $retorno.
			if($retorno == true)
			{
				echo json_encode(array('status' => 1,'respuesta' => 'Se ha registrado correctamente'));
			}
			else
			{
				echo json_encode(array('status' => 0,'error' => 'Ha ocurrido un error al registrar el distrito'));
			}	
		} catch (Exception $e) 
		{
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		} finally 
		{
			$this->conexion = null;
		}
	}

	/**
	 *
	 * @method:eliminarDistrito(parametros) Se encarga de eliminar los distritos de la base de datos.
	 * @param:$post recibe $_POST enviados por el metodo __construct().
	 * NOTA: 
	 * No lo elimina completamente; Edita el status del campo del distrito para deshabilitarlo.
	 * @return:array retorna un arreglo el cual contiene el status booleano del response y un mensaje descriptivo.
	 *
	 */
	private function eliminarDistrito($post)
	{
		try 
		{
			$this->DB->table('distritos');
			$this->DB->where('id','=',$post['idDistritoBorrar']);
			$retorno = $this->DB->update([
				'status' => 0
			]);
			// Se evalua $retorno.
			if($retorno == true)
			{
				
				echo json_encode(array('status' => 1,'respuesta' => 'Se ha editado correctamente'));
			}
			else
			{
				echo json_encode(array('status' => 0,'error' => 'Ha ocurrido un error al actualizar los datos'));
			}
		} catch (Exception $e) {
			echo 'Excepción capturada: ', $e->getMessage(), "\n";
		}finally{
			$this->conexion = null;
		}
	}

	private function informacionGeneralDistrito($id)
	{
		try {
			$this->DB->table('distritos');
			$this->DB->where('id','=',$id);
			$this->DB->select('*');
			$retorno = $this->DB->get();
			$res = Vista_clase::vista('editar_distrito_vista',array(
				"infoDistrito" => $retorno,
			));
		} catch (Exception $e) {
			echo 'Excepción capturada: ', $e->getMessage(), "\n";
		}
	}

	private function editarDistrito($post)
	{
		try {
			$this->DB->table('distritos');
			$this->DB->where('id','=',$post['id']);
			$retorno = $this->DB->update([
				'nombre' 	  => $post['nombreDistrito'],
				'descripcion' => $post['descDistrito']  
			]);
			if($retorno == true) {
				echo json_encode(array('status' => 1,'respuesta' => 'Se ha editado correctamente'));
			} else {
				echo json_encode(array('status' => 0,'error' => 'Ha ocurrido un error al actualizar los datos'));
			}
		} catch (Exception $e) {
			echo 'Excepción capturada: ', $e->getMessage(), "\n";
		}
	}
}
// Se instancia la clase distritos().
new distritos();