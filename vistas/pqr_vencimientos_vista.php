<?php
	$areas = json_decode($arregloDatos['areas'],true);
	$vencimientos = json_decode($arregloDatos['pqrVencimiento'], true);
?>
<div id="listadedistritos" class="col-md-12 mt-2 " >
	<div class="card table-responsive">
		<div class="card-header">
			<center>Vencimiento y recordatorios por <strong>areas</strong></center>
		</div>
		<div class="card-body">
			<div class="row col-md-12" >
			<?php
				for ($i=0; $i < count($areas); $i++) { 
			?>
					<div class="card col-md-4">
						<div class="card-header">
							<h5 class="card-title"><?= $areas[$i]['nombre'] ?></h5>
							<input class="idAreas" type="hidden" value="<?= $areas[$i]['id'] ?>"><i id="<?= $areas[$i]['id'] ?>" class="fas fa-chevron-down contenido"></i>
						</div>
						<div style="display:none" class="card-body otro abrirContenido_<?= $areas[$i]['id'] ?>">
							<button iden="<?= $areas[$i]['id'] ?>" class="btn btn-outline-success peticion" >Peticion</button><button iden="<?= $areas[$i]['id'] ?>" class="btn btn-outline-success queja" >Queja</button><button iden="<?= $areas[$i]['id'] ?>" class="btn btn-outline-success reclamo" >Reclamo</button>
							<hr />
							<div class="col-md-12" >
								<center><strong><p  class="tipo_<?= $areas[$i]['id'] ?>" tipo="1" >Peticion</p></strong></center>
							    <?php
							    	for ($j=0; $j < count($vencimientos); $j++) {
							    		if ($vencimientos[$j]['id_area'] == $areas[$i]['id']) {
							    			if ($vencimientos[$j]['tipo'] == 'peticion') {
							    				echo '
							    				    <div class="contenidoPeticion_'.$areas[$i]['id'].'" >
							    				    	<strong><center>Dias de vencimiento</center></strong>
								    					<input value="'.$vencimientos[$j]['dias_vencimiento'].'" class="form-control Peticion_diasVencimiento_'.$areas[$i]['id'].'" type="number"/>
								    					<strong><center>Dias de vencimiento para ususario</center></strong>
								    					<input value="'.$vencimientos[$j]['dias_vencimiento_usuario'].'" class="form-control Peticion_cierreAutomatico_'.$areas[$i]['id'].'" type="number"/>
								    					<strong><center>Recordatorio</center></strong>
								    					<input value="'.$vencimientos[$j]['dias_recordatorio'].'" class="form-control Peticion_recordatorioPqr_'.$areas[$i]['id'].'" type="number" />
								    				</div>
							    				';
							    			} elseif ($vencimientos[$j]['tipo'] == 'queja') {
							    				echo '
							    				    <div style="display:none" class="contenidoQueja_'.$areas[$i]['id'].'" >
							    				    	<strong><center>Dias de vencimiento</center></strong>
								    					<input value="'.$vencimientos[$j]['dias_vencimiento'].'" class="form-control Queja_diasVencimiento_'.$areas[$i]['id'].'" type="number" />
								    					<strong><center>Dias de vencimiento para ususario</center></strong>
								    					<input value="'.$vencimientos[$j]['dias_vencimiento_usuario'].'" class="form-control Queja_cierreAutomatico_'.$areas[$i]['id'].'" type="number" />
								    					<strong><center>Recordatorio</center></strong>
								    					<input value="'.$vencimientos[$j]['dias_recordatorio'].'" class="form-control Queja_recordatorioPqr_'.$areas[$i]['id'].'" type="number" />
								    				</div>
							    				';
							    			} else {
							    				echo '
							    					<div style="display:none" class="contenidoReclamo_'.$areas[$i]['id'].'" >
							    						<strong><center>Dias de vencimiento</center></strong>
								    					<input value="'.$vencimientos[$j]['dias_vencimiento'].'" class="form-control Reclamo_diasVencimiento_'.$areas[$i]['id'].'" type="number" />
								    					<strong><center>Dias de vencimiento para ususario</center></strong>
								    					<input value="'.$vencimientos[$j]['dias_vencimiento_usuario'].'" class="form-control Reclamo_cierreAutomatico_'.$areas[$i]['id'].'" type="number" />
								    					<strong><center>Recordatorio</center></strong>
									    				<input value="'.$vencimientos[$j]['dias_recordatorio'].'" class="form-control Reclamo_recordatorioPqr_'.$areas[$i]['id'].'" type="number" />
									    			</div>
							    				';
							    			}
							    		}
							    	}
							    ?>
							</div>
							<hr />
							<button value="<?= $areas[$i]['id'] ?>" class="btn btn-outline-primary editar">Editar</button>
						</div>
					</div>	
			<?php
		      }
		    ?>
		    </div>
		</div>
	</div>
</div>
<script>
	function contenido()
	{
		$(".contenido").click(function(){
			let id = $(this).attr('id');
			$(".abrirContenido_" + id).css("display","block");
			$(this).attr('class',"fas fa-chevron-up contenidoInverso");
			contenidoInverso();
		});
	}
	function contenidoInverso()
	{
		$(".contenidoInverso").click(function(){
			let id = $(this).attr('id');
			$(".abrirContenido_" + id).css("display","none");
			$(this).attr('class',"fas fa-chevron-down contenido");
			contenido();

		});
	}
	$(".peticion").click(function(){
		var id = $(this).attr('iden');
		$(".tipo_" + id).html("Peticion");
		$(".tipo_" + id).attr("tipo","1");

		$(".contenidoQueja_" + id).css("display","none");
		$(".contenidoReclamo_" + id).css("display","none");
		$(".contenidoPeticion_" + id).css("display","block");
	});
	$(".queja").click(function(){
		var id = $(this).attr('iden');
		$(".tipo_" + id).html("Queja");
		$(".tipo_" + id).attr("tipo","2");

		$(".contenidoQueja_" + id).css("display","block");
		$(".contenidoReclamo_" + id).css("display","none");
		$(".contenidoPeticion_" + id).css("display","none");
	});
	$(".reclamo").click(function(){
		var id = $(this).attr('iden');
		$(".tipo_" + id).html("Reclamo");
		$(".tipo_" + id).attr("tipo","3");

		$(".contenidoQueja_" + id).css("display","none");
		$(".contenidoReclamo_" + id).css("display","block");
		$(".contenidoPeticion_" + id).css("display","none");
	});

	$(".editar").click(function(){
		let id = $(this).val();
		let data = new FormData();
		let idArea = $(this).val();
		let tipo = $(".tipo_" + id).attr("tipo");
		data.append('idArea',id);
		if (tipo == 1) {
			let diasVencimiento = $('.Peticion_diasVencimiento_' + id).val();
			let cierreAutomatico = $('.Peticion_cierreAutomatico_' + id).val();
			let recordatorioPqr = $('.Peticion_recordatorioPqr_' + id).val();
			data.append('diasVencimiento',diasVencimiento);
			data.append('cierreAutomatico',cierreAutomatico);
			data.append('recordatorioPqr',recordatorioPqr);
			data.append('tipo','peticion');
		} else if (tipo == 2) {
			let diasVencimiento = $('.Queja_diasVencimiento_' + id).val();
			let cierreAutomatico = $('.Queja_cierreAutomatico_' + id).val();
			let recordatorioPqr = $('.Queja_recordatorioPqr_' + id).val();
			data.append('diasVencimiento',diasVencimiento);
			data.append('cierreAutomatico',cierreAutomatico);
			data.append('recordatorioPqr',recordatorioPqr);
			data.append('tipo','queja');
		} else {
			let diasVencimiento = $('.Reclamo_diasVencimiento_' + id).val();
			let cierreAutomatico = $('.Reclamo_cierreAutomatico_' + id).val();
			let recordatorioPqr = $('.Reclamo_recordatorioPqr_' + id).val();
			data.append('diasVencimiento',diasVencimiento);
			data.append('cierreAutomatico',cierreAutomatico);
			data.append('recordatorioPqr',recordatorioPqr);
			data.append('tipo','reclamo');
			
		}
		envioDeinformacion(data);
	});

	function envioDeinformacion(data)
	{
		$.ajax({
			url: '../controladores/pqr_vencimiento_controlador.php',
			processData: false,
			contentType: false,
			type: "POST",
			dataType: "html",
			data: data
		})
		.done(function(datos) {
			datos = JSON.parse(datos);
			if (datos['ejecutado'] == true) {
				fetch('../controladores/pqr_vencimiento_controlador.php');
				Swal.fire('Perfecto','Datos guardados','success');
			} else {
				alert("Ha ocurrido un error");
			}
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("hecho");
		});
	}

	contenido();
</script>