function envioporajax(data, url) {
	respuesta = '';
	$.ajax({
			url: url,
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

function envioDeInformacion() 
{
	var contador=isNaN(parseInt(window.name))?1:parseInt(window.name);
	contador++;
	window.name=contador;
	if (window.name > 2) {
		console.log(contador);
	}else{
		var idPqr = document.getElementById('idPqr').value;
		var randomPqr = document.getElementById('randomPqr').value;
		datos = new FormData();
		datos.append('datos',1);
		datos.append('idPqr',idPqr);
		datos.append('randomPqr',randomPqr);
		ajax = envioporajax(datos,'../controladores/respuesta_usuario.php');
		console.log(ajax);
	}
}
$(document).ready(function(){
	setTimeout(function(){
		envioDeInformacion();
	},1400);
})