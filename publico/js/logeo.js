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


function logeo() 
{
	var data = new FormData();
	var nombreDeUsuario = document.getElementById('nombredeusuario').value;
	var contrasena = document.getElementById('contrasena').value;
	data.append('nombreUsuario',nombreDeUsuario);
	data.append('contrasena',contrasena)
	data.append('tipo','insercion');	
	var ajax = envioporajax(data,'../controladores/login_controlador.php');
	try
	{
		respuesta = JSON.parse(ajax);
		switch(respuesta.status)
		{
			case 1:
				toastr.success("Bienvenido",nombreDeUsuario,{
					"positionClass": "toast-bottom-right",
					"extendedTimeOut": "2500",
					"timeOut": "4500",
					"progressBar":true,
					"preventOpenDuplicates":true,
					"preventDuplicates":true
				});
				document.getElementById('nombredeusuario').style.color = "#28a745";
				document.getElementById('nombredeusuario').style.borderColor = "#28a745";
				document.getElementById('contrasena').style.borderColor = "#28a745";
				event.preventDefault();
				setTimeout(function(){
					location.replace("/vistas/panel_control_vista");
				},1400);		
			break;
			case 0:
				toastr.error("Error","Nombre de usuario o contraseña incorrecta",{
					"positionClass": "toast-bottom-right",
					"extendedTimeOut": "2500",
					"timeOut": "4500",
					"progressBar":true,
					"preventOpenDuplicates":true,
					"preventDuplicates":true
				});
				document.getElementById('nombredeusuario').style.color = "#dc3545";
				document.getElementById('nombredeusuario').style.borderColor = "#dc3545";
				document.getElementById('contrasena').style.borderColor = "#dc3545";
				event.preventDefault();
			break;
		}
	}
	catch (error)
	{
		console.log(error);
	}
}

$(document).ready(function(){
	document.getElementById('formulariodelogeo').addEventListener('submit',function(event){
		event.preventDefault();
		logeo();
	});
	
})