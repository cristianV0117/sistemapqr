<?php
	$tipoPqr = json_decode($arregloDatos['tipopqr'],true);
	$distritos = json_decode($arregloDatos['distritos'],true);
	$areas = json_decode($arregloDatos['areas'],true);
?>
<div class="col-md-12 card" >
	<div class="card-header" >
		<center><strong><h4>Informes</h4></strong></center>
	</div>
	<div class="card-body">
		<div class="row" >
			<div class="col-md-4" >
				<strong>Tipo de PQR</strong>
				<select id="tipoPqr" class="form-control" >
					<option value="0" >No aplica</option>
					<?php
					foreach ($tipoPqr as $value) 
					{
						echo '<option value="'.$value['id'].'" >'.$value['tipo'].'</option>';
					}
					?>	
				</select>
			</div>
			<div class="col-md-4" >
				<strong>Distrito</strong>
				<select id="distrito" class="form-control" >
					<option value="0" >No aplica</option>
					<?php
					foreach ($distritos as $value) 
					{
						if ($value['id'] != 0) 
						{
							echo '<option value="'.$value['id'].'" >'.$value['nombre'].'</option>';
						}
					}
					?>		
				</select>
			</div>
			<div class="col-md-4" >
				<strong>Area</strong>
				<select id="area" class="form-control" >
					<option value="0.1" >No aplica</option>
					<?php
					foreach ($areas as $value) 
					{
						echo '<option value="'.$value['id'].'" >'.$value['nombre'].'</option>';
					}
					?>		
				</select>
			</div>
		</div>
		<br>
		<div class="row" >
			<div class="col-md-6" >			
				<input id="desde" class="form-control" type="date" />
			</div>
			<div class="col-md-6" >				
				<input id="hasta" class="form-control" type="date" />
			</div>
		</div>
		<br>
		<button id="generarInforme" class="btn btn-outline-success float-right" >Generar</button>
		<br>
		<hr>
		<div id="infoInformes" class="col-md-12 table-responsive" >
		</div>
	</div>
</div>
			
