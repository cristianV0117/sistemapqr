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
			data: data
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

function radios() {
	$("#nitradio").click(function(){
		$("#nit").show('slow');
		$("#documento").hide('slow');
	});
	$("#documentoradio").click(function(){
		$("#documento").show('slow');
		$("#nit").hide('slow');
	});
}

function enviodedatospqr() {
	var data = new FormData();
	var campoFarmaco = document.getElementById('campoFarmaco').value;
	var idUsuario = document.getElementById('idUsuario').value;
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
	data.append('idUsuario',idUsuario);
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
		Swal.fire("El archivo supera el peso requerido!", "", "error");
	}
}

$(document).ready(function() {
	$(".txt-content").trumbowyg();
	$("#pqr").fileinput({
		language: 'es',
	});
	$("#nit").hide();
	$("#documento").hide();
	radios();
	document.getElementById('formulariodepqrform').addEventListener('submit', function(event) {
		event.preventDefault();
		enviodedatospqr();
	});
	
});