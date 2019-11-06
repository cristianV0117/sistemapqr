function envioporajax(data, url) {
	respuesta = '';
	$.ajax({
			url: url,
			async: false,
			processData: false,
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

function envioporfetch(data,url) 
{
	return fetch(url,{method:'POST',body:data})
	.then(response => {
		if(response.ok)
		{
			return response.json();
		}
		else
		{
			console.log('error');
		}
	})
	.catch(error => {
		console.log(error);
	});
	
}

function envioDatosAreas() {
	mostrarformularios();
	document.getElementById('formulariodeareas').addEventListener('submit', function(event) {
		event.preventDefault();
		var data = new FormData();
		var nombrearea = document.getElementById('nombredearea').value;
		var descarea = document.getElementById('descarea').value;
		data.append('nombredearea',nombrearea);
		data.append('descarea',descarea);
		data.append('tipo','insercion');
		var ajax = envioporajax(data, '../controladores/areas_controlador.php');
		try
		{
			respuesta = JSON.parse(ajax);
			switch(respuesta.status)
			{
				case 1:
					var datosRecibidos = peticionesDeVistas("../controladores/areas_controlador.php", "get", null);
					Swal.fire('Perfecto!','Se ha registrado el area','success');
					setTimeout(function() {
						$("#info").html(datosRecibidos);
						mostrarformularios();
						envioDatosAreas();
						$("#tablaareas").DataTable({
							"pageLength": 20,
							"order": [
								[0, "desc"]
							],
							"language": {
								"lengthMenu": "Mostrar _MENU_ registros",
								"zeroRecords": "No hay usuarios",
								"info": "mostrar pagina _PAGE_ de _PAGES_",
								"infoEmpty": "No records available",
								"search": "Buscar:",
								"infoFiltered": "(filtered from _MAX_ total records)",
								"paginate": {
									"first": "Primero",
									"last": "Ultimo",
									"next": "Siguiente",
									"previous": "Anterior"
								},
							}
						});
					}, 20);
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

function envioDatosDistritos() {
	mostrarformularios();
	document.getElementById('formulariodedistritos').addEventListener('submit', function(event) {
		event.preventDefault();
		var data = new FormData();
		var nombrededistrito = document.getElementById('nombrededistrito').value;
		var descdistrito = document.getElementById('descdistrito').value;
		data.append('nombrededistrito',nombrededistrito);
		data.append('descdistrito',descdistrito);
		data.append('tipo','insercion');
		var ajax = envioporajax(data, '../controladores/distritos_controlador.php');
		try
		{
			respuesta = JSON.parse(ajax);
			switch(respuesta.status)
			{
				case 1:
					var datosRecibidos = peticionesDeVistas("../controladores/distritos_controlador.php", "get", "info", null);
					Swal.fire("Perfecto!",respuesta.respuesta, "success");
					setTimeout(function() {
						$("#info").html(datosRecibidos);
						mostrarformularios();
						envioDatosDistritos();
						$("#tabladistritos").DataTable({
							"pageLength": 20,
							"order": [
								[0, "desc"]
							],
							"language": {
								"lengthMenu": "Mostrar _MENU_ registros",
								"zeroRecords": "No hay usuarios",
								"info": "mostrar pagina _PAGE_ de _PAGES_",
								"infoEmpty": "No records available",
								"search": "Buscar:",
								"infoFiltered": "(filtered from _MAX_ total records)",
								"paginate": {
									"first": "Primero",
									"last": "Ultimo",
									"next": "Siguiente",
									"previous": "Anterior"
								},
							}
						});
					}, 20);
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

function inputContrasena() {
	$("#primerNombre").keyup(function(){
		var primerNombre = $("#primerNombre").val();
		$("#primerApellido").keyup(function(){
			var primerApellido = $("#primerApellido").val();
			$("#nombreUsuario").val(primerNombre + "." + primerApellido +"."+ Math.floor(Math.random() * 99));
		});
		$("#nombreUsuario").val(primerNombre + "." + primerApellido.value);
		
	});
}

function envioDatosUsuarios() {
	mostrarformularios();
	document.getElementById('formulariodeusuarios').addEventListener('submit', function(event) {
		event.preventDefault();
		$("#contrasena").val($("#nombreUsuario").val());
		var data = new FormData();
		var nombredeusuario = document.getElementById('nombreUsuario').value;
		var primernombre = document.getElementById('primerNombre').value;
		var segundonombre = document.getElementById('segundoNombre').value;
		var primerapellido = document.getElementById('primerApellido').value;
		var segundoapellido = document.getElementById('segundoApellido').value;
		var email = document.getElementById('email').value;
		var documento = document.getElementById('documento').value;
		var ciudad = document.getElementById('ciudad').value;
		var distrito = document.getElementById('distrito').value;
		var area = document.getElementById('area').value;
		var rol = document.getElementById('rol').value;
		var contrasena = document.getElementById('contrasena').value;
		data.append('nombredeusuario',nombredeusuario);
		data.append('primernombre',primernombre);
		data.append('segundonombre',segundonombre);
		data.append('primerapellido',primerapellido);
		data.append('segundoapellido',segundoapellido);
		data.append('email',email);
		data.append('documento',documento);
		data.append('ciudad',ciudad);
		data.append('distrito',distrito);
		data.append('area',area);
		data.append('rol',rol);
		data.append('contrasena',contrasena);
		data.append('tipo','insercion');
		var ajax = envioporajax(data, '../controladores/usuarios_controlador.php');
		console.log(ajax);
		try
		{
			respuesta = JSON.parse(ajax);
			switch(respuesta.status)
			{
				case 1:
					Swal.fire('Perfecto',respuesta.respuesta,'success');
					var datosRecibidos = peticionesDeVistas("../controladores/usuarios_controlador.php", "get", null);
					setTimeout(function() {
					$("#info").html(datosRecibidos);
					inputContrasena();
					mostrarformularios();
					envioDatosUsuarios();
					$("#tablausuarios").DataTable({
						"pageLength": 20,
						"order": [
							[0, "desc"]
						],
						"language": {
							"lengthMenu": "Mostrar _MENU_ registros",
							"zeroRecords": "No hay usuarios",
							"info": "mostrar pagina _PAGE_ de _PAGES_",
							"infoEmpty": "No records available",
							"search": "Buscar:",
							"infoFiltered": "(filtered from _MAX_ total records)",
							"paginate": {
								"first": "Primero",
								"last": "Ultimo",
								"next": "Siguiente",
								"previous": "Anterior"
							},
						}
					});
				}, 20);
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

function borrarUsuario() {
	$(".borrarUsuario").click(function() {
		var idUsuario = this.getAttribute('usuario');
		data = new FormData();
		data.append('idUsuario',idUsuario);
		data.append('tipo','eliminacion');
		Swal.fire({
			title: 'Quieres borrar el usuario?',
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#28a745',
			cancelButtonColor: '#dc3545',
			confirmButtonText: 'Si, Borrar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {
			if (result.value) 
			{
				var ajax = envioporajax(data, '../controladores/usuarios_controlador.php');
				try
				{
					respuesta = JSON.parse(ajax);
					switch(respuesta.status)
					{
						case 1:
							Swal.fire('Perfecto',respuesta.respuesta,'success');
							setTimeout(function(){
								location.reload();
							},1300);
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
			} else 
			{
				Swal.fire('Cancelado', '', 'error');
			}
		})
	})
}

function editarperfil(){
	$("#editarperfil").click(function(){
		var idUsuario = document.getElementById('idUsuario').value;
		var nombreUsuario = document.getElementById('nombreUsuario').value;
		var primerNombre = document.getElementById('primerNombre').value;
		var segundoNombre = document.getElementById('segundoNombre').value;
		var primerApellido = document.getElementById('primerApellido').value;
		var segundoApellido = document.getElementById('segundoApellido').value;
		var emailUsuario = document.getElementById('emailUsuario').value;
		var ciudadUsuario = document.getElementById('ciudadUsuario').value;
		var documento = document.getElementById('documentoUsuario').value;
		datos = new FormData();
		datos.append('nombreUsuario',nombreUsuario);
		datos.append('primerNombre',primerNombre);
		datos.append('segundoNombre',segundoNombre);
		datos.append('primerApellido',primerApellido);
		datos.append('segundoApellido',segundoApellido);
		datos.append('emailUsuario',emailUsuario);
		datos.append('ciudadUsuario',ciudadUsuario);
		datos.append('documento',documento);
		datos.append('idUsuario',idUsuario);
		//for (var value of datos.values()) {
		//   console.log(value); 
		//}*/
		var ajax = envioporajax(datos,'../controladores/nuestroPerfil.php');
		try
		{
			respuesta = JSON.parse(ajax);
			switch(respuesta.status)
			{
				case 1:
					Swal.fire('Perfecto',respuesta.respuesta,'success');
					setTimeout(function(){
						location.reload();
					},1300);
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

function edicionUsuario(){
	$("#editarUsuario").click(function(){
		var datos = new FormData();
		var idUsuario = document.getElementById('idUsuario').value;
		var nombreUsuario = document.getElementById('nombreUsuario').value;
		var primerNombre = document.getElementById('primerNombre').value;
		var segundoNombre = document.getElementById('segundoNombre').value;
		var primerApellido = document.getElementById('primerApellido').value;
		var segundoApellido = document.getElementById('segundoApellido').value;
		var email = document.getElementById('email').value;
		var ciudad = document.getElementById('ciudad').value;
		var documento = document.getElementById('documentoUsuario').value;
		var distrito = document.getElementById('distrito').value;
		var area = document.getElementById('area').value;
		var rol = document.getElementById('rol').value;
		datos.append('idUsuario',idUsuario);
		datos.append('nombreUsuario',nombreUsuario);
		datos.append('primerNombre',primerNombre);
		datos.append('segundoNombre',segundoNombre);
		datos.append('primerApellido',primerApellido);
		datos.append('segundoApellido',segundoApellido);
		datos.append('email',email);
		datos.append('ciudad',ciudad);
		datos.append('documento',documento);
		datos.append('distrito',distrito);
		datos.append('area',area);
		datos.append('rol',rol);
		datos.append('tipo','edicion');
		var ajax = envioporajax(datos,'../controladores/usuarios_controlador.php');
		try
		{
			respuesta = JSON.parse(ajax);
			switch(respuesta.status)
			{
				case 1:
					Swal.fire('Perfecto',respuesta.respuesta,'success');
					setTimeout(function(){
						location.reload();
					},1300);
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


function respuestaUsuario(){
	$("#respuestaUsuarioBoton").click(function(){
		arregloEmails = [];
		var occemails = document.getElementsByClassName('emailEscrito');
		for(i in occemails)
		{
			arregloEmails.push(occemails[i].innerHTML);
		}
		data = [];
		arreglo = [];
		var mensaje = $(".pqrrespuestausuario").val();
		var idUsuario = $("#idUsuario").val();
		var idPqr = $("#idPqr").val();
		var email = $("#email").val();
		arreglo = [
			idPqr,
			idUsuario,
			mensaje,
			email,
			arregloEmails
		];
		for (var i = $(".archivousuariorespuesta").length - 1; i >= 0; i--) {
		if ($(".archivousuariorespuesta")[i].checked == true) {
				data.push(
				 	$(".archivousuariorespuesta")[i].value
				)
			}
		}
		arreglo.push(data);
		var envio = {
			'info':arreglo
		}
		Swal.fire({
			title: 'Quieres guardar cambios?',
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#28a745',
			cancelButtonColor: '#dc3545',
			confirmButtonText: 'Si, Guardar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {
			if (result.value) {
				$.ajax({
				url: '../controladores/respuesta_usuario.php',
				async: false,
				type: "POST",
				dataType: "html",
				data: envio
				})
				.done(function( datos ) {
					console.log(datos);
			    	Swal.fire('Perfecto','Datos guardados','success');
					setTimeout(function(){
						location.reload();
					},1700);
			  	})
			  	.fail(function() {
			    	console.log("error");
			  	})
				.always(function() {
					console.log("hecho");
				});
			} else {
				Swal.fire('Cancelado', '', 'error');
			}
		})
	});
}

function vencimientoPqr() {
	$("#vencimientopqrboton").click(function(){
		datos = new FormData();
		datos.append('tipo','vencimientoPqr');
		for (var i = $(".inputVencimieto").length - 1; i >= 0; i--) {
			var tipo = $(".inputVencimieto")[i].getAttribute('id') +"_"+ $(".inputVencimieto")[i].value;
			datos.append(''+i+'',tipo); 
		}
		var e = 3;
		for (var i = $(".inputRecordatorio").length - 1; i >= 0; i--) {
			var tipo = $(".inputRecordatorio")[i].getAttribute('id') +"_"+ $(".inputRecordatorio")[i].value;
			datos.append(''+ e++ +'',tipo); 
		}

		ajax = envioporajax(datos,'../controladores/pqr_vencimiento_controlador.php');
		console.log(ajax);
		if (ajax == 1) {
			var datosRecibidos = peticionesDeVistas('../controladores/pqr_vencimiento_controlador.php',"get","info",null);
	    	Swal.fire('Perfecto','Datos guardados','success');
			setTimeout(function(){
				$("#info").html(datosRecibidos);
				vencimientoPqr();
			},1700);
	    }
	});
}

function vencimientoPqrRespuesta() {
	$("#vencimientousuarioboton").click(function(){
		datos = new FormData();
		datos.append('tipo','respuestaVencimientoUsuario');
		for (var i = $(".inputVencimietoRespuesta").length - 1; i >= 0; i--) {
			var tipo = $(".inputVencimietoRespuesta")[i].getAttribute('id') +"_"+ $(".inputVencimietoRespuesta")[i].value;
			datos.append(''+i+'',tipo); 
		}
		ajax = envioporajax(datos,'../controladores/pqr_vencimiento_controlador.php');
		console.log(ajax);
		if (ajax == 1) {
			var datosRecibidos = peticionesDeVistas('../controladores/pqr_vencimiento_controlador.php',"get","info",null);
	    	Swal.fire('Perfecto','Datos guardados','success');
			setTimeout(function(){
				$("#info").html(datosRecibidos);
				vencimientoPqr();
			},1700);
	    }
	});
}

function editarDatosPqr() {
	
	$("#editarDatosPqr").click(function(){
		var tipoPqr = document.getElementById('tipoPqr').value;
		var areaEditar = document.getElementById('areaEditar').value;
		var distritoEditar = document.getElementById('distritoEditar').value;
		var idPqr = document.getElementById('idPqr').value;
		datos.append('tipoPqr',tipoPqr);
		datos.append('areaEditar',areaEditar);
		datos.append('distritoEditar',distritoEditar);
		datos.append('idPqr',idPqr);
		datos.append('tipo','edicion');
		ajax = envioporajax(datos,'../controladores/pqr_controlador.php');
		if (ajax == 1) {
	    	Swal.fire('Perfecto','Datos guardados','success');
			setTimeout(function(){
				location.reload();
			},1500);
	    }
	});
}

function borrarDistrito() {
	try {

		datos = new FormData();
		$(".borrarDistrito").click(function(){
			var id = $(".borrarDistrito").attr('value');
			datos.append('idDistritoBorrar',id);
			datos.append('tipo','eliminacion');
			ajax = envioporajax(datos,'../controladores/distritos_controlador.php');
			try
			{
				respuesta = JSON.parse(ajax);
				switch(respuesta.status)
				{
					case 1:
						Swal.fire('Perfecto',respuesta.respuesta,'success');
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
			setTimeout(function(){
				var datosRecibidos = peticionesDeVistas("../controladores/distritos_controlador.php", "get", "info", null);
				$("#info").html(datosRecibidos);
				mostrarformularios();
				envioDatosDistritos();
				$("#tabladistritos").DataTable({
					"pageLength": 20,
					"order": [
						[0, "desc"]
					],
					"language": {
					"lengthMenu": "Mostrar _MENU_ registros",
					"zeroRecords": "No hay registros",
					"info": "mostrar pagina _PAGE_ de _PAGES_",
					"infoEmpty": "No records available",
					"search": "Buscar:",
					"infoFiltered": "(filtered from _MAX_ total records)",
					"paginate": {
							"first": "Primero",
							"last": "Ultimo",
							"next": "Siguiente",
							"previous": "Anterior"
						},
					}
				});
			},20);
		});
	} catch(e) {
		console.log(e);
	}
}

function editarDistrito()
{
	try {
		datos = new FormData();
		$(".editarDistrito").click(function(){
			id = this.value;
			nombreDistrito = $("#nombreDistrito").val();
			descDistrito   = $("#descDistrito").val();
			datos.append('id',id);
			datos.append('nombreDistrito',nombreDistrito);
			datos.append('descDistrito',descDistrito);
			datos.append('tipo','edicion');
			ajax = envioporajax(datos,'../controladores/distritos_controlador.php');
			try {
				respuesta = JSON.parse(ajax);
				switch(respuesta.status)
				{
					case 1:
						Swal.fire('Perfecto',respuesta.respuesta,'success');
					break;
					case 0:
						Swal.fire('Error',respuesta.error,'error');
					break;
				}
			}
			catch (error) {
				console.log(error);
			}
		});
	} catch (e) {
		console.log(e);
	}
}

function borrarArea() {
	try {
		datos = new FormData();
		$(".borrarArea").click(function(){
			var id = $(".borrarArea").attr('value');
			datos.append('idAreaBorrar',id);
			datos.append('tipo','eliminacion');
			ajax = envioporajax(datos,'../controladores/areas_controlador.php');
			try
			{
				respuesta = JSON.parse(ajax);
				switch(respuesta.status)
				{
					case 1:
						Swal.fire('Perfecto',respuesta.respuesta,'success');
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
			setTimeout(function(){
				var datosRecibidos = peticionesDeVistas("../controladores/areas_controlador.php", "get", null);
				$("#info").html(datosRecibidos);
				mostrarformularios();
				envioDatosAreas();
					$("#tablaareas").DataTable({
						"pageLength": 20,
						"order": [
							[0, "desc"]
						],
						"language": {
							"lengthMenu": "Mostrar _MENU_ registros",
							"zeroRecords": "No hay registros",
							"info": "mostrar pagina _PAGE_ de _PAGES_",
							"infoEmpty": "No records available",
							"search": "Buscar:",
							"infoFiltered": "(filtered from _MAX_ total records)",
							"paginate": {
								"first": "Primero",
								"last": "Ultimo",
								"next": "Siguiente",
								"previous": "Anterior"
							},
						}
					});
			},20);
		});
	} catch(e) {
		console.log(e);
	}
}

function editarArea()
{
	try {
		datos = new FormData();
		$(".editarArea").click(function(){
			id = this.value;
			nombreArea = $("#nombreArea").val();
			descArea   = $("#descArea").val();
			datos.append('id',id);
			datos.append('nombreArea',nombreArea);
			datos.append('descArea',descArea);
			datos.append('tipo','edicion');
			ajax = envioporajax(datos,'../controladores/areas_controlador.php');
			try {
				respuesta = JSON.parse(ajax);
				switch(respuesta.status)
				{
					case 1:
						Swal.fire('Perfecto',respuesta.respuesta,'success');
					break;
					case 0:
						Swal.fire('Error',respuesta.error,'error');
					break;
				}
			}
			catch (error) {
				console.log(error);
			}
		});
	} catch (e) {
		console.log(e);
	}
}

function generarInforme() 
{
	try {
		datos = new FormData();
		$("#generarInforme").click(function(){
			var tipoPqr = $("#tipoPqr").val();
			var distrito = $("#distrito").val();
			var area = $("#area").val();
			if ($("#desde").val().length > 0 ) {
				var desde = $("#desde").val();
			}else{
				var desde = 0;
			}
			if($("#hasta").val().length > 0){
				var hasta = $("#hasta").val();
			}else{
				var hasta = 0;
			}
			datos.append('tipoPqr',tipoPqr);
			datos.append('distrito',distrito);
			datos.append('area',area);
			datos.append('desde',desde);
			datos.append('hasta',hasta);
			var ajax = envioporajax(datos,'../controladores/informes_controlador.php');
			var respuesa = JSON.parse(ajax);
			var a = JSON.parse(respuesta);
			var HTML = "";
			if(!a == 0)
			{
				HTML = "<table class='table' >"
							+"<thead class='thead-dark'>"
							+"<tr>"
							+"<th>ID</th>"
							+"<th>PQR</th>"
							+"<th>SOLICITANTE</th>"
							+"<th>FECHA DE FILTRADO</th>"
							+"<th>ESTADO</th>"
							+"<th>VER</th>"
							+"<th>PDF</th>"
							+"</tr>"
							+"</thead>"
							+"<tbody id='bodyInformes' >"
							+"</tbody>"
							+"</table>";
				var informes = "";
				var prueba = "";
				var estadoBage = "";
				for(var i in a)
				{
					switch (a[i][10]) {
					 	case 'finalizado':
					 		estadoBage = '<span class="badge badge-success">'+a[i][10]+'</span>';
					 		prueba = informes += "<tr><td>"+a[i][13]+"</td><td>"+a[i][9]+"</td><td>"+a[i][11]+"</td><td>"+a[i][6]+"</td><td>"+estadoBage+"</td><td><a href='/controladores/pqr_controlador?pqrcerrado="+btoa(a[i][13])+"' ><i class='fas fa-eye fa-lg'></i></a></td><td><a href='/controladores/clases/generador_pdf?pqrcerrado="+btoa(a[i][13])+"' ><i class='fas fa-file-pdf fa-lg'></i></a></td></tr>";
					 		break;
					 	case 'proceso':
					 		estadoBage = '<span class="badge badge-warning">'+a[i][10]+'</span>';
					 		prueba = informes += "<tr><td>"+a[i][13]+"</td><td>"+a[i][9]+"</td><td>"+a[i][11]+"</td><td>"+a[i][6]+"</td><td>"+estadoBage+"</td><td><a href='/controladores/pqr_controlador?pqrproceso="+btoa(a[i][13])+"' ><i class='fas fa-eye fa-lg'></i></a></td><td><a href='/controladores/clases/generador_pdf?pqrproceso="+btoa(a[i][13])+"' ><i class='fas fa-file-pdf fa-lg'></i></a></td></tr>";
					 		break;
					 	case 'vencido':
					 		estadoBage = '<span class="badge badge-danger">'+a[i][10]+'</span>';
					 		prueba = informes += "<tr><td>"+a[i][13]+"</td><td>"+a[i][9]+"</td><td>"+a[i][11]+"</td><td>"+a[i][6]+"</td><td>"+estadoBage+"</td><td><a href='/controladores/pqr_controlador?pqrproceso="+btoa(a[i][13])+"' ><i class='fas fa-eye fa-lg'></i></a></td><td><a href='/controladores/clases/generador_pdf?pqrproceso="+btoa(a[i][13])+"' ><i class='fas fa-file-pdf fa-lg'></i></a></td></tr>";
					 		break;
					 	case 'rechazado':
					 		estadoBage = '<span class="badge badge-danger">'+a[i][10]+'</span>';
					 		prueba = informes += "<tr><td>"+a[i][13]+"</td><td>"+a[i][9]+"</td><td>"+a[i][11]+"</td><td>"+a[i][6]+"</td><td>"+estadoBage+"</td><td><a href='/controladores/pqr_controlador?pqrproceso="+btoa(a[i][13])+"' ><i class='fas fa-eye fa-lg'></i></a></td><td><a href='/controladores/clases/generador_pdf?pqrproceso="+btoa(a[i][13])+"' ><i class='fas fa-file-pdf fa-lg'></i></a></td></tr>";
					 		break;
					 	default:
					 		
					 		break;
					 }
					$("#infoInformes").html(HTML);
					$("#bodyInformes").html(prueba);
				}
			}
			else
			{
				HTML = '<div class="alert alert-warning" role="alert"><strong>No se encuentran PQRS</strong></div>'
				$("#infoInformes").html(HTML);
			}
			
			
	 	});
	} catch(e) {
		console.log(e);
	}
}

function guardarCambiosNuevaContrasena() 
{
	try
	{
		$("#guardarCambiosNuevaContrasena").click(function(){
			contra = $("#nuevaContrasena").val();
			idUsuario = $("#idUsuario").val();
			data = new FormData();
			data.append('contrasena',contra);
			data.append('idUsuario',idUsuario);
			data.append('tipo','seguridadUsuario');
			envioporfetch(data,'../controladores/usuarios_controlador.php')
			.then(info => {
				try
				{
					switch(info.status)
					{
						case 1:
							$("#respuesta").html(info.respuesta);
							setTimeout(function(){
								location.reload();
							},1600);
						break;
						case 0:
							$("#respuesta").html(info.error);
						break;
					}
				}
				catch (error)
				{
					console.log(error);
				}
			});
		});
	}
	catch(e)
	{
		console.log(e);
	}
}

function escucharFunciones() 
{
	try
	{
		borrarUsuario();
		editarperfil();
		edicionUsuario();
		respuestaUsuario();
		vencimientoPqr();
		vencimientoPqrRespuesta();
		editarDatosPqr();
		borrarDistrito();
		borrarArea();
		generarInforme();
		guardarCambiosNuevaContrasena();
		editarArea();
		editarDistrito();
	}
	catch(e)
	{
		console.log(e);
	}
}

function mostrarformularios() {
	$("#mostrarformularioarea").click(function() {
		$("#areaoculto").css('display', 'block');
	});
	$("#mostrarformularioarea").click(function() {
		$("#distritooculto").css('display', 'block');
	});
	$("#mostrarformularioausuario").click(function() {
		$("#usuariooculto").css('display', 'block');
	});
	escucharFunciones();
}

$(document).ready(function(){
	escucharFunciones();
	if (typeof verificarEmail == 'function') 
	{
		$("#occ").hide('slow');
		verificarEmail();
		copiaoculta();
		cerrarOcc();
	}
	
});