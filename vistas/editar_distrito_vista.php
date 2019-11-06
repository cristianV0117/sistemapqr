<?php
	include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/clases/plantilla_clase.php';
	$plantilla = new plantilla();
	$plantilla->cabezera();
	$plantilla->cuerpo();
	$plantilla->menuNavegacion();
	$datosDistritos = json_decode($arregloDatos['infoDistrito'],true);
?>
<div class="container col-md-8 mt-5" >
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="/vistas/panel_control_vista">Inicio</a></li>
			<li class="breadcrumb-item active" aria-current="page">Editar Distrito</li>
		</ol>
	</nav>
	<div id="formulariodepqrform" ></div>
	<div class="card">
		<div class="card-header">
			<label><strong>Editar distrito</strong></label>
		</div>
		<div class="card-body">
			<div class="row col-md-12" >
				<div class="col-md-6" >
					<strong>Nombre</strong>
					<input id="nombreDistrito" class="form-control" value="<?= $datosDistritos[0]['nombre'] ?>" />
				</div>
				<div class="col-md-6" >
					<strong>descripcion</strong>
					<input id="descDistrito" class="form-control" value="<?= $datosDistritos[0]['descripcion'] ?>" />
				</div>
			</div>
			<hr />
			<button value="<?= $datosDistritos[0]['id'] ?>" class="btn btn-outline-success float-right editarDistrito" >Guardar datos</button>
		</div>
	</div>
</div>
<?php
$plantilla->pie();
?>