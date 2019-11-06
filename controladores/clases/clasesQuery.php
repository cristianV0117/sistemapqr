<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/clases/constructorQuery.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/modelos/conexion.php';
class clasesQuery extends constructorQuery
{
	private $conexion;
	private $get;

	public function table($parametro)
	{
		try 
		{
			$consulta = $this->identificarTabla($parametro);
		} catch (Exception $e) 
		{
			die('Excepción capturada: '.$e->getMessage()."\n");
		}
	}

	public function insert($arreglo = [])
	{
		try 
		{
			$consulta = $this->metodoInsertar($arreglo);
			$respuesta = $this->retornoSQLEjecutarMetodo($consulta);
			return $respuesta;
		} catch (Exception $e) 
		{
			die('Excepción capturada: '.$e->getMessage()."\n");
		}
	}

	public function update($arreglo = [])
	{
		try 
		{
			$consulta = $this->metodoEditar($arreglo);
			$respuesta = $this->retornoSQLEjecutarMetodo($consulta);
			return $respuesta;
		} catch (Exception $e) 
		{
			die('Excepción capturada: '.$e->getMessage()."\n");
		}
	}

	public function delete()
	{
		try 
		{
			$consulta = $this->metodoEliminar();
			$respuesta = $this->retornoSQLEjecutarMetodo($consulta);
			return $respuesta;
		} catch (Exception $e) 
		{
			die('Excepción capturada: '.$e->getMessage()."\n");
		}
	}

	public function select($columna)
	{
		try 
		{
			$this->get = $this->metodoSelect($columna);
		} catch (Exception $e) 
		{
			die('Excepción capturada: '.$e->getMessage()."\n");
		}
	}

	public function join($arreglo = [])
	{
		try 
		{
			$res = $this->metodoJoin($arreglo);
		} catch (Exception $e) 
		{
			die('Excepción capturada: '.$e->getMessage()."\n");
		}
	}

	public function where($esto,$delimitante = null,$aesto)
	{
		try 
		{
			$consulta = $this->metodoWhere($esto,$delimitante,$aesto);
		} catch (Exception $e) 
		{
			die('Excepción capturada: '.$e->getMessage()."\n");
		}
	}

	public function andWhere($arreglo = [])
	{
		try 
		{
			$consulta = $this->metodoAndWhere($arreglo);
		} catch (Exception $e) 
		{
			die('Excepción capturada: '.$e->getMessage()."\n");
		}
	}

	public function orWhere($arreglo = [])
	{
		try 
		{
			$consulta = $this->metodoOrWhere($arreglo);
		} catch (Exception $e) 
		{
			die('Excepción capturada: '.$e->getMessage()."\n");
		}
	}

	public function orderBy($columna,$orden)
	{
		try 
		{
			$res = $this->metodoOrderBy($columna,$orden);
		} catch (Exception $e) 
		{
			die('Excepción capturada: '.$e->getMessage()."\n");
		}
	}

	public function groupBy($columna)
	{
		try {
			$res = $this->metodoGroupBy($columna);
		} catch (Exception $e) {
			die('Excepción capturada: '.$e->getMessage()."\n");
		}
	}

	public function get()
	{
		try 
		{
			$respuesta = $this->retornoSQLEjecutarMetodo($this->get);
			return $respuesta;
		} catch (Exception $e) 
		{
			die('Excepción capturada: '.$e->getMessage()."\n");
		}
	}

	private function retornoSQLEjecutarMetodo($consulta)
	{
		try {
			$this->conexion = new conexion();
			$retorno = $this->conexion->consulQuery($consulta);
			if ($retorno['resultado']->execute()) 
			{
				$datos = $retorno['resultado']->fetchAll();
				if(empty($datos))
				{
					return array('ejecutado' => true, 'ultimoID' => $retorno['ultimoID']->lastInsertId());
				}
				else
				{
					return json_encode($datos);
				}
			}
			else
			{
				print_r($retorno['resultado']->errorInfo());
			}
		} catch (Exception $e) {
			die('Excepción capturada: '.$e->getMessage()."\n");
		}
	}
}