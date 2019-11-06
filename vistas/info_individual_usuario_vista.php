<?php
	include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/clases/plantilla_clase.php';
	$plantilla = new plantilla();
	$plantilla->cabezera();
	$plantilla->cuerpo();
	$plantilla->menuNavegacion();
	$datosUsuario = json_decode($arregloDatos['infoUsuario'],true);
	$areas        = json_decode($arregloDatos['area'],true);
	$distritos    = json_decode($arregloDatos['distritos'],true);
	$roles        = json_decode($arregloDatos['roles'],true);
?>
<div class="container col-md-8 mt-5" >
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="/vistas/panel_control_vista">Inicio</a></li>
			<li class="breadcrumb-item active" aria-current="page">Usuario <?= $datosUsuario[0]['nombre_usuario'] ?></li>
		</ol>
	</nav>
	<div id="formulariodepqrform" ></div>
	<div class="card">
		<div class="card-header">
			<label><strong>Informacion de usuario</strong></label>
		</div>
		<div class="card-body">
			<div class="col-md-7" >
				<h3><strong><?= $datosUsuario[0]['nombre_usuario'] ?></strong></h3>
				<h3 style="color:#007bff" ><?= $datosUsuario[0]['tipo'] ?></h3>
			</div>
			<br>
			<nav>
				<div class="nav nav-tabs" id="nav-tab" role="tablist">
					<a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true"><strong>Informacion personal</strong></a>
					<a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false"><strong>Información empleado</strong></a>
				</div>
			</nav>
			<div class="tab-content" id="nav-tabContent">
				<div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
					<br>
					<input id="idUsuario" value="<?= $datosUsuario[0]['id'] ?>" type="hidden" >
					<div class="row" >
						<div class="col-md-2 ml-3" >
							<strong>Nombre Usuario</strong>
						</div>
						<div class="col-md-7" >
							<input id="nombreUsuario" class="inputs form-control col-md-6" type="text" value="<?= $datosUsuario[0]['nombre_usuario'] ?>" >
						</div>
					</div>
					<br>
					<div class="row" >
						<div class="col-md-2 ml-3" >
							<strong>Primer Nombre</strong>
						</div>
						<div class="col-md-7" >
							<input id="primerNombre" class="inputs form-control col-md-6" type="text" value="<?= $datosUsuario[0]['primer_nombre'] ?>" >
						</div>
					</div>
					<br>
					<div class="row" >
						<div class="col-md-2 ml-3" >
							<strong>Segundo Nombre</strong>
						</div>
						<div class="col-md-7" >
							<input id="segundoNombre" class="inputs form-control col-md-6" type="text" value="<?= $datosUsuario[0]['segundo_nombre'] ?>" >
						</div>
					</div>
					<br>
					<div class="row" >
						<div class="col-md-2 ml-3" >
							<strong>Primer Apellido</strong>
						</div>
						<div class="col-md-7" >
							<input id="primerApellido" class="inputs form-control col-md-6" type="text" value="<?= $datosUsuario[0]['primer_apellido'] ?>" >
						</div>
					</div>
					<br>
					<div class="row" >
						<div class="col-md-2 ml-3" >
							<strong>Segundo Apellido</strong>
						</div>
						<div class="col-md-7" >
							<input id="segundoApellido" class="inputs form-control col-md-6" type="text" value="<?= $datosUsuario[0]['segundo_apellido'] ?>" >
						</div>
					</div>
					<br>
					<div class="row" >
						<div class="col-md-2 ml-3" >
							<strong>Email</strong>
						</div>
						<div class="col-md-7" >
							<input id="email" class="inputs form-control col-md-6" type="text" value="<?= $datosUsuario[0]['email'] ?>" >
						</div>
					</div>
					<br>
					<div class="row" >
						<div class="col-md-2 ml-3" >
							<strong>Ciudad</strong>
						</div>
						<div class="col-md-7" >
							<input id="ciudad" class="inputs form-control col-md-6" type="text" value="<?= $datosUsuario[0]['ciudad'] ?>" >
						</div>
					</div>
					<br>
					<div class="row" >
						<div class="col-md-2 ml-3" >
							<strong>Documento</strong>
						</div>
						<div class="col-md-7" >
							<input id="documentoUsuario" class="inputs form-control col-md-6" type="text" value="<?= $datosUsuario[0]['documento'] ?>" >
						</div>
					</div>
				</div>
				<div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
					<br>
					<div class="row" >
						<div class="col-md-2 ml-3" >
							<strong>Distrito</strong>
						</div>
						<div class="col-md-7" >
							<select id="distrito" class="form-control inputs">';
							<?php
							for ($i = 0; $i < count($distritos); $i++) {
								if ($datosUsuario[0]['distrito'] == $distritos[$i][1]) {
									echo '<option class="inputs" value="' . $distritos[$i][0] . '" selected>' . $distritos[$i][1] . '</option>';
								} else {
									echo '<option class="inputs" value="' . $distritos[$i][0] . '">' . $distritos[$i][1] . '</option>';
								}
							}
							?>
							</select>
						</div>
					</div>
					<br>
					<div class="row" >
						<div class="col-md-2 ml-3" >
							<strong>Area</strong>
						</div>
						<div class="col-md-7" >
							<select id="area" class="form-control inputs">';
							<?php
							for ($i = 0; $i < count($areas); $i++) {
								if ($datosUsuario[0]['area'] == $areas[$i][1]) {
									echo '<option class="inputs" value="' . $areas[$i][0] . '" selected>' . $areas[$i][1] . '</option>';
								} else {
									echo '<option class="inputs" value="' . $areas[$i][0] . '">' . $areas[$i][1] . '</option>';
								}
							}
							?>
							</select>
						</div>
					</div>
					<br>
					<div class="row" >
						<div class="col-md-2 ml-3" >
							<strong>Cargo</strong>
						</div>
						<div class="col-md-7" >
							<select id="rol" class="form-control inputs">';
							<?php
							for ($i = 0; $i < count($roles); $i++) {
								if ($datosUsuario[0]['tipo'] == $roles[$i][1]) {
									echo '<option class="inputs" value="' . $roles[$i][0] . '" selected>' . $roles[$i][1] . '</option>';
								} else {
									echo '<option class="inputs" value="' . $roles[$i][0] . '" >' . $roles[$i][1] . '</option>';
								}
							}
							?>
							</select>
						</div>
					</div>
				</div>
			</div>
			<hr>
			<button id="editarUsuario" class="btn btn-outline-success btn-block" >Guardar cambios</button>
		</div>
	</div>
</div>
<?php
$plantilla->pie();
?>