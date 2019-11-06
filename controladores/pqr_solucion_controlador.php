<?php
/**
 *
 */
include_once $_SERVER['DOCUMENT_ROOT'] . '/modelos/conexion.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/consultas/consultasCrud.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/consultas/querys.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/clases/plantilla_clase.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/clases/carga_archivos_clase.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/pqr_controlador.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/clases/envio_mails.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/clases/clasesQuery.php';

if (!isset($_SESSION)) {
	session_name('PQRUSER');
	session_start();
}
class pqrSolucion {

	private $plantilla;
	private $querys;
	private $conexion;
	private $consulta;
	private $DB;

	public function __construct() {
		if (isset($_GET['pqrfiltrado'])) {
			$this->escalarPqr($_GET);
		} elseif (isset($_POST['info'])) {
			$this->escalarDatoPqr($_POST);
		} elseif (isset($_GET['pqrsolu'])) {
			$this->solucionarpqr($_GET);
		} elseif (isset($_POST['solucion'])) {
			$this->solucionPqr($_POST, $_FILES);
		} elseif (isset($_GET['pqrcerr'])) {
			if ($_SESSION['rolUsuario'] == 1 || $_SESSION['rolUsuario'] == 2 || $_SESSION['rolUsuario'] == 7 || $_SESSION['rolUsuario'] == 6) {
				$this->cerrarPqrVista($_GET);
			} else {
				header('location:../vistas/panel_control_vista');
			}
		} elseif (isset($_POST['cerrar'])) {
			$this->cerrarPqr($_POST);
		}elseif (isset($_POST['remover'])) {
			$this->removerEscalamiento($_POST);
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

	public function listadPqrFiltrados() {
		try {
			$obj = new pqr();
			$this->consulta = new consultasCrud();
			$idUsuario = $_SESSION['idUsuario'];
			$pqrs = $obj->consultarPqr('filtrados');
			$pqrsAsignados = $this->consulta->consultarTablas('asignados_coor',$idUsuario);
			if ($_SESSION['rolUsuario'] == 2) {
				echo '
					<div id="formulariodepqrform" ></div>
					<div id="info"  class="col-md-12 " >
						<div class="card table-responsive">
						  <div class="card-header">
						    <center><h4>Lista de nuevos <strong>PQR</strong></h4></center>
						  </div>
						  <div class="card-body">
						  		<ul class="nav nav-tabs" id="myTab" role="tablist">
								  <li class="nav-item">
								    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">PQR Filtrados</a>
								  </li>
								  <li class="nav-item">
								    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">PQR Asignados</a>
								  </li>
								</ul>
								<div class="tab-content" id="myTabContent">
								  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
								  <br>
								  		<table id="tablapqr" class="table table-borderless">
										  <thead class="thead-dark" >
										    <tr>
										      <th scope="col">ID</th>
										      <th scope="col">Numero registro</th>
										      <th scope="col">Estado</th>
										      <th scope="col">Informacion PQR</th>
										      <th scope="col">Solucionar</th>
										      <th scope="col">Cerrar</th>
											</tr>
										  </thead>
										  <tbody>';
											for ($i = 0; $i < count($pqrs); $i++) {
												echo '<tr>';
												echo '<td width="10%" >'. $pqrs[$i][0] .'</td>';
												echo '<td>' . $pqrs[$i][10] . '</td>';
												echo '<td><span class="badge badge-warning">' . $pqrs[$i][11] . '</span></td>';
												if ($pqrs[$i][8] != 2 && $pqrs[$i][8] != 4) {
													echo '<td><a href="../controladores/pqr_solucion_controlador?pqrfiltrado=' . base64_encode($pqrs[$i][9]) . '" ><i class="fas fa-handshake fa-lg"></i></a></td>';
													echo '<td><a href="../controladores/pqr_solucion_controlador?pqrsolu=' . base64_encode($pqrs[$i][9]) . '" ><i class="fas fa-thumbs-up fa-lg"></i></a></td>';
													echo '<td><strong> </strong></td>';
												} else {
													echo '<td><a href="../controladores/pqr_solucion_controlador?pqrfiltrado=' . base64_encode($pqrs[$i][9]) . '" ><i class="fas fa-handshake fa-lg"></i></a></td>';
													echo '<td><strong>PQR en solución</strong></td>';
													echo '<td width="16%" ><a href="../controladores/pqr_solucion_controlador?pqrsolu=' . base64_encode($pqrs[$i][9]) . '" ><button id="cerrarTicket" class="btn btn-outline-primary" >Agregar respuesta</button></a>
													<a href="../controladores/pqr_solucion_controlador?pqrcerr=' . base64_encode($pqrs[$i][9]) . '" ><button id="cerrarTicket" class="btn btn-outline-success" >Ver</button></a>
													</td>';
												}
												echo '</tr>';
											}

									echo '</tbody>
										</table>
								  </div>
								  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
								  		<br>
								  		<table  class="table table-borderless" >
								  			<thead class="thead-dark" >
									  			<tr>
									  				<th scope="col">ID</th>
												     <th scope="col">Numero registro</th>
												     <th scope="col">Estado</th>
												     <th scope="col">Informacion PQR</th>
												     <th scope="col">Solucionar</th>
												     <th scope="col">Cerrar</th>
									  			</tr>
									  		</thead>
									  		<tbody>';
									  		for($i=0; $i<count($pqrsAsignados); $i++){
									  			echo '<tr>';
												echo '<td width="10%" >'. $pqrsAsignados[$i][0] .'</td>';
												echo '<td>' . $pqrsAsignados[$i][1] . '</td>';
												echo '<td><span class="badge badge-warning">' . $pqrsAsignados[$i][2] . '</span></td>';
												if ($pqrsAsignados[$i][3] != 2) {
													echo '<td><a href="../controladores/pqr_solucion_controlador?pqrfiltrado=' . base64_encode($pqrsAsignados[$i][0]) . '" ><i class="fas fa-handshake fa-lg"></i></a></td>';
													echo '<td><a href="../controladores/pqr_solucion_controlador?pqrsolu=' . base64_encode($pqrsAsignados[$i][0]) . '" ><i class="fas fa-thumbs-up fa-lg"></i></a></td>';
													echo '<td><strong> </strong></td>';
												} else {
													echo '<td><a href="../controladores/pqr_solucion_controlador?pqrfiltrado=' . base64_encode($pqrsAsignados[$i][0]) . '" ><i class="fas fa-handshake fa-lg"></i></a></td>';
													echo '<td><strong>PQR en solución</strong></td>';
													echo '<td width="16%" ><a href="../controladores/pqr_solucion_controlador?pqrsolu=' . base64_encode($pqrsAsignados[$i][0]) . '" ><button id="cerrarTicket" class="btn btn-outline-primary" >Agregar respuesta</button></a>
													<a href="../controladores/pqr_solucion_controlador?pqrcerr=' . base64_encode($pqrsAsignados[$i][0]) . '" ><button id="cerrarTicket" class="btn btn-outline-success" >Ver</button></a>
													</td>';
												}
												echo '</tr>';
									  		}
									  echo'</tbody>
								  		</table>
								  	</div>
								</div>
							</div>
						</div>
					</div>
				';
			} elseif ($_SESSION['rolUsuario'] == 4 || $_SESSION['rolUsuario'] == 8) {
				echo '
					<div id="formulariodepqrform" ></div>
					<div id="info"  class="col-md-12" >
						<div class="card table-responsive">
						  <div class="card-header">
						    <center><h4>Lista de nuevos <strong>PQR</strong></h4></center>
						  </div>
						  <div class="card-body">
						  		<table id="tablapqr" class="table table-borderless">
								  <thead class="thead-dark" >
								    <tr>
								      <th scope="col">ID</th>		
								      <th scope="col">Numero registro</th>
								      <th scope="col">Estado</th>
								      <th scope="col">Detalle</th>
								      <th scope="col">Solucionar</th>
								      <th scope="col">Agregar respuesta</th>
									</tr>
								  </thead>
								  <tbody>';
									for ($i = 0; $i < count($pqrs); $i++) {
										echo '<tr>';
										echo '<td width="5%" >'.$pqrs[$i][1].'</td>';
										echo '<td>' . $pqrs[$i][8] . '</td>';
										echo '<td><span class="badge badge-warning">' . $pqrs[$i][9] . '</span></td>';
										if ($_SESSION['rolUsuario'] != 3) {
											if ($pqrs[$i]['pqrstatus'] != 2 && $pqrs[$i]['pqrstatus'] != 4) {
												echo '<td><a href="../controladores/pqr_solucion_controlador?pqrfiltrado=' . base64_encode($pqrs[$i][1]) . '" ><i class="fas fa-handshake fa-lg"></i></a></td>';
												echo '<td><a href="../controladores/pqr_solucion_controlador?pqrsolu=' . base64_encode($pqrs[$i][1]) . '" ><i class="fas fa-thumbs-up fa-lg"></i></a></td>';
												echo '<td><strong> </strong></td>';
											} else {
												echo '<td><strong>PQR en solución</strong></td>';
												echo '<td><strong>PQR en solución</strong></td>';
												echo '<td><a href="../controladores/pqr_solucion_controlador?pqrsolu=' . base64_encode($pqrs[$i][1]) . '" ><button id="cerrarTicket" class="btn btn-outline-primary" >Agregar respuesta</button></a></td>';
											}
										}
										echo '</tr>';
									}
							echo '</tbody>
								</table>
							</div>
						</div>
					</div>
				';
			} elseif ($_SESSION['rolUsuario'] == 3) {
				echo '
					<div id="formulariodepqrform" ></div>
					<div id="info"  class="col-md-12" >
						<div class="card table-responsive">
						  <div class="card-header">
						    <center><h4>Lista de nuevos <strong>PQR</strong></h4></center>
						  </div>
						  <div class="card-body">
						  		<table id="tablapqr" class="table table-borderless">
								  <thead class="thead-dark" >
								    <tr>
								      <th scope="col">ID</th>
								      <th scope="col">Numero registro</th>
								      <th scope="col">Estado</th>
									</tr>
								  </thead>
								  <tbody>';
									for ($i = 0; $i < count($pqrs); $i++) {
										echo '<tr>';
										echo '<td width="10%" >'.$pqrs[$i][0].'</td>';
										echo '<td>' . $pqrs[$i][10] . '</td>';
										if ($pqrs[$i][9] == 'proceso' || 'vencido') {
											echo '<td><span class="badge badge-warning">' . $pqrs[$i][9] . '</span></td>';
										} elseif ($pqrs[$i][9] == 'finalizado') {
											echo '<td><span class="badge badge-success">' . $pqrs[$i][9] . '</span></td>';
										}
										echo '</tr>';
									}
							echo '</tbody>
								</table>
							</div>
						</div>
					</div>
				';
			} elseif ($_SESSION['rolUsuario'] == 1 || $_SESSION['rolUsuario'] == 6 || $_SESSION['rolUsuario'] == 7) {

				echo '
					<div id="formulariodepqrform" ></div>
					<div id="info"  class="col-md-12" >
						<div class="card table-responsive">
						  <div class="card-header">
						    <center><h4>Lista de nuevos <strong>PQR</strong></h4></center>
						  </div>
						  <div class="card-body">
						  		<table id="tablapqr" class="table table-borderless">
								  <thead class="thead-dark" >
								    <tr>
								      <th scope="col">ID</th>
								      <th scope="col">Numero registro</th>
								      <th scope="col">Estado</th>
								      <th scope="col">Escalar</th>
								      <th scope="col">Ediar PQR</th>
								      <th scope="col">Solucionar</th>
								      <th scope="col">Ver</th>
									</tr>
								  </thead>
								  <tbody>';
									for ($i = 0; $i < count($pqrs); $i++) {
										echo '<tr>';
										echo '<td width="5%" >'.$pqrs[$i][0].'</td>';
										echo '<td>' . $pqrs[$i][10] . '</td>';
										if ($pqrs[$i][9] == 'proceso') {
											echo '<td><span class="badge badge-warning">' . $pqrs[$i][9] . '</span></td>';
										} elseif ($pqrs[$i][9] == 'vencido') {
											echo '<td><span class="badge badge-danger">' . $pqrs[$i][9] . '</span></td>';
										}elseif ($pqrs[$i][9] == 'finalizado') {
											echo '<td><span class="badge badge-success">' . $pqrs[$i][9] . '</span></td>';
										} elseif ($pqrs[$i][9] == 'rechazado') {
											echo '<td><span class="badge badge-danger">' . $pqrs[$i][9] . '</span></td>';
										}
										echo '<td><a href="../controladores/pqr_solucion_controlador?pqrfiltrado=' . base64_encode($pqrs[$i][1]) . '" ><i class="fas fa-handshake fa-lg"></i></a></td>';
										echo '<td><a href="../controladores/pqr_controlador?pqrEdit='.base64_encode($pqrs[$i][1]).'" ><i class="fas fa-edit fa-lg"></i></a></td>';
										if ($pqrs[$i][8] != 2 && $pqrs[$i][8] != 4) {
											echo '<td><a href="../controladores/pqr_solucion_controlador?pqrsolu=' . base64_encode($pqrs[$i][1]) . '" ><i class="fas fa-thumbs-up fa-lg"></i></a></td>';
											echo '<td><strong> </strong></td>';
										} else {
											echo '<td><strong>PQR en solución</strong></td>';
											echo '<td width="14%" ><a href="../controladores/pqr_solucion_controlador?pqrsolu=' . base64_encode($pqrs[$i][1]) . '" ><button id="cerrarTicket" class="btn btn-outline-primary col-md-11" >Agregar respuesta</button></a>';
											echo '<a href="../controladores/pqr_solucion_controlador?pqrcerr=' . base64_encode($pqrs[$i][1]) . '" ><button id="cerrarTicket" class="btn btn-outline-success col-md-11" >Ver</button></a></td>';
										}
										echo '</tr>';
									}

							echo '</tbody>
								</table>
							</div>
						</div>
					</div>
				';
			}

		} catch (Exception $e) {
			echo 'Excepción capturada: ', $e->getMessage(), "\n";
		}
	}

	private function escalarPqr($get) {
		try {
			$datos = base64_decode($get['pqrfiltrado']);
			$this->querys = new Querys();
			$this->plantilla = new plantilla();
			$this->plantilla->cabezera();
			$this->plantilla->cuerpo();
			$this->plantilla->menuNavegacion();
			echo '
				<div id="formulariodepqrform"></div>
				<div class="container col-md-11 mt-5" >
				<nav aria-label="breadcrumb">
				  <ol class="breadcrumb">
				    <li class="breadcrumb-item"><a href="/vistas/panel_control_vista">Inicio</a></li>
				    <li class="breadcrumb-item active" aria-current="page">Escalar PQR</li>
				  </ol>
				</nav>
					<div class="card" >
						<div class="card-header">
				    		<center><strong><h4>Información PQR</h4></strong></center>
				  		</div>
				  		<div class="card-body">
				  			<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
							  <li class="nav-item">
							    <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Informacion del solicitante</a>
							  </li>
							  <li class="nav-item">
							    <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">PQR</a>
							  </li>
							  <li class="nav-item">
							    <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-pqrfiltrado" role="tab" aria-controls="pills-profile" aria-selected="false">PQR filtrado</a>
							  </li>';
			if ($_SESSION['rolUsuario'] != 4) {
				echo '
							 <li class="nav-item">
							    <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">Escalar PQR</a>
							  </li>
							';
			}
			echo '</ul>
							<div class="tab-content" id="pills-tabContent">
							  <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab"><br><hr><br>';
			$resultadoLocal = $this->querys->consultarQuery('pqr', $datos);
			echo 
			'
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
					echo
					'
					<div class="col-md-1" >
						<label class="ml-3" ><strong>NIT</strong></label>
					</div>
					<div class="col-md-5" >
						<input value="' . $resultadoLocal[0][3] . '-' . $resultadoLocal[0][4] . '" class="form-control" readonly>
					</div>
					';
					}
			echo'</div>
			';
			echo '
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

				';
			echo '</div>
				<div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab"><br><hr><br>';
			
				echo 
				'
					<div class="row justify-content-md-center" >
						<div class="col-md-3" >
							<label><strong>Numero de PQR</strong></label>
							<input value="' . $resultadoLocal[0][1] . '" class="form-control" readonly>
						</div>
						<div class="col-md-3" >
							<label class="ml-3" ><strong>Fecha</strong></label>
							<input value="' . $resultadoLocal[0][12] . '" class="form-control" readonly>
						</div>
						<div class="col-md-3" >
							<label class="ml-4" ><strong>Estado</strong></label>
							<br>
							<span class="badge badge-warning col-md-4 ml-3"><h6>' . $resultadoLocal[0][14] . '</h6></span>
						</div>
					</div>
					<hr>
					<div>
						<label><strong><h3>Mensaje</h3></strong></label>
						<br>
						' . $resultadoLocal[0][10] . '
					</div>
				';
			echo '</div>
			<div class="tab-pane fade" id="pills-pqrfiltrado" role="tabpanel" aria-labelledby="pills-contact-tab">';
			$resultadoLocalFiltro = $this->querys->consultarQuery('pqr_filtrado', $datos);
			echo 
			'
				<br>
				<hr>
				<div class="row" >
					<div class="col-md-3" >
						<label><strong>Usuario filtrador</strong></label>
						<input class="form-control" value="' . $resultadoLocalFiltro[0][2] . '" readonly>
					</div>
					<div class="col-md-3" >
						<label><strong>Distrito</strong></label>
						<input class="form-control" value="' . $resultadoLocalFiltro[0][3] . '" readonly>
					</div>
					<div class="col-md-2" >
						<label><strong>Area</strong></label>
						<input class="form-control" value="' . $resultadoLocalFiltro[0][5] . '" readonly>
					</div>
					<div class="col-md-2" >
						<label><strong>Fecha de filtrado</strong></label>
						<input class="form-control" value="' . $resultadoLocalFiltro[0][1] . '" readonly>
					</div>
					<div class="col-md-2" >
						<label><strong>Tipo de PQR</strong></label>
						<br>
						<span class="badge badge-success"><h6>' . $resultadoLocalFiltro[0][6] . '</h6></span>
					</div>
				</div>
			';
			echo '</div>
							  <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">';
			$escalamiento = $this->querys->consultarQuery('usuarios_area', $resultadoLocalFiltro[0][4] . '-' . $datos);
			$usuariosconescalamiento = $this->querys->consultarQuery('usuarios_con_pqr',$datos);
			echo '
				<br>
				<form id="escalapqr" >
					<input id="pqrid" type="hidden" value="' . $datos . '" >
					<input id="idarea" type="hidden" value="' . $resultadoLocalFiltro[0][4] . '">
					<div class="row" >
	                <div class="col-md-12 ml-5"><label><strong>Selecciona los usuarios</strong></label><hr>';
			for ($i = 0; $i < count($escalamiento); $i++) {
				echo '
	                			<label class="customcheck">' . $escalamiento[$i][1] . '
				            		<input id="gestionador" value="' . $escalamiento[$i][0] . '" class="sitesCustom" name="' . $escalamiento[$i][1] . '" type="checkbox"/>
				            		<span class="checkmark"></span>
								</label>
	                		';
			}
			echo '	</div>

	           		</div>
	           	<button type="submit" class="btn btn-outline-success ml-5" >Escalar</button>
	           </form>
	           <hr>
	           <form id="removerEscalamiento" >
	           		<div class="ml-5" >
	           			<input id="pqridr" type="hidden" value="'.$datos.'" />
	           			<strong>Usuarios con el PQR</strong>
	           			<br>';
	           			for ($i=0; $i < count($usuariosconescalamiento); $i++) { 
	           			echo '
	                		<label class="customcheck">' . $usuariosconescalamiento[$i]['nombre_usuario'] . '
				            	<input id="gestionador" value="' . $usuariosconescalamiento[$i]['id_usuario'] . '" class="sitesCustom claseRemover" name="' . $usuariosconescalamiento[$i]['nombre_usuario'] . '" type="checkbox"/>
				            	<span class="checkmark"></span>
							</label>
	                	';
	           			}
	           	echo'
	           		</div>
	           		<button type="submit" class="btn btn-outline-success ml-5" >Remover</button>
	           </form>
			';
			echo '</div>
							</div>
				  		</div>
					</div>
				</div>
			';

			$this->plantilla->pie();
			$objcarga = new carga(9);
			$objcarga = new carga(20);
		} catch (Exception $e) {
			echo 'Excepción capturada: ', $e->getMessage(), "\n";
		}
	}

	private function escalarDatoPqr($post) {
		try {
			$this->conexion = new conexion();
			$this->querys = new querys();
			$idActivo = $_SESSION['idUsuario'];
			$idPqr = $post['info'][0];
			$idarea = $post['info'][1];
			$sql_escalar = "INSERT INTO pqr_gestionadores (id_pqr,id_usuario,id_area,id_usuarioser,fecha,status) VALUES (:idpqr,:idusuario,:idarea,:usuarioactivo,CURRENT_TIMESTAMP,1)";
			$sql_editar = "UPDATE pqr_gestionadores SET status = 1 WHERE id_pqr = :idpqr AND id_usuario = :idusuario";
			$retorno = $this->conexion->consulQuery($sql_escalar);
			$retorno_editar = $this->conexion->consulQuery($sql_editar);
			$retorno['resultado']->bindParam(':idpqr', $idPqr, PDO::PARAM_INT);
			$retorno['resultado']->bindParam(':idarea', $idarea, PDO::PARAM_INT);
			$retorno['resultado']->bindParam(':usuarioactivo',$idActivo,PDO::PARAM_INT);
			$retorno_editar['resultado']->bindParam(':idpqr',$idPqr,PDO::PARAM_INT);
			for ($i = 0; $i < count($post['info'][2]); $i++) {
				$resultado_si_hay_datos = $this->querys->consultarQuery('pqr_gestionadores',$idPqr,$post['info'][2][$i]);
				if(!count($resultado_si_hay_datos) > 0)
				{
					$retorno['resultado']->bindParam(':idusuario', $post['info'][2][$i], PDO::PARAM_INT);
					$retorno['resultado']->execute();
				}
				else
				{
					$retorno_editar['resultado']->bindParam(':idusuario',$post['info'][2][$i], PDO::PARAM_INT);
					$retorno_editar['resultado']->execute();
				}
				
			}
			echo true;
		} catch (Exception $e) {
			echo 'Excepción capturada: ', $e->getMessage(), "\n";
		} finally {
			$this->conexion = NULL;
		}
	}

	private function removerEscalamiento($post)
	{
		try {
			$idPqr = $post['remover'][0];
			for ($i=0; $i < count($post['remover'][1]); $i++) { 
				$this->editarStatusEscalamiento($idPqr,$post['remover'][1][$i]);
			}
		} catch (Exception $e) {
			echo 'Excepción capturada: ', $e->getMessage(), "\n";
		}finally{
			$this->conexion = NULL;
		}
	}

	private function editarStatusEscalamiento($idPqr,$idUsuario)
	{
		try {
			$this->conexion = new conexion();
			$sql_safe = "SET SQL_SAFE_UPDATES = 0"; 
			$retorno_safe = $this->conexion->consulQuery($sql_safe);
			if ($retorno_safe['resultado']->execute()) 
			{
				$sql_remover_escalamiento = "UPDATE pqr_gestionadores SET status = 0 WHERE id_pqr = :idPqr AND id_usuario = :usuarioid";
				$retorno = $this->conexion->consulQuery($sql_remover_escalamiento);
				$retorno['resultado']->bindParam(':idPqr', $idPqr, PDO::PARAM_INT);
				$retorno['resultado']->bindParam(':usuarioid',$idUsuario, PDO::PARAM_INT);
				if ($retorno['resultado']->execute()) 
				{
					echo true;
				}
				else
				{
					print_r($retorno['resultado']->errorInfo());
				}
			}
			else
			{
				print_r($retorno_safe['resultado']->errorInfo());
			}
		} catch (Exception $e) {
			echo 'Excepción capturada: ', $e->getMessage(), "\n";
		}finally{
			$this->conexion = NULL;
		}
	}

	private function solucionarpqr($get) {
		try {
			$emailSesion = $_SESSION['emailUsuario'];
			$datos = base64_decode($get['pqrsolu']);
			$this->querys = new Querys();
			$this->plantilla = new plantilla();
			$this->plantilla->cabezera();
			$this->plantilla->cuerpo();
			$this->plantilla->menuNavegacion();
			$tmp = rand(10000,99999);
			$resultadoLocal = $this->querys->consultarQuery('pqr', $datos);
			echo '
				<div id="formulariodepqrform"></div>
				<div class="container col-md-10 mt-5" >
				<nav aria-label="breadcrumb">
				  <ol class="breadcrumb">
				    <li class="breadcrumb-item"><a href="/vistas/panel_control_vista">Inicio</a></li>
				    <li class="breadcrumb-item active" aria-current="page">Solucionar PQR</li>
				  </ol>
				  <div class="card table-responsive" >
				  	<div class="card-header">
				    	<center><strong><h4>Solución PQR</h4></strong></center>
				  	</div>
				  	<div class="card-body">';
			echo '
				<br
					
					
				<hr>
				<div class="row" >
					<div class="col-md-1" >
						<label class="ml-3" ><strong>Numero de PQR</strong></label>
					</div>
					<div class="col-md-2" >
						<input value="' . $resultadoLocal[0][1] . '" class="form-control" readonly>
					</div>
					<div class="col-md-1" >
						<label class="ml-3" ><strong>Fecha</strong></label>
					</div>
					<div class="col-md-2" >
						<input value="' . $resultadoLocal[0][12] . '" class="form-control" readonly>
					</div>
					<div class="col-md-1" >
						<label class="ml-4" ><strong>Solicitante</strong></label>
					</div>
					<div class="col-md-2" >
						<input value="' . $resultadoLocal[0][2] . '" class="form-control" readonly>
					</div>
					<div class="col-md-1" >
						<label class="ml-4" ><strong>Estado</strong></label>
					</div>
					<div class="col-md-2" >
						<span class="badge badge-warning"><h6>' . $resultadoLocal[0][14] . '</h6></span>
					</div>
				</div>
				<hr>
				<div>
					<label ><strong><h3>Mensaje</h3></strong></label>
					<br>
					' . $resultadoLocal[0][10] . '
				</div><br><hr>';
			$solucion = $this->querys->consultarQuery('pqr_solucion', $datos);
			if(!empty($solucion)){
				for ($i = 0; $i < count($solucion); $i++) {
					echo '
						<div class="alert alert-secondary" role="alert">
						  	<center><h4 class="alert-heading">Soluciones del PQR</h4></center>
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
						  <label><strong>Descripcion</strong></label>
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
					echo '<hr>';
				}
			}else{
				echo '<input id="emailsolucionid" class="emailsolucion" value="'.$emailSesion.'"  type="hidden" >';
			}
			echo '	<form action="../controladores/pqr_solucion_controlador.php" method="POST" id="solucionarpqr" enctype="multipart/form-data">
					  		<div>
					  			<hr>
								<input type="hidden" value="'.$tmp.'" id="randomPqr">';
					  		echo'<label><strong>Descripcion de la solución</strong></label>
					  			<br>
					  			<input type="hidden" id="idPqr" value="' . $datos . '" >
					  			<textarea id="pqrsolucion" name="solucion" class="txt-content pqrsolucion" required></textarea>
					  			<br>
					  			<hr>
					  			<br>
					  			<strong>Archivos</strong>
					  			<br>
					  			<br>
					  			<input id="archivossolucionpqr" name="archivos" type="file"  data-preview-file-type="text" multiple >
		                        <br>
		                        <button type="submit" class="btn btn-outline-success btn-block">Responder</button>
		                        <br>
					  		</div>
					  	</form>
				  	</div>
				  </div>
				</div>

			';
			$this->plantilla->pie();
			$objcarga = new carga(10);
			$objcarga = new carga(12);
			$objcarga = new carga(5);
		} catch (Exception $e) {
			echo 'Excepción capturada: ', $e->getMessage(), "\n";
		}
	}

	private function solucionPqr($post, $archivos) {
		try {
			$this->conexion = new conexion();
			$this->querys = new Querys();
			$consulta = $this->querys->consultarQuery('id_area_por_pqr', $post['idPqr']);
			$fecha_subida = date('m/d/Y');
			$idusuario = $_SESSION['idUsuario'];
			$idArea = $consulta[0]['id_area'];
			$idPqr = htmlentities(addslashes($post['idPqr']));
			$randomPqr = htmlentities(addslashes($post['randomPqr']));
			$mensaje = addslashes($post['solucion']);
			if (!empty($archivos['archivos']['name'])) {
				for ($i = 0; $i < count($archivos['archivos']['name']); $i++) {
					$nombre = $archivos['archivos']["name"][$i];
					$tipo = $archivos['archivos']["type"][$i];
					$ruta = $archivos['archivos']["tmp_name"][$i];
					$tamano = $archivos['archivos']["size"][$i];
					$nueva_ruta = $_SERVER["DOCUMENT_ROOT"] . "/archivos/solucionados/" . $nombre;
					$var = "/archivos/solucionados/" . $nombre;
					if (move_uploaded_file($ruta, $nueva_ruta)) {
						$sql_insertar_imagenes = "INSERT INTO pqrsolucion_archivos (id_pqr,ruta,nombre,tipo,tamano,random) VALUES (:id_pqr,:ruta,:nombre,:tipo,:tamano,:random)";
						$retorno_imagenes = $this->conexion->consulQuery($sql_insertar_imagenes);
						$retorno_imagenes['resultado']->bindParam(':id_pqr', $idPqr, PDO::PARAM_INT);
						$retorno_imagenes['resultado']->bindParam(':ruta', $var, PDO::PARAM_STR);
						$retorno_imagenes['resultado']->bindParam(':nombre', $nombre, PDO::PARAM_STR);
						$retorno_imagenes['resultado']->bindParam(':tipo', $tipo, PDO::PARAM_STR);
						$retorno_imagenes['resultado']->bindParam(':tamano', $tamano, PDO::PARAM_INT);
						$retorno_imagenes['resultado']->bindParam(':random' ,$randomPqr, PDO::PARAM_INT);
						$resultado = array_unique($post['email']);
						if ($retorno_imagenes['resultado']->execute()) {
							$this->envioMensajeSolucion($resultado,$idPqr);
						} else {
							print_r($retorno_imagenes['resultado']->errorInfo());
						}
					}
				}
			}
			$sql_insertar_solucion = "INSERT INTO pqr_solucion (id_pqr,id_usuario,id_area,mensaje,fecha,random) VALUES (:idPqr,:idUsuario,:idArea,:mensaje,:fecha,:random)";
			$retorno = $this->conexion->consulQuery($sql_insertar_solucion);
			$retorno['resultado']->bindParam(':idPqr', $idPqr, PDO::PARAM_INT);
			$retorno['resultado']->bindParam(':idUsuario', $idusuario, PDO::PARAM_INT);
			$retorno['resultado']->bindParam(':idArea', $idArea, PDO::PARAM_INT);
			$retorno['resultado']->bindParam(':mensaje', $mensaje, PDO::PARAM_STR);
			$retorno['resultado']->bindParam(':fecha', $fecha_subida, PDO::PARAM_STR);
			$retorno['resultado']->bindParam(':random',$randomPqr,PDO::PARAM_INT);
			$resultado = array_unique($post['email']);
			if ($retorno['resultado']->execute()) {
				$_slq_status_filtrado = "UPDATE pqr_filtrado SET status = 2 WHERE id_pqr = :idPqr";
				$retorno_status_filtrado = $this->conexion->consulQuery($_slq_status_filtrado);
				$retorno_status_filtrado['resultado']->bindParam(':idPqr', $idPqr, PDO::PARAM_INT);
				if ($retorno_status_filtrado['resultado']->execute()) {
					echo true;
					$this->envioMensajeSolucion($resultado,$idPqr);
				} else {
					print_r($retorno_status_filtrado['resultado']->errorInfo());
				}
			} else {
				print_r($retorno['resultado']->errorInfo());
			}
		} catch (Exception $e) {
			echo 'Excepción capturada: ', $e->getMessage(), "\n";
		} finally {
			$this->conexion = new conexion();
		}
	}

	private function envioMensajeSolucion($resultado,$idPqr)
	{
		try {
			for ($i=0; $i < count($resultado) ; $i++) 
			{ 
				$mensaje = '
					<div>
						<center>
							<img height="80" src="https://www.ropsohn.com.co/images/IDENTIFICADOR_ROPSON-04.jpg" style="border:0" class="CToWUd">
							<br>
							<br>
							<hr style="border-top:solid 1px #dbdbdb;width:300px;" >
							<p style="width:300px;padding:0;margin:0;text-align:center;color:#262626;font-size:18px;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif" >
								Solucion del <strong>PQR</strong>
							</p>
							<p style="width:300px;padding:0;margin:0;text-align:center;color:#999999;font-size:14px;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif">
								Hola, tienes una ueva solucion del PQR con numero de registro:
							</p>
							<br>
							<br>
							<span class="m_-2429985624185834423m_-923009222503390390btn-content" style="background-color:#2c9ce9;border-radius:4px;display:inline-block">
								<a href="#" style="color:#fff;display:inline-block;font-family:Roboto,Helvetica,Arial,sans-serif;font-size:16px;line-height:1.4;text-align:center;text-decoration:none;min-width:115px;padding:8px 16px;font-weight:500" target="_blank">
							<span style="color:#fff">'.$idPqr.'</span></a></span>
							<br>
							<br>
							<hr style="width:310px;border-top:solid 1px #dbdbdb;" >
							<p style="width:300px;padding:0;margin:0;text-align:center;color:#262626;font-size:16px;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif" >
								Verifica el estado de el PQR en la plataforma 
								<a style="text-decoration: none" href="https://'.$_SERVER['HTTP_HOST'].'" > Sistema PQR</a>
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
				$mail = new mail($resultado[$i],$mensaje,'solucion de PQR');
			}
		} catch (Exception $e) {
			echo 'Excepción capturada: ', $e->getMessage(), "\n";
		}
	}

	private function respuestasDelPqr($get)
	{
		try {
			$this->DBinstancia();
			$datosId = $get;
			$this->DB->table('pqr_respuesta_usuario');
			$this->DB->where('id_pqr','=',$datosId);
			$this->DB->groupBy('random');
			$this->DB->select('*');
			return $datos = $this->DB->get();
		} catch (Exception $e) {
			die('Excepción capturada: ' . $e->getMessage() . "\n");
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

	private function cerrarPqrVista($get) {
		try {
			$html = '';
			$datos = base64_decode($get['pqrcerr']);
			$datosPQRR    = $this->respuestasDelPqr($datos);
			$archivosPQRR = $this->archivosDelPqr($datos);
			if (!isset($datosPQRR['ejecutado'])) {
				$datosPQRR = json_decode($datosPQRR,true);
			}
			if (!isset($archivosPQRR['ejecutado'])) {
				$archivosPQRR = json_decode($archivosPQRR,true);
			}
			$this->querys = new Querys();
			$this->plantilla = new plantilla();
			$this->plantilla->cabezera();
			$this->plantilla->cuerpo();
			$this->plantilla->menuNavegacion();
			echo '
				<div class="container col-md-10 mt-5" >
					<div id="formulariodepqrform" ></div>
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="/vistas/panel_control_vista">Inicio</a></li>
							<li class="breadcrumb-item active" aria-current="page">Contestar PQR</li>
						 </ol>
					</nav>
					<nav>
					<div class="nav nav-tabs" id="nav-tab" role="tablist">
					    <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Soluciones</a>
					    <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Respuestas enviadas</a>
					  </div>
					</nav>
					<div class="tab-content" id="nav-tabContent">
					  <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
					    <br />
					  	<div class="card table-responsive" >
					  		<div class="card-header">
				    			<center><strong>Contestar PQR</strong></center>
				  			</div>
				  			<div class="card-body">';
				  			$resultadoLocal = $this->querys->consultarQuery('pqr', $datos);
				  			echo '
				  			<div class="row" >
								<div class="col-md-1" >
									<label class="ml-3" ><strong>Numero de PQR</strong></label>
								</div>
								<div class="col-md-3" >
									<input value="' . $resultadoLocal[0][1] . '" class="form-control" readonly>
								</div>
								<div class="col-md-1" >
									<label class="ml-3" ><strong>Fecha</strong></label>
								</div>
								<div class="col-md-3" >
									<input value="' . $resultadoLocal[0][12] . '" class="form-control" readonly>
								</div>
								<div class="col-md-1" >
									<label class="ml-4" ><strong>Estado</strong></label>
								</div>
								<div class="col-md-3" >
									<span class="badge badge-warning"><h6>' . $resultadoLocal[0][14] . '</h6></span>
								</div>
							</div>
							<hr />
							<div>
								<label><strong><h4>Mensaje</h4></strong></label>
								<br>
									' . $resultadoLocal[0][10] . '
							</div>
							<br />
							<hr />
				  			';
				  			$e = 1;
				  			$solucion = $this->querys->consultarQuery('pqr_solucion', $datos);
				  			for ($i = 0; $i < count($solucion); $i++) {
				  				echo '
									<div class="alert alert-secondary" role="alert">
									  	<center><h4 class="alert-heading">Soluciones del PQR</h4></center>
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
									  <hr>';
									  if ($solucion[$i]['mensaje'] == 'Se ha vencido el PQR') {
										echo '
											<div class="alert alert-danger" role="alert">
											  '.$solucion[$i]['mensaje'].'
											</div>
										'; 
									  } else {
										echo $solucion[$i]['mensaje'];
									  }
								echo'</div>
								';
								
								echo '<hr>';
								$archivos = $this->querys->consultarQuery('pqr_solucion_imagenes', $datos);
								if (!empty($archivos)) {
									echo '<label><strong>Archivos</strong></label><br>';
									for ($e = 0; $e < count($archivos); $e++) {
										if ($solucion[$i]['random'] == $archivos[$e]['random']) {
											echo '
												<a href="' . $archivos[$e][2] . '" >' .$archivos[$e][0]."__". $archivos[$e][3] . '</a><br>
											';
										}
									}
								}
							echo'</p>
								<hr>';
				  			}
				  			echo '
								<a href="../controladores/respuesta_usuario.php?res='.base64_encode($datos).'&&ema='.base64_encode($resultadoLocal[0][6]).'" <button id="respuestaUsuario" class="btn btn-outline-primary btn-block" >Enviar respuesta usuario</button></a>
								<button id="cerrarPqr" class="btn btn-outline-success btn-block" >Contestar PQR</button>
							';
				  		echo'</div>
					  	</div>
					  </div>
					  <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">';
						if (!isset($datosPQRR['ejecutado'])) {
							$HTML = '';
							for ($i=0; $i < count($datosPQRR); $i++) { 
								
									$HTML .= '
										<br />
										<div class="card">
											<div class="card-header">
												<strong>Mensaje</strong>
											</div>
											<div class="card-body" >
												'.$datosPQRR[$i]['mensaje'].'
												<hr />
												<strong>Fecha</strong><br />
												'.$datosPQRR[$i]['fecha'].'
												<br />
												<br />';
												if (!isset($archivosPQRR['ejecutado'])) {
													for ($n=0; $n < count($archivosPQRR); $n++) { 
														if ($archivosPQRR[$n]['id_pqr_respuesta'] == $datosPQRR[$i]['id']) {
															$HTML .= '
																<br><a href="'.$archivosPQRR[$n]['ruta'].'" >'.$archivosPQRR[$n]['nombre'].'</a>
															';
														}
													}
												}
										$HTML.='<br />
												<br />
											</div>
										</div>
									';
								
							}

							print_r($HTML);
						} else {
							echo 'no hay respuesta disponibles';
						}
					  echo'</div>
					</div>
				</div>
			';
			$this->plantilla->pie();
			$objcarga = new carga(13);
		} catch (Exception $e) {
			echo 'Excepción capturada: ', $e->getMessage(), "\n";
		}
	}

	private function cerrarPqr($post) {
		try {
			$this->conexion = new conexion();
			$cerrar = htmlentities(addslashes($_POST['cerrar']));
			$idPqr = htmlentities(addslashes($_POST['idPqr']));
			$idUsuario = $_SESSION['idUsuario'];
			$sql = "INSERT INTO pqr_cerrado (id_pqr,id_usuario,fecha,mensaje) VALUES (:id_pqr,:id_usuario,CURRENT_TIMESTAMP,:mensaje)";
			$retorno = $this->conexion->consulQuery($sql);
			$retorno['resultado']->bindParam(':id_pqr', $idPqr, PDO::PARAM_INT);
			$retorno['resultado']->bindParam(':id_usuario', $idUsuario, PDO::PARAM_INT);
			$retorno['resultado']->bindParam(':mensaje', $cerrar, PDO::PARAM_STR);
			if ($retorno['resultado']->execute()) {
				$editar_pqr_filtrado = "UPDATE pqr_filtrado SET status = 3 WHERE id_pqr = :idPqr";
				$retorno_filtrado = $this->conexion->consulQuery($editar_pqr_filtrado);
				$retorno_filtrado['resultado']->bindParam(':idPqr', $idPqr, PDO::PARAM_INT);
				if ($retorno_filtrado['resultado']->execute()) {
					$sql_pqr = "UPDATE pqr SET status = 0, estado = 'finalizado' WHERE id = :idPqr";
					$retorno_pqr = $this->conexion->consulQuery($sql_pqr);
					$retorno_pqr['resultado']->bindParam(':idPqr', $idPqr, PDO::PARAM_INT);
					if ($retorno_pqr['resultado']->execute()) {
						echo true;
					} else {
						print_r($retorno_pqr['resultado']->errorInfo());
					}
				} else {
					print_r($retorno_filtrado['resultado']->errorInfo());
				}

			} else {
				print_r($retorno['resultado']->errorInfo());
			}
		} catch (Exception $e) {
			echo 'Excepción capturada: ', $e->getMessage(), "\n";
		}
	}

}
if (isset($_SESSION['bool'])) {
	$obj = new pqrSolucion();
} else {
	header('location:../index.php');
}


