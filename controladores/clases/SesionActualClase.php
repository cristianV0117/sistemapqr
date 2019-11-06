<?php
/**
 * 
 */
namespace clases;

class SesionActualClase
{
	private $idUsuario;
	private $nombreUsuario;
	private $primerNombre;
	private $segundoNombre;
	private $primerApellido;
	private $segundoApellido;
	private $emailUsuario;
	private $ciudadUsuario;
	private $documento;
	private $distritoUsuario;
	private $areaUsuario;
	private $rolUsuario;
	private $statusUsuario;
	private $bool;

	protected $arregloSesion;

	public function __construct()
	{	
		(!isset($_SESSION)) ? $this->iniciarSesion() : 'asdas';
		$this->asignarValoresSesion();
	}

	private function asignarValoresSesion()
	{
		try 
		{
			$this->idUsuario 	   = $_SESSION['idUsuario'];
			$this->nombreUsuario   = $_SESSION['nombreUsuario'];
			$this->primerNombre    = $_SESSION['primerNombre'];
			$this->segundoNombre   = $_SESSION['segundoNombre'];
			$this->primerApellido  = $_SESSION['primerApellido'];
			$this->segundoApellido = $_SESSION['segundoApellido'];
			$this->emailUsuario    = $_SESSION['emailUsuario'];
			$this->ciudadUsuario   = $_SESSION['ciudadUsuario'];
			$this->documento 	   = $_SESSION['documento'];
			$this->distritoUsuario = $_SESSION['distritoUsuario'];
			$this->areaUsuario 	   = $_SESSION['areaUsuario'];
			$this->rolUsuario 	   = $_SESSION['rolUsuario'];
			$this->statusUsuario   = $_SESSION['statusUsuario'];
			$this->bool 		   = $_SESSION['bool'];
			$this->creacionArregloSesion();
		} catch (Exception $e) 
		{
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}

	private function creacionArregloSesion()
	{
		try 
		{
			$this->arregloSesion = array(
				'idUsuario' 	  => $this->idUsuario,
				'nombreUsuario'   => $this->nombreUsuario,
				'primerNombre'	  => $this->primerNombre,
				'segundoNombre'   => $this->segundoNombre,
				'primerApellido'  => $this->primerApellido,
				'segundoApellido' => $this->segundoApellido,
				'emailUsuario'    => $this->emailUsuario,
				'ciudadUsuario'   => $this->ciudadUsuario,
				'documento'       => $this->documento,
				'distritoUsuario' => $this->distritoUsuario,
				'areaUsuario'     => $this->areaUsuario,
				'rolUsuario'      => $this->rolUsuario,
				'statusUsuario'   => $this->statusUsuario,
				'bool'            => $this->bool
			);
					
		} catch (Exception $e) 
		{
			die('Excepción capturada: ' . $e->getMessage() . "\n");		
		}		
	}

	public function obtenerArregloSesion()
	{
		try 
		{
			$arregloSesion = $this->arregloSesion;
			return json_encode($arregloSesion);	
			
		} catch (Exception $e) 
		{
			die('Excepción capturada: ' . $e->getMessage() . "\n");	
		}
	}

	public function nuevoInfoSesion($arreglo = [])
	{
		try 
		{
			$_SESSION['nombreUsuario']   = $arreglo['nombreUsuario'];
			$_SESSION['primerNombre'] 	 = $arreglo['primerNombre'];
			$_SESSION['segundoNombre']   = $arreglo['segundoNombre'];
			$_SESSION['primerApellido']  = $arreglo['primerApellido'];
			$_SESSION['segundoApellido'] = $arreglo['segundoApellido'];
			$_SESSION['emailUsuario']    = $arreglo['emailUsuario'];
			$_SESSION['ciudadUsuario']	 = $arreglo['ciudadUsuario'];
			$_SESSION['documento']   	 = $arreglo['documento'];
			return 1;
		} catch (Exception $e) 
		{
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}

	private function iniciarSesion()
	{
		try 
		{
			session_name('PQRUSER');
			session_start(); //Se inicia la sesion
		} catch (Exception $e) 
		{
			die('Excepción capturada: ' . $e->getMessage() . "\n");
		}
	}
}
