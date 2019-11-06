<?php
/**
 *
 */

class homePrincipal {

	public function menuNavegacion() {
		try {
			echo '
	  		<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
			  <a class="navbar-brand" href="#">Bienvenido</a>
			  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			    <span class="navbar-toggler-icon"></span>
			  </button>

			  <div class="collapse navbar-collapse" id="navbarSupportedContent">
			    <ul class="navbar-nav mr-auto">

			    </ul>
			    <a id="farmaco" style="color:white" ><strong>Farmaco Vigilancia</strong></a>
			    <strong><a id="pqrconsulta" class="nav-link" style="color:white">Consultar estado PQR</a></strong>
			    <form class="form-inline my-2 my-lg-0">

			      	<i id="formulariodeingresologeo" style="color:white" class="mr-3  fas fa-user fa-lg" ></i>
			    </form>
			  </div>
			</nav>
	  	';
		} catch (Exception $e) {
			echo 'Excepción capturada: ', $e->getMessage(), "\n";
		}
	}

	public function formularioLogeo() {
		echo '
			<div id="formulariodepqr" class="container col-md-11 mt-5 mb-4">
				<form id="formulariodepqrform" >
	                    <div class="card">
	                        <div class="card-body">
	                            <h3 id="tituloprincipal" class="text-center blue-text py-3"><i class="fas fa-hand-point-up"></i> PQR:</h3>
	                            <div class="md-form ml-1">
	                            	<div id="campoDiferente" >
	                          			<input class="form-control" id="campoFarmaco" type="hidden" value="0" />
	                          		</div>
	                                <i class="fa fa-user prefix blue-text"></i>
	                                <input placeholder="Nombre completo - Razón social..."  type="text" id="nombrecompleto" class="form-control col-md-11" required>
	                            </div>
	                            <div class="row ml-5" >
	                            	<div class="col-md-2 mt-3">
	                            		<label class="customcheck">NIT
		            						<input id="nitradio" class="sitesCustom" name="radio" type="radio" required/>
		            						<span class="checkmark"></span>
										</label>
	                            	</div>
	                            	<div class="col-md-2 mt-3">
	                            		<label class="customcheck">C.C
											<input id="documentoradio" class="sitesCustom" name="radio" type="radio" required/>
											<span class="checkmark"></span>
									 	</label>
	                            	</div>
									<div class="col-md-7" >
										<div id="documento" class="md-form ml-4">
											<i class="fa fa-id-card prefix blue-text"></i>
											<input placeholder="Documento.." oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxLength="12"  type="number" id="documentopersonal" class="form-control col-md-12">
										</div>
										<div id="nit" class="md-form ml-4">
			                            	<i class="fa fa-id-card prefix blue-text"></i>
			                            	<div class="row" >
			                            		<div class="col-md-6 ml-5" >
				                            		<input placeholder="NIT.."  type="number" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxLength="9" id="nitrut" class="form-control">
				                            	</div>
				                            	<div class="col-md-5" >
				                            		<input placeholder="Codigo de verificación.." oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxLength="1"  type="number" id="codigoverificacion" class="form-control">
				                            	</div>
				                            </div>
			                            </div>
									</div>
	                            </div>
	                            <br>
	                            <div class="row ">
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
			                            	<i class="fa fa-mobile prefix blue-text"></i>
			                            	<input placeholder="Telefono/celular.."  type="number" id="telefono" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="10" type = "number" class="form-control col-md-10">
	                            		</div>
	                            	</div>
	                            	<div class="col-md-2">
	                            		<div class="md-form">
			                            	<i class="fa fa-mobile prefix blue-text"></i>
			                            	<input max="[0-11]{11}" placeholder="Telefono/celular.."  type="number" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" type = "number" maxlength = "10" id="celular" class="form-control col-md-10" required>
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
	                            <center><label><h4><strong>He leído las políticas de <a href="#" >Tratamiento de datos</a></strong></h4></label></center>
	                            <div class="col-md-7 float-right" >
		                            <label style="margin-left:44px;"  class="customcheck ">Acepto
			            				<input id="acepto" class="sitesCustom" name="acepto" type="checkbox" required/>
			            				<span class="checkmark"></span>
									</label>
								</div>
								<br>
								<hr>
	                            <div class="text-center">
	                                <button type="submit" class="btn btn-primary">Enviar</button>
	                            </div>
	                            <div id="prueba" >
	                            </div>
	                        </div>
	                    </div>
	            </form>
             </div>
            <div id="formulariodeingreso" class="container mt-5 col-md-5">
				<div class="card">
				  <div class="card-header">
				    <center>Ingresa al modulo de administración <strong>PQR</strong></center>
				  </div>
				  <div class="card-body">
				  	<form id="formulariodelogeo" >
					  	<div class="col-md-12" >
					  		<div class="row ml-2">
					  			<div>
					  				<i class="fa fa-user mt-4" ></i>
					  			</div>
						  		<div class="col-md-11" >
						  			<input id="nombredeusuario" type="text" class="form-control m-3" placeholder="Nombre de usuario..." required>
						  		</div>
						  	</div>
						  	<div class="row ml-1">
						  		<div>
						  			<i class="fas fa-unlock-alt mt-4"></i>
						  		</div>
						  		<div class="col-md-11" >
						  			<input id="contrasena" type="password" class="form-control m-3" placeholder="Contraseña..." required>
						  		</div>
						  	</div>
					  		<button type="submit" class="btn btn-success m-3">Ingresar</button>
					  		<button id="formularioenviarpqr" type="button" class="btn btn-warning m-3 float-left formularioenviarpqr">Enviar PQR</button>
					  	</div>
					</form>
				  </div>
				</div>
			</div>

			<div id="consulpqr" class="container mt-5 col-md-10">
				<div class="card" >
					<div class="card-header">
						<label><strong>Consultar mi PQR</strong></label>
					</div>
					<div class="card-body" >
						<label>Ingresa el codigo del PQR</label>
						<input id="consultarpqr" type="text" class="form-control" >
						<div class="row m-3" >
							<button id="consultarpqrfiltro" type="button" class="btn btn-success" >Consultar</button>
							<button id="formularioenviarpqr" type="button" class="btn btn-warning formularioenviarpqr">Enviar PQR</button>
						</div>
						<hr>
						<div id="info">
						</div>
					</div>
				</div>

			</div>
		';
	}
}
