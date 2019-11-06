<?php
/**
 *
 * @author Cristian Camilo Vasquez
 * Clase cerrarSesion
 * Se incluyen:
 * El archivo del historial de ingresos y salidas.
 *
 */
include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/clases/historial_ingreso_salida.php';
/**
 * Se verifica si existe una sesion, al no existir se crea: session_start();
*/
if (!isset($_SESSION)) 
{
	session_name('PQRUSER');
	session_start();
}

class cerrarSesion 
{
	/**
	 * @property his null
	*/
	private $his;
	/**
	 * @property sesion null
	*/
	private $sesion;

	/**
     *
     * @property:$this->his es un objeto de la clase HistorialIngresoSalida().
     * @property:$this->sesion almacena la variable de sesion @var:$_SESSION['idUsuario'].
     * Se ejecuta el @method:cerrarSesion().
     *
     */
	public function __construct() 
	{
		try
		{
			$this->his = new HistorialIngresoSalida();
			$this->sesion = $_SESSION['idUsuario'];
			$this->cerrarSesion();
		}
		catch(Exception $e)
		{
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}

	/**
     *
     * @method:cerrarSesion() Se encarga del cerrado de la sesion actual activa.
     * @property:$this->his llama el @method:ingreso_salida(@property:$this->sesion,'descripción').
     *
     */
	private function cerrarSesion() {
		try 
		{
			// Se verifica si se ha cerrado la sesion utilizando session_destroy().
			if (session_destroy()) 
			{
				$this->his->ingreso_salida($this->sesion,'Salida de la plataforma');
				// Se realiza un redireccionamiento del sitio actual.
				header('location:../index.php');
			}
		} catch (Exception $e) {
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}
}
// Se instancia la clase cerrarSesion().
new cerrarSesion();