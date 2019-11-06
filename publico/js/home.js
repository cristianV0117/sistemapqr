function radios() {
	$("#nitradio").click(function(){
		$("#nit").show('slow');
		$("#documento").hide('slow');
		$("#nitrut").attr('required','true');
		$("#codigoverificacion").attr('required','true');
	});
	$("#documentoradio").click(function(){
		$("#documento").show('slow');
		$("#nit").hide('slow');
		$("#documentopersonal").attr('required','true');
	});
}

function formularios() {
	$("#formulariodeingreso").hide();
	$("#formulariodeingresologeo").click(function(){
		$("#formulariodeingreso").show('slow');
		$("#formulariodepqr").hide('slow');
		$("#consulpqr").hide();
	});
	$(".formularioenviarpqr").click(function(){
		$("#formulariodeingreso").hide('slow');
		$("#consulpqr").hide();
		$("#formulariodepqr").show('slow');
	});
	$("#pqrconsulta").click(function(){
		$("#consulpqr").show('slow');
		$("#formulariodeingreso").hide('slow');
		$("#formulariodepqr").hide('slow');
	});
}

function farmaco() 
{
	try {
		$("#farmaco").click(function(){
			$("#formulariodepqr").show('slow');
			$("#tituloprincipal").html('FARMACO VIGILANCIA');
			$("#farmaco").html('Enviar PQR');
			$("#farmaco").attr('id','enviarPqr');
			$("#campoFarmaco").attr('value',1);
			$("#formulariodeingreso").hide('slow');
			$("#consulpqr").hide();
			enviarPqr();

		});
	} catch(e) {
		console.log(e);
	}
}

function enviarPqr() 
{
	try {
		$("#enviarPqr").click(function(){
			$("#formulariodepqr").show('slow');
			$("#tituloprincipal").html('<i class="fas fa-hand-point-up"></i> PQR:');
			$("#enviarPqr").html('Farmaco Vigilancia');
			$("#enviarPqr").attr('id','farmaco');
			$("#campoFarmaco").attr('value',0);
			$("#formulariodeingreso").hide('slow');
			$("#consulpqr").hide();
			farmaco();
		});
	} catch(e) {
		console.log(e);
	}
}

$(document).ready(function(){
	$(".txt-content").trumbowyg();
	$(".form-control").keyup(function(){
		$(".label").hide("slow");
	});
	$("#nit").hide();
	$("#documento").hide();
	$("#pqr").fileinput({
		language: 'es',
	});
	$("#consulpqr").hide();
	radios();
	formularios();
	farmaco();
	enviarPqr();  
	//console.log($('.form-control'));
});
