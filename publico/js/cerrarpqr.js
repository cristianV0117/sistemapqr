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

function aceptarCerrarPqr() {
	$("#cerrarPqr").click(function() {
		Swal.fire({
			title: 'Quieres guardar los cambios?',
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#28a745',
			cancelButtonColor: '#dc3545',
			confirmButtonText: 'Si, cerrar PQR',
			cancelButtonText: 'Cancelar'
		}).then((result) => {
			if (result.value) {
				Swal.fire({
					title: 'Descripcion',
					confirmButtonText: 'Guardar',
					confirmButtonColor: '#28a745',
					html:'<input id="cerrarpqr" placeholder="Descripcion..." class="swal2-input" />',
				}).then((result) => {
					var cerrar = document.getElementById('cerrarpqr').value;
					var idPqr = document.getElementById('idPqr').value;
					datos = new FormData();
					datos.append('cerrar',cerrar);
					datos.append('idPqr',idPqr);
					var ajax = envioporajax(datos,'../controladores/pqr_solucion_controlador.php');
					if (ajax == 1) {
						Swal.fire("PQR!", "Se solucionó el PQR", "success");
						setTimeout(function() {
							location.reload();
						}, 1800);
					}
				});
			} else {
				Swal.fire('Cancelado', '', 'error');
			}
		})
	});
}

$(document).ready(function() {
	aceptarCerrarPqr();
});