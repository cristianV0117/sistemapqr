function envioporajax(data,url){
	respuesta = '';
	$.ajax({
		url: url,
		async: false,
		type: "POST",
		dataType: "html",
		data: data
	})
	.done(function( datos ) {
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

function escalarPqr(){
	var idpqr = document.getElementById('pqrid').value;
	var idarea = document.getElementById('idarea').value;
	var arreglo = [
		idpqr,
		idarea,	
	]
	var data = [];
	for (var i = $(".sitesCustom").length - 1; i >= 0; i--) {
		if ($(".sitesCustom")[i].checked == true) {
			data.push(
				 $(".sitesCustom")[i].value
			)
		}
	}
	arreglo.push(data);
	var envio = {
		'info':arreglo
	}
	var ajax = envioporajax(envio,'../controladores/pqr_solucion_controlador.php');
	if (ajax == 1) {
		Swal.fire("PQR!", "Se escaló el PQR", "success");
		setTimeout(function(){
			location.reload();
		},1800);
	}
}

$(document).ready(function(){
	document.getElementById('escalapqr').addEventListener('submit',function( event ){
		event.preventDefault();
		escalarPqr();
	});
})