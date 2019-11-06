<?php
/**
 *
 * @author Cristian Camilo Vasquez
 * Clase respuestaUsuario
 * Se incluyen:
 * El archivo de carga de archivos js.
 * El archivo de envio de mails.
 * El archivo de clases query para la utilizacion del constructor de querys.
 * El archivo de auto carga de clases.
 *
 */
include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/clases/carga_archivos_clase.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/clases/envio_mails.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/clases/clasesQuery.php';
require $_SERVER['DOCUMENT_ROOT'] . '/autoRecarga.php';
use clases\Vista_clase;

class respuestaUsuario
{
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
	 * @property:$this->DB Almacena el objeto clasesQuery().
	 * Se verifica si existe la @var:$_GET['res']; Al existir se llama el  @method:$this->respuestaUsuarioIndex(Argumentos).
	 * Se verifica si existe la @var:$_POST['info'] Al existir se llama el @method:$this->insertarRespuestaUsuario(Argumentos).
	 * Se verifica si existe la @var:$_POST['tipo'] Al existir se llama el @method:$this->aceptarPqrCerrado(Argumentos).
	 * Se verifica si existe la @var:$_POST['datos'] Al existir se llama el @method:$this->insertarDatosUsuarioLocal(Argumentos).
	 * Se verifica si existe la @var:$_GET['pqrUltima'] Al existir se llama el @method:$this->ultimaRespuestaUsuario(Argumentos).
	 *
	 */
	public function __construct()
	{
		try
		{
			// Verificaciones
			if (isset($_GET['res'])) 
			{
				$this->respuestaUsuarioIndex($_GET['res'],$_GET['ema']);
			}elseif (isset($_POST['info'])) 
			{
				$this->insertarRespuestaUsuario($_POST['info']);
			}elseif (isset($_POST['tipo']) && $_POST['tipo'] == 'aceptarUsuario') 
			{
				$this->aceptarPqrCerrado($_POST);
			} elseif (isset($_POST['tipo']) && $_POST['tipo'] == 'rechazarUsuario') 
			{
				$this->rechazarPqrCerrado($_POST);
			}elseif (isset($_POST['datos'])) 
			{
				$this->insertarDatosUsuarioLocal($_POST);
			}
			elseif (isset($_GET['pqrUltima'])) 
			{
				$this->ultimaRespuestaUsuario($_GET);
			}
			elseif (isset($_POST['general'])) {
				$this->insertarVisualizacionGeneral($_POST);
			}
		}
		catch (Exception $e)
		{
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}
	
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
	 * @method:respuestaUsuarioIndex(parametros) Se encarga de llamar la vista formulario_respuesta_usuario_vista y envia los respectivos datos.
	 * @param:$get int.
	 * @param:$email string.
	 * @var:$idUsuario Almacena la variable de sesion @var:$_SESSION['idUsuario'].
	 * @var:$datos Almacena el @param:$get descodeado.
	 * @var:$email Almacena el @param:$email descodeado.
	 * @var:$imagenes Almacena los datos obtenidos.
	 * @method:respuestaUsuarioIndex() ejecuta el llamado de la vista con el metodo @method:Vista_clase::(string vista,array(variables)). 
	 *
	 */
	public function respuestaUsuarioIndex($get,$email)
	{
		try 
		{
			$this->DBinstancia();
			$idUsuario = $_SESSION['idUsuario'];
			$datos = base64_decode($get);
			$email = base64_decode($email);
			// Obtenciòn de los datos.
			$this->DB->table('pqrsolucion_archivos');
			$this->DB->where('id_pqr','=',$datos);
			$this->DB->select('*');
			$imagenes = $this->DB->get();
			//En la vista usuarios_vista se usaran las variables enviadas.
			Vista_clase::vista('formulario_respuesta_usuario_vista',array(
				'idUsuario' => $idUsuario,
				'datos'     => $datos,
				'email'     => $email,
				'imagenes'  => $imagenes
			));
		} catch (Exception $e) 
		{
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}

	/**
	 *
	 * @method:textoMensajeUsuario(parametros) Se encarga de alamacenar el mensaje para ser enviado por el metodo mail.
	 * @param:$idPqr int.
	 * @param:$random int.
	 * @var:$mensajeMail Almacena el mensaje para ser enviado adjuntando los parametros en el mensaje
	 * @return $mensajeMail. 
	 *
	 */
	private function textoMensajeUsuario($idPqr,$random)
	{
		try 
		{
			// Mensaje
			$mensajeMail = '
				<div>
					<center>
						<img height="80" src="https://www.ropsohn.com.co/images/IDENTIFICADOR_ROPSON-04.jpg" style="border:0" class="CToWUd">
						<br>
						<br>
						<hr style="border-top:solid 1px #dbdbdb;width:300px;" >
						<p style="width:300px;padding:0;margin:0;text-align:center;color:#262626;font-size:18px;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif" >
							Respuesta a tu <strong>PQR</strong>
						</p>
						<p style="width:300px;padding:0;margin:0;text-align:center;color:#999999;font-size:14px;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif">
							Hola, Por favor verifica el mensaje<br>
						</p>
						<br>
						<br>
						<span class="m_-2429985624185834423m_-923009222503390390btn-content" style="background-color:#2c9ce9;border-radius:4px;display:inline-block">
							<a href="https://'.$_SERVER['HTTP_HOST'].'/vistas/respuesta_usuario_vista.php?pqr='.base64_encode($idPqr)."&&rand=".base64_encode($random).'" style="color:#fff;display:inline-block;font-family:Roboto,Helvetica,Arial,sans-serif;font-size:16px;line-height:1.4;text-align:center;text-decoration:none;min-width:115px;padding:8px 16px;font-weight:500" target="_blank">
						<span style="color:#fff">Para consultas Haz click aquí</span></a></span>
						<br>
						<br>
						<hr style="width:310px;border-top:solid 1px #dbdbdb;" >
						<p style="width:300px;padding:0;margin:0;text-align:center;color:#262626;font-size:14px;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif" >
							Por favor verifica la respuesta que te hemos enviado.
						</p>
						<p style="width:300px;padding:0;margin:0;text-align:center;color:#999999;font-size:14px;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif" >
							Mensaje enviado automaticamente.<br>
							<br>
							<br>
							<strong>© Desarrollo - ZK SAS.</strong>
						</p>
					</center>
				</div>
			';
			return $mensajeMail;
		} catch (Exception $e) 
		{
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}

	/**
	 *
	 * @method:insertarRespuestaUsuario(parametros) Se encarga de alamacenar la respuesta al respectivo PQR.
	 * @param:$post array.
	 * @var:$email Almacena el escapado del $post[3] //email.
	 * @var:$random Almacena un random para ser insertado.
	 * @var:Almacena el response de la consulta.
	 *
	 */
	private function insertarRespuestaUsuario($post)
	{
		try 
		{
			$this->DBinstancia();
			$email = htmlentities(addslashes($post[3]));
			$random = rand(1000000,9999999);
			// Insercion de los datos a la tabla pqr_respuesta_usuario.
			$this->DB->table('pqr_respuesta_usuario');
			$respuesta = $this->DB->insert([
				'id_pqr'			  => $post[0],
				'id_usuario'		  => $post[1],
				'mensaje'			  => $post[2],
				'fecha'				  => 'CURRENT_TIMESTAMP',
				'random'			  => $random,
				'visibilidad_usuario' => 0,
				'ip'				  => 0,
				'navegador'			  => 'SIN',
				'fecha_visualizacion' => 'CURRENT_TIMESTAMP'
			]);
			if (isset($respuesta['ejecutado'])) 
			{
				if (isset($post[4])) //En $post[4] contiene el arreglo con los emails para copia oculta
				{
					if (isset($post[5])) //En $post[5] contiene el arreglo de archivos
					{
						$this->DB->table('rutas_respuestas_archivos');
						foreach ($post[5] as $rutaArchivo) 
						{
							$respuestaRutas = $this->DB->insert([
								'id_pqr_respuesta' => $respuesta['ultimoID'],
								'ruta_archivo'	   => $rutaArchivo
							]);
							$mensajeParaEmail = $this->textoMensajeUsuario($post[0],$random);
						}
						$this->envioMail($email,$mensajeParaEmail,'respuesta de tu PQR',null,$post[4]);
					}
					else
					{
						$mensajeParaEmail = $this->textoMensajeUsuario($post[0],$random);
						$this->envioMail($email,$mensajeParaEmail,'respuesta de tu PQR',null,$post[4]);
					} 
				}
				else
				{
					if (isset($post[5])) 
					{
						$this->DB->table('rutas_respuestas_archivos');
						foreach ($post[5] as $rutaArchivo) 
						{
							$respuestaRutas = $this->DB->insert([
								'id_pqr_respuesta' => $respuesta['ultimoID'],
								'ruta_archivo'	   => $rutaArchivo
							]);
							$mensajeParaEmail = $this->textoMensajeUsuario($post[0],$random);
						}
						$this->envioMail($email,$mensajeParaEmail,'respuesta de tu PQR',null);
					}
					else
					{
						$mensajeParaEmail = $this->textoMensajeUsuario($post[0],$random);
						$this->envioMail($email,$mensajeParaEmail,'respuesta de tu PQR',null);
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



	private function envioMail($email,$mensajeParaEmail,$asunto,$opcion,$arreglo = [])
	{
		try 
		{
			$mail = new mail($email,$mensajeParaEmail,$asunto,$opcion,$arreglo);	
		} catch (Exception $e) 
		{
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}

	public function vistaParaUsuario()
	{
		try 
		{
			include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/consultas/querys.php';
			$datos = base64_decode($_GET['pqr']);
			$random = base64_decode($_GET['rand']);
			$this->conexion = new conexion();
			$this->querys = new Querys();
			$resultadoLocal = $this->querys->consultarQuery('pqr_respondidos', $random);
			$sql = "SELECT * FROM pqr_cerrado WHERE id_pqr = :idPqr";
			$retorno = $this->conexion->consulQuery($sql);
			$retorno['resultado']->bindParam(':idPqr',$datos,PDO::PARAM_INT);
			if ($retorno['resultado']->execute()) {
				$datosRetorno = $retorno['resultado']->fetchAll();
				if (!count($datosRetorno) > 0 ) {
					echo '
						<html>
							<head>
								<title>PQR</title>
								<link rel="stylesheet" type="text/css" href="/node_modules/bootstrap/dist/css/bootstrap.css">
								<link rel="stylesheet" type="text/css" href="/publico/css/estilos.css">
								<link rel="stylesheet" type="text/css" href="/node_modules/fortawesome/fontawesome-free/css/all.css">
								<link rel="icon" href="/archivos/favicon.png" type="image/x-icon"/>
								<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
							</head>
							<body>
								<div class="container col-md-10 mt-5" >
									<div class="card">
										<div class="card-header">
											<center><strong>Respuesta a tu PQR</strong></center>
										</div>
										<div class="card-body" >
											<input type="hidden" id="idPqr" value="'.$datos.'">
											<input type="hidden" id="randomPqr" value="'.$random.'">
											'.$resultadoLocal['mensaje'].'';
											$arreglo = array();
											$sql_archivos = "SELECT * FROM rutas_respuestas_archivos WHERE id_pqr_respuesta = :idres";
											$retorno_archivos = $this->conexion->consulQuery($sql_archivos);
											$retorno_archivos['resultado']->bindParam(':idres',$resultadoLocal['id'],PDO::PARAM_INT);
											if ($retorno_archivos['resultado']->execute()) {
												while ($datos = $retorno_archivos['resultado']->fetch(PDO::FETCH_ASSOC)) {
													$idArchivo = $datos['ruta_archivo'];
													array_push($arreglo,$idArchivo);
												}
												$sql_archivos_indi = "SELECT * FROM pqrsolucion_archivos WHERE id = :idArchivo";
												$retorno_archivos_indi = $this->conexion->consulQuery($sql_archivos_indi);
												echo '<hr><br><strong>Archivos adjuntos</strong>';
												for ($i=0; $i < count($arreglo); $i++) { 
													$retorno_archivos_indi['resultado']->bindParam(':idArchivo',$arreglo[$i],PDO::PARAM_INT);
													if ($retorno_archivos_indi['resultado']->execute()) {
														while ($datos_archivos = $retorno_archivos_indi['resultado']->fetch(PDO::FETCH_ASSOC)) {
															echo '<br><a href="'.$datos_archivos['ruta'].'" >'.$datos_archivos['nombre'].'</a>';
														}
													} else {
														print_r($retorno_archivos_indi['resultado']->errorInfo());
													}
												}
											} else {
												print_r($retorno_archivos['resultado']->errorInfo());
											}
											echo'<hr>
											<center><strong>Estas satisfecho con la respuesta ?</strong>
											<br>
											<button id="acepto" class="btn btn-outline-success mt-1 col-md-2" >Acepto</button>
											<button id="noAceptoBoton" class="btn btn-outline-danger" mt-1 col-md-2">No acepto</button>
											<div id="razonRechazo">
											  <hr />
											  <strong>Escribe una razon</strong>
											  <textarea id="razon" class="form-control" >

											  </textarea>
											  <br />
											  <button id="noAcepto" class="btn btn-outline-danger" mt-1 col-md-2">Guardar cambios</button>
											</div>									
											<br></center>
											<br>
											<div id="aceptoRes" ></div>
										</div>
									</div>
								</div>';
								$objcarga = new carga(19);
						echo'</body>
						</html>
					';
					$obj = new carga(18);
				} else {
					echo '
						<html>
							<head>
								<title>PQR</title>
								<link rel="stylesheet" type="text/css" href="/node_modules/bootstrap/dist/css/bootstrap.css">
								<link rel="stylesheet" type="text/css" href="/publico/css/estilos.css">
								<link rel="stylesheet" type="text/css" href="/node_modules/fortawesome/fontawesome-free/css/all.css">
								<link rel="icon" href="/archivos/favicon.png" type="image/x-icon"/>
							</head>
							<body>
								<div class="container col-md-10 mt-5" >
									<div class="card">
										<div class="card-header">
											<strong>Respuesta</strong>
										</div>
										<div class="card-body" >
											<div class="alert alert-success" role="alert">
											  <strong>Su respuesta se ha enviado al sistema</strong>
											  <br>
											  <strong>Link vencido</strong>
											</div>
										</div>
									</div>
								</div>
							</body>
						</html>
					';
				}
				
			} else 
			{
				print_r($retorno->errorInfo());
			}
			
		} catch (Exception $e) 
		{
			echo 'Excepción capturada: ', $e->getMessage(), "\n";
		}
	}


	private function traerDatosUsuarioLocal($randomPqr)
	{
		try {
			$this->DBinstancia();
			$this->DB->table('pqr_respuesta_usuario');
			$this->DB->where('random','=',$randomPqr);
			$this->DB->select('*');
			return $datos = $this->DB->get();
		} catch (Exception $e) {
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}

	private function insertarDatosUsuarioLocal($post)
	{
		try 
		{
			$pqrActual = $this->traerDatosUsuarioLocal($post['randomPqr']);
			if (!isset($pqrActual['ejecutado'])) {
				$pqrActual = json_decode($pqrActual, true);
			}
			$this->DBinstancia();
			$IP = $_SERVER['REMOTE_ADDR'];
			$navegador = $this->navegadorActual($_SERVER['HTTP_USER_AGENT']);
			$fecha = "CURRENT_TIMESTAMP";
			$this->DB->table('pqr_respuesta_usuario');
			$this->DB->where('random','=',$post['randomPqr']);
			if ($pqrActual[0]['visibilidad_usuario'] == 0) {
				$respuesta = $this->DB->update([
					'visibilidad_usuario' => 1,
					'ip'				  => $IP,
					'navegador'			  => $navegador,
					'fecha_visualizacion' => 'CURRENT_TIMESTAMP'
				]);
			} else {
				$respuesta = $this->DB->insert([
					'id_pqr'			  => $pqrActual[0][1],
					'id_usuario'		  => $pqrActual[0][2],
					'mensaje'			  => $pqrActual[0][3],
					'fecha'				  => $pqrActual[0][4],
					'random'			  => $pqrActual[0][5],
					'visibilidad_usuario' => 1,
					'ip'				  => $IP,
					'navegador'			  => $navegador,
					'fecha_visualizacion' => 'CURRENT_TIMESTAMP'
				]);
			}
			if($respuesta == true)
			{
				echo json_encode(array('status' => 1,'respuesta' => 'Se ha editado la visualizacion'));
			}
			else
			{
				echo json_encode(array('status' => 0,'error' => 'Ha ocurrido un error al editar'));
			}
		} catch (Exception $e) 
		{
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}

	private function navegadorActual($target)
	{
		try 
		{
			if(strpos($target, 'MSIE') !== FALSE)
			   return 'Internet explorer';
			 elseif(strpos($target, 'Edge') !== FALSE) //Microsoft Edge
			   return 'Microsoft Edge';
			 elseif(strpos($target, 'Trident') !== FALSE) //IE 11
			    return 'Internet explorer';
			 elseif(strpos($target, 'Opera Mini') !== FALSE)
			   return "Opera Mini";
			 elseif(strpos($target, 'Opera') || strpos($target, 'OPR') !== FALSE)
			   return "Opera";
			 elseif(strpos($target, 'Firefox') !== FALSE)
			   return 'Mozilla Firefox';
			 elseif(strpos($target, 'Chrome') !== FALSE)
			   return 'Google Chrome';
			 elseif(strpos($target, 'Safari') !== FALSE)
			   return "Safari";
			 else
			   return 'No hemos podido detectar su navegador';
		} catch (Exception $e) 
		{
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}

	private function aceptarPqrCerrado($post)
	{
		try 
		{
			$this->DBinstancia();
			$this->DB->table('pqr_cerrado');
			$respuestaAceptar = $this->DB->insert([
				'id_pqr'	 => $post['idPqr'],
				'id_usuario' => 0,
				'fecha'		 => 'CURRENT_TIMESTAMP',
				'mensaje'	 => 'Se ha cerrado el PQR por parte del solicitante'
			]);
			if ($respuestaAceptar == true) 
			{
				$this->DB->table('pqr_filtrado');
				$this->DB->where('id_pqr','=',$post['idPqr']);
				$respuestaEdicion = $this->DB->update([
					'status' => 3
				]);
				if($respuestaEdicion == true)
				{
					$this->DB->table('pqr');
					$this->DB->where('id','=',$post['idPqr']);
					$respuestaPqr = $this->DB->update([
						'status' => 0,
						'estado' => 'finalizado'
					]);
					if($respuestaPqr == true)
					{
						echo json_encode(array('status' => 1,'respuesta' => 'Se ha registrado correctamente'));
					} 
				}
				else
				{
					echo json_encode(array('status' => 0,'error' => 'Ha ocurrido un error al editar:' . $respuestaEdicion));
				}
			}
			else
			{
				echo json_encode(array('status' => 0,'error' => 'Ha ocurrido un error al insertar:' . $respuestaAceptar));
			}
		} catch (Exception $e) 
		{
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}finally
		{
			$this->DB = null;
		}
	}

	private function rechazarPqrCerrado($post)
	{
		try {
			$this->DBinstancia();
			$this->DB->table('pqr_filtrado');
			$this->DB->where('id_pqr','=',$post['idPqr']);
			$respuestaRechazar = $this->DB->update([
				'status' => 4
			]);
			if($respuestaRechazar == true) {
				$this->DB->table('pqr');
				$this->DB->where('id','=',$post['idPqr']);
				$respuestaPqr = $this->DB->update([
					'status' => 1,
					'estado' => 'rechazado'
				]);
				if($respuestaPqr == true) {
					$this->DB->table('pqr_rechazado');
					$respuestaspuestaPqrRechazar = $this->DB->insert([
						'id_pqr' 				=> $post['idPqr'],
						'fecha'  				=> 'CURRENT_TIMESTAMP',
						'mensaje'			 	=> $post['razon'],
						'random_pqr_respuesta'  => $post['randomPqr']
					]);
					if ($respuestaspuestaPqrRechazar == true) {
						echo json_encode(array('status' => 1,'respuesta' => 'Se ha registrado correctamente'));
					} else {
						echo json_encode(array('status' => 0,'error' => 'Ha ocurrido un error al editar:' . $respuestaspuestaPqrRechazar));	
					}	
				} else {
					echo json_encode(array('status' => 0,'error' => 'Ha ocurrido un error al editar:' . $respuestaPqr));
				} 
			} else {
				echo json_encode(array('status' => 0,'error' => 'Ha ocurrido un error al editar:' . $respuestaRechazar));
			}
		} catch (Exception $e) {
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		} finally {
			$this->DB = null;
		}
	}

	private function archivosDelPqr($get)
	{
		try {
			$this->DBinstancia();
			$datosId = $get;
			$this->DB->table('rutas_respuestas_archivos');
			$this->DB->join([
				["pqr_respuesta_usuario","rutas_respuestas_archivos.id_pqr_respuesta","=","pqr_respuesta_usuario.id"],
				["pqrsolucion_archivos","rutas_respuestas_archivos.ruta_archivo","=","pqrsolucion_archivos.id"]
			]);
			$this->DB->where('pqr_respuesta_usuario.id_pqr','=',$datosId);
			$this->DB->select(
				'rutas_respuestas_archivos.*,
				pqr_respuesta_usuario.mensaje,
				pqr_respuesta_usuario.fecha,
				pqrsolucion_archivos.ruta,
				pqrsolucion_archivos.nombre
				'
			);
			return $datos = $this->DB->get();
		} catch (Exception $e) {
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}

	public function ultimaRespuestaUsuario($get)
	{
		try 
		{
			$this->DBinstancia();
			$datosId = base64_decode($get['pqrUltima']);
			$random = base64_decode($get['rand']);
			$this->DB->table('pqr_respuesta_usuario');
			$this->DB->where('id_pqr','=',$datosId);
			$this->DB->select('*');
			$datos 	  = $this->DB->get();
			$archivos = $this->archivosDelPqr($datosId);
			if (!isset($datos['ejecutado'])) {
				$res = Vista_clase::vista('ultima_respuesta_usuario_vista',array(
					"sesion"   => true,
					"tipo"     => 'novacio',
					"datos"    => $datos,
					"archivos" => $archivos
				));
			} else {
				$res = Vista_clase::vista('ultima_respuesta_usuario_vista',array(
					"sesion" => true,
					"tipo"   => 'vacio'
				));
			}
		} catch (Exception $e) 
		{
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}finally
		{
			$this->DB = null;
			$this->conexion = null;
		}
	}

	public function insertarVisualizacionGeneral($post)
	{
		try {
			$navegador = $this->navegadorActual($_SERVER['HTTP_USER_AGENT']);
			$IP = $_SERVER['REMOTE_ADDR'];
			/*$this->DBinstancia();
			$this->DB->table('pqr');
			$this->DB->where('identidicacion','=',$post['codigo']);
			$this->DB->select('id');
			$id = $this->DB->get();
			if (!isset($id['ejecutado'])) {
				$id = json_decode($id,true);
				$id = $id[0]['id'];
				$this->DB->table('visualizacion_todas_respuestas_usario');
				$respuesta = $this->DB->insert([
					'id_pqr' 		      => $id,
					'ip'                  => $IP,
					'navegador'           => $navegador,
					'fecha_visualizacion' => 'CURRENT_TIMESTAMP'
				]);
				print_r($respuesta);
			}*/
			$this->DBinstancia();
			$this->DB->table('pqr_respuesta_usuario');
			$this->DB->where('id_pqr','=',$post['codigo']);
			$this->DB->orderBy('id','DESC');
			$this->DB->select('*');
			$info = $this->DB->get();
			$id = json_decode($info,true);
			$info = json_decode($info,true);
			if (!isset($info['ejecutado'])) {
				if ($info[0]['tipo'] == 1) {
					$this->DB->table('pqr_respuesta_usuario');
					$respuesta = $this->DB->insert([
						'id_pqr'      	 	  => $post['codigo'],
						'id_usuario'   		  => $info[0]['id_usuario'],
						'mensaje'      		  => $info[0]['mensaje'],
						'fecha' 	   		  => $info[0]['fecha'],
						'random' 	   	      => $info[0]['random'],
						'visibilidad_usuario' => $info[0]['visibilidad_usuario'],
						'ip'                  => $info[0]['ip'],
						'navegador'           => $info[0]['navegador'],
						'fecha_visualizacion' => $info[0]['fecha_visualizacion'],
						'tipo'  	   		  => 1,
						'ip_vi' 	   		  => $IP,
						'navegador_vi' 		  => $navegador,
						'fecha_vi'         	  => 'CURRENT_TIMESTAMP'
					]);
				} else {
					$id = $id[0]['id'];
					$this->DB->table('pqr_respuesta_usuario');
					$this->DB->where('id','=',$id);
					$respuesta = $this->DB->update([
						'tipo'  	   => 1,
						'ip_vi' 	   => $IP,
						'navegador_vi' => $navegador,
						'fecha_vi'     => 'CURRENT_TIMESTAMP'
					]);
					print_r($respuesta);
				}
			}
		} catch (Exception $e) {
			die('Excepción capturada: ' . $e->getMessage() . "\n");	
		}	
	}
}
new respuestaUsuario();


