<?php
/**
 *
 * @author Cristian Camilo Vasquez
 * Clase pqrVencimiento
 * Se incluyen:
 * El archivo de conexion a la BD.
 * El archivo de auto carga de clases.
 * El archivo de clases query para la utilizacion del constructor de querys.
 *
 */
include_once $_SERVER['DOCUMENT_ROOT'] . '/modelos/conexion.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/consultas/consultasCrud.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/clases/clasesQuery.php';
require $_SERVER['DOCUMENT_ROOT'] . '/autoRecarga.php';
use clases\Vista_clase;

class pqrVencimiento
{
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
	 * Se verifica si la variable @var:$_POST 
	 * Se filtra la variable y dependiendo del resultado se ejecuta el metodo @method:$this->pqrVencimiento($_POST) ó @method:$this->pqrRespuestaVencimiento($_POST).
	 * Si se encuentra vacia la variable se ejecuta el @method:index() por defecto.
	 *
	 */
	public function __construct()
	{
		try 
		{
			if (!empty($_POST)) {
				$this->pqrVencimientos($_POST);
			}
			else {
				$this->index();
			}
		} catch (Exception $e) {
			echo 'Excepción capturada: ', $e->getMessage(), "\n";
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
	 * @method:index() Se encarga de de retornar la vista pqr_vencimientos_vista con sus respectivos datos.
	 * @var:$datostipopqr Almacena los datos de los tipos de PQR.
	 * @var:$datospqrVencimiento Almacena los datos del tipo pqr vencimiento.
	 * @method:index() ejecuta el llamado de la vista con el metodo @method:Vista_clase::(string vista,array(variables)).
	 *
	 */
	public function index()
	{
		try 
		{
			$this->DBinstancia();
			$this->DB->table('tipo_pqr_vencimiento_area');
			$this->DB->select('*');
			$pqrVencimiento = $this->DB->get();
			$this->DBinstancia();
			$this->DB->table('areas');
			$this->DB->where('id','<>',0);
			$this->DB->andWhere([
				['status','=','1']
			]);
			$this->DB->select('*');
			$areas = $this->DB->get();
			//El uso del llamado de la vista se almacena en $res.
			//En la vista usuarios_vista se usaran las variables enviadas.
			$res = Vista_clase::vista('pqr_vencimientos_vista',array(
				'areas' 		=> $areas,
				'pqrVencimiento'=> $pqrVencimiento
			));	
		} catch (Exception $e) {
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}


	private function pqrVencimientos($post)
	{
		try {
			$this->DBinstancia();
			$this->DB->table('tipo_pqr_vencimiento_area');
			$this->DB->where('id_area','=',$post['idArea']);
			$this->DB->andWhere([
				['tipo','=',$post['tipo']]
			]);
			$resultado = $this->DB->update([
				'dias_vencimiento' 		   => $post['diasVencimiento'],
				'dias_vencimiento_usuario' => $post['cierreAutomatico'],
				'dias_recordatorio'		   => $post['recordatorioPqr']
			]);
			echo json_encode($resultado);
		} catch (Exception $e) {
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}
}
/**
 * Se instancia la clase pqrVencimiento().
 */
$obj = new pqrVencimiento();