<?php
/**
 *
 * @author Cristian Camilo Vasquez
 * Clase ingresos_salidas
 * Se incluyen:
 * El archivo de auto carga de clases.
 * El archivo de clases query para la utilizacion del constructor de querys.
 *
 */
include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/clases/clasesQuery.php';
require $_SERVER['DOCUMENT_ROOT'] . '/autoRecarga.php';
use clases\Vista_clase;

class ingresos_salidas
{
	/**
	 * @property consulta null
	*/
	private $consulta;
	/**
	 * @property vista null
	*/
	private $vista;
 	
 	/**
     *
     * @property:$this->consulta Es un objeto de la clase clasesQuery().
     * @method:index() Se instancia el metodo index.
     *
     */
	public function __construct()
	{
		try 
		{
			$this->consulta = new clasesQuery();
			$this->index();
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
		finally
		{
			$this->vista = NULL;
		}
	}

	/**
     *
     * @method:index() Se encarga de llamar la vista ingresos_salidas_vista cargando los datos del historial de ingresos y salidas.
     * @var:$datos Almacena los datos al consultar el hisotorial de ingresos y salidas.
     * @method:index() ejecuta el llamado de la vista con el metodo @method:Vista_clase::(string vista,array(variables)).
     *
     */
	public function index()
	{
		try 
		{
			$this->consulta->table('ingresos_salidas');
			$this->consulta->join([
				['usuarios','ingresos_salidas.usuario','=','usuarios.id']
			]);
			$this->consulta->orderBy('ingresos_salidas.id','DESC');
			$this->consulta->select('*');
			$datos = $this->consulta->get();
			//En la vista usuarios_vista se usaran las variables enviadas.
			$this->vista = Vista_clase::vista('ingresos_salidas_vista',array(
				"ingresos_salidas" => $datos
			));
		} catch (Exception $e) 
		{
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}
}
// Se instancia la clase ingresos_salidas().
new ingresos_salidas();