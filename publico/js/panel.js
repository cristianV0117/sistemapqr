function menuLateral() 
{
	var booleano = true;
	$("#lateralmenu").click(function() {
		if (booleano == true) {
			$("#lateral").hide('slow');
			booleano = false;
		} else {
			$("#lateral").show('slow');
			booleano = true;
		}
	});
}

function informes() 
{
	try {
		$("#informes").click(function(){
			var datosRecibidos = peticionesDeVistas("../controladores/informes_controlador.php","get",null);
			$("#info").html(datosRecibidos);
			generarInforme();
		});
	} catch(e) {
		console.log(e);
	}
}

function formularioAreas() 
{
	$("#areas").click(function() {
		var datosRecibidos = peticionesDeVistas("../controladores/areas_controlador.php", "get", null);
		$("#info").html(datosRecibidos);
		envioDatosAreas();
		$("#tablaareas").DataTable({
			"pageLength": 25,
			"order": [
				[0, "desc"]
			],
			"language": {
				"lengthMenu": "Mostrar _MENU_ registros",
				"zeroRecords": "No hay areas",
				"info": "mostrar pagina _PAGE_ de _PAGES_",
				"infoEmpty": "No hay registros",
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
	});
}

function formularioDistritos() {
	$("#distritos").click(function() {
		var datosRecibidos = peticionesDeVistas("../controladores/distritos_controlador.php", "get", "info", null);
		$("#info").html(datosRecibidos);
		envioDatosDistritos();
		$("#tabladistritos").DataTable({
			"pageLength": 25,
			"order": [
				[0, "desc"]
			],
			"language": {
				"lengthMenu": "Mostrar _MENU_ registros",
				"zeroRecords": "No hay distritos",
				"info": "mostrar pagina _PAGE_ de _PAGES_",
				"infoEmpty": "No hay registros",
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

function formularioUsuarios() {
	$("#usuarios").click(function() {
		var datosRecibidos = peticionesDeVistas("../controladores/usuarios_controlador.php", "get", "info", null);
		$("#info").html(datosRecibidos);
		inputContrasena();
		envioDatosUsuarios();
		$("#tablausuarios").DataTable({
			"pageLength": 25,
			"order": [
				[0, "desc"]
			],
			"language": {
				"lengthMenu": "Mostrar _MENU_ registros",
				"zeroRecords": "No hay usuarios",
				"info": "mostrar pagina _PAGE_ de _PAGES_",
				"infoEmpty": "No hay registros",
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
	});
}

function vencimientoPqrFormulario(){
	$("#vencimientoPqr").click(function(){
		var datosRecibidos = peticionesDeVistas('../controladores/pqr_vencimiento_controlador.php',"get","info",null);
		$("#info").html(datosRecibidos);
		vencimientoPqr();
		vencimientoPqrRespuesta();
	});
}

function pqrFiltrados() {
	$("#pqrfiltrado").click(function() {
		var datosRecibidos = peticionesDeVistas("../vistas/pqr_filtrados_vista.php", "get", "info", null);
		$("#info").html(datosRecibidos);
		$("#tablapqr").DataTable({
			"pageLength":25,
			"order": [
				[0, "desc"]
			],
			"language": {
				"lengthMenu": "Mostrar _MENU_ registros",
				"zeroRecords": "No hay PQR",
				"info": "mostrar pagina _PAGE_ de _PAGES_",
				"infoEmpty": "No hay registros",
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
	});
}

function pqrsolucionado() 
{
	$("#pqrsolucionado").click(function(){
		var datosRecibidos = peticionesDeVistas('../vistas/pqr_cerrados_vista.php',"get","info",null);
		$("#info").html(datosRecibidos);
		$("#tablapqr").DataTable({
			"pageLength":25,
			"order": [
				[0, "desc"]
			],
			"language": {
				"lengthMenu": "Mostrar _MENU_ registros",
				"zeroRecords": "No hay PQR",
				"info": "mostrar pagina _PAGE_ de _PAGES_",
				"infoEmpty": "No hay registros",
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

	});
}

function ingresosSalidas() 
{
	$("#ingresosSalidas").click(function(){
		var datosRecibidos = peticionesDeVistas('../controladores/ingresos_salidas_controlador.php',"get","info",null);
		$("#info").html(datosRecibidos);
		$("#ingresos_salidas").DataTable({
			"pageLength":25,
			"order": [
				[2, "desc"]
			],
			"language": {
				"lengthMenu": "Mostrar _MENU_ registros",
				"zeroRecords": "No hay registros",
				"info": "mostrar pagina _PAGE_ de _PAGES_",
				"infoEmpty": "No hay registros",
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
	});
}

function codigoRandom(chars, lon)
{
	try
	{
		code = "";
		for (x=0; x < lon; x++)
		{
			rand = Math.floor(Math.random()*chars.length);
			code += chars.substr(rand, 1);
		}
		return code;
	}
	catch(e)
	{
		console.log(e);
	}
}

function generarContrasena()
{
	try
	{
		$("#generarContrasena").click(function(){
			caracteres = "0123456789abcdefABCDEF";
			longitud = 12;
			var random = codigoRandom(caracteres, longitud);
			if ($("#nuevaContrasena").val(random)) 
			{
				aceptarCambioContrasena();
			}
		});
	}
			
	catch(e)
	{
		console.log(e);
	}
}

function aceptarCambioContrasena()
{
	try
	{
		$("#aceptarCambioContrasena").click(function(){
			if($(this).is(':checked'))
			{
				$("#guardarCambiosNuevaContrasena").removeAttr('disabled');
			}
			else
			{
				$("#guardarCambiosNuevaContrasena").attr('disabled',"true");
			}
			
		})
	}
	catch(e)
	{
		console.log(e);
	}
}

function escuchaFunciones() {
	menuLateral();
	formularioAreas();
	formularioDistritos();
	formularioUsuarios();
	vencimientoPqrFormulario();
	pqrFiltrados();
	pqrsolucionado();
	informes();
	ingresosSalidas();
	generarContrasena();
}

$(document).ready(function() {
	escuchaFunciones();
	$("#tablapqr").DataTable({
		"pageLength":25,
		"order": [
			[0, "desc"]
		],
		"language": {
			"lengthMenu": "Mostrar _MENU_ registros",
			"zeroRecords": "No hay PQR",
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
})