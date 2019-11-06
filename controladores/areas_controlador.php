<?php
/**
 *
 * @author Cristian Camilo Vasquez
 * Clase areas
 * Se incluyen:
 * El archivo de auto carga de clases.
 * El archivo de clases query para la utilizacion del constructor de querys.
 *
 */
include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/clases/clasesQuery.php';
require $_SERVER['DOCUMENT_ROOT'] . '/autoRecarga.php';
use clases\Vista_clase;

class areas {

	/**
	 * @property conexion null
	*/
	private $conexion;
	/**
	 * @property nombredearea null
	*/
	private $nombredearea; 
	/**
	 * @property descarea null
	*/
	private $descarea;
	/**
	 * @property DB null
	*/
	private $DB;

	/**
	 *
	 * @property:$this->DB Almacena el objeto clasesQuery().
	 * Se verifica la super global $_POST para saber si esta vacia o no.
	 * Al no estar vacia se filtra la variable @var:$_POST['tipo'].
	 * Dependiendo del tipo se llamará los metodos @method:$this->insertarAreas($_POST) o @method:$this->eliminarArea($_POST).
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
						$this->insertarAreas($_POST);
						break;
					case 'eliminacion':
						$this->eliminarArea($_POST);
						break;
					case 'edicion':
						$this->editarArea($_POST);
						break;
					default:
						# code...
						break;
				}
			} elseif (isset($_GET['infoarea'])) {
				include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/consultas/consultasCrud.php';
				include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/clases/plantilla_clase.php';
				include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/consultas/querys.php';
				$this->informacionGeneralArea( base64_decode($_GET['infoarea']) );
			}
			else {
				$this->index();
			}
		} catch (Exception $e)
		{
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}

	/**
	 *
	 * @method:index() Se encarga de la obtención de datos de la tabla areas y el llamado de la vista areas_vista retornando sus respectivos datos.
	 * Dependiendo de la @var:$_SESSION['idUsuario'] Y/O @var:$_SESSION['rolUsuario'] se ejecutara una consulta con distintos parametros.
	 * Los datos adquiridos se almacenaran en @var:$datos.
	 * @method:index() ejecuta el llamado de la vista con el metodo @method:Vista_clase::(string vista,array(variables)).
	 *
	 */
	public function index()
	{
		try 
		{
			// Las consultan dependeran del rol y del id del usuario activo en la plataforma.
			if ($_SESSION['idUsuario'] == 1) 
			{
				$this->DB->table('areas');
				$this->DB->where('status','=',1);
				$this->DB->orderBy('id','DESC');
				$this->DB->select('*');
				$datos = $this->DB->get();
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
				$datos = $this->DB->get();
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
				$datos = $this->DB->get();
			}
			//El uso del llamado de la vista se almacena en $res.
			//En la vista usuarios_vista se usaran las variables enviadas.
			$res = Vista_clase::vista('areas_vista',array(
				"areas" => $datos
			));	
		} catch (Exception $e) 
		{
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}

	/**
	 *
	 * @method:insertarAreas(parametros) Se encarga de insertar registros de areas en la base de datos.
	 * @param:$post recibe $_POST enviados por el metodo __construct().
	 * @return:array retorna un arreglo el cual contiene el status booleano del response y un mensaje descriptivo.
	 *
	 */
	private function insertarAreas($post) 
	{
		try 
		{
			// Insertar areas.
			$this->DB->table('areas');
			$retorno = $this->DB->insert([
				'nombre' => $post['nombredearea'],'descripcion' => $post['descarea'], 'status' => 1
			]);
			if ($retorno['ejecutado'] === true) {
				$idArea = $retorno['ultimoID'];
				$this->DB->table('tipo_pqr_vencimiento_area');
				$retornoVen = $this->DB->insert([
					'id_area' 		   		   => $idArea,
					'tipo'    		   		   => 'peticion',
					'dias_vencimiento' 		   => 3,
					'dias_vencimiento_usuario' => 3,
					'dias_recordatorio' 	   => 3
				]);
				if ($retornoVen['ejecutado'] === true) {
					$this->DB->table('tipo_pqr_vencimiento_area');
					$retornoVen = $this->DB->insert([
						'id_area' 		   		   => $idArea,
						'tipo'    		   		   => 'queja',
						'dias_vencimiento' 		   => 3,
						'dias_vencimiento_usuario' => 3,
						'dias_recordatorio' 	   => 3
					]);
					if ($retornoVen['ejecutado'] === true) {
						$this->DB->table('tipo_pqr_vencimiento_area');
						$retornoVen = $this->DB->insert([
							'id_area' 		   		   => $idArea,
							'tipo'    		   		   => 'reclamo',
							'dias_vencimiento' 		   => 3,
							'dias_vencimiento_usuario' => 3,
							'dias_recordatorio' 	   => 3
						]);
						if ($retornoVen['ejecutado'] === true) {
							echo json_encode(array('status' => 1,'respuesta' => 'Se ha registrado correctamente'));
						} else {
							echo json_encode(array('status' => 0,'error' => 'Ha ocurrido un error al registrar el area'));
						}
					} else {
						echo json_encode(array('status' => 0,'error' => 'Ha ocurrido un error al registrar el area'));
					}
				} else {

					echo json_encode(array('status' => 0,'error' => 'Ha ocurrido un error al registrar el area'));
				}
			} else {
				echo json_encode(array('status' => 0,'error' => 'Ha ocurrido un error al registrar el area'));
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
	 * @method:vistaEdicionAreas() Se encarga de consultar las areas utilizando el metodo consultarTablas('areas').
	 * @var:$resultados Contiene los registros de las areas actuales.
	 *
	 */
	public function vistaEdicionAreas() 
	{
		try 
		{
			// Se instancia consultasCrud().
			$consulta = new consultasCrud();
			$resultados = $consulta->consultarTablas('areas', null);
			
		} catch (Exception $e) {
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		} finally {
			$this->conexion;
		}
	}

	/**
	 *
	 * @method:eliminarArea(parametros) Se encarga de eliminar las areas de la base de datos.
	 * @param:$post recibe $_POST enviados por el metodo __construct().
	 * NOTA: 
	 * No lo elimina completamente; Edita el status del campo del area para deshabilitarlo.
	 * @return:array retorna un arreglo el cual contiene el status booleano del response y un mensaje descriptivo.
	 *
	 */
	private function eliminarArea($post)
	{
		try 
		{
			$this->DB->table('areas');
			$this->DB->where('id','=',$post['idAreaBorrar']);
			$retorno = $this->DB->update([
				'status' => 0
			]);
			// Se evalua $retorno.
			if($retorno == true)
			{
				echo json_encode(array('status' => 1,'respuesta' => 'Se ha borrado correctamente'));
			}
			else
			{
				echo json_encode(array('status' => 0,'error' => 'Ha ocurrido un error al actualizar los datos'));
			}
		} catch (Exception $e) {
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}finally{
			$this->conexion = NULL;
		}
	}

	private function informacionGeneralArea($id)
	{
		try {
			$this->DB->table('areas');
			$this->DB->where('id','=',$id);
			$this->DB->select('*');
			$retorno = $this->DB->get();
			$res = Vista_clase::vista('editar_area_vista',array(
				"infoArea" => $retorno,
			));
		} catch (Exception $e) {
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}

	private function editarArea($post)
	{
		try {
			$this->DB->table('areas');
			$this->DB->where('id','=',$post['id']);
			$retorno = $this->DB->update([
				'nombre' 	  => $post['nombreArea'],
				'descripcion' => $post['descArea']  
			]);
			if($retorno == true) {
				echo json_encode(array('status' => 1,'respuesta' => 'Se ha editado correctamente'));
			} else {
				echo json_encode(array('status' => 0,'error' => 'Ha ocurrido un error al actualizar los datos'));
			}
		} catch (Exception $e) {
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}

}
// Se instancia la clase areas().
new areas();
