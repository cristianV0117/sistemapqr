<?php
	include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/clases/plantilla_clase.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/clases/carga_archivos_clase.php';
	$plantilla = new plantilla();
	$plantilla->cabezera();
	$plantilla->cuerpo();
	$plantilla->menuNavegacion();
	$idUsuario = $arregloDatos['idUsuario'];
	$datos     = $arregloDatos['datos'];
	$email 	   = $arregloDatos['email'];
	if (!isset($arregloDatos['imagenes']['ejecutado'])) {
		$archivos  = json_decode($arregloDatos['imagenes'],true);
	}
	
?>
<div class="container col-md-10 mt-5" >
	<div id="formulariodepqrform"></div>
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a  href="/vistas/panel_control_vista">Inicio</a></li>
			<li class="breadcrumb-item"><a href="#" id="atras">Contestar PQR</a></li>
			<li class="breadcrumb-item active" aria-current="page">Respuesta a usuario PQR</li>
		</ol>
	</nav>
	<div class="card" >
		<div class="card-header">
			<center><strong>Respuesta a usuario PQR</strong></center>
		</div>
		<div class="card-body">
			<input id="idUsuario" type="hidden" value="<?= $idUsuario ?>" />
			<input id="idPqr" type="hidden" value="<?= $datos ?>" />
			<input id="email" type="hidden" value="<?= $email ?>" />
			<textarea id="pqrrespuestausuario" name="respuestaUsuario" class="txt-content pqrrespuestausuario" required></textarea>
			<br>
			<h3><strong>Deseas adjuntar un archivo?</strong></h3>
			<?php
			if(isset($archivos))
			{
				for ($i = 0; $i < count($archivos); $i++) 
				{
					echo '
		                <label class="customcheck">' .'<a href="'.$archivos[$i][2].'" download>'.$archivos[$i][3] .'</a>'. '
					        <input value="' . $archivos[$i][0] . '" class="archivousuariorespuesta" name="' . $archivos[$i][1] . '" type="checkbox"/>
					        <span class="checkmark"></span>
						</label>
		            ';
				}
			}
			
			?>
			<hr>  		
			<hr>
			<button id="copiaoculta" type="button" class="btn btn-outline-primary" >Enviar copia oculta</button>
			<br>
			<div id="occ" class="col-md-12" >
				<hr>
				<strong>OCC</strong>
				<div class="alert alert-success" role="alert">
					<strong>Agrega los emails</strong>
				</div>
				<div id="textoemails" ></div>
				<div id="error" >
				</div>
				<div class="row mt-2" >
					<div class="col-md-4" >
						<input placeholder="Correo electronico" id="emailocc" pattern="[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{1,63}$" class="form-control" type="email" />
					</div>
					<br>
					<div class="col-md-3" >
						<button id="buttonemailocc" type="button" class="btn btn-outline-primary" >Agregar</button>
						<button id="buttoncancelocc" type="button" class="btn btn-outline-danger" >Cancelar</button>
					</div>
				</div>
			</div>
			<br>
			<button id="respuestaUsuarioBoton" class="btn btn-outline-success btn-block" >Enviar respuesta</button>
		</div>
	</div>
</div>
<script>
	var atras = document.getElementById("atras").addEventListener("click",function(){
		window.history.go(-1);
	});
</script>
<?php
	$plantilla->pie();
	$carga = new carga(21);
?>
