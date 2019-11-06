<?php
/**
 *
 * @author Cristian Camilo Vasquez
 * Clase pqr
 * Se incluyen:
 * El archivo de conexion a la BD.
 * El archivo de clases encargada de retornar consultas simples.
 * El archivo de clases query para la utilizacion del constructor de querys.
 * El archivo de carga de archivos JS.
 * El archivo de envio de mails.
 *
 */
include_once $_SERVER['DOCUMENT_ROOT'] . '/modelos/conexion.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/consultas/consultasCrud.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/consultas/querys.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/clases/plantilla_clase.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/clases/carga_archivos_clase.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/clases/envio_mails.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/clases/clasesQuery.php';
/**
 * Se verifica si existe una sesion activa.
 */
if (!isset($_SESSION)) {
	session_name('PQRUSER');
	session_start();
}

class pqr 
{
	/**
	 * @property hash string
	*/
	private $hash = "pqraplicacion";
	/**
	 * @property conexion null
	*/
	private $conexion;
	/**
	 * @property plantilla null
	*/
	private $plantilla;
	/**
	 * @property querys null
	*/
	private $querys;
	/**
	 * @property DB null
	*/
	private $DB;

	/**
	 *
	 * Se verifica si esta vacio la variable @var:$_POST['tipo']
	 * Al no estar vacia se evalua y dependiendo del resultado se ejecutan @method:$this->insertarPqr($_POST), @method:$this->filtrarDatosPqr($_POST), @method:$this->pqrcerrados($_POST), $this->edicionPqrDatos($_POST).
	 * Se evalua si existe la variable @var:$_GET y dependiendo del resultado se ejecutan @method:$this->filtrarPqr(),$this->pqrcerradodetalles(),$this->pqrEditarVista(), $this->pqrprocesodetalles().
	 *
	 */
	public function __construct() 
	{
		// Se evalua la variable $_POST.
		if (!empty($_POST['tipo'])) 
		{
			switch ($_POST['tipo']) 
			{
				case 'insercion':
					$this->insertarPqr($_POST, $_FILES);
				break;
				case 'filtrado':
					$this->filtrarDatosPqr($_POST);
				break;
				case 'consulta':
					$this->pqrcerrados($_POST);
				break;
				case 'edicion':
					$this->edicionPqrDatos($_POST);
				break;
				default:
				break;
			}
		} elseif (isset($_GET['pqr'])) 
		{
			$pqrindividual = base64_decode($_GET['pqr']);
			$this->filtrarPqr($pqrindividual);
		} elseif (isset($_GET['pqrcerrado'])) 
		{
			$pqrindividual = base64_decode($_GET['pqrcerrado']);
			$this->pqrcerradodetalles($pqrindividual);
		}elseif (isset($_GET['pqrEdit'])) 
		{
			$pqr = base64_decode($_GET['pqrEdit']);
			$this->pqrEditarVista($pqr);
		}elseif (isset($_GET['pqrproceso'])) 
		{
			$pqrindividual = base64_decode($_GET['pqrproceso']);
			$this->pqrprocesodetalles($pqrindividual);
		}

	}

	/**
 	 * @method:verificarHash(parametro) Se encarga de verificar 
 	 * @param:$argumento string.
 	 * @var:$llave almacena $argumento desencodeado.
 	 */
	private function verificarHash($argumento) 
	{
		try
		{
			$llave = base64_decode($argumento);
			// Se verifica el hash con un password_verify.
			if (password_verify($this->hash, $llave)) {
				return 1;
			} else {
				return 0;
			}
		} catch (Exception $e) 
		{
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}

	/**
 	 * @method:generadorCodigo() Se encarga de generar el codigo PQR. 
 	 * @return:$random
 	 */
	private function generadorCodigo()
	{
		try
		{
			$rand = mt_rand(100000, 999999);
			$length = 10;
			$caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$longitudCaracteres = strlen($caracteres);
			$randomCaracteres = '';
			for ($i = 0; $i < $length; $i++) {
				$randomCaracteres .= $caracteres[rand(0, $longitudCaracteres - 1)];
			}
			$randomCaracteresfinal = substr($randomCaracteres, 3, 3);
			$random = $randomCaracteresfinal . $rand;
			return $random;
		} catch (Exception $e) 
		{
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}

	/**
 	 * @method:mensaje(parametros) Se encarga de crear el mensaje para el envio de mails. 
 	 * @var:$nombrecompleto string.
 	 * @var:$datos_seleccionar string.
 	 * @return mensaje construido.
 	 */
	private function mensaje($datos_seleccionar,$nombrecompleto)
	{
		try 
		{ 
			// Se retorna el mensaje.
			return 
			'
			<div>
				<center>
					<img height="80" src="https://www.ropsohn.com.co/images/IDENTIFICADOR_ROPSON-04.jpg" style="border:0" class="CToWUd">
					<br>
					<br>
					<hr style="border-top:solid 1px #dbdbdb;width:300px;" >
					<p style="width:300px;padding:0;margin:0;text-align:center;color:#262626;font-size:18px;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif" >
						PQR registrado
					</p>
					<p style="width:300px;padding:0;margin:0;text-align:center;color:#999999;font-size:14px;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif">
						Hola, '.$nombrecompleto.'. Tu PQR fue registrado exitosamente y el codigo es 
					</p>
					<br>
					<br>
					<span class="m_-2429985624185834423m_-923009222503390390btn-content" style="background-color:#2c9ce9;border-radius:4px;display:inline-block">
						<a href="#" style="color:#fff;display:inline-block;font-family:Roboto,Helvetica,Arial,sans-serif;font-size:16px;line-height:1.4;text-align:center;text-decoration:none;min-width:115px;padding:8px 16px;font-weight:500" target="_blank">
						<span style="color:#fff">'.$datos_seleccionar.'</span>
						</a>
					</span>
					<br>
					<br>
					<hr style="width:310px;border-top:solid 1px #dbdbdb;" >
					<p style="width:300px;padding:0;margin:0;text-align:center;color:#262626;font-size:16px;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif" >
						Para futuras consultas Ingrese aquí
						<a style="text-decoration: none" href="https://'.$_SERVER['HTTP_HOST'].'" > https://'.$_SERVER['HTTP_HOST'].' </a>
					</p>
					<p style="width:300px;padding:0;margin:0;text-align:center;color:#999999;font-size:14px;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif" >
						<br>
						<br>
						<strong>© Desarrollo - ZK SAS.</strong>
					</p>
				</center>
			</div>
			';
		} catch (Exception $e) 
		{
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}

	/**
 	 * @method:insertarPqr(parametros) Se encarga de insertar PQR a la BD. 
 	 * @param:$post array.
 	 * @param:$files array.
 	 * @property:$this->DB Almacena la clase clasesQuery().
 	 * @var:$resultado_hash Almacena la respuesta del metodo @method:$this->verificarHash($post['hash']). 
 	 * @var:$random Almacena el codigo PQR generado por el metodo @method:$this->verificarHash($post['hash']).
 	 */
	private function insertarPqr($post, $files) 
	{
		try 
		{
			$this->DB = new clasesQuery();
			$resultado_hash = $this->verificarHash($post['hash']);
			if($resultado_hash == 1)
			{
				$fecha_subida = date('d/m/Y');
				$random = $this->generadorCodigo();
				// Insercion del pqr.
				$this->DB->table('pqr');
				// Se verifica si la variable $files esta vacia.
				if (empty($files['imagen']['name'])) 
				{
					if ($post['campoFarmaco'] == 0) 
					{
						$archivo = 0;
						$campoFarmaco = 0;
					}
					else
					{
						$archivo = 0;
						$campoFarmaco = 1;
					}
				}
				else
				{
					$nombre 	= $_FILES['imagen']["name"];
					$tipo 	    = $_FILES['imagen']["type"];
					$ruta 	    = $_FILES['imagen']["tmp_name"];
					$tamano     = $_FILES['imagen']["size"];
					$nueva_ruta = $_SERVER["DOCUMENT_ROOT"] . "/archivos/pqr/" . $nombre;
					$var = "/archivos/pqr/" . $nombre;
					if (move_uploaded_file($ruta, $nueva_ruta)) 
					{
						if ($post['campoFarmaco'] == 0) 
						{
							$archivo = $var;
							$campoFarmaco = 0;
						}
						else
						{
							$archivo = $var;
							$campoFarmaco = 1;
						}
					}
				}
				if (isset($post['idUsuario'])) 
				{
					$idUsuario = htmlentities(addslashes($post['idUsuario']));
				} else 
				{
					$idUsuario = 0;
				}
				if(!isset($post['documento']))
				{
					$post['documento'] = 0;
				}
				$respuesta = $this->DB->insert([
					'identidicacion' => $random,
					'nombre'		 => $post['nombrecompleto'],
					'nit'			 => $post['nitrut'],
					'codigonit' 	 => $post['codigo'],
					'documento'		 => $post['documento'],
					'email'			 => $post['email'],
					'ciudad'		 => $post['ciudad'],
					'telefono'		 => $post['telefono'],
					'celular'  		 => $post['celular'],
					'mensaje'		 => $post['mensaje'],
					'imgRuta'		 => '',
					'fecha_subida'   => $fecha_subida,
					'status'		 => 1,
					'estado'		 => 'abierto',
					'archivo'		 => $archivo,
					'habeasdata'	 => 1,
					'fmv'			 => $campoFarmaco,
					'id_usuario'	 => $idUsuario
				]);
				if ($post['campoFarmaco'] == 0) 
				{
					if ($respuesta == true) 
					{
						$this->DB->table('pqr');
						$this->DB->where('identidicacion','=',$random);
						$this->DB->select('identidicacion');
						$dato = $this->DB->get();
						$tmp = json_decode($dato,true);
						$iden = $tmp[0]['identidicacion'];
						$mensaje = $this->mensaje($iden,$post['nombrecompleto']);
						$mail = $this->envioDeMail($post['email'],$mensaje,$iden);
					}
				}
				else
				{
					if ($respuesta == true) 
					{
						$this->DB->table('pqr');
						$this->DB->where('identidicacion','=',$random);
						$this->DB->select('id,identidicacion');
						$dato = $this->DB->get();
						$tmp = json_decode($dato,true);
						$iden = $tmp[0]['identidicacion'];
						$iddepqr = $tmp[0]['id'];
						$iddedistrito = 0;
						$iddearea = 0;
						$iddetipo = 4;
						$idusuario = 2;
						$this->DB->table('pqr_filtrado');
						$respuestaFiltrado = $this->DB->insert([
							'id_pqr'	  => $iddepqr,
							'id_distrito' => $iddedistrito,
							'id_area'	  => $iddearea,
							'id_tipo'	  => $iddetipo,
							'id_usuario'  => $idusuario,
							'fecha'		  => 'CURRENT_TIMESTAMP',
							'grupo'		  => 1,
							'status'	  => 1
						]);
						if ($respuestaFiltrado == true) 
						{
							$mensaje = $this->mensaje($iden,$post['nombrecompleto']);
							$mail = $this->envioDeMail($post['email'],$mensaje,$iden);
						}
					}
				}

			}
			else
			{
				return false;
			}
		} catch (Exception $e) 
		{
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}

	/**
 	 * @method:envioDeMail(parametros) Se encarga de insertar PQR a la BD. 
 	 * @var:$mail instancia la clase mail(argumentos).
 	 * @return $mail.
 	 */
	private function envioDeMail($email,$mensaje,$datos_seleccionar)
	{
		try
		{
			$mail = new mail($email,$mensaje,'codigo de PQR',$datos_seleccionar);
			return $mail;
		} catch (Exception $e) 
		{
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}

	/**
 	 * @method:consultarPqr(parametros) Se encarga de filtrar la variable @param:$datos. 
 	 * @param:$datos string.
 	 * @var:$consulta Almacena la clase consultasCrud().
 	 * Se ejecutan los metodos @method:consultarTablas() y @method:consultarTablas().
 	 */
	public function consultarPqr($datos) {
		try {
			$consulta = new consultasCrud();
			// Se verifica la variable $datos.
			switch ($datos) {
			case 'abiertos':
				$resultado = $consulta->consultarTablas('pqr_abiertos', null);
				return $resultado;
				break;
			case 'filtrados':
				$resultado = $consulta->consultarTablas('pqr_filtrados', null);
				return $resultado;
				break;
			default:
				# code...
				break;
			}
		} catch (Exception $e) {
			echo 'Excepción capturada: ', $e->getMessage(), "\n";
		}
	}

	private function filtrarPqr($datos) 
	{
		try 
		{
			$this->conexion = new conexion();
			$this->querys = new Querys();
			$this->plantilla = new plantilla();
			$consulta = new consultasCrud();
			$this->plantilla->cabezera();
			$this->plantilla->cuerpo();
			$this->plantilla->menuNavegacion();
			$resultadoLocal = $this->querys->consultarQuery('pqr', $datos);
			$distritos = $consulta->consultarTablas('distritos', null);
			$areas = $consulta->consultarTablas('areas', null);
			$tipoPqr = $consulta->consultarTablas('tipopqr', null);
			echo '
				<div id="formulariodepqrform"></div>
				<div class="container col-md-11 mt-5" >
					<nav aria-label="breadcrumb">
					  <ol class="breadcrumb">
					    <li class="breadcrumb-item"><a href="/vistas/panel_control_vista">Inicio</a></li>
					    <li class="breadcrumb-item active" aria-current="page">Filtrar PQR</li>
					  </ol>
					</nav>
					<div class="card" >
						<div class="card-header">
				    		<center><h4>Filtrar PQR</h4></center>
				  		</div>
				  		<div class="card-body">
							<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
							  <li class="nav-item">
							    <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Datos del solicitante</a>
							  </li>
							  <li class="nav-item">
							    <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">PQR</a>
							  </li>
							</ul>
							<div class="tab-content" id="pills-tabContent">
							  <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
							    <br>
							  	<div class="row" >
							  		<div class="col-md-1" >
										<label class="ml-3" ><strong>Nombre</strong></label>
									</div>
							  		<div class="col-md-5" >
							  			<input value="' . $resultadoLocal[0][2] . '" class="form-control" readonly>
							  		</div>';
							  		if ($resultadoLocal[0][3] == 0 && $resultadoLocal[0][4] == 0) {
							  			echo '
							  				<div class="col-md-1" >
												<label class="ml-1" ><strong>Documento</strong></label>
											</div>
											<div class="col-md-5" >
							  					<input value="' . $resultadoLocal[0][5] . '" class="form-control" readonly>
							  				</div>
							  			';
							  		}else{
							  			echo'
							  				<div class="col-md-1" >
												<label class="ml-1" ><strong>NIT</strong></label>
											</div>
											<div class="col-md-5" >
							  					<input value="' . $resultadoLocal[0][3] . '" class="form-control" readonly>
							  				</div>
							  			';
							  		}
							  echo'</div>
							  	<br>
							  	<div class="row" >
							  		<div class="col-md-1" >
										<label class="ml-3" ><strong>Email</strong></label>
									</div>
									<div class="col-md-5" >
							  			<input value="' . $resultadoLocal[0][6] . '" class="form-control" readonly>
							  		</div>
							  		<div class="col-md-1" >
										<label class="ml-3" ><strong>Ciudad</strong></label>
									</div>
									<div class="col-md-5" >
							  			<input value="' . $resultadoLocal[0][7] . '" class="form-control" readonly>
							  		</div>
							  	</div>
							  	<br>
							  	<div class="row" >
							  		<div class="col-md-1" >
										<label class="ml-3" ><strong>Telefono</strong></label>
									</div>
									<div class="col-md-5" >
							  			<input value="' . $resultadoLocal[0][8] . '" class="form-control" readonly>
							  		</div>
							  		<div class="col-md-1" >
										<label class="ml-3" ><strong>Celular</strong></label>
									</div>
									<div class="col-md-5" >
							  			<input value="' . $resultadoLocal[0][9] . '" class="form-control" readonly>
							  		</div>
							  	</div>
							  </div>
							  <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">';
							  	if ($resultadoLocal[0][18] != 0) {
							  		$idUsuarioP = $resultadoLocal[0][18];
							  		$sql_usuario = "SELECT nombre_usuario FROM usuarios WHERE id = :idUsuario";
							  		$retorno_usuario = $this->conexion->consulQuery($sql_usuario);
							  		$retorno_usuario['resultado']->bindParam(':idUsuario',$idUsuarioP,PDO::PARAM_INT);
							  		if ($retorno_usuario['resultado']->execute()) {
							  			$resultadoU = $retorno_usuario['resultado']->fetchAll();
							  			echo '
										    <div>
										    	<div class="row">
													<div class="col-md-6" >
														<label class="ml-3" ><strong>Usuario creador del PQR</strong></label>
														<input class="form-control col-md-5 ml-2"  value="' . $resultadoU[0]['nombre_usuario'] . '" readonly>

													</div>
													<br>
												</div>
											</div>
											<hr>
										';
							  		}
							  	}
							echo'
								<div class="row justify-content-md-center" >
									<div class="col-md-3" >
										<label class="ml-3" ><strong>Numero de PQR</strong></label>
										<input id="numeroPqr" value="' . $resultadoLocal[0][1] . '" class="form-control ml-2" readonly>
									</div>
									<div class="col-md-3" >
										<label class="ml-3" ><strong>Fecha</strong></label>
										<input value="' . $resultadoLocal[0][12] . '" class="form-control" readonly>
									</div>
									<div class="col-md-1" >
										<label class="ml-4" ><strong>Estado</strong></label><br>
										<span class="col-md-12 badge badge-danger"><h6>' . $resultadoLocal[0][14] . '</h6></span>
									</div>
								</div>
								<hr>
								<div>
									<label ><strong><h3>Mensaje</h3></strong></label>
									<br>
									<label class="ml-2" >' . $resultadoLocal[0][10] . '</label>
								</div>
								<hr>';
								if (!empty($resultadoLocal[0][15])) {
									echo '
											<label class="ml-2" ><strong>Documento</strong></label>
											<i class="ml-1 fas fa-paperclip"></i>
											<a href="' . $resultadoLocal[0][15] . '" target="_blank">Descargar ' . substr($resultadoLocal[0][15], 14, 200) . '</a>
										';
								}
								if ($_SESSION['rolUsuario'] == 3 || $_SESSION['rolUsuario'] == 1) {
									echo '
										<hr>
										<label><strong><h3>Filtrar PQR</h3></strong></label>
										<br>
										<form id="formulariodefiltrado" >
											<input id="iddepqr" type="hidden" value="' . $datos . '" >
											<div class="row" >
												<div class="col-md-4" >
													<label><strong>Distritos</strong></label>
													<select id="iddedistrito" class="form-control" >';
													for ($i = 0; $i < count($distritos); $i++) {
														echo '
														<option value="' . $distritos[$i][0] . '" >' . $distritos[$i][1] . '</option>
														';
													}
											echo'	</select>
												</div>
											  	<div class="col-md-4" >
											  		<label><strong>Area</strong></label>
											  		<select id="iddearea" class="form-control" >';
											  		for ($i = 0; $i < count($areas); $i++) {
														echo '
														<option value="' . $areas[$i][0] . '" >' . $areas[$i][1] . '</option>
														';
													}
											  	echo'</select>
											  	</div>
											  	<div class="col-md-4" >
											  		<label><strong>Tipo de PQR</strong></label>
											  		<select id="iddetipo" class="form-control" >';
											  			for ($i = 0; $i < count($tipoPqr); $i++) {
															echo '
															<option value="' . $tipoPqr[$i][0] . '" >' . $tipoPqr[$i][1] . '</option>
															';
														}
											  	echo'</select>
											  	</div>
											</div>
											<hr>
											<button id="copiaoculta" type="button" class="btn btn-outline-primary" >Enviar copia oculta</button>
											<div id="occ" class="col-md-12" >
												<hr>
												<strong>OCC</strong>
												<div class="alert alert-success" role="alert">
													<strong>Agrega los emails</strong>
												</div>
												<div id="textoemails" ></div>
												<div id="error" >
												</div>
												<div class="row mt-2" >
													<div class="col-md-4" >
														<input placeholder="Correo electronico" id="emailocc" pattern="[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{1,63}$" class="form-control" type="email" />
													</div>
													<div class="col-md-2" >
														<button id="buttonemailocc" type="button" class="btn btn-outline-primary" >Agregar</button>
														<button id="buttoncancelocc" type="button" class="btn btn-outline-danger" >Cancelar</button>
													</div>
												</div>
											</div>
											<br>
											<button type="submit" class="mt-4 btn btn-outline-success btn-block" >Filtrar</button>
										</form>
									';
								}
					     echo'</div>
							</div>
						</div>
					</div>
				</div>
			';
			$this->plantilla->pie();
			$objcarga = new carga(21);
			$objcarga = new carga(8);
		} catch (Exception $e) {
			echo 'Excepción capturada: ', $e->getMessage(), "\n";
		}
	}

	private function filtrarDatosPqr($post) {
		try {
			$this->conexion = new conexion();
			$this->querys = new Querys();
			$idusuario = $_SESSION['idUsuario'];
			$iddepqr = htmlentities(addslashes($post['iddepqr']));
			$iddedistrito = htmlentities(addslashes($post['iddedistrito']));
			$iddearea = htmlentities(addslashes($post['iddearea']));
			$iddetipo = htmlentities(addslashes($post['iddetipo']));
			$sql_filtrado = "INSERT INTO pqr_filtrado (id_pqr,id_distrito,id_area,id_tipo,id_usuario,fecha,grupo,status) VALUES (:iddepqr,:iddedistrito,:iddearea,:iddetipo,:idusuario,CURRENT_TIMESTAMP,1,1)";
			$retorno = $this->conexion->consulQuery($sql_filtrado);
			$retorno['resultado']->bindParam(':iddepqr', $iddepqr, PDO::PARAM_INT);
			$retorno['resultado']->bindParam(':iddedistrito', $iddedistrito, PDO::PARAM_INT);
			$retorno['resultado']->bindParam(':iddearea', $iddearea, PDO::PARAM_INT);
			$retorno['resultado']->bindParam(':iddetipo', $iddetipo, PDO::PARAM_INT);
			$retorno['resultado']->bindParam(':idusuario', $idusuario, PDO::PARAM_INT);
			if ($retorno['resultado']->execute()) {
				$estado = 'proceso';
				$sql_cambiar_estado = "UPDATE pqr SET estado = :estado WHERE id = :id";
				$retorno_estado = $this->conexion->consulQuery($sql_cambiar_estado);
				$retorno_estado['resultado']->bindParam(':estado', $estado, PDO::PARAM_STR);
				$retorno_estado['resultado']->bindParam(':id', $iddepqr, PDO::PARAM_INT);
				if ($retorno_estado['resultado']->execute()) {
					$resultados = $this->querys->consultarQuery('usuarios_especificos',$iddearea);
					for ($i=0; $i < count($resultados); $i++) {
						$mensaje = '
							<div>
								<center>
									<img height="80" src="https://www.ropsohn.com.co/images/IDENTIFICADOR_ROPSON-04.jpg" style="border:0" class="CToWUd">
									<br>
									<br>
									<hr style="border-top:solid 1px #dbdbdb;width:300px;" >
									<p style="width:300px;padding:0;margin:0;text-align:center;color:#262626;font-size:18px;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif" >
										Nuevo PQR
									</p>
									<p style="width:300px;padding:0;margin:0;text-align:center;color:#999999;font-size:14px;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif">
										Hola, '.$resultados[$i]['primer_nombre'].'. Tienes un nuevo PQR para solucionar
									</p>
									<br>
									<br>
									<span class="m_-2429985624185834423m_-923009222503390390btn-content" style="background-color:#2c9ce9;border-radius:4px;display:inline-block">
									    <a href="#" style="color:#fff;display:inline-block;font-family:Roboto,Helvetica,Arial,sans-serif;font-size:16px;line-height:1.4;text-align:center;text-decoration:none;min-width:115px;padding:8px 16px;font-weight:500" target="_blank">
									   	<span style="color:#fff">'.$post['numeroPqr'].'</span></a></span>
									<br>
									<br>
									<hr style="width:310px;border-top:solid 1px #dbdbdb;" >
									<p style="width:300px;padding:0;margin:0;text-align:center;color:#262626;font-size:16px;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif" >
									   	Ingresa en:
									    <a style="text-decoration: none" href="https://'.$_SERVER['HTTP_HOST'].'" > Sistema PQR</a>
									</p>
									<p style="width:300px;padding:0;margin:0;text-align:center;color:#999999;font-size:14px;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif" >
									    <br>
									    <br>
									   	<strong>© Desarrollo - ZK SAS.</strong>
									</p>
								</center>
							</div>
						';
						$mail = new mail($resultados[$i]['email'],$mensaje,'notificacion PQR',null,$post['emailsocc']);
					}
				} else {
					print_r($retorno_estado->errorInfo());
				}
			} else {
				print_r($retorno->errorInfo());
			}
		} catch (Exception $e) {
			echo 'Excepción capturada: ', $e->getMessage(), "\n";
		} finally {
			$this->conexion = null;
		}
	}

	private function pqrcerrados($post) {
		try {
			$this->querys = new Querys();
			$cerrados = $this->querys->consultarQuery('pqr_cerrado', $post['codigo']);
			echo json_encode($cerrados);
		} catch (Exception $e) {
			echo 'Excepción capturada: ', $e->getMessage(), "\n";
		}
	}

	public function pqrcerradosvista() {
		try {
			$consulta = new consultasCrud();
			$respuesta = $consulta->consultarTablas('pqr_cerrados', null);
			echo '
					<div id="formulariodepqrform"></div>
					<div id="info"  class="col-md-12" >
						<div class="card table-responsive">
						  <div class="card-header">
						    <center><strong><h4>PQR Cerrados</h4></strong></center>
						  </div>
						  <div class="card-body">
						  		<table id="tablapqr" class="table table-borderless">
								  <thead class="thead-dark" >
								    <tr>
								      <th scope="col">ID</th>
								      <th scope="col">Numero registro</th>
								      <th scope="col">Solicitante</th>
								      <th scope="col">Estado</th>
								      <th scope="col">Detalle</th>
									</tr>
								  </thead>
								  <tbody>';
									for ($i = 0; $i < count($respuesta); $i++) {
										echo '<tr>';
										echo '<td width="5%" >' . $respuesta[$i]['id'] . '</td>';
										echo '<td>' . $respuesta[$i]['identidicacion'] . '</td>';
										echo '<td>' . $respuesta[$i]['nombre'] . '</td>';
										echo '<td><span class="badge badge-success">' . $respuesta[$i][14] . '</span></td>';
										echo '<td><a href="../controladores/pqr_controlador?pqrcerrado=' . base64_encode($respuesta[$i]['id']) . '"><i class="fa fa-eye fa-lg" ></i></a></td>';
										echo '</tr>';
									}
							echo '</tbody>
								</table>
							</div>
						</div>
					</div>
				';
		} catch (Exception $e) {
			echo 'Excepción capturada: ', $e->getMessage(), "\n";
		}
	}

	private function pqrcerradodetalles($datos) {
		try {
			$this->querys = new Querys();
			$this->plantilla = new plantilla();
			$this->plantilla->cabezera();
			$this->plantilla->cuerpo();
			$this->plantilla->menuNavegacion();
			$solucion = $this->querys->consultarQuery('pqr_solucion', $datos);
			$respuestaFinal = $this->querys->consultarQuery('pqr_cerrado_general', $datos);
			$resultadoLocalFiltro = $this->querys->consultarQuery('pqr_filtrado', $datos);
			$respuestaUsuario = $this->querys->consultarQuery('pqr_respuesta_usuario',$datos);
			$respuestas 	  = $this->querys->consultarQuery('respuestas_para_usuario',$datos);
			$resultadoLocal = $this->querys->consultarQuery('pqr', $datos);
			$respuestaRechazado = $this->querys->consultarQuery('pqr_rechazado',$datos);
			$consultaPlataforma = $this->querys->consultarQuery('consultaPlataforma',$datos);
			$pqr_gestionadores = $this->querys->consultarQuery('pqr_gestionadores_indi',$datos);
			echo '
				<div id="formulariodepqrform"></div>
				<div class="container col-md-11 mt-5" >
				<nav aria-label="breadcrumb">
				  <ol class="breadcrumb">
				    <li class="breadcrumb-item"><a href="/vistas/panel_control_vista">Inicio</a></li>
				    <li class="breadcrumb-item active" aria-current="page">Detalle PQR</li>
				  </ol>
				 </nav>
				  <div class="card" >
				  	<div class="card-header">
				    	<center><strong><h4>Detalles PQR</h4></strong></center>
				  	</div>
				  	<div class="card-body">';
				  		echo '
				  			<div class="alert alert-success" role="alert">
							  <center><strong><h4>INFORMACIÓN DEL SOLICITANTE</h4></strong></center>
							</div>
				  			<div class="row" >
				  				<div class="col-md-4" >
				  					<label class="ml-3" ><strong>Nombre</strong></label>
				  					<input value="' . $resultadoLocal[0][2] . '" class="form-control" readonly>
				  				</div>
				  				<div class="col-md-4" >';
				  					if ($resultadoLocal[0][3] == 0 && $resultadoLocal[0][4] == 0) {
				  						echo '
				  							<label class="ml-2" ><strong>Documento</strong></label>
				  							<input value="' . $resultadoLocal[0][5] . '" class="form-control" readonly>
				  						';
				  					}else{
				  						echo '
				  							<label class="ml-3" ><strong>NIT</strong></label>
				  							<input value="' . $resultadoLocal[0][3] . '-' . $resultadoLocal[0][4] . '" class="form-control" readonly>
				  						';
				  					}
				  				echo'</div>
				  				<div class="col-md-4" >
				  					<label class="ml-3" ><strong>Email</strong></label>
									<input value="' . $resultadoLocal[0][6] . '" class="form-control" readonly>
				  				</div>
				  			</div>
				  			<br>
				  			<div class="row" >
				  				<div class="col-md-4" >
				  					<label class="ml-3" ><strong>Ciudad</strong></label>
									<input value="' . $resultadoLocal[0][7] . '" class="form-control" readonly>
				  				</div>
				  				<div class="col-md-4" >
				  					<label class="ml-3" ><strong>Telefono</strong></label>
									<input value="' . $resultadoLocal[0][8] . '" class="form-control" readonly>
				  				</div>
				  				<div class="col-md-4" >
				  					<label class="ml-3" ><strong>Celular</strong></label>
									<input value="' . $resultadoLocal[0][9] . '" class="form-control" readonly>
				  				</div>
				  			</div>
				  		';
						echo '
							<br>
							<hr>
							<div class="alert alert-secondary" role="alert">
							  <center><strong><h4>PQR FILTRADO</h4></strong></center>
							  <hr>
							 <div class="row">
								<div class="col-md-3" >
									<strong>Usuario filtrador</strong>
									<p><strong>'. $resultadoLocalFiltro[0][2] .'</strong></p>
								</div>
								<div class="col-md-3" >
									<strong>Distrito</strong>
									<p><strong>' . $resultadoLocalFiltro[0][3] . '</strong></p>
								</div>
								<div class="col-md-3" >
									<strong>Area</strong>								
									<p><strong>' . $resultadoLocalFiltro[0][5] . '</strong></p>
								</div>
								<div class="col-md-2" >
									<strong>Fecha de filtrado</strong>								
									<p><strong>' . $resultadoLocalFiltro[0][1] . '</strong></p>
								</div>
								<div class="col-md-1" >
									<strong>Tipo de PQR</strong>
									<span class="badge badge-success"><h6>' . $resultadoLocalFiltro[0][6] . '</h6></span>
								</div>
							</div>
							</div>
							<br>
							<hr>
						';
						if (count($pqr_gestionadores) > 0) 
						{
							for ($i=0; $i <count($pqr_gestionadores); $i++) { 
								echo 
								'
								<div class="alert alert-secondary" role="alert">
									<center><strong><h4>Escalamientos del PQR</h4></strong></center>
									<hr>
									<div class="row justify-content-md-center" >
										<div class="col-md-3" >
											<strong>Usuario quien escala</strong>
											<p><strong>'.$pqr_gestionadores[$i]['usuarioAfecta'].'</strong></p>
										</div>
										<div class="col-md-3" >
											<strong>Usuario que recibe el PQR</strong>
											<p><strong>'.$pqr_gestionadores[$i]['nombre_usuario'].'</strong></p>
										</div>
										<div class="col-md-2" >
											<strong>Fecha del escalamiento</strong>
											<p><strong>'.$pqr_gestionadores[$i]['fecha'].'</strong></p>
										</div>
										<div class="col-md-2" >
											<span class="badge badge-success"><h6>Escalamiento</h6></span>
										</div>
									</div>
								</div>
								';
							}
						}
						for ($i = 0; $i < count($solucion); $i++) {
								echo '
								<div class="alert alert-secondary" role="alert">
								  	<center><h4 class="alert-heading">SOLUCIONES DEL PQR</h4></center>
								  <hr>
								  <div class="row" >
								  	<div class="col-md-4" >
										<label><strong>PQR</strong></label>
										<input type="hidden" value="' . $solucion[$i]['id_pqr'] . '" id="idPqr">
										<p><strong>'.$solucion[$i]['identidicacion'].'</strong></p>
									</div>
									<div class="col-md-4" >
										<label><strong>Respuesta por parte de</strong></label>
										<input id="emailsolucionid" class="emailsolucion" value="'.$solucion[$i]['email'].'"  type="hidden" >
										<p><strong>'. $solucion[$i]['nombre_usuario'] .'</strong></p>
									</div>
									<div class="col-md-4" >
										<label><strong>Fecha</strong></label>
										<p><strong>'.$solucion[$i]['fecha'].'</strong></p>
									</div>
								  </div>
								  <hr>
								  <label><strong>Descripcion de la solución</strong></label>
									<p>' . $solucion[$i]['mensaje'] . '</p>
								</div>
							';
							$archivos = $this->querys->consultarQuery('pqr_solucion_imagenes', $datos);
							echo '
								<div class="alert alert-info" role="alert">
									<label><strong>Archivos</strong></label><br>

								</div>
							';
							for ($e=0; $e < count($archivos) ; $e++) {
								if (!empty($archivos)) { 
									if ($solucion[$i]['random'] == $archivos[$e]['random']) {
										echo '
											<a href="' . $archivos[$e][2] . '" >'.$archivos[$e][0] ."__". $archivos[$e][3] . '</a><br>
										';
									}
								} 
							}
						}
						$HTML = '';
						if (count($respuestaUsuario) > 0) {
							for ($i = 0; $i < count($respuestaUsuario); $i++) {
								$HTML .= '
									<br />
									<hr />
									<div class="alert alert-secondary" role="alert">
										<center><strong><h4>RESPUESTAS A USUARIO</h4></strong></center>
										<hr />
										<div class="row" >
											<div class="col-md-6" >
												<strong>Usuario</strong>
												<input class="form-control" value="'.$respuestaUsuario[$i]['nombre_usuario'].'"  readonly>
											</div>
											<div class="col-md-6" >
												<strong>Fecha del mensaje</strong>
												<input class="form-control" value="'.$respuestaUsuario[$i]['fecha'].'"  readonly>
											</div>
										</div>
										<hr />
									   	<p>' . $respuestaUsuario[$i]['mensaje'] . '</p>
									</div>
								';

								for ($f=0; $f < count($respuestas); $f++) { 
									if ($respuestas[$f]['random'] == $respuestaUsuario[$i]['random']) {
										$HTML .= '
											<br>
											<hr>
											<div class="alert alert-secondary" role="alert">
											  <center><strong><h4>VISUALIZACIÓN DEL MENSAJE</h4></strong></center>
											  <hr>
											  <div class="row" >
												<div class="col-md-4" >
													<strong>IP</strong>
													<input class="form-control" value="'.$respuestas[$f]['ip'].'"  readonly>
												</div>
												<div class="col-md-4" >
													<strong>Fecha de la visualización</strong>
													<input class="form-control" value="'.$respuestas[$f]['fecha_visualizacion'].'"  readonly>
												</div>
												<div class="col-md-4" >
													<strong>navegador</strong>
													<input class="form-control" value="'.$respuestas[$f]['navegador'].'"  readonly>
												</div>
											 </div>
											</div>
											
											<br>
										';
									}
								}
								for ($j=0; $j < count($consultaPlataforma); $j++) { 
									if ($consultaPlataforma[$j]['id_pqr'] == $respuestaUsuario[$i]['id_pqr']) {
										$HTML .= '
											<br />
											<hr />
											<div class="alert alert-secondary" >
												<center><strong><h4>CONSULTA DESDE PLATAFORMA</h4></strong></center>
												<hr>
												  <div class="row" >
													<div class="col-md-4" >
														<strong>IP</strong>
														<input class="form-control" value="'.$consultaPlataforma[$j]['ip_vi'].'"  readonly>
													</div>
													<div class="col-md-4" >
														<strong>Fecha de la visualización</strong>
														<input class="form-control" value="'.$consultaPlataforma[$j]['fecha_vi'].'"  readonly>
													</div>
													<div class="col-md-4" >
														<strong>navegador</strong>
														<input class="form-control" value="'.$consultaPlataforma[$j]['navegador_vi'].'"  readonly>
													</div>
												 </div>
											</div>
										';
									}
								}
								if (count($respuestaRechazado) > 0) {
									for ($n=0; $n < count($respuestaRechazado); $n++) {
										if ($respuestaRechazado[$n]['random_pqr_respuesta'] == $respuestaUsuario[$i]['random']) {
											$HTML .='
												<hr />
												<div class="alert alert-secondary" role="alert" >
													<center><strong><h4>PQR RECHAZADO</h4></strong></center>
											  		<hr>
											  		<div class="row" >
											  			<div class="col-md-4" >
											  				<label><strong>Fecha</strong></label>
											  				<input class="form-control" value="' . $respuestaRechazado[$n]['fecha'] . '" readonly>
											  			</div>
											  		</div>
											  		<hr>
													<label><strong>Razón</strong></label>
													<p><strong>' . $respuestaRechazado[$n]['mensaje'] . '</strong></p>
												</div>
												<hr />
											';
										}
									}
								}
							}
							print_r($HTML);
						}
						echo '
							<br>
							<hr>
							<div class="alert alert-secondary" role="alert">
							  <center><strong><h4>PQR CERRADO</h4></strong></center>
							  <hr>
							  <label><strong>Respuesta del cierre</strong></label>
							<br>
							<div class="row" >
								<div class="col-md-4" >
									<label><strong>PQR</strong></label>
									<input class="form-control" value="' . $solucion[0]['identidicacion'] . '" readonly>
								</div>
								<div class="col-md-4" >
									<label><strong>Usuario</strong></label><br>';
										if (is_null($respuestaFinal[0]['nombre_usuario'])) {
											echo '<input class="form-control" value="Sistema" readonly>';
										} else {
											echo '<input class="form-control" value="'.$respuestaFinal[0]['nombre_usuario'].'"  readonly>';
										}
									echo'</div>
								<div class="col-md-4" >
									<label><strong>Fecha</strong></label><br>
									<input class="form-control" value="'.$respuestaFinal[0]['fecha'].'"  readonly>
								</div>
								</div>
								<hr>
								<label><strong>Descripcion</strong></label>
									<p><strong>' . $respuestaFinal[0]['mensaje'] . '</strong></p>
							</div>
							
						';
				echo'</div>
				  </div>
				</div>

			';
			$this->plantilla->pie();
		} catch (Exception $e) {
			echo 'Excepción capturada: ', $e->getMessage(), "\n";
		}
	}

	private function pqrprocesodetalles($datos)
	{
		try {
			$idUsuario = $_SESSION['idUsuario'];
			$this->querys = new Querys();
			$this->plantilla = new plantilla();
			$this->plantilla->cabezera();
			$this->plantilla->cuerpo();
			$this->plantilla->menuNavegacion();
			$solucion = $this->querys->consultarQuery('pqr_solucion', $datos);
			$respuestaFinal = $this->querys->consultarQuery('pqr_cerrado_general', $datos);
			$resultadoLocalFiltro = $this->querys->consultarQuery('pqr_filtrado', $datos);
			$respuestaUsuario = $this->querys->consultarQuery('pqr_respuesta_usuario',$datos);
			$respuestas 	  = $this->querys->consultarQuery('respuestas_para_usuario',$datos);
			$resultadoLocal = $this->querys->consultarQuery('pqr', $datos);
			$respuestaRechazado = $this->querys->consultarQuery('pqr_rechazado',$datos);
			$consultaPlataforma = $this->querys->consultarQuery('consultaPlataforma',$datos);
			$pqr_gestionadores = $this->querys->consultarQuery('pqr_gestionadores_indi',$datos);
			echo '
				<div id="formulariodepqrform"></div>
				<div class="container col-md-11 mt-5" >
				<nav aria-label="breadcrumb">
				  <ol class="breadcrumb">
				    <li class="breadcrumb-item"><a href="/vistas/panel_control_vista">Inicio</a></li>
				    <li class="breadcrumb-item active" aria-current="page">Detalle PQR</li>
				  </ol>
				 </nav>
				  <div class="card" >
				  	<div class="card-header">
				    	<center><strong><h4>Detalles PQR</h4></strong></center>
				  	</div>
				  	<div class="card-body">';
				  		echo '
				  			<div class="alert alert-success" role="alert">
							  <center><strong><h4>INFORMACIÓN DEL SOLICITANTE</h4></strong></center>
							</div>
				  			<div class="row" >
				  				<div class="col-md-4" >
				  					<label class="ml-3" ><strong>Nombre</strong></label>
				  					<input value="' . $resultadoLocal[0][2] . '" class="form-control" readonly>
				  				</div>
				  				<div class="col-md-4" >';
				  					if ($resultadoLocal[0][3] == 0 && $resultadoLocal[0][4] == 0) {
				  						echo '
				  							<label class="ml-2" ><strong>Documento</strong></label>
				  							<input value="' . $resultadoLocal[0][5] . '" class="form-control" readonly>
				  						';
				  					}else{
				  						echo '
				  							<label class="ml-3" ><strong>NIT</strong></label>
				  							<input value="' . $resultadoLocal[0][3] . '-' . $resultadoLocal[0][4] . '" class="form-control" readonly>
				  						';
				  					}
				  				echo'</div>
				  				<div class="col-md-4" >
				  					<label class="ml-3" ><strong>Email</strong></label>
									<input value="' . $resultadoLocal[0][6] . '" class="form-control" readonly>
				  				</div>
				  			</div>
				  			<br>
				  			<div class="row" >
				  				<div class="col-md-4" >
				  					<label class="ml-3" ><strong>Ciudad</strong></label>
									<input value="' . $resultadoLocal[0][7] . '" class="form-control" readonly>
				  				</div>
				  				<div class="col-md-4" >
				  					<label class="ml-3" ><strong>Telefono</strong></label>
									<input value="' . $resultadoLocal[0][8] . '" class="form-control" readonly>
				  				</div>
				  				<div class="col-md-4" >
				  					<label class="ml-3" ><strong>Celular</strong></label>
									<input value="' . $resultadoLocal[0][9] . '" class="form-control" readonly>
				  				</div>
				  			</div>
				  		';
						echo '
							<br>
							<hr>
							<div class="alert alert-secondary" role="alert">
							  <center><strong><h4>PQR FILTRADO</h4></strong></center>
							  <hr>
							 <div class="row">
								<div class="col-md-3" >
									<strong>Usuario filtrador</strong>
									<p><strong>'. $resultadoLocalFiltro[0][2] .'</strong></p>
								</div>
								<div class="col-md-3" >
									<strong>Distrito</strong>
									<p><strong>' . $resultadoLocalFiltro[0][3] . '</strong></p>
								</div>
								<div class="col-md-3" >
									<strong>Area</strong>								
									<p><strong>' . $resultadoLocalFiltro[0][5] . '</strong></p>
								</div>
								<div class="col-md-2" >
									<strong>Fecha de filtrado</strong>								
									<p><strong>' . $resultadoLocalFiltro[0][1] . '</strong></p>
								</div>
								<div class="col-md-1" >
									<strong>Tipo de PQR</strong>
									<span class="badge badge-success"><h6>' . $resultadoLocalFiltro[0][6] . '</h6></span>
								</div>
							</div>
							</div>
							<br>
							<hr>
						';
						if (count($pqr_gestionadores) > 0) 
						{
							for ($i=0; $i <count($pqr_gestionadores); $i++) { 
								echo 
								'
								<div class="alert alert-secondary" role="alert">
									<center><strong><h4>Escalamientos del PQR</h4></strong></center>
									<hr>
									<div class="row justify-content-md-center" >
										<div class="col-md-3" >
											<strong>Usuario quien escala</strong>
											<p><strong>'.$pqr_gestionadores[$i]['usuarioAfecta'].'</strong></p>
										</div>
										<div class="col-md-3" >
											<strong>Usuario que recibe el PQR</strong>
											<p><strong>'.$pqr_gestionadores[$i]['nombre_usuario'].'</strong></p>
										</div>
										<div class="col-md-2" >
											<strong>Fecha del escalamiento</strong>
											<p><strong>'.$pqr_gestionadores[$i]['fecha'].'</strong></p>
										</div>
										<div class="col-md-2" >
											<span class="badge badge-success"><h6>Escalamiento</h6></span>
										</div>
									</div>
								</div>
								';
							}
						}
						for ($i = 0; $i < count($solucion); $i++) {
								echo '
								<div class="alert alert-secondary" role="alert">
								  	<center><h4 class="alert-heading">SOLUCIONES DEL PQR</h4></center>
								  <hr>
								  <div class="row" >
								  	<div class="col-md-4" >
										<label><strong>PQR</strong></label>
										<input type="hidden" value="' . $solucion[$i]['id_pqr'] . '" id="idPqr">
										<p><strong>'.$solucion[$i]['identidicacion'].'</strong></p>
									</div>
									<div class="col-md-4" >
										<label><strong>Respuesta por parte de</strong></label>
										<input id="emailsolucionid" class="emailsolucion" value="'.$solucion[$i]['email'].'"  type="hidden" >
										<p><strong>'. $solucion[$i]['nombre_usuario'] .'</strong></p>
									</div>
									<div class="col-md-4" >
										<label><strong>Fecha</strong></label>
										<p><strong>'.$solucion[$i]['fecha'].'</strong></p>
									</div>
								  </div>
								  <hr>
								  <label><strong>Descripcion de la solución</strong></label>
									<p>' . $solucion[$i]['mensaje'] . '</p>
								</div>
							';
							$archivos = $this->querys->consultarQuery('pqr_solucion_imagenes', $datos);
							echo '
								<div class="alert alert-info" role="alert">
									<label><strong>Archivos</strong></label><br>

								</div>
							';
							for ($e=0; $e < count($archivos) ; $e++) {
								if (!empty($archivos)) { 
									if ($solucion[$i]['random'] == $archivos[$e]['random']) {
										echo '
											<a href="' . $archivos[$e][2] . '" >'.$archivos[$e][0] ."__". $archivos[$e][3] . '</a><br>
										';
									}
								} 
							}
						}
						$HTML = '';
						if (count($respuestaUsuario) > 0) {
							for ($i = 0; $i < count($respuestaUsuario); $i++) {
								$HTML .= '
									<br />
									<hr />
									<div class="alert alert-secondary" role="alert">
										<center><strong><h4>RESPUESTAS A USUARIO</h4></strong></center>
										<hr />
										<div class="row" >
											<div class="col-md-6" >
												<strong>Usuario</strong>
												<input class="form-control" value="'.$respuestaUsuario[$i]['nombre_usuario'].'"  readonly>
											</div>
											<div class="col-md-6" >
												<strong>Fecha del mensaje</strong>
												<input class="form-control" value="'.$respuestaUsuario[$i]['fecha'].'"  readonly>
											</div>
										</div>
										<hr />
									   	<p>' . $respuestaUsuario[$i]['mensaje'] . '</p>
									</div>
								';

								for ($f=0; $f < count($respuestas); $f++) { 
									if ($respuestas[$f]['random'] == $respuestaUsuario[$i]['random']) {
										$HTML .= '
											<br>
											<hr>
											<div class="alert alert-secondary" role="alert">
											  <center><strong><h4>VISUALIZACIÓN DEL MENSAJE</h4></strong></center>
											  <hr>
											  <div class="row" >
												<div class="col-md-4" >
													<strong>IP</strong>
													<input class="form-control" value="'.$respuestas[$f]['ip'].'"  readonly>
												</div>
												<div class="col-md-4" >
													<strong>Fecha de la visualización</strong>
													<input class="form-control" value="'.$respuestas[$f]['fecha_visualizacion'].'"  readonly>
												</div>
												<div class="col-md-4" >
													<strong>navegador</strong>
													<input class="form-control" value="'.$respuestas[$f]['navegador'].'"  readonly>
												</div>
											 </div>
											</div>
											
											<br>
										';
									}
								}
								for ($j=0; $j < count($consultaPlataforma); $j++) { 
									if ($consultaPlataforma[$j]['random'] == $respuestaUsuario[$i]['random']) {
										$HTML .= '
											<br />
											<hr />
											<div class="alert alert-secondary" >
												<center><strong><h4>CONSULTA DESDE PLATAFORMA</h4></strong></center>
												<hr>
												  <div class="row" >
													<div class="col-md-4" >
														<strong>IP</strong>
														<input class="form-control" value="'.$consultaPlataforma[$j]['ip_vi'].'"  readonly>
													</div>
													<div class="col-md-4" >
														<strong>Fecha de la visualización</strong>
														<input class="form-control" value="'.$consultaPlataforma[$j]['fecha_vi'].'"  readonly>
													</div>
													<div class="col-md-4" >
														<strong>navegador</strong>
														<input class="form-control" value="'.$consultaPlataforma[$j]['navegador_vi'].'"  readonly>
													</div>
												 </div>
											</div>
										';
									}
								}
								if (count($respuestaRechazado) > 0) {
									for ($n=0; $n < count($respuestaRechazado); $n++) {
										if ($respuestaRechazado[$n]['random_pqr_respuesta'] == $respuestaUsuario[$i]['random']) {
											$HTML .='
												<hr />
												<div class="alert alert-secondary" role="alert" >
													<center><strong><h4>PQR RECHAZADO</h4></strong></center>
											  		<hr>
											  		<div class="row" >
											  			<div class="col-md-4" >
											  				<label><strong>Fecha</strong></label>
											  				<input class="form-control" value="' . $respuestaRechazado[$n]['fecha'] . '" readonly>
											  			</div>
											  		</div>
											  		<hr>
													<label><strong>Razón</strong></label>
													<p><strong>' . $respuestaRechazado[$n]['mensaje'] . '</strong></p>
												</div>
												<hr />
											';
										}
									}
								}
							}
							print_r($HTML);
						}
				echo'</div>
				  </div>
				</div>

			';
			$this->plantilla->pie();
		} catch (Exception $e) {
			echo 'Excepción capturada: ', $e->getMessage(), "\n";
		}
	}

	

	private function pqrEditarVista()
	{
		try {
			$consultas = new consultasCrud();
			$datos = base64_decode($_GET['pqrEdit']);
			$this->plantilla = new plantilla();
			$this->plantilla->cabezera();
			$this->plantilla->cuerpo();
			$this->plantilla->menuNavegacion();
			$querys = new Querys();
			$pqr_individual = $querys->consultarQuery('pqr_filtrado',$datos);
			$tipoRoles = $consultas->consultarTablas('tipopqr',null);
			$areas = $consultas->consultarTablas('areas',null);
			$distritos = $consultas->consultarTablas('distritos',null);
			echo '
				<div  class="container col-md-10 mt-5" >
					<nav aria-label="breadcrumb">
				  		<ol class="breadcrumb">
				    		<li class="breadcrumb-item"><a href="/vistas/panel_control_vista">Inicio</a></li>
				   	 		<li class="breadcrumb-item active" aria-current="page">Editar PQR</li>
				  		</ol>
				 	</nav>
				 	<div id="formulariodepqrform"></div>
				 	<div class="card" >
				 		<div class="card-header" >
				 			<center><strong>Editar PQR</strong></center>
				 		</div>
				 		<div class="card-body" >
				 			<div class="row" >
				 				<div class="col-md-4" >
				 					<input id="idPqr" type="hidden" value="'.$datos.'" >
				 					<select id="tipoPqr" class="form-control inputs" >';
				 						for ($i=0; $i < count($tipoRoles) ; $i++) {
				 							if ($pqr_individual[0]['tipoPqr'] == $tipoRoles[$i][0]) {
				 								echo'<option class="inputs" value="'.$tipoRoles[$i][0].'" selected>'.$tipoRoles[$i][1].'</option>';
				 							} else {
				 								echo'<option class="inputs" value="'.$tipoRoles[$i][0].'">'.$tipoRoles[$i][1].'</option>';
				 							}
				 						}
				 				echo'</select>
				 				</div>
				 				<div class="col-md-4" >
				 					<select id="areaEditar" class="form-control inputs" >';
				 						for ($i=0; $i < count($areas) ; $i++) {
				 							if ($pqr_individual[0]['idArea'] == $areas[$i][0]) {
				 								echo '<option class="inputs" value="'.$areas[$i][0].'" selected>'.$areas[$i][1].'</option>';
				 							} else {
				 								echo '<option class="inputs" value="'.$areas[$i][0].'">'.$areas[$i][1].'</option>';
				 							}
				 						}
				 				echo'</select>
				 				</div>
				 				<div class="col-md-4" >
				 					<select id="distritoEditar" class="form-control inputs" >';
				 						for ($i=0; $i < count($distritos) ; $i++) {
				 							if ($pqr_individual[0]['idDistrito'] == $distritos[$i][0]) {
				 								echo '<option class="inputs" value="'.$distritos[$i][0].'" selected>'.$distritos[$i][1].'</option>';
				 							} else {
				 								echo '<option class="inputs" value="'.$distritos[$i][0].'">'.$distritos[$i][1].'</option>';
				 							}
				 						}
				 				echo'</select>
				 				</div>
				 			</div>
				 			<br>
				 			<button id="editarDatosPqr" class="btn btn-outline-success btn-block" >Guardar cambios</button>
				 		</div>
				 	</div>
				</div>
			';
			$this->plantilla->pie();
		} catch (Exception $e) {
			echo 'Excepción capturada: ', $e->getMessage(), "\n";
		}
		finally{
			$consultas = null;
		}
	}

	private function edicionPqrDatos($post)
	{
		try {
			$this->conexion = new conexion();
			$idPqr = htmlentities(addslashes($post['idPqr']));
			$areaEditar = htmlentities(addslashes($post['areaEditar']));
			$distritoEditar = htmlentities(addslashes($post['distritoEditar']));
			$tipoPqr = htmlentities(addslashes($post['tipoPqr']));
			$sql_editar_pqr = "UPDATE pqr_filtrado SET id_tipo = :idTipo,id_area = :idArea,id_distrito = :idDistrito WHERE id_pqr = :idPqr";
			$retorno = $this->conexion->consulQuery($sql_editar_pqr);
			$retorno['resultado']->bindParam(':idTipo',$tipoPqr,PDO::PARAM_INT);
			$retorno['resultado']->bindParam(':idArea',$areaEditar,PDO::PARAM_INT);
			$retorno['resultado']->bindParam(':idDistrito',$distritoEditar,PDO::PARAM_INT);
			$retorno['resultado']->bindParam(':idPqr',$idPqr,PDO::PARAM_INT);
			if ($retorno['resultado']->execute()) {
				echo  true;
			} else {
				print_r($retorno['resultado']->errorInfo());
			}
		} catch (Exception $e) {
			echo 'Excepción capturada: ', $e->getMessage(), "\n";
		}finally{
			$this->conexion = null;
		}
	}
}
$obj = new pqr();
