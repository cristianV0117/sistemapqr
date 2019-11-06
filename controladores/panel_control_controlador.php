<?php
/**
 *
 * @author Cristian Camilo Vasquez
 * Clase panelControl
 * Se incluyen:
 * El archivo de carga de archivos JS.
 * El archivo de controlador de PQR.
 * El archivo de solucion de PQRs.
 *
 */
include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/pqr_controlador.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/pqr_solucion_controlador.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/clases/carga_archivos_clase.php';
/**
 * Se verifica si existe una sesion actual.
 */
if (!isset($_SESSION)) 
{
	session_name('PQRUSER');
	session_start();
}

class panelControl 
{

	/**
	 * Se ejecuta el @method:$this->panel().
	 */
	public function __construct() 
	{
		try {
			$this->panel();
		} catch (Exception $e) {
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
		
	}

	/**
	 *
	 * @method:panel() Se encarga de construir el formulario principal de los PQR.
	 * @var: Almacena la clase pqr().
	 * Dependiendo de la variable @var:$_SESSION['rolUsuario'] se ejecutaran los @method:listadPqrFiltrados().
	 * O se ejecuta la vista principal del formulario.
	 *
	 */
	public function panel() 
	{
		$obj = new pqr();
		$pqrs = $obj->consultarPqr('abiertos');
		if ($_SESSION['rolUsuario'] == 2 || $_SESSION['rolUsuario'] == 4 || $_SESSION['rolUsuario'] == 8) {
			$objpqr = new pqrSolucion();
			echo '
				<div id="info"  class="col-md-10 mt-3" >';
			$objpqr->listadPqrFiltrados();
			echo '</div>
			';

		}elseif ($_SESSION['rolUsuario'] == 6 || $_SESSION['rolUsuario'] == 7) {
			$objpqr = new pqrSolucion();
			echo '
				<div id="info"  class="col-md-10 mt-3" >';
			$objpqr->listadPqrFiltrados();
			echo '</div>
			';
		}elseif ($_SESSION['rolUsuario'] == 5) {
			echo '
				<div id="formulariodepqr" class=" col-md-9 mt-3 ml-5">
					<form id="formulariodepqrform" >
						<div class="card">
							<div class="card-body">
								<h3 class="text-center blue-text py-3"><i class="fas fa-hand-point-up"></i> PQR:</h3>
								<hr>
								<input id="idUsuario" type="hidden" value="' . $_SESSION['idUsuario'] . '">
								<div class="row ml-5">
									<div id="campoDiferente" >
	                          			<input class="form-control" id="campoFarmaco" type="hidden" value="0" />
	                          		</div>
									<div>
		                            	<i class="fa fa-user prefix blue-text"></i>
		                            </div>
		                            <div class="col-md-11" >
		                            	<input placeholder="Nombre completo - Razón social..."  type="text" id="nombrecompleto" class="form-control col-md-10" required>
		                            </div>
		                        </div>
		                        <br>
		                        <div class="row ml-5" >
	                            	<div class="col-md-2">
	                            		<label class="customcheck">NIT
		            						<input id="nitradio" class="sitesCustom" name="radio" type="radio"/>
		            						<span class="checkmark"></span>
										</label>
	                            	</div>
	                            	<div class="col-md-5">
	                            		<label class="customcheck">Documento
											<input id="documentoradio" class="sitesCustom" name="radio" type="radio"/>
											<span class="checkmark"></span>
									 	</label>
	                            	</div>
	                            </div>
	                            <div id="nit" class="md-form ml-5">
	                            	<div class="row" >
	                            		<div>
	                            			<i class="fa fa-id-card prefix blue-text"></i>
	                            		</div>
	                            		<div class="col-md-5" >
		                            		<input oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxLength="9" placeholder="NIT.."  type="number" id="nitrut" class="form-control">
		                            	</div>
		                            	<div class="col-md-5" >
		                            		<input oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxLength="1" placeholder="Codigo de verificación.."  type="number" id="codigoverificacion" class="form-control">
		                            	</div>
		                            </div>
	                            </div>
	                            <br>
	                            <div id="documento" class="md-form ml-5">
	                            	<div class="row" >
	                            		<div>
	                            			<i class="fa fa-id-card prefix blue-text"></i>
	                            		</div>
	                            		<div class="col-md-10" >
	                            			<input oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxLength="12" placeholder="Documento.."  type="number" id="documentopersonal" class="form-control">
	                            		</div>
	                            	</div>
	                            </div>
	                            <br>
	                            <div class="row ml-4">
	                            	<div class="col-md-3">
	                            		<div class="md-form">
	                            			<i class="fa fa-users prefix blue-text"></i>
	                            			<input placeholder="Email.."  type="text" id="email" class="form-control col-md-10" required>
	                            		</div>
	                            	</div>
	                            	<div class="col-md-3">
	                            		<div class="md-form">
			                            	<i class="fa fa-building prefix blue-text"></i>
			                            	<input placeholder="Ciudad.."  type="text" id="ciudad" class="form-control col-md-10" required>
	                            		</div>
	                            	</div>
	                            	<div class="col-md-3">
	                            		<div class="md-form">
			                            	<i class="fa fa-phone prefix blue-text"></i>
			                            	<input oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxLength="7" placeholder="Telefono.."  type="number" id="telefono" class="form-control col-md-10" required>
	                            		</div>
	                            	</div>
	                            	<div class="col-md-2">
	                            		<div class="md-form">
			                            	<i class="fa fa-mobile prefix blue-text"></i>
			                            	<input oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxLength="10" placeholder="Celular.."  type="number" id="celular" class="form-control col-md-10" required>
	                            		</div>
	                            	</div>
	                            </div>
	                             <br>
	                            	<textarea class="txt-content pqrmensaje" required></textarea>
	                            <br>
	                            <hr>
	                            	<strong>(Opcional)</strong>
	                            	<input id="pqr" name="archivos" type="file"  data-preview-file-type="text">
	                            <hr>
	                            <div class="text-center">
	                                <button id="jeje" type="submit" class="btn btn-primary">Enviar</button>
	                                
	                            </div>
							</div>
						</div>
					</form>
				</div>
			';

		} else {
			echo '
				<div id="formulariodepqrform" ></div>
				<div id="info"  class="col-md-10 mt-3" >
					<div class="card">
					  <div class="card-header">
					    <center><h4>Lista de nuevos <strong>PQR</strong></h4></center>
					  </div>
					  <div class="card-body table-responsive">
					  		<table id="tablapqr" class="table table-borderless">
							  <thead class="thead-dark" >
							    <tr>
							      <th scope="col" >ID</th>
							      <th scope="col">Numero registro</th>
							      <th scope="col">Solicitante</th>
							      <th scope="col">Fecha de subida</th>
							      <th scope="col">Estado</th>
							      <th scope="col">Ver mas</th>
							    </tr>
							  </thead>
							  <tbody>';
								for ($i = 0; $i < count($pqrs); $i++) {
									echo '<tr>
											<td width="5%" >' . $pqrs[$i]['id'] . '</td>
											<td>' . $pqrs[$i]['identidicacion'] . '</td>
											<td>' . $pqrs[$i]['nombre'] . '</td>
											<td>' . $pqrs[$i]['fecha_subida'] . '</td>';
									if ($pqrs[$i]['estado'] == 'abierto') {
										echo '<td><span class="badge badge-danger">Abierto</span></td>';
									}
									echo '
											<td><a href="../controladores/pqr_controlador?pqr=' . base64_encode($pqrs[$i]['id']) . '" ><i class="fa fa-eye fa-lg" ></a></i></td>
										';

									echo '</tr>
										';
								}
						echo '</tbody>
							</table>
						</div>
					</div>
				</div>
			';
		}

	}
}
