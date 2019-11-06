<?php
	include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/clases/plantilla_clase.php';
	$plantilla = new plantilla();
	$plantilla->cabezera();
	$plantilla->cuerpo();
	$plantilla->menuNavegacion();
	$idUsuario = $arregloDatos['idUsuario'];
?>

<div id="formulariodepqrform" ></div>
<div class="container col-md-9 mt-5" >
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="/vistas/panel_control_vista">Inicio</a></li>
			<li class="breadcrumb-item active" aria-current="page">Seguridad</li>
		</ol>
	</nav>
	<div class="card" >
		<div class="card-header" >
			<h4><center><strong>Cambio de contraseña</strong></center></h4>
		</div>
		<div class="card-body" >
			<div class="col-md-12" >
				<div class="row justify-content-md-center">
					<div class="col-md-4" >
						<input id="idUsuario" value="<?= $idUsuario ?>" type="hidden" />
						<input id="nuevaContrasena" placeholder="contraseña" class="form-control" type="text" readonly/>
					</div>
					<div class="col-md-4">
						<button id="generarContrasena" class="btn btn-outline-warning" >Generar contraseña</button>
					</div>
					<div class="col-md-8 mt-3">
						<input id="aceptarCambioContrasena" type="checkbox"><strong> He copiado mi contraseña</strong>
						<br>
						<button id="guardarCambiosNuevaContrasena" class="btn btn-outline-success" disabled>Guardar cambios</button>
					</div>
				</div>
				<br>
				<div class="col-md-12 justify-content-md-center">
					<div id="respuesta" >
					</div>
				</div>
			</div>
		</div>
	</div>	
</div>
<?php
	$carga = new carga(1);
	$carga = new carga(2);
	$carga = new carga(4);
	$carga = new carga(7);
?>
</body>
</html>
