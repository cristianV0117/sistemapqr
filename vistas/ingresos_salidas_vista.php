<?php
	$arregloDatos = $arregloDatos['ingresos_salidas'];
	$datos = json_decode($arregloDatos,true);
?>
<div id="listadedistritos" class="card col-md-12 mt-4 table-responsive" >
	<div class="card-header">
		<center><h4><strong>Ingresos y salidas a la plataforma</strong></h4></center>
	</div>
	<div class="card-body mx-auto col-md-11" >
		<table id="ingresos_salidas" class="table table-borderless">
			<thead class="thead-dark" >
				<tr>
					<th scope="col">Usuario</th>
					<th scope="col">Tipo</th>
					<th scope="col">Fecha</th>
					<th scope="col">IP</th>
					<th scope="col">Navegador</th>
					<th scope="col">SO</th>
				</tr>
			</thead>
			<tbody>
				<?php
					foreach ($datos as $dato) 
					{
						echo '<tr>';
							echo '<td>'.$dato['nombre_usuario'].'</td>';
							echo '<td>'.$dato['tipo'].'</td>';
							echo '<td>'.$dato['fecha'].'</td>';
							echo '<td>'.$dato['ip'].'</td>';
							echo '<td>'.$dato['navegador'].'</td>';
							echo '<td>'.$dato['so'].'</td>';
						echo '</tr>';
					}	
				?>		
			</tbody>
		</table>
	</div>
</div>