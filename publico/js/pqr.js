function envioporajax(data, url) {
	respuesta = '';
	$.ajax({
			url: url,
			async: false,
			processData: false,
			contentType: false,
			contentType: false,
			enctype: 'multipart/form-data',
			type: "POST",
			dataType: "html",
			data: data,
			beforeSend: function ()
			{
				var respuesta = toastr.success("Cargando",'Cargando petición',{
					"positionClass": "toast-bottom-right",
					"extendedTimeOut": "2500",
					"timeOut": "4500",
					"progressBar":true,
					"preventOpenDuplicates":true,
					"preventDuplicates":true
				});
			}	
		})
		.done(function(datos) {
			respuesta = datos;
		})
		.fail(function() {
			//console.log("error");
		})
		.always(function() {
			//console.log("hecho");
		});
	return respuesta;
}

function enviodedatospqr() 
{
	var data = new FormData();
	var campoFarmaco = document.getElementById('campoFarmaco').value;
	var nombrecompleto = document.getElementById('nombrecompleto').value;
	var documento = document.getElementById('documentopersonal').value;
	var nitrut = document.getElementById('nitrut').value;
	var codigo = document.getElementById('codigoverificacion').value;
	var email = document.getElementById('email').value;
	var ciudad = document.getElementById('ciudad').value;
	var telefono = document.getElementById('telefono').value;
	var celular = document.getElementById('celular').value;
	var imagen = document.getElementById('pqr').files[0];
	var mensaje = $(".pqrmensaje").val();
	var hash = btoa("$2y$10$1QzxkgkV4W9B.Y9ZbWdxeO.w41pBT/1b1qWVlPriLvOhuIISXmdj6");
	data.append('nombrecompleto', nombrecompleto);
	data.append('documento', documento);
	data.append('nitrut', nitrut);
	data.append('codigo', codigo);
	data.append('email', email);
	data.append('ciudad', ciudad);
	data.append('telefono', telefono);
	data.append('celular', celular);
	data.append('mensaje', mensaje);
	data.append('hash', hash);
	data.append('tipo', 'insercion');
	data.append('imagen', imagen);
	data.append('campoFarmaco',campoFarmaco);
	var ajax = envioporajax(data, '../controladores/pqr_controlador.php');
	try {
		var res = JSON.parse(ajax);
		if (res.funcion == 1 ) {
			Swal.fire({
				title: 'PQR enviado con exito',
				type: 'success',
				html: 'Tu codigo de PQR es <strong>' + res.respuesta + "</strong> Por favor tenga en cuenta su codigo para futuras consultas<br><br>Se ha enviado un mensaje al correo electronico registrado",
				confirmButtonColor: '#28a745',
				confirmButtonText: 'OK',
			}).then((result) => {
				location.reload();
			})
		}else{
			Swal.fire("Error en el ingreso del PQR!", "", "error");
		}
	} catch(e) {
		Swal.fire("Ha sucedido un error al registrar el PQR ó el archivo supera el peso requerido!", "", "error");
	}
}

function consultarpqrfiltro(){
	$("#consultarpqrfiltro").click(function(){
		datos = new FormData();
		var codigo = document.getElementById('consultarpqr').value;
		datos.append('codigo',codigo);
		datos.append('tipo','consulta');
		var ajax = envioporajax(datos,'../controladores/pqr_controlador.php');
		var respuesta = JSON.parse(ajax);
		console.log(respuesta);
		if (!respuesta == false) {
			if (typeof respuesta['fecha'] == 'undefined') {
				if (respuesta['estado'] == 'abierto') {
					$("#info").html('<div class="row ml-5" >'
					+'<div class="col-md-4" >'
					+'<p><strong>Codigo</strong><br><strong>'+respuesta['identidicacion']+'</strong></p><hr>'
					+'</div>'
					+'<div class="col-md-4" >'
					+'<p><strong>Fecha de registro</strong><br><strong>'+respuesta['fecha_subida']+'</strong></p><hr>'
					+'</div>'
					+'<div>'
					+'<strong>ESTADO</strong><strong><h4><span class="badge badge-pill badge-danger">'+respuesta['estado']+'</h4></span></strong>'
					+'</div>'
				   	+'</div>');
				}else{
					$("#info").html('<div class="row ml-5" >'
					+'<div class="col-md-3" >'
					+'<p><strong>Codigo</strong><br><strong>'+respuesta['identidicacion']+'</strong></p><hr>'
					+'</div>'
					+'<div class="col-md-3" >'
					+'<p><strong>Fecha de registro</strong><br><strong>'+respuesta['fecha_subida']+'</strong></p><hr>'
					+'</div>'
					+'<div class="col-md-2" >'
					+'<strong>ESTADO</strong><strong><h4><span class="badge badge-pill badge-warning">'+respuesta['estado']+'</h4></span></strong>'
					+'</div>'
					+'<div class="col-md-4" >'
					+'<strong>Respuestas recibidas</strong><br>'
					+'<a href="https://'+window.location.hostname+'/controladores/respuesta_usuario.php?pqrUltima='+btoa(respuesta['id'])+'&&rand='+btoa('sinrand')+'" id="guardarDatosVisualizcion"  ><h4><span class="badge badge-pill badge-success"><i class="fas fa-eye"></i></span></h4></a>'
					+'</div>'
				   	+'</div>');
				   	guardarDatosVisualizcion(respuesta['id']);
				}
			}else{
				$("#info").html('<div class="row ml-5" >'
					+'<div class="col-md-3" >'
					+'<p><strong>Codigo</strong><br><strong>'+respuesta['identidicacion']+'</strong></p><hr>'
					+'</div>'
					+'<div class="col-md-3" >'
					+'<p><strong>Fecha de respuesta</strong><br><strong>'+respuesta['fecha']+'</strong></p><hr>'
					+'</div>'
					+'<div class="col-md-2" >'
					+'<strong>ESTADO</strong><strong><h4><span class="badge badge-pill badge-success">'+respuesta['estado']+'</h4></span></strong>'
					+'</div>'
					+'<div class="col-md-4" >'
					+'<strong>Respuestas recibidas</strong><br>'
					+'<a href="https://'+window.location.hostname+'/controladores/respuesta_usuario.php?pqrUltima='+btoa(respuesta['id'])+'&&rand='+btoa('sinrand')+'" id="guardarDatosVisualizcion" href="#" ><h4><span class="badge badge-pill badge-success"><i class="fas fa-eye fa-lg"></i></span></h4></a>'
					+'</div>'
				   	+'</div>');
				guardarDatosVisualizcion(respuesta['id']);
			}
		}else{
			$("#info").html("<strong>Codigo invalido</strong>");
		}
	});

}

function guardarDatosVisualizcion(codigo)
{
	document.getElementById('guardarDatosVisualizcion').addEventListener('click',function(){
		data = new FormData();
		data.append('general',true);
		data.append('codigo',codigo);
		var ajax = envioporajax(data, '../controladores/respuesta_usuario.php');
		console.log(ajax);
	})
}

$(document).ready(function() {
	document.getElementById('formulariodepqrform').addEventListener('submit', function(event) {
		event.preventDefault();
		enviodedatospqr();
	});
	consultarpqrfiltro();
});