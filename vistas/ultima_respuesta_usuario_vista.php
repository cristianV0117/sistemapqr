<?php
	$var = $_SERVER['HTTP_HOST'] . '/';
	if ($arregloDatos['tipo'] == 'novacio') 
	{
		$datos    = json_decode($arregloDatos['datos'],true);
		if (!isset($arregloDatos['archivos']['ejecutado'])) {
			$archivos = json_decode($arregloDatos['archivos'],true);
		} else {
			$archivos = true;
		}
?>
<html>
	<head>
		<title>PQR</title>
		<link rel="stylesheet" type="text/css" href="/node_modules/bootstrap/dist/css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="/publico/css/estilos.css">
		<link rel="stylesheet" type="text/css" href="/node_modules/fortawesome/fontawesome-free/css/all.css">
		<link rel="icon" href="/archivos/favicon.png" type="image/x-icon"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	</head>
	<body>
		<div class="container col-md-10 mt-5" >
			<strong><center>Respuestas</center></strong>
			<ul class="nav nav-tabs" id="myTab" role="tablist">
				<li class="nav-item">
	    			<a class="nav-link active" id="contenido-tab" data-toggle="tab" href="#contenido" role="tab" aria-controls="contenido" aria-selected="true">Contenido</a>
	  			</li>
	  			<li class="nav-item">
	    			<a class="nav-link" id="archivos-tab" data-toggle="tab" href="#archivos" role="tab" aria-controls="archivos" aria-selected="false">Todos los Archivos</a>
	  			</li>
			</ul>
			<div  class="tab-content" id="tab">
				<div class="tab-pane fade show active" id="contenido" role="tabpanel" aria-labelledby="contenido-tab">
				<?php
						$HTML = '';
						for ($i=0; $i < count($datos); $i++) { 
							
								$HTML .= '
									<br />
									<div class="card">
										<div class="card-header">
											<strong>Mensaje</strong>
										</div>
										<div class="card-body" >
											'.$datos[$i]['mensaje'].'
											<hr />
											<strong>Fecha</strong><br />
											'.$datos[$i]['fecha'].'
											<br />
											<br />';
											if (!$archivos === true) {
												for ($n=0; $n < count($archivos); $n++) { 
													if ($archivos[$n]['id_pqr_respuesta'] == $datos[$i]['id']) {
														$HTML .= '
															<br><a href="'.$archivos[$n]['ruta'].'" >'.$archivos[$n]['nombre'].'</a>
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
				?>
				</div>
  				<div class="tab-pane fade" id="archivos" role="tabpanel" aria-labelledby="archivos-tab">
					<?php
					if (!$archivos === true) {
						echo '<hr><br><strong>Archivos adjuntos</strong>';
						for ($n=0; $n < count($archivos); $n++) { 
						?>
						<?= '<br><a href="'.$archivos[$n]['ruta'].'" >'.$archivos[$n]['nombre'].'</a>' ?>
						<?php
						}
					} else {
						echo 'No hay archivos';
					}
					?>
  				</div>
			</div>
		</div>
		<script src="../node_modules/jquery/dist/jquery.js" ></script>	
		<script src="../node_modules/bootstrap/dist/js/bootstrap.js" ></script>						
	</body>
</html>
<?php
	}
	else
	{
?>
<html>
	<head>
		<title>PQR</title>
		<link rel="stylesheet" type="text/css" href="/node_modules/bootstrap/dist/css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="/publico/css/estilos.css">
		<link rel="stylesheet" type="text/css" href="/node_modules/fortawesome/fontawesome-free/css/all.css">
		<link rel="icon" href="/archivos/favicon.png" type="image/x-icon"/>
	</head>
	<body>
		<div class="container col-md-10 mt-5" >
			<div class="card">
				<div class="card-header">
					<strong>Respuesta</strong>
				</div>
				<div class="card-body" >
					<div class="alert alert-success" role="alert">
							<strong>Aún no tienes respuestas disponibles</strong>
					</div>
						<hr>
						<a href="https:/<?= $_SERVER['HTTP_HOST'] ?>" ><button class="btn btn-outline-primary" >Regresar</button></a>
				</div>
			</div>
		</div>
	</body>
</html>
<?php
   }
?>