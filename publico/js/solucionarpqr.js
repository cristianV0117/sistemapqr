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

function enviarDatos() {
	Swal.fire({
		title: "Estas seguro de guardar?",
		icon: "warning",
		showCancelButton:true,
		confirmButtonColor: '#28a745',
		cancelButtonColor: '#dc3545',
		confirmButtonText: 'OK',
		cancelButtonText: 'Cancelar'
	}).then(function(isConfirm) {
		console.log(isConfirm);
		if (isConfirm.value) {
			var datos = {}
			var mensaje = $(".pqrsolucion").val();
			var idPqr = $("#idPqr").val();
			var randomPqr = $("#randomPqr").val();
			var enviodato = new FormData();
			enviodato.append('idPqr', idPqr);
			enviodato.append('solucion', mensaje);
			enviodato.append('randomPqr',randomPqr);
			var data = document.getElementById('archivossolucionpqr').files.length;
			for (var i = 0; i < data; i++) {
				enviodato.append('archivos[]', document.getElementById('archivossolucionpqr').files[i]);
			}
			for (var i = 0; i < $(".emailsolucion").length; i++) {
				enviodato.append('email[]',$(".emailsolucion")[i].value);
			}
			var ajax = envioporajax(enviodato, '../controladores/pqr_solucion_controlador.php');
			Swal.fire("PQR!", "Se solucionó el PQR", "success");
			setTimeout(function() {
				location.reload();
			}, 1800);
		} else {
			Swal.fire("Cancelado", "", "error");
		}
	})
}

$(document).ready(function() {
	$("#pqrsolucion").trumbowyg();
	$("#archivossolucionpqr").fileinput({
		language: 'es',
	});
	document.getElementById('solucionarpqr').addEventListener('submit', function(event) {
		event.preventDefault();
		enviarDatos();
	});
})