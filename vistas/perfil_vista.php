<?php
	include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/clases/plantilla_clase.php';
	$plantilla = new plantilla();
	$plantilla->cabezera();
	$plantilla->cuerpo();
	$plantilla->menuNavegacion();
	$datos = json_decode($arregloDatos['sesion'],true);
?>
<div id="formulariodepqrform" ></div>
<div class="container col-md-10 mt-5" >
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="/vistas/panel_control_vista">Inicio</a></li>
			<li class="breadcrumb-item active" aria-current="page">Perfil</li>
		</ol>
	</nav>
	<div class="cartaperfil card" >
		<div id="cabezaperfil" class="card-header">
			<label><strong>Editar perfil</strong></label>
		</div>
		<div class="card-body">
			<ul class="list-group list-group-flush">
				<li class="list-group-item">
					<div class="row" >
						<div class="col-md-6" >
							<label><strong>Nombre de usuario</strong></label>
						</div>
						<input id="idUsuario" type="hidden" value="<?= $datos['idUsuario'] ?>" >
						<div class="col-md-6">
							<input id="nombreUsuario" value='<?= $datos["nombreUsuario"] ?>' class="form-control" >
						</div>
					</div>
				</li>
				<li class="list-group-item">
					<div class="row" >
						<div class="col-md-6" >
							<label><strong>Primer nombre</strong></label>
						</div>
						<div class="col-md-6" >
							<input id="primerNombre" value='<?= $datos["primerNombre"] ?>' class="form-control" >
						</div>
					</div>
				</li>
				<li class="list-group-item">
					<div class="row" >
						<div class="col-md-6" >
							<label><strong>Segundo nombre</strong></label>
						</div>
						<div class="col-md-6" >
							<input id="segundoNombre" value='<?= $datos["segundoNombre"] ?>' class="form-control" >
						</div>
					</div>
				</li>
				<li class="list-group-item">
					<div class="row" >
						<div class="col-md-6" >
							<label><strong>Primer apellido</strong></label>
						</div>
						<div class="col-md-6" >
							<input id="primerApellido" value="<?= $datos["primerApellido"] ?>" class="form-control" >
						</div>
					</div>
				</li>
				<li class="list-group-item">
					<div class="row" >
						<div class="col-md-6" >
							<label><strong>Segundo apellido</strong></label>
						</div>
						<div class="col-md-6" >
							<input id="segundoApellido" value="<?= $datos["segundoApellido"] ?>" class="form-control" >
						</div>
					</div>
				</li>
				<li class="list-group-item">
					<div class="row" >
						<div class="col-md-6" >
							<label><strong>Email</strong></label>
						</div>
						<div class="col-md-6" >
							<input id="emailUsuario" value="<?= $datos["emailUsuario"] ?>" class="form-control" >
						</div>
					</div>
				</li>
				<li class="list-group-item">
					<div class="row" >
						<div class="col-md-6" >
							<label><strong>Ciudad</strong></label>
						</div>
						<div class="col-md-6" >
							<input id="ciudadUsuario" value="<?= $datos["ciudadUsuario"] ?>" class="form-control" >
						</div>
					</div>
				</li>
				<li class="list-group-item">
					<div class="row" >
						<div class="col-md-6" >
							<label><strong>Documento</strong></label>
						</div>
						<div class="col-md-6" >
							<input id="documentoUsuario" type="number" value="<?= $datos["documento"] ?>" class="form-control" >
						</div>
					</div>
				</li>
			</ul>
			<br>
			<button id="editarperfil" class="btn btn-outline-success" >Guardar cambios</button>
		</div>
	</div>
</div>
<?php
$plantilla->pie();
?>
