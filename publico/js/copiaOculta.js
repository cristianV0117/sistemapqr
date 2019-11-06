function copiaoculta()
{
	try {
		$("#copiaoculta").click(function(){
			$("#textoemails").html("");
			$("#occ").show('slow');
			$("#emailocc").val("");
		});
	} catch(e) {
		console.log(e);
	}
}

function cerrarOcc()
{
	try {
		$("#buttoncancelocc").click(function(){
			$("#textoemails").html("");
			$("#occ").hide('slow');
		});
	} catch(e) {
		console.log(e);
	}
}

function cerraremail(id)
{
	try {
		$("#" + id).click(function(){
			$(this).remove();
		})
	} catch(e) {
		console.log(e);
	}
}

function verificacionPattern(str)
{
	try {
		var formatoEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;  
		if(formatoEmail.test(str))
		{  	
			var random = Math.floor((Math.random() * 100) + 1);
			$("#textoemails").append('<span id="'+random+'" ><span class="badge badge-primary mt-2"><h6><span class="emailEscrito" >'+str+"</span><i class='fas fa-window-close'></i>"+'</h6></span>;</span>');
			$("#error").html(" ");
			cerraremail(random); 
		}  
		else  
		{  
			$("#error").append('<div class="alert alert-danger" role="alert"><strong>Correo invalido</strong></div>');
		}
	} catch(e) {
		console.log(e);
	}
}


function verificarEmail()
{
	try {

		$("#buttonemailocc").click(function(){
			var emailocc = $("#emailocc").val();
			verificacionPattern(emailocc);
			cerraremail();
		});
	} catch(e) {
		console.log(e);
	}
}
