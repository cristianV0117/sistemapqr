<?php
/**
 *
 * @author Cristian Camilo Vasquez
 * Clase HistorialIngresoSalida
 * Se incluyen:
 * El archivo de clases query para la utilizacion del constructor de querys.
 *
 */
include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/clases/clasesQuery.php';
class HistorialIngresoSalida

{
	/**
	 * @property $navegadorActual null
	*/
	private $navegadorActual;
	/**
	 * @property $DB null
	*/
	private $DB;
	/**
	 * @property $SO null
	*/
	private $SO;

	/**
     *
     * @property:$this->DB Es un objeto de clasesQuery();
     * @property:$this->IP Almacena la dirección IP desde la cual está viendo la página actual el usuario. 
     * @property:$this->navegadorActual almacena el navegador filtrado del metodo @method:$this->navegadorActual($_SERVER['HTTP_USER_AGENT']).
     * @property:$this->SO almacena el sistema operativo filtrado del metodo @method:$this->soActual($_SERVER['HTTP_USER_AGENT']).
     *
     */
	public function __construct()
	{
		try 
		{
			$this->DB = new clasesQuery();
			$this->IP = $_SERVER['REMOTE_ADDR'];
			$this->navegadorActual = $this->navegadorActual($_SERVER['HTTP_USER_AGENT']);
			$this->SO = $this->soActual($_SERVER['HTTP_USER_AGENT']);
		} catch (Exception $e) 
		{
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}	
	}

	/**
     *
     * @method:ingreso_salida(parametros) Almacena en la tabla ingresos_salidas, los ingresos y salidas a la plataforma.
     * @param:$idUsuario Contiene el id del usuario el cual ha realizado la acción correspondiente.
     * @param:$tipo Contiene el tipo de accion que se ha realizado.
     *
     */
	public function ingreso_salida($idUsuario,$tipo)
	{
		try 
		{
			$this->DB->table('ingresos_salidas');
			$this->DB->insert([
				'usuario' 	=> $idUsuario,
				'tipo' 	    => $tipo,
				'fecha' 	=> 'CURRENT_TIMESTAMP',
				'ip' 	    => $this->IP,
				'navegador' => $this->navegadorActual, 
				'so'		=> $this->SO
			]);
		} catch (Exception $e) 
		{
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}

	/**
     *
     * @method:navegadorActual(parametro) Identifica el navegador actual en la que se encuentra el usuario.
     * @param:$target Contiene la informacion enviada desde __construct().
     * @return:Retorna el navegador actual utilizado.
     *
     */
	private function navegadorActual($target)
	{
		try 
		{
			if(strpos($target, 'MSIE') !== FALSE)
			   return 'Internet explorer';
			 elseif(strpos($target, 'Edge') !== FALSE)
			   return 'Microsoft Edge';
			 elseif(strpos($target, 'Trident') !== FALSE)
			    return 'Internet explorer';
			 elseif(strpos($target, 'Opera Mini') !== FALSE)
			   return "Opera Mini";
			 elseif(strpos($target, 'Opera') || strpos($target, 'OPR') !== FALSE)
			   return "Opera";
			 elseif(strpos($target, 'Firefox') !== FALSE)
			   return 'Mozilla Firefox';
			 elseif(strpos($target, 'Chrome') !== FALSE)
			   return 'Google Chrome';
			 elseif(strpos($target, 'Safari') !== FALSE)
			   return "Safari";
			 else
			   return 'No hemos podido detectar su navegador';
		} catch (Exception $e) 
		{
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}

	/**
     *
     * @method:navegadorActual(parametro) Identifica el sistema operativo actual en la que se encuentra el usuario.
     * @param:$target Contiene la informacion enviada desde __construct().
     * @return:Retorna el sistema operativo actual utilizado.
     *
     */
	private function soActual($target)
	{
		try 
		{
			if(strpos($target,'Windows') !== FALSE)
			{
				return "Windows";
			}
			elseif (strpos($target,'Macintosh') !== FALSE) 
			{
				return "Mac";
			}
			elseif (strpos($target,'Linux') !== FALSE) 
			{
				return "Linux";
			}
			else
			{
				return "Otro SO";
			}
		} catch (Exception $e) 
		{
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}
}