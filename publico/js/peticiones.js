jQuery.ajaxSetup({async:false});
function peticionesDeVistas(url,tipo,data){
	var respuesta = '';
	switch (tipo) {
		case 'get':
			$.get(url, function() {
			})
			.done(function(resp) {
    			respuesta = resp;
  			});
  			return respuesta;
			break;
		case 'post':
			$.post(url,{datos:data})
			.done(function(resp){
				$("#"+contenedor).html(resp);
			});
			break;
		default:
			// statements_def
			break;
	}
}