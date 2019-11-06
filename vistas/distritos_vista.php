<?php
	$arregloDatos = $arregloDatos['distritos'];
	$datos = json_decode($arregloDatos,true);	
?>
<div  id="distritooculto" class="col-md-12" >
	<div class="card">
		<div class="card-header">
			<center>Crear <strong>Distritos</strong></center>
		</div>
	<div class="card-body table-responsive">
		<form id="formulariodedistritos" >
			<div class="col-md-12 ml-1" >
				<div class="row ml-1">
					<div>
					  	<i class="fas fa-location-arrow mt-4" ></i>
					</div>
					<div class="col-md-11" >
						<input id="nombrededistrito" autofocus="autofocus" type="text" class="form-control m-3" placeholder="Nombre de distritos..." required>
					</div>
				</div>
				<div class="row ml-1">
					<div>
						<i class="fas fa-location-arrow mt-4"></i>
					</div>
					<div class="col-md-11" >
						<textarea id="descdistrito" placeholder="descripcion.." class="form-control ml-3" required></textarea>
					</div>
				</div>
				<hr class="ml-1">
				<button type="submit" class="btn btn-outline-primary  float-right">Registrar</button>
			</div>
		</form>
	</div>
</div>
</div>
<div id="listadedistritos" class="card col-md-12 mt-4 table-responsive" >
	<div class="card-header">
		<button id="mostrarformularioarea" class="btn btn-outline-success" >Nuevo distrito</button>
		<center><h4>Lista de <strong>Distritos</strong></h4></center>
	</div>
	<div class="card-body mx-auto col-md-11" >
		<table id="tabladistritos" class="table table-borderless">
			<thead class="thead-dark" >
				<tr>
					<th scope="col">ID</th>
					<th scope="col">Nombre</th>
					<th scope="col">Descripcion</th>
					<th scope="col">Eliminar</th>
					<th scope="col">Editar</th>
				</tr>
			</thead>
			<tbody>
			<?php
				if (is_array($datos)) {
					foreach ($datos as $dato) 
					{
						echo '<tr>';
						echo '<td>'.$dato['id'].'</td>';
						echo '<td>'.$dato['nombre'].'</td>';
						echo '<td>'.$dato['descripcion'].'</td>';
						echo '<td><a value="'.$dato['id'].'" class="borrarDistrito" ><i class="fas fa-trash-alt fa-lg"></i></a></td>';
						echo '<td><a href="../controladores/distritos_controlador?infodistri=' . base64_encode($dato['id']) . '" value="'.$dato['id'].'" ><i class="fas fa-edit fa-lg" ></i></a></td>';
						echo '</tr>';
					}
				}	
			?>
			</tbody>
		</table>
	</div>
</div>
