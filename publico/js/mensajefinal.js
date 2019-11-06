function envioporajax(data, url) {
	respuesta = '';
	$.ajax({
			url: url,
			async: false,
			processData: false,
			contentType: false,
			contentType: false,
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

function acepto() {
	$("#acepto").click(function(){
		datos = new FormData();
		var idPqr = document.getElementById('idPqr').value;
		datos.append('idPqr',idPqr);
		datos.append('tipo','aceptarUsuario');
		ajax = envioporajax(datos,'../controladores/respuesta_usuario.php');
		try
		{
			respuesta = JSON.parse(ajax);
			switch(respuesta.status)
			{
				case 1:
					$("#aceptoRes").html('<div class="alert alert-success" role="alert">Tu respuesta se ha envaido</div>');
					setTimeout(function(){
						location.reload();
					},1500);
				break;
				case 0:
					Swal.fire('Error',respuesta.error,'error');
				break;
			}
		}
		catch (error)
		{
			console.log(error);
		}
	});
}

function noAceptoRechazo() {
	$("#noAceptoBoton").click(function(){
		$("#razonRechazo").show('slow');
	});
}

function noAcepto() {
	$("#noAcepto").click(function(){
		datos = new FormData();
		var idPqr = document.getElementById('idPqr').value;
		var randomPqr = document.getElementById('randomPqr').value;
		var razon = document.getElementById('razon').value;
		datos.append('idPqr',idPqr);
		datos.append('randomPqr',randomPqr);
		datos.append('razon',razon);
		datos.append('tipo','rechazarUsuario');
		ajax = envioporajax(datos,'../controladores/respuesta_usuario.php');
		try {
			respuesta = JSON.parse(ajax);
			switch(respuesta.status)
			{
				case 1:
					$("#aceptoRes").html('<div class="alert alert-success" role="alert">Tu respuesta se ha envaido</div>');
					setTimeout(function(){
						location.reload();
					},1500);
				break;
				case 0:
					Swal.fire('Error',respuesta.error,'error');
				break;
			}
		} catch (error) {
			console.log(error);
		}
	});
}

$(document).ready(function(){
	$("#razonRechazo").hide();
	acepto();
	noAceptoRechazo();
	noAcepto();
});