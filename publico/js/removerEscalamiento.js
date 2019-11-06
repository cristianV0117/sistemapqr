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

function removerescalarPqr()
{
	try {
		var idPqr = document.getElementById('pqridr').value;
		var arreglo = [
			idPqr
		];
		var data = [];
		for (var i = $(".claseRemover").length - 1; i >= 0; i--) {
		if ($(".claseRemover")[i].checked == true) {
				data.push(
					 $(".claseRemover")[i].value
				)
			}
		}
		arreglo.push(data);
		var envio = {
			'remover':arreglo
		}
		var ajax = envioporajax(envio,'../controladores/pqr_solucion_controlador.php');
		if (ajax) {
			Swal.fire("PQR!", "Se removió el PQR", "success");
			setTimeout(function(){
				location.reload();
			},1800);
		}
	} catch(e) {
		console.log(e);
	}
}

$(document).ready(function(){
	document.getElementById('removerEscalamiento').addEventListener('submit',function( event ){
		event.preventDefault();
		removerescalarPqr();
	});
})