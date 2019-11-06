<?php
/**
 *
 * @author Cristian Camilo Vasquez
 * Clase Vista_clase
 *
 */
/**
 * Se inicia una nueva sesion.
 */
namespace clases;
session_name('PQRUSER');
session_start();

class Vista_clase
{
	/**
	 *
     * @method:vista(parametros) Se encarga de llamar y retornar en la plataforma las vistas.
     * Es opcional si se envian datos a esa vista para ser tratados en el frontend del sitio.
     * @param:$url string.
     * @param:$arregloDatos array.
     * Si no exisite una sesion activa se redireccionara al index del sitio
     *
     */
	public static function vista($url = null,$arregloDatos = array())
	{
		try 
		{
			if (isset($_SESSION['bool'])) 
			{
				require_once($_SERVER['DOCUMENT_ROOT'] . '/vistas/' . $url . '.php');
			}
			elseif ($arregloDatos['sesion'] == true) 
			{
				require_once($_SERVER['DOCUMENT_ROOT'] . '/vistas/' . $url . '.php');
			}
			else
			{
				require_once($_SERVER['DOCUMENT_ROOT'] . '/index.php');
			}	
		} catch (Exception $e)
		{ 
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}
}