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

function enviodefiltradopqr(){
	arregloEmails = [];
	var occemails = document.getElementsByClassName('emailEscrito');
	for(i in occemails)
	{
		arregloEmails.push(occemails[i].innerHTML);
	}
	var numeroPqr = document.getElementById('numeroPqr').value;
	var iddepqr = document.getElementById('iddepqr').value;
	var iddedistrito = document.getElementById('iddedistrito').value;
	var iddearea = document.getElementById('iddearea').value;
	var iddetipo = document.getElementById('iddetipo').value;
	var data = {};
	data = {
		'iddepqr':iddepqr,
		'iddedistrito':iddedistrito,
		'iddearea':iddearea,
		'iddetipo':iddetipo,
		'tipo':'filtrado',
		'numeroPqr':numeroPqr,
		'emailsocc':arregloEmails
	}
	console.log(data);
	var ajax = envioporajax(data,'../controladores/pqr_controlador.php');	
	Swal.fire("PQR!", "Se filtrado el PQR", "success");
	setTimeout(function(){
		window.history.go(-1);
	},2000);
}


$(document).ready(function(){
	document.getElementById('formulariodefiltrado').addEventListener('submit',function( event ){
		event.preventDefault();
		enviodefiltradopqr();
		verificarEmail();
		copiaoculta();
		cerrarOcc();
	});
	$("#occ").hide('slow');
})