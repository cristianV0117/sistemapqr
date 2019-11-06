<?php
	include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/clases/plantilla_clase.php';
	$plantilla = new plantilla();
	$plantilla->cabezera();
	$plantilla->cuerpo();
	$plantilla->menuNavegacion();
	$datosAreas = json_decode($arregloDatos['infoArea'],true);
?>
<div class="container col-md-8 mt-5" >
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="/vistas/panel_control_vista">Inicio</a></li>
			<li class="breadcrumb-item active" aria-current="page">Editar Area</li>
		</ol>
	</nav>
	<div id="formulariodepqrform" ></div>
	<div class="card">
		<div class="card-header">
			<label><strong>Editar area</strong></label>
		</div>
		<div class="card-body">
			<div class="row col-md-12" >
				<div class="col-md-6" >
					<strong>Nombre</strong>
					<input id="nombreArea" class="form-control" value="<?= $datosAreas[0]['nombre'] ?>" />
				</div>
				<div class="col-md-6" >
					<strong>descripcion</strong>
					<input id="descArea" class="form-control" value="<?= $datosAreas[0]['descripcion'] ?>" />
				</div>
			</div>
			<hr />
			<button value="<?= $datosAreas[0]['id'] ?>" class="btn btn-outline-success float-right editarArea" >Guardar datos</button>
		</div>
	</div>
</div>
<?php
$plantilla->pie();
?>