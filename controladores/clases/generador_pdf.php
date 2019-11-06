<?php
/**
 *
 */
include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/consultas/consultasCrud.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/consultas/querys.php';
require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;

class generadorPdf
{
    private $querys;
    private $consulta;

    function __construct()
    {
        try {
            if (isset($_GET)) {
                $this->generarVista($_GET);
            }
        } catch (Exception $e) {
            echo 'Excepción capturada: ', $e->getMessage(), "\n";
        }
    }

    private function generarVista($get)
    {
        try {
            if (isset($get['pqrcerrado'])) {
                $datos = base64_decode($get['pqrcerrado']);
            } else {
                $datos = base64_decode($get['pqrproceso']);
            }
            $this->querys = new Querys();
			$this->consulta = new consultasCrud();
			$solucion = $this->querys->consultarQuery('pqr_solucion', $datos);
			$respuestaFinal = $this->querys->consultarQuery('pqr_cerrado_general', $datos);
			$resultadoLocalFiltro = $this->querys->consultarQuery('pqr_filtrado', $datos);
			$respuestaUsuario = $this->querys->consultarQuery('pqr_respuesta_usuario',$datos);
			$respuestas 	  = $this->querys->consultarQuery('respuestas_para_usuario',$datos);
			$resultadoLocal = $this->querys->consultarQuery('pqr', $datos);
			$respuestaRechazado = $this->querys->consultarQuery('pqr_rechazado',$datos);
			$consultaPlataforma = $this->querys->consultarQuery('consultaPlataforma',$datos);
			$pqr_gestionadores = $this->querys->consultarQuery('pqr_gestionadores_indi',$datos);
			$archivos = $this->querys->consultarQuery('pqr_solucion_imagenes', $datos);
            if (isset($get['pqrcerrado'])) {
                $html = '
                	<page backtop="7mm" backbottom="7mm" backleft="10mm" backright="10mm" >
                		<div align="center">
							<img height="80" src="../../archivos/rop.jpg" style="border:0" class="CToWUd">
						</div>
						<br />
						<div align="center">
							<strong><h4>INFORMACIÓN DEL SOLICITANTE</h4></strong>
						</div>
						<br />
						<table>
							<thead>
					  			<tr>
					  				<th style="padding-left:0px;" >Nombre</th>
					  				<th style="padding-left:20px;">Documento</th>
					  				<th style="padding-left:20px;">Email</th>
					  				<th style="padding-left:20px;">Ciudad</th>
					  				<th style="padding-left:20px;">Celular</th>
					  				<th style="padding-left:20px;">Celular</th>
					  			</tr>
				  			</thead>
				  			<tbody>
				  				<tr>
				  					<td style="padding-left:0px;">' . ucwords($resultadoLocal[0][2]) . '</td>';
					                if ($resultadoLocal[0][3] == 0 && $resultadoLocal[0][4] == 0) {
					                    $html .= '<td style="padding-left:20px;">' . $resultadoLocal[0][5] . '</td>';
					                } else {
					                    $html .= '<td style="padding-left:20px;">' . $resultadoLocal[0][3] . '-' . $resultadoLocal[0][4] . '</td>';
					                }
                	  $html .= '<td style="padding-left:20px;">' . $resultadoLocal[0][6] . '</td>
				  				<td style="padding-left:20px;">' . $resultadoLocal[0][7] . '</td>
				  				<td style="padding-left:20px;">' . $resultadoLocal[0][8] . '</td>
				  				<td style="padding-left:20px;">' . $resultadoLocal[0][9] . '</td>
				  				</tr>
				  			</tbody>
						</table>
						<br />
				  		<hr style="height:1px;" />
				  		<br />
				  		<div align="center">
							<strong><h4>PQR FILTRADO</h4></strong>
						</div>
						<br />
						<table>
							<thead>
								<tr>
									<th style="padding-left:20px;">Usuario</th>
									<th style="padding-left:20px;">Distrito</th>
									<th style="padding-left:20px;">Area</th>
									<th style="padding-left:20px;">Fecha de filtrado</th>
									<th style="padding-left:40px;">Tipo de PQR</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td style="padding-left:20px;">' . $resultadoLocalFiltro[0][2] . '</td>
									<td style="padding-left:20px;">' . $resultadoLocalFiltro[0][3] . '</td>
									<td style="padding-left:20px;">' . $resultadoLocalFiltro[0][5] . '</td>
									<td style="padding-left:20px;">' . $resultadoLocalFiltro[0][1] . '</td>
									<td style="padding-left:40px;">' . $resultadoLocalFiltro[0][6] . '</td>
								</tr>
							</tbody>
						</table>
						<br />
					  	<hr style="height:1px;" />
					  	<br />';
					  	if (count($pqr_gestionadores) > 0) {
					  		for ($i=0; $i <count($pqr_gestionadores); $i++) {
					  			$html .= '
					  				<div align="center" >
					  					<strong><h4>ESCALAMIENTOS DEL PQR</h4></strong>
					  				</div>
					  				<table>
					  					<thead>
					  						<tr>
					  							<th style="padding-left:20px;">Usuario quien escala</th>
					  							<th style="padding-left:20px;">Usuario que recibe el PQR</th>
					  							<th style="padding-left:20px;">Fecha del escalamiento</th>
					  							<th style="padding-left:20px;">Escalamiento</th>
					  						</tr>
					  					</thead>
					  					<tbody>
					  						<tr>
					  							<td style="padding-left:20px;">'.$pqr_gestionadores[$i]['usuarioAfecta'].'</td>
					  							<td style="padding-left:20px;">'.$pqr_gestionadores[$i]['nombre_usuario'].'</td>
					  							<td style="padding-left:20px;">'.$pqr_gestionadores[$i]['fecha'].'</td>
					  							<td style="padding-left:20px;">Escalamiento</td>
					  						</tr>
					  					</tbody>
					  				</table>
					  			';
					  		}
					  	}
					  	if (count($solucion) > 0) {
					  		for ($i = 0; $i < count($solucion); $i++) {
					  			$html .= '
					  				<div align="center">
										<strong><h4>SOLUCIONES DEL PQR</h4></strong>
									</div>
									<table>
										<thead>
											<tr>
												<th style="padding-left:40px;">PQR</th>
												<th style="padding-left:40px;">Respuesta por parte de</th>
												<th style="padding-left:40px;">Fecha</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td style="padding-left:40px;">' . $solucion[$i]['identidicacion'] . '</td>
												<td style="padding-left:40px;">' . $solucion[$i]['nombre_usuario'] . '</td>
												<td style="padding-left:40px;">' . $solucion[$i]['fecha'] . '</td>
											</tr>
										</tbody>
									</table>
									<br />
									<div style="padding-left:40px;">
										<strong>Descripción de la solución</strong>
										<p>' . $solucion[$i]['mensaje'] . '</p>
									</div>
									<br />
					  			';
					  			for ($e=0; $e < count($archivos) ; $e++) {
									if (!empty($archivos)) { 
										if ($solucion[$i]['random'] == $archivos[$e]['random']) {
											$html .= '
												<div style="padding-left:40px;">
													<label><strong>Archivos</strong></label><br>
												</div>
												<a href="' . $archivos[$e][2] . '" >'.$archivos[$e][0] ."__". $archivos[$e][3] . '</a><br>
											';
										}
									} 
								}
						  	}
						  	if (count($respuestaUsuario) > 0) {
						  		for ($i = 0; $i < count($respuestaUsuario); $i++) {
						  			$html .= '
										<br />
										<hr style="height:1px;" />
										<div align="center">
											<strong><h4>RESPUESTAS A USUARIO</h4></strong>
										</div>
										<table>
											<thead>
												<tr>
													<th style="padding-left:150px;">Usuario</th>
													<th style="padding-left:150px;">Fecha del mensaje</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td style="padding-left:150px;">'.$respuestaUsuario[$i]['nombre_usuario'].'</td>
													<td style="padding-left:150px;">'.$respuestaUsuario[$i]['fecha'].'</td>
												</tr>
											</tbody>
										</table>
										<br />
										<div style="padding-left:40px;">
											<strong>Mensaje</strong>
											<p>' . $respuestaUsuario[$i]['mensaje'] . '</p>
										</div>
									';
									for ($f=0; $f < count($respuestas); $f++) {
										if ($respuestas[$f]['random'] == $respuestaUsuario[$i]['random']) {
											$html .= '
												<br />
												<hr />
												<div align="center" >
													<strong><h4>VISUALIZACIÓN DEL MENSAJE</h4></strong>
												</div>
												<table>
													<thead>
														<tr>
															<th style="padding-left:60px;">IP</th>
															<th style="padding-left:60px;">Fecha de la visualización</th>
															<th style="padding-left:60px;">Navegador</th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td style="padding-left:60px;">'.$respuestas[$f]['ip'].'</td>
															<td style="padding-left:60px;">'.$respuestas[$f]['fecha_visualizacion'].'</td>
															<td style="padding-left:60px;">'.$respuestas[$f]['navegador'].'</td>
														</tr>
													</tbody>
												</table>
											';
										}
									}
									for ($j=0; $j < count($consultaPlataforma); $j++) {
										if ($consultaPlataforma[$j]['id_pqr'] == $respuestaUsuario[$i]['id_pqr']) {
											$html .= '
												<br />
												<hr />
												<div align="center" >
													<strong><h4>CONSULTA DESDE PLATAFORMA</h4></strong>
												</div>
												<table>
													<thead>
														<tr>
															<th style="padding-left:60px;">IP</th>
															<th style="padding-left:60px;">Fecha de la visualización</th>
															<th style="padding-left:60px;">Navegador</th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td style="padding-left:60px;">'.$consultaPlataforma[$j]['ip_vi'].'</td>
															<td style="padding-left:60px;">'.$consultaPlataforma[$j]['fecha_vi'].'</td>
															<td style="padding-left:60px;">'.$consultaPlataforma[$j]['navegador_vi'].'</td>
														</tr>
													</tbody>
												</table>
											';
										}
									}
									for ($n=0; $n < count($respuestaRechazado); $n++) {
										if ($respuestaRechazado[$n]['random_pqr_respuesta'] == $respuestaUsuario[$i]['random']) {
											$html .= '
												<br />
												<hr />
												<div align="center" >
													<strong><h4>PQR RECHAZADO</h4></strong>
												</div>
												<table>
													<thead>
														<tr>
															<th style="padding-left:150px;">Fecha</th>
															<th style="padding-left:150px;">Tipo</th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td style="padding-left:150px;">'.$respuestaRechazado[$n]['fecha'].'</td>
															<td style="padding-left:150px;">PQR rechazado por el solicitante</td>
														</tr>
													</tbody>
												</table>
												<div style="padding-left:40px;" >
													<strong>Mensaje</strong>
													<p>'.$respuestaRechazado[$n]['mensaje'].'</p>
												</div>
											';
										}
									}
						  		}
						  	}
					  	}  	
            $html .= '
            			<br />
						<br />
						<hr  />
						<div align="center" >
							<strong><h4>PQR CERRADO</h4></strong>
						</div> 
						<table>
							<thead>
								<tr>
									<th style="padding-left:40px;">PQR</th>
									<th style="padding-left:40px;">Usuario</th>
									<th style="padding-left:40px;">Fecha</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td style="padding-left:40px;">'.$solucion[0]['identidicacion'].'</td>';
									if (is_null($respuestaFinal[0]['nombre_usuario'])) {
										$html .= '<td style="padding-left:40px;">Sistema</td>';
									} else {
										$html .= '<td style="padding-left:40px;">'.$respuestaFinal[0]['nombre_usuario'].'</td>';
									}
						$html.='
									<td style="padding-left:40px;">'.$respuestaFinal[0]['fecha'].'</td>
								</tr>
							</tbody>
						</table>
						<div style="padding-left:40px;" >
							<p><strong>' . $respuestaFinal[0]['mensaje'] . '</strong></p>
						</div>	
            		</page>
                ';
            } else {
               $html = '
               		<page backtop="7mm" backbottom="7mm" backleft="10mm" backright="10mm">
               			<div align="center">
							<img height="80" src="../../archivos/rop.jpg" style="border:0" class="CToWUd">
						</div>
						<br />
						<div align="center">
							<strong><h4>INFORMACIÓN DEL SOLICITANTE</h4></strong>
						</div>
						<br />
						<table>
							<thead>
					  			<tr>
					  				<th style="padding-left:0px;" >Nombre</th>
					  				<th style="padding-left:20px;">Documento</th>
					  				<th style="padding-left:20px;">Email</th>
					  				<th style="padding-left:20px;">Ciudad</th>
					  				<th style="padding-left:20px;">Celular</th>
					  				<th style="padding-left:20px;">Celular</th>
					  			</tr>
				  			</thead>
				  			<tbody>
				  				<tr>
				  					<td style="padding-left:0px;">' . ucwords($resultadoLocal[0][2]) . '</td>';
					                if ($resultadoLocal[0][3] == 0 && $resultadoLocal[0][4] == 0) {
					                    $html .= '<td style="padding-left:20px;">' . $resultadoLocal[0][5] . '</td>';
					                } else {
					                    $html .= '<td style="padding-left:20px;">' . $resultadoLocal[0][3] . '-' . $resultadoLocal[0][4] . '</td>';
					                }
                	  $html .= '<td style="padding-left:20px;">' . $resultadoLocal[0][6] . '</td>
				  				<td style="padding-left:20px;">' . $resultadoLocal[0][7] . '</td>
				  				<td style="padding-left:20px;">' . $resultadoLocal[0][8] . '</td>
				  				<td style="padding-left:20px;">' . $resultadoLocal[0][9] . '</td>
				  				</tr>
				  			</tbody>
						</table>
						<br />
				  		<hr style="height:1px;" />
				  		<br />
				  		<div align="center">
							<strong><h4>PQR FILTRADO</h4></strong>
						</div>
						<br />
						<table>
							<thead>
								<tr>
									<th style="padding-left:20px;">Usuario</th>
									<th style="padding-left:20px;">Distrito</th>
									<th style="padding-left:20px;">Area</th>
									<th style="padding-left:20px;">Fecha de filtrado</th>
									<th style="padding-left:40px;">Tipo de PQR</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td style="padding-left:20px;">' . $resultadoLocalFiltro[0][2] . '</td>
									<td style="padding-left:20px;">' . $resultadoLocalFiltro[0][3] . '</td>
									<td style="padding-left:20px;">' . $resultadoLocalFiltro[0][5] . '</td>
									<td style="padding-left:20px;">' . $resultadoLocalFiltro[0][1] . '</td>
									<td style="padding-left:40px;">' . $resultadoLocalFiltro[0][6] . '</td>
								</tr>
							</tbody>
						</table>
						<br />
					  	<hr style="height:1px;" />
					  	<br />';
					  	if (count($pqr_gestionadores) > 0) {
					  		for ($i=0; $i <count($pqr_gestionadores); $i++) {
					  			$html .= '
					  				<div align="center" >
					  					<strong><h4>ESCALAMIENTOS DEL PQR</h4></strong>
					  				</div>
					  				<table>
					  					<thead>
					  						<tr>
					  							<th style="padding-left:20px;">Usuario quien escala</th>
					  							<th style="padding-left:20px;">Usuario que recibe el PQR</th>
					  							<th style="padding-left:20px;">Fecha del escalamiento</th>
					  							<th style="padding-left:20px;">Escalamiento</th>
					  						</tr>
					  					</thead>
					  					<tbody>
					  						<tr>
					  							<td style="padding-left:20px;">'.$pqr_gestionadores[$i]['usuarioAfecta'].'</td>
					  							<td style="padding-left:20px;">'.$pqr_gestionadores[$i]['nombre_usuario'].'</td>
					  							<td style="padding-left:20px;">'.$pqr_gestionadores[$i]['fecha'].'</td>
					  							<td style="padding-left:20px;">Escalamiento</td>
					  						</tr>
					  					</tbody>
					  				</table>
					  			';
					  		}
					  	}
					  	if (count($solucion) > 0) {
					  		for ($i = 0; $i < count($solucion); $i++) {
					  			$html .= '
					  				<div align="center">
										<strong><h4>SOLUCIONES DEL PQR</h4></strong>
									</div>
									<table>
										<thead>
											<tr>
												<th style="padding-left:40px;">PQR</th>
												<th style="padding-left:40px;">Respuesta por parte de</th>
												<th style="padding-left:40px;">Fecha</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td style="padding-left:40px;">' . $solucion[$i]['identidicacion'] . '</td>
												<td style="padding-left:40px;">' . $solucion[$i]['nombre_usuario'] . '</td>
												<td style="padding-left:40px;">' . $solucion[$i]['fecha'] . '</td>
											</tr>
										</tbody>
									</table>
									<br />
									<div style="padding-left:40px;">
										<strong>Descripción de la solución</strong>
										<p>' . $solucion[$i]['mensaje'] . '</p>
									</div>
									<br />
					  			';
					  			for ($e=0; $e < count($archivos) ; $e++) {
									if (!empty($archivos)) { 
										if ($solucion[$i]['random'] == $archivos[$e]['random']) {
											$html .= '
												<div style="padding-left:40px;">
													<label><strong>Archivos</strong></label><br>
												</div>
												<a href="' . $archivos[$e][2] . '" >'.$archivos[$e][0] ."__". $archivos[$e][3] . '</a><br>
											';
										}
									} 
								}
						  	}
						  	if (count($respuestaUsuario) > 0) {
						  		for ($i = 0; $i < count($respuestaUsuario); $i++) {
						  			$html .= '
										<br />
										<hr style="height:1px;" />
										<div align="center">
											<strong><h4>RESPUESTAS A USUARIO</h4></strong>
										</div>
										<table>
											<thead>
												<tr>
													<th style="padding-left:150px;">Usuario</th>
													<th style="padding-left:150px;">Fecha del mensaje</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td style="padding-left:150px;">'.$respuestaUsuario[$i]['nombre_usuario'].'</td>
													<td style="padding-left:150px;">'.$respuestaUsuario[$i]['fecha'].'</td>
												</tr>
											</tbody>
										</table>
										<br />
										<div style="padding-left:40px;">
											<strong>Mensaje</strong>
											<p>' . $respuestaUsuario[$i]['mensaje'] . '</p>
										</div>
									';
									for ($f=0; $f < count($respuestas); $f++) {
										if ($respuestas[$f]['random'] == $respuestaUsuario[$i]['random']) {
											$html .= '
												<br />
												<hr />
												<div align="center" >
													<strong><h4>VISUALIZACIÓN DEL MENSAJE</h4></strong>
												</div>
												<table>
													<thead>
														<tr>
															<th style="padding-left:60px;">IP</th>
															<th style="padding-left:60px;">Fecha de la visualización</th>
															<th style="padding-left:60px;">Navegador</th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td style="padding-left:60px;">'.$respuestas[$f]['ip'].'</td>
															<td style="padding-left:60px;">'.$respuestas[$f]['fecha_visualizacion'].'</td>
															<td style="padding-left:60px;">'.$respuestas[$f]['navegador'].'</td>
														</tr>
													</tbody>
												</table>
											';
										}
									}
									for ($j=0; $j < count($consultaPlataforma); $j++) {
										if ($consultaPlataforma[$j]['id_pqr'] == $respuestaUsuario[$i]['id_pqr']) {
											$html .= '
												<br />
												<hr />
												<div align="center" >
													<strong><h4>CONSULTA DESDE PLATAFORMA</h4></strong>
												</div>
												<table>
													<thead>
														<tr>
															<th style="padding-left:60px;">IP</th>
															<th style="padding-left:60px;">Fecha de la visualización</th>
															<th style="padding-left:60px;">Navegador</th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td style="padding-left:60px;">'.$consultaPlataforma[$j]['ip_vi'].'</td>
															<td style="padding-left:60px;">'.$consultaPlataforma[$j]['fecha_vi'].'</td>
															<td style="padding-left:60px;">'.$consultaPlataforma[$j]['navegador_vi'].'</td>
														</tr>
													</tbody>
												</table>
											';
										}
									}
									for ($n=0; $n < count($respuestaRechazado); $n++) {
										if ($respuestaRechazado[$n]['random_pqr_respuesta'] == $respuestaUsuario[$i]['random']) {
											$html .= '
												<br />
												<hr />
												<div align="center" >
													<strong><h4>PQR RECHAZADO</h4></strong>
												</div>
												<table>
													<thead>
														<tr>
															<th style="padding-left:150px;">Fecha</th>
															<th style="padding-left:150px;">Tipo</th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td style="padding-left:150px;">'.$respuestaRechazado[$n]['fecha'].'</td>
															<td style="padding-left:150px;">PQR rechazado por el solicitante</td>
														</tr>
													</tbody>
												</table>
												<div style="padding-left:40px;" >
													<strong>Mensaje</strong>
													<p>'.$respuestaRechazado[$n]['mensaje'].'</p>
												</div>
											';
										}
									}
						  		}
						  	}
					  	}  	

               	$html.='</page>
               ';
            }
            $this->generarPdf($html);
        } catch (Exception $e) {
            echo 'Excepción capturada: ', $e->getMessage(), "\n";
        }
    }

    private function generarPdf($html)
    {
        try {
            $html2pdf = new Html2Pdf('P', 'A4', 'es', 'true', 'UTF-8');
            $html2pdf->writeHTML($html);
            $html2pdf->setDefaultFont('Arial');
            $html2pdf->output();
        } catch (Exception $e) {
            echo 'Excepción capturada: ', $e->getMessage(), "\n";
        }
    }
}

new generadorPdf();