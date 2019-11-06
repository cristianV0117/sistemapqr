<?php
/**
 *
 * @author Cristian Camilo Vasquez
 * Clase plantilla
 * Se incluyen:
 * El archivo de carga de archivos js.
 *
 */
include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/clases/carga_archivos_clase.php';
/**
 * Se verifica si existe una sesion activa.
 */
if (!isset($_SESSION)) {
	session_name('PQRUSER');
	session_start();
}

class plantilla 
{
	/**
     * @method:cabezera() Se encarga de crear la cabezera de la plataforma PQR.
     * Incluye los archivos correspondientes css para su funcionamiento.
 	 */
	public function cabezera() 
	{
		echo '
			<!DOCTYPE html>
			<!--
			             +yhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh+
			             hhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh+
			             hhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh+
			             -------------:/ohhhhhhhhhhhhhhhhMBhhhhh+
			                             +hhhhhhhhhhhhhhhhhhyo/.
			             .------..``    -yhhhhhhhhhhhhhhs+:.
			            shh:.......-:/oyhhhhhhhhhhhhs/-`
			             :os/. `-/shhhhhhhhhhhhyo:.                      `````````````
			               .ohyhhhhhhhhhhhhs+:`             ::-`       -ohhhhhhhhhhhs.
			          `-/oyhhhhhhhhhhhhs/-`  .:::::::::`     `.:/++/./shhhhhhhhhhy+.
			      `:+shhhhhhhhhhhhhy/..::::-./hhhhhhhhh-          .oyhhhhhhhhhhy:`
			  ./oyhhhhhhhhhhhhhhhhh.        .+hhhhhhhhh-        -shhhhhhhhhhhys:.
			ohhhhhhhhhhhhhhhhhhhhhho-````````/hhhhhhhhh/---.../yhhhhhhhhhhy+.ohhhyo/.
			hhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhho-::+hhhhhhhs.
			hhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh--:://+++++++/`
			hhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh+`
			-:::::-`````````````````````:::::ohhhhhhhhh+::::/+yhhhhhhhhhhh+.
			                                 /hhhhhhhhh-       -shhhhhhhhhhhs:`
			                                 /hhhhhhhhh-         .+oyhhhhhhhhhy/`
			                                 -sssssssss.             :shhhhhhhhhho-
			                                                           .+hhhhhhhhhhy/`
			                                                             `+hhhhhhhhhhy+.
			                                                               -shhhhhhhhhhhs:
			                                                                 .+hhhhhhhhhhhy+
			 -->
			<html>
			<head>
				<title>PQR</title>
				<link rel="stylesheet" type="text/css" href="/node_modules/bootstrap/dist/css/bootstrap.css">
				<link rel="stylesheet" type="text/css" href="/publico/css/estilos.css">
				<link rel="stylesheet" type="text/css" href="/node_modules/@fortawesome/fontawesome-free/css/all.css">
				<link rel="stylesheet" type="text/css" href="/publico/css/toastr.min.css">
				<link rel="stylesheet" type="text/css" href="/node_modules/datatables.net-bs4/css/dataTables.bootstrap4.css">
				<link rel="stylesheet" type="text/css" href="/externos/editor/dist/trumbowyg.min.css">
				<link media="all" rel="stylesheet" type="text/css" href="/node_modules/bootstrap-fileinput/css/fileinput.css">
				<link rel="stylesheet" type="text/css" href="/node_modules/sweetalert2/dist/sweetalert2.css">
				<link rel="icon" href="/archivos/favicon.png" type="image/x-icon"/>
				<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
			</head>
		';
	}

	/**
     * @method:cuerpo() Se encarga de abrir la etiqueta <body>; Al ser instanciado este metodo se abre el body en el respectivo documento.
 	 */
	public function cuerpo() 
	{
		echo '
			<body>
		';
	}

	/**
     * @method:menuNavegacion() Se encarga de crear el menu de navegación principal de la plataforma.
 	 */
	public function menuNavegacion() {
		echo '
	  		<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
			  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			    <span class="navbar-toggler-icon"></span>
			  </button>
			  <div class="collapse navbar-collapse" id="navbarSupportedContent">
			    <ul class="navbar-nav mr-auto">
			    		<li class="nav-item dropdown">
					    	<a class="nav-link dropdown-toggle  navbar-brand" role="button" data-toggle="dropdown" href="#">
		    					<img style="border-radius:50%;" src="../archivos/plantilla.jpg" width="30" height="30" class="d-inline-block align-top" alt="">
		  					</a>
		  					<div class="dropdown-menu">
					          	<a class="dropdown-item" href="../controladores/nuestroPerfil?usuario=' . base64_encode($_SESSION['idUsuario']) . '">Pefil</a>
					          	<div class="dropdown-divider"></div>
					          	<a class="dropdown-item" href="../controladores/usuarios_controlador?seguridadUsuario=' . base64_encode($_SESSION['idUsuario']) . '">Contraseña</a>
					          	<div class="dropdown-divider"></div>			 
					          	<a class="dropdown-item" href="../controladores/cerrar_sesion_controlador.php">Cerrar sesion</a>
			        		</div>
		  				</li>
		  			</ul>
			  </div>
			</nav>
			<div id="formulariod epqrform"></div>
			<div class="row row-contenido" >
	  	';
	}

	/**
     * @method:pie() Se encarga de crear el pie de la plataforma en el cual se encuentran anexados archivos JS correspondientes.
     * @var:$objcarga Almacena la clase carga(argumento) la cual se encarga de obtener los archivos JS.
 	 */
	public function pie() {
		echo '
				</div>';
		$objcarga = new carga(1);
		$objcarga = new carga(7);
		$objcarga = new carga(3);
		$objcarga = new carga(4);
		$objcarga = new carga(2);
		$objcarga = new carga(5);
		$objcarga = new carga(11);
		$objcarga = new carga(12);
		$objcarga = new carga(16);
		//Se cierran las etiquetas de body y html.
		echo '
		</body>
	</html>
		';
	}

	/**
     * @method:migaPan() Se encarga de crear la miga de pan del sitio para que el usuario tenga conocimiento de en que parte del sitio se encuentra.
 	 */
	public function migaPan() {
		echo '
			<nav aria-label="breadcrumb">
			  <ol class="breadcrumb">
			    <li class="breadcrumb-item active" aria-current="page">Inicio</li>
			  </ol>
			</nav>

		';
	}

	/**
	 *
     * @method:menuLateral() Se encarga de crear el menu de gestion de la plataforma.
     * Este menú dependera sus funciones del rol del usuario activo en plataforma.
     * Se filtra las opciones del menu dependiendo de la variable @var:$_SESSION['rolUsuario'].
     *
 	 */
	public function menuLateral() {
		try {
			switch ($_SESSION['rolUsuario']) {
			case 1:
				echo '
						<div id="lateral" class="col-md-2 mt-3">
							<div class="list-group" id="list-tab" role="tablist">
						      <a class="list-group-item list-group-item-action active" id="list-home-list"  href="/vistas/panel_control_vista" >
						      	<i class="ml-2 fa fa-home" ></i>
						      	<span class="ml-2" ><strong>Inicio</strong></span>
						      </a>
						      <a id="distritos" class="list-group-item list-group-item-action" id="list-profile-list" data-toggle="list" href="#list-profile" role="tab" aria-controls="profile">
						      	<i class=" ml-2 fas fa-location-arrow" ></i>
						      	<span class="ml-2" ><strong>Distritos</strong></span>
						      </a>
						      <a id="areas" class="list-group-item list-group-item-action" id="list-messages-list" data-toggle="list" href="#list-messages" role="tab" aria-controls="messages">
						      	<i class="ml-2 fas fa-warehouse"></i>
						      	<span class="ml-2" ><strong>Areas</strong></span>
						      </a>
						      <a id="usuarios" class="list-group-item list-group-item-action" id="list-settings-list" data-toggle="list" href="#list-settings" role="tab" aria-controls="settings">
						      	<i class="ml-2 fas fa-users"></i>
						      	<span class="ml-2" ><strong>Usuarios</strong></span>
						      </a>
						      <hr>
						      <a id="vencimientoPqr" class="list-group-item list-group-item-action" id="list-settings-list" data-toggle="list" href="#list-settings" role="tab" aria-controls="settings">
						      	<i class="ml-2 fas fa-clock"></i>
						      	<span class="ml-2" ><strong>Vencimiento PQR</strong></span>
						      </a>
						      <a id="pqrfiltrado" class="list-group-item list-group-item-action" id="list-settings-list" data-toggle="list" href="#list-settings" role="tab" aria-controls="settings">
						      	<i class="ml-2 fas fa-hand-point-up"></i>
						      	<span class="ml-2" ><strong>PQR filtrados</strong></span>
						      </a>
						      <a id="pqrsolucionado" class="list-group-item list-group-item-action" id="list-settings-list" data-toggle="list" href="#list-settings" role="tab" aria-controls="settings">
						      	<i class="ml-2 fas fa-check-circle"></i>
						      	<span class="ml-2" ><strong>PQR cerrados</strong></span>
						      </a>
						      <a id="informes" class="list-group-item list-group-item-action" id="list-settings-list" data-toggle="list" href="#list-settings" role="tab" aria-controls="settings" >
						      	<i class="ml-2 fas fa-file-download"></i>
						      	<span class="ml-2" ><strong>Informes</strong></span>
						      </a>
						      <a id="ingresosSalidas" class="list-group-item list-group-item-action" id="list-settings-list" data-toggle="list" href="#list-settings" role="tab" aria-controls="settings" >
						      	<i class="ml-2 fas fa-user-clock"></i>
						      	<span class="ml-2" ><strong>Ingresos y salidas</strong></span>
						      </a>
			    			</div>
						</div>
						<i id="lateralmenu" class="fas fa-bars mt-4 ml-4 fa-lg" ></i>
					';
				break;
			case 2:
				echo '
						<div id="lateral" class="col-md-2 mt-3">
							<div class="list-group" id="list-tab" role="tablist">
						      <a class="list-group-item list-group-item-action active" id="list-home-list"  href="/vistas/panel_control_vista" >
						      	<i class="ml-2 fa fa-home" ></i>
						      	<span class="ml-2" ><strong>Inicio</strong></span>
						      </a>
						      <a id="pqrsolucionado" class="list-group-item list-group-item-action" id="list-settings-list" data-toggle="list" href="#list-settings" role="tab" aria-controls="settings">
						      	<i class="ml-2 fas fa-check-circle"></i>
						      	<span class="ml-2" ><strong>PQR cerrados</strong></span>
						      </a>
			    			</div>
						</div>
						<i id="lateralmenu" class="fas fa-bars mt-4 ml-4 fa-lg" ></i>
				';
				break;
			case 3:
				echo '
						<div id="lateral" class="col-md-2 mt-3">
							<div class="list-group" id="list-tab" role="tablist">
						      <a class="list-group-item list-group-item-action active" id="list-home-list"  href="/vistas/panel_control_vista" >
						      	<i class="ml-2 fa fa-home" ></i>
						      	<span class="ml-2" ><strong>Inicio</strong></span>
						      </a>
						      <a id="distritos" class="list-group-item list-group-item-action" id="list-profile-list" data-toggle="list" href="#list-profile" role="tab" aria-controls="profile">
						      	<i class=" ml-2 fas fa-location-arrow" ></i>
						      	<span class="ml-2" ><strong>Distritos</strong></span>
						      </a>
						      <a id="areas" class="list-group-item list-group-item-action" id="list-messages-list" data-toggle="list" href="#list-messages" role="tab" aria-controls="messages">
						      	<i class="ml-2 fas fa-warehouse"></i>
						      	<span class="ml-2" ><strong>Areas</strong></span>
						      </a>
						      <hr>
						      <a id="pqrfiltrado" class="list-group-item list-group-item-action" id="list-settings-list" data-toggle="list" href="#list-settings" role="tab" aria-controls="settings">
						      	<i class="ml-2 fas fa-hand-point-up"></i>
						      	<span class="ml-2" ><strong>PQR filtrados</strong></span>
						      </a>
			    			</div>
						</div>
						<i id="lateralmenu" class="fas fa-bars mt-4 ml-4 fa-lg" ></i>
				';
				break;
			case 4:
				echo '
						<div id="lateral" class="col-md-2 mt-3">
							<div class="list-group" id="list-tab" role="tablist">
						      <a class="list-group-item list-group-item-action active" id="list-home-list"  href="/vistas/panel_control_vista" >
						      	<i class="ml-2 fa fa-home" ></i>
						      	<span class="ml-2" ><strong>Inicio</strong></span>
						      </a>
			    			</div>
						</div>
						<i id="lateralmenu" class="fas fa-bars mt-4 ml-4 fa-lg" ></i>
				';
				break;
			case 5:
				echo '
						<div id="lateral" class="col-md-2 mt-3">
							<div class="list-group" id="list-tab" role="tablist">
						      <a class="list-group-item list-group-item-action active" id="list-home-list"  href="/vistas/panel_control_vista" >
						      	<i class="ml-2 fa fa-home" ></i>
						      	<span class="ml-2" ><strong>Inicio</strong></span>
						      </a>
			    			</div>
						</div>
						<i id="lateralmenu" class="fas fa-bars mt-4 ml-4 fa-lg" ></i>
				';
				break;
			case 6:
				echo '
						<div id="lateral" class="col-md-2 mt-3">
							<div class="list-group" id="list-tab" role="tablist">
						      <a class="list-group-item list-group-item-action active" id="list-home-list"  href="/vistas/panel_control_vista" >
						      	<i class="ml-2 fa fa-home" ></i>
						      	<span class="ml-2" ><strong>Inicio</strong></span>
						      </a>
						      <a id="usuarios" class="list-group-item list-group-item-action" id="list-settings-list" data-toggle="list" href="#list-settings" role="tab" aria-controls="settings">
						      	<i class="ml-2 fas fa-users"></i>
						      	<span class="ml-2" ><strong>Usuarios</strong></span>
						      </a>
						      <hr>
						      <a id="pqrsolucionado" class="list-group-item list-group-item-action" id="list-settings-list" data-toggle="list" href="#list-settings" role="tab" aria-controls="settings">
						      	<i class="ml-2 fas fa-check-circle"></i>
						      	<span class="ml-2" ><strong>PQR cerrados</strong></span>
						      </a>
			    			</div>
						</div>
						<i id="lateralmenu" class="fas fa-bars mt-4 ml-4 fa-lg" ></i>
					';
				break;
			case 7:
				echo '
						<div id="lateral" class="col-md-2 mt-3">
							<div class="list-group" id="list-tab" role="tablist">
						      <a class="list-group-item list-group-item-action active" id="list-home-list"  href="/vistas/panel_control_vista" >
						      	<i class="ml-2 fa fa-home" ></i>
						      	<span class="ml-2" ><strong>Inicio</strong></span>
						      </a>
						      <a id="pqrsolucionado" class="list-group-item list-group-item-action" id="list-settings-list" data-toggle="list" href="#list-settings" role="tab" aria-controls="settings">
						      	<i class="ml-2 fas fa-check-circle"></i>
						      	<span class="ml-2" ><strong>PQR cerrados</strong></span>
						      </a>
			    			</div>
						</div>
						<i id="lateralmenu" class="fas fa-bars mt-4 ml-4 fa-lg" ></i>
				';
				break;
			case '8':
				echo '
						<div id="lateral" class="col-md-2 mt-3">
							<div class="list-group" id="list-tab" role="tablist">
						      <a class="list-group-item list-group-item-action active" id="list-home-list"  href="/vistas/panel_control_vista" >
						      	<i class="ml-2 fa fa-home" ></i>
						      	<span class="ml-2" ><strong>Inicio</strong></span>
						      </a>
			    			</div>
						</div>
						<i id="lateralmenu" class="fas fa-bars mt-4 ml-4 fa-lg" ></i>
				';
				break;
			default:
				# code...
				break;
			}
		} catch (Exception $e) {
			
		}

	}
}