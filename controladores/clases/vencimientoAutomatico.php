<?php
require __DIR__ . '/../../modelos/conexion.php';
require __DIR__ . '/../../PHPMailer/PHPMailerAutoload.php';
/**
 * 
 */
class VencimientoAutomatico
{

	private $mensaje = "Se ha vencido el PQR";
	private $ahora;
	private $dia;
	private $mes;
	private $fin;
	private $feriado = false;

	public function __construct()
	{
		try {
			$this->diasExcp();
			$this->vencimientoAreaPeticion();
			$this->vencimientoAreaQueja();
			$this->vencimientoAreaReclamo();
		} catch (Exception $e) {
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}

	private function diasExcp()
	{
		try {
			$this->ahora = date('Y-m-d H:i:s');
			$this->dia   = date("j");
			$this->mes   = date("m");
			$this->fin   = date("D");
			$feriados = $this->feriados();
			for ($i=0; $i < count($feriados); $i++) { 
				if ($this->mes === $feriados[$i]['meses'] && $this->dia === $feriados[$i]['dias']) {
					$this->feriado = true;
				}
			}
		} catch (Exception $e) {
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}

	private function vencimientoAreaPeticion()
	{
		try {
			$this->conexion = new conexion();
			$pqrVencido = "SELECT tipo_pqr_vencimiento_area.*,
				pqr_filtrado.id_pqr,
				pqr_filtrado.fecha
				FROM tipo_pqr_vencimiento_area
				INNER JOIN pqr_filtrado ON tipo_pqr_vencimiento_area.id_area = pqr_filtrado.id_area
				WHERE tipo_pqr_vencimiento_area.tipo = 'peticion' AND pqr_filtrado.status <> 3 AND pqr_filtrado.id_tipo = 1";
			$retornoSql = $this->conexion->consulQuery($pqrVencido);
			if ($retornoSql['resultado']->execute()) {
				$datos = $retornoSql['resultado']->fetchAll();
				$this->verificarFecha($datos);
				$this->verificarFechaRecordatorio($datos);
			} else {
				echo json_encode($retornoSql['resultado']->errorInfo());
			}
		} catch (Exception $e) {
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}

	private function vencimientoAreaQueja()
	{
		try {
			$this->conexion = new conexion();
			$pqrVencido = "SELECT tipo_pqr_vencimiento_area.*,
				pqr_filtrado.id_pqr,
				pqr_filtrado.fecha
				FROM tipo_pqr_vencimiento_area
				INNER JOIN pqr_filtrado ON tipo_pqr_vencimiento_area.id_area = pqr_filtrado.id_area
				WHERE tipo_pqr_vencimiento_area.tipo = 'queja' AND pqr_filtrado.status <> 3 AND pqr_filtrado.id_tipo = 2";
			$retornoSql = $this->conexion->consulQuery($pqrVencido);
			if ($retornoSql['resultado']->execute()) {
				$datos = $retornoSql['resultado']->fetchAll();
				$this->verificarFecha($datos);
				$this->verificarFechaRecordatorio($datos);
			} else {
				echo json_encode($retornoSql['resultado']->errorInfo());
			}
		} catch (Exception $e) {
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}

	private function vencimientoAreaReclamo()
	{
		try {
			$this->conexion = new conexion();
			$pqrVencido = "SELECT tipo_pqr_vencimiento_area.*,
				pqr_filtrado.id_pqr,
				pqr_filtrado.fecha
				FROM tipo_pqr_vencimiento_area
				INNER JOIN pqr_filtrado ON tipo_pqr_vencimiento_area.id_area = pqr_filtrado.id_area
				WHERE tipo_pqr_vencimiento_area.tipo = 'reclamo' AND pqr_filtrado.status <> 3 AND pqr_filtrado.id_tipo = 3";
			$retornoSql = $this->conexion->consulQuery($pqrVencido);
			if ($retornoSql['resultado']->execute()) {
				$datos = $retornoSql['resultado']->fetchAll();
				$this->verificarFecha($datos);
				$this->verificarFechaRecordatorio($datos);
			} else {
				echo json_encode($retornoSql['resultado']->errorInfo());
			}
		} catch (Exception $e) {
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}


	private function feriados()
	{
		try {
			$this->conexion = new conexion();
			$sql = "SELECT * FROM feriados";
			$retornoSql = $this->conexion->consulQuery($sql);
			if ($retornoSql['resultado']->execute()) {
				$datos = $retornoSql['resultado']->fetchAll();
				return $datos;
			} else {
				echo json_encode($retornoSql['resultado']->errorInfo());
			}
		} catch (Exception $e) {
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}

	private function verificarFecha($datos)
	{
		try {
			$this->conexion = new conexion();
			for ($i=0; $i < count($datos); $i++) { 
				if ($this->feriado === true || $this->fin == 'Sat' || $this->fin == "Sun") {
					$datos[$i]['dias_vencimiento'] = $datos[$i]['dias_vencimiento'] + 1;
					$fechaDiasSumada = date("Y-m-d H:i:s",strtotime($datos[$i]['fecha']."+ ".$datos[$i]['dias_vencimiento']." days"));
				} else {
					$fechaDiasSumada = date("Y-m-d H:i:s",strtotime($datos[$i]['fecha']."+ ".$datos[$i]['dias_vencimiento']." days"));
				}
				if ($this->ahora > $fechaDiasSumada) {
					$this->vencimientos($datos);
				} else {
					echo 'Se ha ejecutado los que no han cumplido el vencimiento' . "\n";
				}
			}
		} catch (Exception $e) {
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}

	private function verificarFechaRecordatorio($datos)
	{
		try {
			$this->conexion = new conexion();
			for ($i=0; $i < count($datos); $i++) {
				if ($this->feriado === true || $this->fin == 'Sat' || $this->fin == "Sun") {
					$datos[$i]['dias_recordatorio'] = $datos[$i]['dias_recordatorio'] + 1;
					$fechaDiasSumada = date("Y-m-d H:i:s",strtotime($datos[$i]['fecha']."+ ".$datos[$i]['dias_recordatorio']." days"));
				} else {
					$fechaDiasSumada = date("Y-m-d H:i:s",strtotime($datos[$i]['fecha']."+ ".$datos[$i]['dias_recordatorio']." days"));
				}
				if ($this->ahora > $fechaDiasSumada) {
					$sql_usuarios = "SELECT email FROM usuarios WHERE rol = 2";
					$retorno_usuarios = $this->conexion->consulQuery($sql_usuarios);
					if ($retorno_usuarios['resultado']->execute()) {
						$datosUsuario = $retorno_usuarios['resultado']->fetch(PDO::FETCH_ASSOC);
						for ($i=0; $i < count($datos); $i++) { 
							$this->envioDeMailRecordatorio($datosUsuario,$datos[$i]['id_pqr']);
						}
					} else {
						echo json_encode($retornoUsuarios['resultado']->errorInfo());
					} 
				}
			}
		} catch (Exception $e) {
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}

	private function vencimientos($datos)
	{
		try {
			for ($i=0; $i < count($datos); $i++) { 
				$sqlMensaje = "SELECT * FROM pqr_solucion WHERE id_pqr = :idPqr";
				$retornoMensaje = $this->conexion->consulQuery($sqlMensaje);
				$retornoMensaje['resultado']->bindParam(':idPqr',$datos[$i]['id_pqr'],PDO::PARAM_INT);
				if ($retornoMensaje['resultado']->execute()) {
					$datosPqrSolucion = $retornoMensaje['resultado']->fetchAll();
					if (!count($datosPqrSolucion) > 0 ) {
						$sqlEdicion = "UPDATE pqr SET estado = 'vencido' WHERE id = :idPqr";
						$retornoEdicionVencimiento = $this->conexion->consulQuery($sqlEdicion);
						$retornoEdicionVencimiento['resultado']->bindParam(':idPqr',$datos[$i]['id_pqr'],PDO::PARAM_INT);
						if ($retornoEdicionVencimiento['resultado']->execute()) {
							$sqlConsultaSolucionados = "SELECT mensaje FROM pqr_solucion WHERE mensaje = :mensajeSolucionado AND id_pqr = :idPqr";
							$retornoConsultaSolucionados = $this->conexion->consulQuery($sqlConsultaSolucionados);
							$retornoConsultaSolucionados['resultado']->bindParam(':mensajeSolucionado',$this->mensaje,PDO::PARAM_STR);
							$retornoConsultaSolucionados['resultado']->bindParam(':idPqr',$datos[$i]['id_pqr'],PDO::PARAM_STR);
							if ($retornoConsultaSolucionados['resultado']->execute()) {
								$sqlTrasabilidad = "INSERT INTO pqr_solucion (id_pqr,id_usuario,id_area,mensaje,fecha) VALUES (:idPqr,2,0,:mensaje,CURRENT_TIMESTAMP)";
								$retornoTrasabilidad = $this->conexion->consulQuery($sqlTrasabilidad);
								$retornoTrasabilidad['resultado']->bindParam(':idPqr',$datos[$i]['id_pqr'],PDO::PARAM_INT);
								$retornoTrasabilidad['resultado']->bindParam(':mensaje',$this->mensaje,PDO::PARAM_STR);
								if ($retornoTrasabilidad['resultado']->execute()) {
									$sqlUsuarios = "SELECT email FROM usuarios WHERE rol = 2 OR rol = 3";
									$retornoUsuarios = $this->conexion->consulQuery($sqlUsuarios);
									if ($retornoUsuarios['resultado']->execute()) {
										$datosUsuario = $retornoUsuarios['resultado']->fetchAll();
										$this->envioDeMail($datosUsuario,$datos[$i]['id_pqr']);
									} else {
										echo json_encode($retornoUsuarios['resultado']->errorInfo());
									}
								} else {
									echo json_encode($retornoTrasabilidad['resultado']->errorInfo());
								}
							} else {
								echo json_encode($retornoConsultaSolucionados['resultado']->errorInfo());
							}
						} else {
							echo json_encode($retornoEdicionVencimiento['resultado']->errorInfo());
						}
					}
				} else {
					echo json_encode($retornoMensaje['resultado']->errorInfo());
				}
			}
		} catch (Exception $e) {
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}

	private function mensaje($idPqr)
	{
		try {
			return '
				<div>
					<center>
						<img height="80" src="https://www.ropsohn.com.co/images/IDENTIFICADOR_ROPSON-04.jpg" style="border:0" class="CToWUd">
						<br>
						<br>
						<hr style="border-top:solid 1px #dbdbdb;width:300px;" >
						<p style="width:300px;padding:0;margin:0;text-align:center;color:#262626;font-size:18px;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif" >
							PQR vencido
						</p>
						<p style="width:300px;padding:0;margin:0;text-align:center;color:#999999;font-size:14px;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif">
							Hola, Se ha vencido el PQR con numero de registro:
						</p>
						<br>
						<br>
						<span class="m_-2429985624185834423m_-923009222503390390btn-content" style="background-color:#2c9ce9;border-radius:4px;display:inline-block">
							<a href="#" style="color:#fff;display:inline-block;font-family:Roboto,Helvetica,Arial,sans-serif;font-size:16px;line-height:1.4;text-align:center;text-decoration:none;min-width:115px;padding:8px 16px;font-weight:500" target="_blank">
						<span style="color:#fff">'.$idPqr.'</span></a></span>
						<br>
						<br>
						<hr style="width:310px;border-top:solid 1px #dbdbdb;" >
						<p style="width:300px;padding:0;margin:0;text-align:center;color:#262626;font-size:14px;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif" >
							Verifica el estado de el PQR en la plataforma.
							<a style="text-decoration: none" href="https://pruebas.zk.com.co/" > Sistema PQR</a>
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
		} catch (Exception $e) {
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}

	private function envioDeMail($datosUsuario,$idPqr)
	{
		try {
			for ($i=0; $i < count($datosUsuario); $i++) { 
				$mail = new PHPMailer;          
				$mail->isSMTP();                                      
				$mail->Host = 'zk.com.co'; 
				$mail->SMTPAuth = true;                               
				$mail->Username = 'no-responder@zk.com.co';                
				$mail->Password = 'RB60Ne^2UOr)';  
				$mail->SMTPSecure = 'ssl';
				$mail->Port = 465;
				$mail->setFrom('no-responder@zk.com.co','ZK Grandes Soluciones');
				$mail->addAddress($datosUsuario[$i]['email'], $datosUsuario[$i]['email']);
				$mail->isHTML(true);
				$mail->Subject = 'PQR Vencido';
				$mail->Body = $this->mensaje($idPqr);
				$mail->AltBody = 'Saludos ZK';
				if(!$mail->send()) {
					echo 'Message could not be sent.';
					echo 'Mailer Error: ' . $mail->ErrorInfo;
				} else {
					echo "enviado\n";
				}
			}
		} catch (Exception $e) {
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}

	private function recordatorioMensaje($idPqr)
	{
		try {
			return '
				<div>
					<center>
						<img height="80" src="https://www.ropsohn.com.co/images/IDENTIFICADOR_ROPSON-04.jpg" style="border:0" class="CToWUd">
						<br>
						<br>
						<hr style="border-top:solid 1px #dbdbdb;width:300px;" >
						<p style="width:300px;padding:0;margin:0;text-align:center;color:#262626;font-size:18px;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif" >
							Recordatorio codigo PQR
						</p>
						<p style="width:300px;padding:0;margin:0;text-align:center;color:#999999;font-size:14px;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif">
							Hola, Recuerda solucionar el PQR con numero de registro
						</p>
						<br />
						<br />
						<span class="m_-2429985624185834423m_-923009222503390390btn-content" style="background-color:#2c9ce9;border-radius:4px;display:inline-block">
							<a href="#" style="color:#fff;display:inline-block;font-family:Roboto,Helvetica,Arial,sans-serif;font-size:16px;line-height:1.4;text-align:center;text-decoration:none;min-width:115px;padding:8px 16px;font-weight:500" target="_blank">
								<span style="color:#fff">'.$idPqr.'</span>
							</a>
						</span>
						<br />
						<br />
						<hr style="width:310px;border-top:solid 1px #dbdbdb;" />
						<p style="width:300px;padding:0;margin:0;text-align:center;color:#262626;font-size:14px;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif" >
							Verifica el estado de el PQR en nuestra plataforma. 
							<a style="text-decoration: none" href="https://pruebas.zk.com.co/" > Sistema PQR</a>
						</p>
						<p style="width:300px;padding:0;margin:0;text-align:center;color:#999999;font-size:14px;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif" >
							<br />
							<br />
							<strong>© Desarrollo - ZK SAS.</strong>
						</p>
					</center>
				</div>
			';
		} catch (Exception $e) {
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}

	private function envioDeMailRecordatorio($datosUsuario,$idPqr)
	{
		try {
			for ($i=0; $i < count($datosUsuario); $i++) { 
				$mail = new PHPMailer;
				//$mail->SMTPDebug = 3;                               // Enable verbose debug output
				$mail->isSMTP();                                      // Set mailer to use SMTP
				$mail->Host = 'zk.com.co';  // Specify main and backup SMTP servers
				$mail->SMTPAuth = true;                               // Enable SMTP authentication
				$mail->Username = 'no-responder@zk.com.co';                 // SMTP username
				$mail->Password = 'RB60Ne^2UOr)';                           // SMTP password
				$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
				$mail->Port = 465;                                    // TCP port to connect to
				$mail->setFrom('no-responder@zk.com.co','ZK Grandes Soluciones');
				$mail->addAddress($datosUsuario['email'], $datosUsuario['email']);     // Add a recipient
				$mail->isHTML(true);                                  // Set email format to HTML
				$mail->Subject = 'Recordatorio PQR';
				$mail->Body = $this->recordatorioMensaje($idPqr);
				$mail->AltBody = 'Saludos ZK';
				if(!$mail->send()) {
					echo 'Message could not be sent.';
					echo 'Mailer Error: ' . $mail->ErrorInfo;
				} else {
					echo "enviado\n";
				}
			}
		} catch (Exception $e) {
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}
	
}

new VencimientoAutomatico();