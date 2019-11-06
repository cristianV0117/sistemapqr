<?php
/**
 *
 * @author Cristian Camilo Vasquez 
 * Modelo de la conexion con la DB por medio de PHP-PDO
 * Cuenta con dos metodos. El metodo de conexion  y Retorno de consultas preparadas
 *
 */
class conexion 
{
	/**
	 * @property host
	 */
	private $servidor;
	/**
	 * @property base de datos
	 */
	private $bd; 
	/**
	 * @property usuario actual
	 */
	private $usuario;
	/**
	 * @property contraseña actual del usuario
	 */
	private $contrasena;
	/**
	 * @property Atributo en la cual se almacena la conexion con la base de datos.
	 */
	private $cone; 
	/**
	 * @property Atributo en la que se almacena la consulta preparada.
	 */
	private $consul;

	/**
	 *
	 * Declaracón de las propiedades en la funcion constructora.
	 * Se llama el metodo de la conexion conexionBaseDeDatos() abriendo la conexion.
	 *
	 */
	function __construct() 
	{
		try
		{
			$this->servidor   = "localhost;";
			$this->bd         = "sistema_pqr";
			$this->usuario    = "root";
			$this->contrasena = "";
			$this->conexionBaseDeDatos();
		} catch (Exception $e)
		{
			die('Excepción capturada: '.$e->getMessage()."\n");
		}
		
	}

	/**
	 *
	 * El atributo cone almacena el objeto PDO el cual genera la conexion a un motor mysql
	 * Utiliza los atributos declarados en el metodo __construct
	 *
	 */
	private function conexionBaseDeDatos() {
		try {
			$this->cone = new \PDO("mysql:host=" . $this->servidor . "dbname=" . $this->bd, $this->usuario, $this->contrasena);
		} catch (Exception $e) {

			echo 'Excepción capturada: ', $e->getMessage(), "\n";
		}
	}

	/**
	 *
	 * @param  $this->query: Recibe la consulta SQL.
	 * @return $this->consul: Retorna la consulta preparada la cual a partir del metodo execute(), ejecuta la consulta.
	 * @var $this->consul: Almacena la consulta preparada con el query.
	 *
	 */
	public function consulQuery($query) {
		try {
			// exec: Habilita utf8 para caracteres especiales.
			$this->cone->exec("SET CHARSET utf8");
			$this->consul = $this->cone->prepare($query);
			return array('resultado' => $this->consul,'ultimoID' => $this->cone);
		} catch (Exception $e) {
			echo 'Excepción capturada: ', $e->getMessage(), "\n";
		}
	}

}