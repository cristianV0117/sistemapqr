<?php
/**
 *
 * @author Cristian Camilo Vasquez
 * Clase carga
 *
 */
class carga 
{

	/**
     *
     * Se ejecuta el @method:$this->cargaScript($argumento).
     *
     */
	public function __construct($argumento) 
	{
		try
		{
			$this->cargaScript($argumento);
		}
		catch(Exception $e)
		{
			die('Excepción capturada: '. $e->getMessage() . "\n");
		}
		
	}

	/**
     *
     * @method:cargaScript(parametro) Se encarga de llamar archivos JS y adjuntarlos en la estructura de las vistas.
     * @param:$tipo int.
     * Se filtra @var:$tipo para saber que archivos JS utilizar
     * Retorna el llamado del archivo correspondiente
     *
     */
	private function cargaScript($tipo) 
	{
		try 
		{
			// Verificar que archivos se van a retornar.
			switch ($tipo) 
			{
				case 0:
					echo '<script src="/node_modules/jquery/dist/jquery.js" ></script>';
					echo '<script src="/node_modules/bootstrap/dist/js/bootstrap.js" ></script>';
					echo '<script src="/publico/js/toastr.min.js" ></script>';
					echo '<script src="/node_modules/sweetalert/dist/sweetalert.min.js"></script>';
					echo '<script src="/publico/js/logeo.js" ></script>';
					echo '<script src="/externos/editor/dist/dis/trumbowyg.min.js"></script>';
					echo '<script src="/node_modules/bootstrap-fileinput/js/fileinput.js"></script>';
					echo '<script src="/node_modules/bootstrap-fileinput/js/locales/es.js"></script>';
					echo '<script src="/publico/js/pqr.js" ></script>';
					echo '<script src="/publico/js/home.js" ></script>';
					echo '<script src="/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>';
					break;
				case 1:
					echo '<script src="/node_modules/jquery/dist/jquery.js" ></script>';
					echo '<script src="/node_modules/bootstrap/dist/js/bootstrap.min.js" ></script>';
					break;
				case 2:
					echo '<script src="/publico/js/panel.js" ></script>';
					break;
				case 3:
					echo '<script src="/publico/js/peticiones.js" ></script>';
					break;
				case 4:
					echo '<script src="/publico/js/formularios.js" ></script>';
					break;
				case 5:
					echo '<script src="/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>';
					break;
				case 6:
					echo '<script src="/publico/js/logeo.js" ></script>';
					break;
				case 7:
					echo '<script src="/node_modules/datatables.net/js/jquery.dataTables.js"></script>';
					echo '<script src="/node_modules/datatables.net-bs4/js/dataTables.bootstrap4.js" ></script>';
					break;
				case 8:
					echo '<script src="/publico/js/filtrarpqr.js" ></script>';
					break;
				case 9:
					echo '<script src="/publico/js/escalarpqr.js" ></script>';
					break;
				case 10:
					echo '<script src="/publico/js/solucionarpqr.js" ></script>';
					break;
				case 11:
					echo '<script src="/externos/editor/dist/dis/trumbowyg.min.js"></script>';
					break;
				case 12:
					echo '<script src="/node_modules/bootstrap-fileinput/js/fileinput.js"></script>';
					echo '<script src="/node_modules/bootstrap-fileinput/js/locales/es.js"></script>';
					break;
				case 13:
					echo '<script src="/publico/js/cerrarpqr.js" ></script>';
					break;
				case 14:
					echo '<script src="/node_modules/sweetforms/swal-forms.js"></script>';
					break;
				case 15:
					echo '<script src="/node_modules/sweetalert/dist/sweetalert.min.js"></script>';
					break;
				case 16:
					echo '<script src="/publico/js/pqr_sistema.js"></script>';
					break;
				case 17:
					echo '<script src="/node_modules/jquery/dist/jquery.js" ></script>';
					echo '<script src="/publico/js/toastr.min.js" ></script>';
					break;
				case 18:
					echo '<script src="/node_modules/jquery/dist/jquery.js" ></script>';
					echo '<script src="/publico/js/mensajefinal.js" ></script>';
					break;
				case 19:
					echo '<script src="/node_modules/jquery/dist/jquery.js" ></script>';
					echo '<script src="/publico/js/vista_carga_respuesta_usuario.js" ></script>';
					break;
				case 20:
					echo '<script src="/publico/js/removerEscalamiento.js" ></script>';
					break;
				case 21:
					echo '<script src="/publico/js/copiaOculta.js" ></script>';
				default:
					# code...
					break;
			}
		} catch (Exception $e) 
		{
			die('Excepción capturada: '. $e->getMessage() . "\n");
		}
	}
}