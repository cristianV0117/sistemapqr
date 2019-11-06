<?php
	$datosDistritos = json_decode($arregloDatos['distritos'],true);
	$datosAreas = json_decode($arregloDatos['areas'],true);
	$datosRoles = json_decode($arregloDatos['roles'],true);
	if (!isset($arregloDatos['usuarios']['ejecutado'])) {
		$datosUsuarios = json_decode($arregloDatos['usuarios'],true); 
	}
?>
<div id="usuariooculto" class="col-md-12" >
	<div class="card">
		<div class="card-header">
			<div>
				<center><i class="fa fa-users" ></i> Crear <strong>Usuarios</strong></center>
			</div>
			<br>
			<div class="alert alert-danger" role="alert">
				<strong>Recuerde que el "nombre de usuario" será por defecto la contraseña del usuario</strong>
			</div>
		</div>
		<div class="card-body">
			<form id="formulariodeusuarios" >
				<div class="row ml-2 col-md-12" >
					<div>
					  	<i class="fa fa-user-tie" ></i>
					</div>
					<div class="col-md-11" >
					  	<input autofocus="autofocus" id="nombreUsuario" class="form-control  type="text" placeholder="Nombre de usuario.." readonly>
					</div>
				</div>
				<br>
				<hr>
				<div class="row ml-4 mt-3">
					<div>
					 	<i class="fa fa-user-circle" ></i>
					</div>
					<div class="col-md-6" >
					  	<input  id="primerNombre" class="form-control" type="text" placeholder="Primer nombre.." required>
					</div>
					<div>
					  	<i class="fa fa-user-circle" ></i>
					</div>
					<div class="col-md-5" >
					  	<input id="segundoNombre" class="form-control"  type="text" placeholder="Segundo nombre.." >
					</div>
				</div>
				<div class="row ml-4 mt-3">
					<div>
					  	<i class="fa fa-user-circle" ></i>
					</div>
					<div class="col-md-6" >
					  	<input  id="primerApellido" class="form-control" type="text" placeholder="Primer apellido.." required>
					</div>
					<div>
					  	<i class="fa fa-user-circle" ></i>
					</div>
					<div class="col-md-5" >
					  	<input id="segundoApellido" class="form-control"  type="text" placeholder="Segundo apellido.." >
					</div>
				</div>
				<div class="row ml-4 mt-3">
					<div>
					  	<i class="fas fa-at" ></i>
					</div>
					<div class="col-md-5" >
					  	<input  id="email" class="form-control" type="email" placeholder="Email.." required>
					</div>
					<div>
					  	<i class="fas fa-id-card" ></i>
					</div>
					<div class="col-md-3" >
					  	<input id="documento" class="form-control"  type="number" placeholder="Documento.." required>
					</div>
					<div>
					  	<i class="fas fa-city" ></i>
					</div>
					<div class="col-md-3" >
					  	<input id="ciudad" class="form-control"  type="text" placeholder="Ciudad.." required>
					</div>
				</div>
				<br>
				<hr>
				<hr>
				<br>
				<div class="row" >
					<div class="col-md-4" >
					  	<label><strong>Distrito</strong></label>
						<select id="distrito" class="form-control" required>';
						<?php
						foreach ($datosDistritos as $value) 
						{
							echo'<option value="' . $value['id'] . '" >' . $value['nombre'] . '</option>';
						}
						?>
						</select>
					</div>
					<div class="col-md-4">
						<label><strong>Area</strong></label>
						<select id="area" class="form-control" required>';
						<?php
						foreach ($datosAreas as $value) 
						{
							echo'<option value="' . $value['id'] . '" >' . $value['nombre'] . '</option>';
						}
						?>
						</select>
					</div>
					<div class="col-md-4" >
						<label><strong>Rol</strong></label>
						<select id="rol" class="form-control" required>';
						<?php
						foreach ($datosRoles as $value) 
						{
							echo'<option value="' . $value['id'] . '" >' . $value['tipo'] . '</option>';
						}
						?>
						</select>
					</div>
				</div>
				<input id="contrasena" class="form-control mt-5" type="hidden">
				<button class="btn btn-outline-primary float-right mt-5" >Registrar</button>
			</form>
		</div>
	</div>
</div>
<div id="listadeusuarios" class="col-md-12 mt-4" >
	<div class="card">
		<div class="card-header">
			<button id="mostrarformularioausuario" class="btn btn-outline-success" >Nuevo usuario</button>
			<center><h4>Lista de <strong>Usuarios</strong></h4></center>
		</div>
		<div class="card-body table-responsive">
			<table id="tablausuarios" class="table table-borderless">
				<thead class="thead-dark" >
					<tr>
						<th scope="col">ID</th>
						<th scope="col">Usuario</th>
						<th scope="col">1.er nombre</th>
						<th scope="col">2.do nombre</th>
						<th scope="col">1.er apellido</th>
						<th scope="col">2.do apellido</th>
						<th scope="col">Email</th>
						<th scope="col">Documento</th>
						<th scope="col">Ciudad</th>
						<th scope="col">Ver mas</th>
						<th scope="col">Eliminar</th>
						<th scope="col">Seguridad</th>
					</tr>
				</thead>
				<tbody>
				<?php
					if (isset($datosUsuarios)) {
						foreach ($datosUsuarios as $value) {
							echo '<tr>';
							echo '<td>'.$value['id'].'</td>';
							echo '<td>'.$value['nombre_usuario'].'</td>';
							echo '<td>'.$value['primer_nombre'].'</td>';
							echo '<td>'.$value['segundo_nombre'].'</td>';
							echo '<td>'.$value['primer_apellido'].'</td>';
							echo '<td>'.$value['segundo_apellido'].'</td>';
							echo '<td>'.$value['email'].'</td>';
							echo '<td>'.$value['documento'].'</td>';
							echo '<td>'.$value['ciudad'].'</td>';
							echo '<td><a href="../controladores/usuarios_controlador?infousuario=' . base64_encode($value['id']) . '" ><i class="fa fa-eye fa-lg" ></i></a></td>';
							echo '<td><i usuario="' . $value['id'] . '"  class="fas fa-trash-alt fa-lg borrarUsuario"></i></td>';
							echo '<td><a href="../controladores/usuarios_controlador?seguridadUsuario=' . base64_encode($value['id']) . '" ><i class="fas fa-lock fa-lg"></i></a></td>';
							echo '</tr>';
						}
					}
				?>
				</tbody>
			</table>
		</div>
	</div>
</div>