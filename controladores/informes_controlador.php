<?php
/**
 *
 * @author Cristian Camilo Vasquez
 * Clase informes
 * Se incluyen:
 * El archivo de clases query para la utilizacion del constructor de querys.
 * El archivo de clases encargada de retornar consultas simples.
 * El archivo de auto carga de clases.
 *
 */
include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/consultas/consultasCrud.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/clases/clasesQuery.php';
require $_SERVER['DOCUMENT_ROOT'] . '/autoRecarga.php';
use clases\Vista_clase;

class informes
{
	/**
	 * @property $conexion null
	*/
	private $conexion;
	/**
	 * @property $consultasCrud null
	*/
	private $consultasCrud;
	/**
	 * @property $DB null
	*/
	private $DB;

	/**
     *
     * Se verifica variable $_POST
     * Si existe el post con la variable @var:$_POST['tipoPqr'] se ejecuta el @method:$this->generarInforme($_POST).
     * De lo contrario se ejecuta el @method:index() por defecto.
     *
     */
	public function __construct()
	{
		try 
		{
			// Se verifica $_POST.
			if(isset($_POST['tipoPqr']))
			{
				$this->generarInforme($_POST);
			}
			else
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
	 * @method:index() Se encarga del llamado de la vista informes_vista enviandole las variables correspondientes.
	 * Se instancia el @method:DBinstancia().
	 * @var:$tipopqr Almacena los datos de los tipos de un pqr.
	 * @var:$distritos Almacena los datos de los distritos.
	 * @var:$areas Almacena los datos de las areas.
	 * @method:index() ejecuta el llamado de la vista con el metodo @method:Vista_clase::(string vista,array(variables)).
	 *
	 */
	public function index()
	{
		try 
		{
			$this->DBinstancia();
			$this->DB->table('tipo_pqr');
			if ($_SESSION['idUsuario'] != 1) 
			{
				$this->DB->where('id','<>',4);
			}
			$this->DB->select('*');
			$tipopqr = $this->DB->get();
			// Se identifican los tipos de pqr, se almacena a $tipopqr.
			$this->DBinstancia();
			$this->DB->table('distritos');
			$this->DB->where('status','=',1);
			$this->DB->select('*');
			$distritos = $this->DB->get();
			// Se identifican los distritos, se almacena a $distritos.
			$this->DBinstancia();
			$this->DB->table('areas');
			$this->DB->where('status','=',1);
			if($_SESSION['idUsuario'] != 1)
			{
				$this->DB->andWhere([
					['id','<>',0]
				]);
			}
			$this->DB->select('*');
			$areas = $this->DB->get();
			// Se identifican las areas, se almacena a $areas.
			// El uso del llamado de la vista se almacena en $res.
			// En la vista usuarios_vista se usaran las variables enviadas.
			$res = Vista_clase::vista('informes_vista',array(
				'tipopqr'   => $tipopqr,
				'distritos' => $distritos,
				'areas'     => $areas
			));	
		} catch (Exception $e) 
		{
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}

	/**
	 *
	 * @method:generarInforme(parametro) Se encarga de generar las consultas dependiendo de las opciones seleccionadas.
	 * @param:$post recibe $_POST enviados por el metodo __construct().
	 * @property:$this->consultasCrud Es objeto de consultasCrud().
	 * Se incian tres variables vacias. @var:$A,@var:$B,@var:$C, dependiendo del campo seleccionado se construye un fragmento de la consulta general.
	 * @var:$desde, @var:$hasta Almacenan los datos de las fechas.
	 * @var:$array Se almacenan todos los datos.
	 * @var:$filtradoInforme Almacena los registros al aplicarse las consultas creadas anteriormente.
	 * @method:$this->vistaResultadoInforme($filtradoInforme). Se ejecuta el metodo y se envia los datos traidos. 
	 *
	 */
	private function generarInforme($post)
	{
		try {
			$this->consultasCrud = new consultasCrud();
			$A = "";
			$B = "";
			$C = "";
			$fecha = "";
			// Filtro de la variable $post.
			if($post['tipoPqr'] != 0){
				$A = " AND pqr_filtrado.id_tipo =".$post['tipoPqr'] . "";
			}
			if($post['distrito'] != 0){
				$B = " AND pqr_filtrado.id_distrito = " . $post['distrito']."";
			}
			if($post['area'] != 0.1){
				$C = " AND pqr_filtrado.id_area = " . $post['area']."";
			}
			if($post['desde'] != 0){
				$tmp1 = $post['desde'];
				$tmp2 = $post['hasta'];
				$desde = "$tmp1";
				$hasta = "$tmp2";
				$fecha = " AND pqr_filtrado.fecha BETWEEN '".$desde."' AND '".$hasta."'";
			}
			// Construccion del array.
			$array = array(
				'A' => $A,
				'B' => $B,
				'C' => $C,
				'fecha'=>$fecha
			);
			$filtradoInforme = $this->consultasCrud->consultarTablas('informes',$array);
			// Se llama el metodo vistaResultadoInforme.
			$this->vistaResultadoInforme($filtradoInforme);
		} catch (Exception $e) {
			echo 'Excepción capturada: ', $e->getMessage(), "\n";
		}
	}

	/**
	 *
	 * @method:vistaResultadoInforme(parametro) Se encarga del retorno de la informacion.
	 * @param:$filtradoInforme Es traido desde el @method:generarInforme().
	 * @return:Se retorna la información encodeada en formato JSON.
	 *
	 */
	private function vistaResultadoInforme($filtradoInforme)
	{
		try {
			if(count($filtradoInforme) > 0 )
			{
				echo(json_encode($filtradoInforme));
			}else{
				echo(json_encode(0));
			}
		} catch (Exception $e) {
			echo 'Excepción capturada: ', $e->getMessage(), "\n";
		}
	}
}
// Se instancia la clase informes().
new informes();