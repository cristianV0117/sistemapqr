<?php

/**
 * 
 */

class constructorQuery
{
	private $insertInto;
	private $values;
	private $primero;
	private $segundo;
	private $where;
	private $eliminar;
	private $tercero;
	private $set;
	private $editar;
	private $cuarto;
	private $seleccionar;
	private $from;
	private $quinto;
	private $innerJoin;
	private $on;
	private $sexto;
	private $septimo;
	private $orderBy;
	private $octavo;
	private $noveno;
	private $and;
	private $or;
	private $decimo;
	private $onceavo;
	private $groupBy;

	function __construct()
	{
		try 
		{
			$this->insertInto = "INSERT INTO ";
			$this->values = " VALUES ";
			$this->where = " WHERE ";
			$this->eliminar = "DELETE FROM ";
			$this->set = " SET ";
			$this->editar = " UPDATE ";
			$this->seleccionar = "SELECT ";
			$this->from = " FROM ";
			$this->innerJoin = " INNER JOIN ";
			$this->on = " ON ";
			$this->orderBy = " ORDER BY ";
			$this->groupBy = " GROUP BY ";
			$this->and = " AND ";
			$this->or = " OR ";

		} catch (Exception $e) {
			echo 'Excepción capturada: ', $e->getMessage(), "\n";
		}
	}

	private function consTructorSQLMetodo($metodo,$valores1 = null, $valores2 = null)
	{
		try {
			switch ($metodo) 
			{
				case 'insert':
					return $this->insertInto . $this->primero . $valores1 . $this->values . $valores2;
					break;
				case 'delete':
					return $this->eliminar . $this->primero . $this->where . $valores1;
					break;
				case 'update':
					if (empty($this->noveno)) {
						return $this->editar . $this->primero . $this->set . $this->cuarto . $this->where . $this->tercero;
					} else {
						return $this->editar . $this->primero . $this->set . $this->cuarto . $this->where . $this->tercero . $this->and . $this->noveno;
					}
					break;
				case 'select':
						if (empty($this->onceavo)) 
						{
							if(empty($this->decimo))
							{
								if (empty($this->noveno)) 
								{
									if(empty($this->octavo))
									{
										if (empty($this->sexto)) 
										{
											if (empty($this->tercero)) 
											{
												return $this->seleccionar . $this->quinto . $this->from . $this->primero;
											}
											else
											{	
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->where . $this->tercero;
											}
										}
										else
										{
											if (empty($this->tercero)) 
											{
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->innerJoin . $this->sexto . $this->on . $this->septimo;
											}
											else
											{
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->innerJoin . $this->sexto . $this->on . $this->septimo . $this->where . $this->tercero;
											}
										}
									}
									else
									{
										if (empty($this->sexto)) 
										{
											if (empty($this->tercero)) 
											{
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->orderBy . $this->octavo;
											}
											else
											{	
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->where . $this->tercero . $this->orderBy . $this->octavo;
											}
										}
										else
										{
											if (empty($this->tercero)) 
											{
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->innerJoin . $this->sexto . $this->on . $this->septimo . $this->orderBy . $this->octavo;
											}
											else
											{
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->innerJoin . $this->sexto . $this->on . $this->septimo . $this->where . $this->tercero . $this->orderBy . $this->octavo;
											}
										}
									}
								}
								else
								{
									if(empty($this->octavo))
									{
										if (empty($this->sexto)) 
										{
											if (empty($this->tercero)) 
											{
												return $this->seleccionar . $this->quinto . $this->from . $this->primero;
											}
											else
											{	
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->where . $this->tercero . $this->and . $this->noveno;
											}
										}
										else
										{
											if (empty($this->tercero)) 
											{
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->innerJoin . $this->sexto . $this->on . $this->septimo;
											}
											else
											{
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->innerJoin . $this->sexto . $this->on . $this->septimo . $this->where . $this->tercero . $this->and . $this->noveno;
											}
										}
									}
									else
									{
										if (empty($this->sexto)) 
										{
											if (empty($this->tercero)) 
											{
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->orderBy . $this->octavo;
											}
											else
											{	
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->where . $this->tercero . $this->and . $this->noveno . $this->orderBy . $this->octavo;
											}
										}
										else
										{
											if (empty($this->tercero)) 
											{
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->innerJoin . $this->sexto . $this->on . $this->septimo . $this->orderBy . $this->octavo;
											}
											else
											{
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->innerJoin . $this->sexto . $this->on . $this->septimo . $this->where . $this->tercero . $this->and . $this->noveno . $this->orderBy . $this->octavo;
											}
										}
									}
								}
							}
							else
							{
								if (empty($this->noveno)) 
								{
									if(empty($this->octavo))
									{
										if (empty($this->sexto)) 
										{
											if (empty($this->tercero)) 
											{
												return $this->seleccionar . $this->quinto . $this->from . $this->primero;
											}
											else
											{	
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->where . $this->tercero . $this->or . $this->decimo;
											}
										}
										else
										{
											if (empty($this->tercero)) 
											{
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->innerJoin . $this->sexto . $this->on . $this->septimo;
											}
											else
											{
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->innerJoin . $this->sexto . $this->on . $this->septimo . $this->where . $this->tercero . $this->or . $this->decimo;
											}
										}
									}
									else
									{
										if (empty($this->sexto)) 
										{
											if (empty($this->tercero)) 
											{
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->orderBy . $this->octavo;
											}
											else
											{	
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->where . $this->tercero . $this->or . $this->decimo . $this->orderBy . $this->octavo;
											}
										}
										else
										{
											if (empty($this->tercero)) 
											{
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->innerJoin . $this->sexto . $this->on . $this->septimo . $this->orderBy . $this->octavo;
											}
											else
											{
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->innerJoin . $this->sexto . $this->on . $this->septimo . $this->where . $this->tercero . $this->or . $this->decimo . $this->orderBy . $this->octavo;
											}
										}
									}
								}
								else
								{
									if(empty($this->octavo))
									{
										if (empty($this->sexto)) 
										{
											if (empty($this->tercero)) 
											{
												return $this->seleccionar . $this->quinto . $this->from . $this->primero;
											}
											else
											{	
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->where . $this->tercero . $this->and . $this->noveno . $this->or . $this->decimo;
											}
										}
										else
										{
											if (empty($this->tercero)) 
											{
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->innerJoin . $this->sexto . $this->on . $this->septimo;
											}
											else
											{
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->innerJoin . $this->sexto . $this->on . $this->septimo . $this->where . $this->tercero . $this->and . $this->noveno . $this->or . $this->decimo; 
											}
										}
									}
									else
									{
										if (empty($this->sexto)) 
										{
											if (empty($this->tercero)) 
											{
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->orderBy . $this->octavo;
											}
											else
											{	
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->where . $this->tercero . $this->and . $this->noveno . $this->or . $this->decimo . $this->orderBy . $this->octavo;
											}
										}
										else
										{
											if (empty($this->tercero)) 
											{
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->innerJoin . $this->sexto . $this->on . $this->septimo . $this->orderBy . $this->octavo;
											}
											else
											{
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->innerJoin . $this->sexto . $this->on . $this->septimo . $this->where . $this->tercero . $this->and . $this->noveno . $this->or . $this->decimo . $this->orderBy . $this->octavo;
											}
										}
									}
								}
							}
						}
						else
						{
							if(empty($this->decimo))
							{
								if (empty($this->noveno)) 
								{
									if(empty($this->octavo))
									{
										if (empty($this->sexto)) 
										{
											if (empty($this->tercero)) 
											{
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->groupBy . $this->onceavo;
											}
											else
											{	
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->where . $this->tercero . $this->groupBy . $this->onceavo;
											}
										}
										else
										{
											if (empty($this->tercero)) 
											{
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->innerJoin . $this->sexto . $this->on . $this->septimo . $this->groupBy . $this->onceavo;
											}
											else
											{
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->innerJoin . $this->sexto . $this->on . $this->septimo . $this->where . $this->tercero . $this->groupBy . $this->onceavo;
											}
										}
									}
									else
									{
										if (empty($this->sexto)) 
										{
											if (empty($this->tercero)) 
											{
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->orderBy . $this->octavo . $this->groupBy . $this->onceavo;
											}
											else
											{	
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->where . $this->tercero . $this->orderBy . $this->octavo . $this->groupBy . $this->onceavo;
											}
										}
										else
										{
											if (empty($this->tercero)) 
											{
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->innerJoin . $this->sexto . $this->on . $this->septimo . $this->orderBy . $this->octavo . $this->groupBy . $this->onceavo;
											}
											else
											{
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->innerJoin . $this->sexto . $this->on . $this->septimo . $this->where . $this->tercero . $this->orderBy . $this->octavo . $this->groupBy . $this->onceavo;
											}
										}
									}
								}
								else
								{
									if(empty($this->octavo))
									{
										if (empty($this->sexto)) 
										{
											if (empty($this->tercero)) 
											{
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->groupBy . $this->onceavo;
											}
											else
											{	
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->where . $this->tercero . $this->and . $this->noveno . $this->groupBy . $this->onceavo;
											}
										}
										else
										{
											if (empty($this->tercero)) 
											{
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->innerJoin . $this->sexto . $this->on . $this->septimo . $this->groupBy . $this->onceavo;
											}
											else
											{
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->innerJoin . $this->sexto . $this->on . $this->septimo . $this->where . $this->tercero . $this->and . $this->noveno . $this->groupBy . $this->onceavo;
											}
										}
									}
									else
									{
										if (empty($this->sexto)) 
										{
											if (empty($this->tercero)) 
											{
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->orderBy . $this->octavo . $this->groupBy . $this->onceavo;
											}
											else
											{	
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->where . $this->tercero . $this->and . $this->noveno . $this->orderBy . $this->octavo . $this->groupBy . $this->onceavo;
											}
										}
										else
										{
											if (empty($this->tercero)) 
											{
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->innerJoin . $this->sexto . $this->on . $this->septimo . $this->orderBy . $this->octavo . $this->groupBy . $this->onceavo;
											}
											else
											{
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->innerJoin . $this->sexto . $this->on . $this->septimo . $this->where . $this->tercero . $this->and . $this->noveno . $this->orderBy . $this->octavo . $this->groupBy . $this->onceavo;
											}
										}
									}
								}
							}
							else
							{
								if (empty($this->noveno)) 
								{
									if(empty($this->octavo))
									{
										if (empty($this->sexto)) 
										{
											if (empty($this->tercero)) 
											{
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->groupBy . $this->onceavo;
											}
											else
											{	
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->where . $this->tercero . $this->or . $this->decimo . $this->groupBy . $this->onceavo;
											}
										}
										else
										{
											if (empty($this->tercero)) 
											{
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->innerJoin . $this->sexto . $this->on . $this->septimo . $this->groupBy . $this->onceavo;
											}
											else
											{
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->innerJoin . $this->sexto . $this->on . $this->septimo . $this->where . $this->tercero . $this->or . $this->decimo . $this->groupBy . $this->onceavo;
											}
										}
									}
									else
									{
										if (empty($this->sexto)) 
										{
											if (empty($this->tercero)) 
											{
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->orderBy . $this->octavo . $this->groupBy . $this->onceavo;
											}
											else
											{	
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->where . $this->tercero . $this->or . $this->decimo . $this->orderBy . $this->octavo . $this->groupBy . $this->onceavo;
											}
										}
										else
										{
											if (empty($this->tercero)) 
											{
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->innerJoin . $this->sexto . $this->on . $this->septimo . $this->orderBy . $this->octavo . $this->groupBy . $this->onceavo;
											}
											else
											{
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->innerJoin . $this->sexto . $this->on . $this->septimo . $this->where . $this->tercero . $this->or . $this->decimo . $this->orderBy . $this->octavo . $this->groupBy . $this->onceavo;
											}
										}
									}
								}
								else
								{
									if(empty($this->octavo))
									{
										if (empty($this->sexto)) 
										{
											if (empty($this->tercero)) 
											{
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->groupBy . $this->onceavo;
											}
											else
											{	
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->where . $this->tercero . $this->and . $this->noveno . $this->or . $this->decimo . $this->groupBy . $this->onceavo;
											}
										}
										else
										{
											if (empty($this->tercero)) 
											{
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->innerJoin . $this->sexto . $this->on . $this->septimo . $this->groupBy . $this->onceavo;
											}
											else
											{
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->innerJoin . $this->sexto . $this->on . $this->septimo . $this->where . $this->tercero . $this->and . $this->noveno . $this->or . $this->decimo . $this->groupBy . $this->onceavo; 
											}
										}
									}
									else
									{
										if (empty($this->sexto)) 
										{
											if (empty($this->tercero)) 
											{
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->orderBy . $this->octavo . $this->groupBy . $this->onceavo;
											}
											else
											{	
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->where . $this->tercero . $this->and . $this->noveno . $this->or . $this->decimo . $this->orderBy . $this->octavo . $this->groupBy . $this->onceavo;
											}
										}
										else
										{
											if (empty($this->tercero)) 
											{
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->innerJoin . $this->sexto . $this->on . $this->septimo . $this->orderBy . $this->octavo . $this->groupBy . $this->onceavo;
											}
											else
											{
												return $this->seleccionar . $this->quinto . $this->from . $this->primero . $this->innerJoin . $this->sexto . $this->on . $this->septimo . $this->where . $this->tercero . $this->and . $this->noveno . $this->or . $this->decimo . $this->orderBy . $this->octavo . $this->groupBy . $this->onceavo;
											}
										}
									}
								}
							}
						}
					break;
				default:
					# code...
					break;
			}
		} catch (Exception $e) {
			echo 'Excepción capturada: ', $e->getMessage(), "\n";
		}
	}

	protected function identificarTabla($tabla = null)
	{
		try {
			$this->primero = $tabla;
		} catch (Exception $e) {
			echo 'Excepción capturada: ', $e->getMessage(), "\n";
		}
	}

	protected function metodoInsertar($arregloInsert = [])
	{
		try 
		{
			$arregloEscapado = $this->escaparDatosArreglo($arregloInsert);
			$valor = $this->construccionCampoValor($arregloEscapado);
			return $valor;
		} catch (Exception $e) {
			echo 'Excepción capturada: ', $e->getMessage(), "\n";
		}
	}

	protected function metodoEditar($arregloUpdate = [])
	{
		try {
			$arregloEscapado = $this->escaparDatosArreglo($arregloUpdate);
			$valor = $this->construccionCampoValorEditar($arregloEscapado);
			$editar = $this->grupoPerteneciente('editar');
			return $editar;
		} catch (Exception $e) {
			echo 'Excepción capturada: ', $e->getMessage(), "\n";
		}
	}

	protected function metodoEliminar()
	{
		try {
			$eliminar = $this->grupoPerteneciente('eliminar');
			return $eliminar;
		} catch (Exception $e) {
			echo 'Excepción capturada: ', $e->getMessage(), "\n";
		}
	}

	protected function metodoSelect($columna)
	{
		try 
		{
			$this->quinto = $columna;
			$select = $this->grupoPerteneciente('seleccionar');
			return $select;
		} catch (Exception $e) 
		{
			echo 'Excepción capturada: ', $e->getMessage(), "\n";
		}
	}

	protected function metodoJoin($arreglo = [])
	{
		try 
		{
			$nuevo = '';
			$nuevoTabla = '';
			if (count($arreglo) === 1) 
			{
				$this->sexto = $arreglo[0][0];
				$this->septimo = $arreglo[0][1] ." ".$arreglo[0][2]." ". $arreglo[0][3];
			}
			else
			{
				$a = 1;
				for ($i=0; $i < count($arreglo); $i++) 
				{ 
					if (!$i == 0) 
					{
						$a++;
					}
					$nuevo .=  $arreglo[$i][1] ." ".$arreglo[$i][2]." ". $arreglo[$i][3]."," . ' INNER JOIN ' . $arreglo[isset($arreglo[$a]) ? $a : $a - 1][0] . " ON ";
				}
				$this->sexto = $arreglo[0][0];
				$nuevo = explode(",",$nuevo);
				array_pop($nuevo);
				$resultado = implode(" ", $nuevo);											
				$this->septimo = $resultado;
			}	
		} catch (Exception $e) 
		{
			echo 'Excepción capturada: ', $e->getMessage(), "\n";
		}
	}

	protected function metodoOrderBy($columna,$orden)
	{
		try 
		{
			$this->octavo = $columna ." ".  $orden;
		} catch (Exception $e) 
		{
			echo 'Excepción capturada: ', $e->getMessage(), "\n";
		}
	}

	protected function metodoGroupBy($columna)
	{
		try 
		{
			$this->onceavo = $columna;
		} catch (Exception $e) 
		{
			echo 'Excepción capturada: ', $e->getMessage(), "\n";
		}
	}

	protected function metodoWhere($esto,$delimitante = null,$aesto)
	{
		try 
		{
			if (is_numeric($aesto)) 
			{
				$aesto = $aesto;
			}
			else
			{
				$aesto = "'$aesto'";
			}
			$this->tercero = $esto ." ".$delimitante." ".$aesto;
		} catch (Exception $e) 
		{
			echo 'Excepción capturada: ', $e->getMessage(), "\n";
		}
	}

	protected function metodoAndWhere($arreglo = [])
	{
		try 
		{
			for ($i=0; $i < count($arreglo); $i++) 
			{ 
				if (is_numeric($arreglo[$i][2])) 
				{
					$arreglo[$i][2] = $arreglo[$i][2];
				}
				else
				{
					$valor = $arreglo[$i][2];
					$arreglo[$i][2] = "'$valor'";
					
				}

			}
			$nuevo = '';
			if (count($arreglo) === 1) 
			{	
				foreach ($arreglo[0] as $value) 
				{

					$this->noveno .= $value . " ";
				}
			}
			{
				for ($i=0; $i < count($arreglo); $i++) 
				{ 

					$nuevo .= $arreglo[$i][0] . $arreglo[$i][1] . $arreglo[$i][2] . ' AND ';
					
					
				}
				$resultado = substr($nuevo,0,-4);
				$this->noveno = $resultado;

			}
			
		} catch (Exception $e) 
		{
			echo 'Excepción capturada: ', $e->getMessage(), "\n";
		}
	}

	protected function metodoOrWhere($arreglo = [])
	{
		try 
		{
			for ($i=0; $i < count($arreglo); $i++) 
			{ 
				if (is_numeric($arreglo[$i][2])) 
				{
					$arreglo[$i][2] = $arreglo[$i][2];
				}
				else
				{
					$valor = $arreglo[$i][2];
					$arreglo[$i][2] = "'$valor'";
					
				}

			}
			$nuevo = '';
			if (count($arreglo) === 1) 
			{	
				foreach ($arreglo[0] as $value) 
				{

					$this->decimo .= $value . " ";
				}
			}
			{
				for ($i=0; $i < count($arreglo); $i++) 
				{ 

					$nuevo .= $arreglo[$i][0] . $arreglo[$i][1] . $arreglo[$i][2] . ' OR ';
					
					
				}
				$resultado = substr($nuevo,0,-4);
				$this->decimo = $resultado;

			}

		} catch (Exception $e) 
		{
			echo 'Excepción capturada: ', $e->getMessage(), "\n";
		}
	}

	private function escaparDatosArreglo($arregloInsert)
	{
		try {
			if (isset($arregloInsert['mensaje'])) {
				$var = addslashes($arregloInsert['mensaje']);
				unset($arregloInsert['mensaje']);
				array_walk($arregloInsert,function(& $valor){
					$valor = htmlentities(addslashes($valor));
				});
				$arregloInsert['mensaje'] = $var;
			} else {
				array_walk($arregloInsert,function(& $valor){
					$valor = htmlentities(addslashes($valor));
				});
			}

			return $arregloInsert;
		} catch (Exception $e) {
			echo 'Excepción capturada: ', $e->getMessage(), "\n";
		}
	}

	private function construccionCampoValor($arregloEscapado)
	{
		try {
			$temporal = '';
			$tmpValues = '';
			foreach ($arregloEscapado as $key => $value) {
				$temporal .= " $key" . ",";
				if (is_numeric($value)) 
				{
					$tmpValues .= "$value" . ",";
				}elseif ($value == 'CURRENT_TIMESTAMP') 
				{
					$tmpValues .= "$value" . ",";
				}
				else
				{
					$tmpValues .= "'$value'" . ",";
				}
			}
			$campoValor = substr ($temporal, 0, -1);
			$valorCampo = substr ($tmpValues, 0, -1);
			$campoValor = " (".$campoValor.")";
			$valorCampo = "(".$valorCampo.")";
			$final = $this->consTructorSQLMetodo('insert',$campoValor,$valorCampo);
			return $final;
		} catch (Exception $e) {
			echo 'Excepción capturada: ', $e->getMessage(), "\n";
		}
	}

	private function construccionCampoValorEditar($arregloEscapado)
	{
		try {
			$temporal = '';
			foreach ($arregloEscapado as $key => $value) {
				if (is_numeric($value)) 
				{
					$temporal .= $key . " = ". $value . ",";
				}
				elseif ($value == 'CURRENT_TIMESTAMP') 
				{
					$temporal .= $key . " = ". $value . ",";
				}
				else
				{
					$temporal .= $key . " = ". "'$value'" . ",";
				}
			}
			$campo = substr($temporal, 0, -1);
			$this->cuarto = $campo;
		} catch (Exception $e) {
			echo 'Excepción capturada: ', $e->getMessage(), "\n";
		}
	}

	private function grupoPerteneciente($parametro = null)
	{
		try {
			
			switch ($parametro) {
				case 'eliminar':
					$var = $this->consTructorSQLMetodo('delete',$this->tercero,null);
					return $var;
					break;
				case 'editar':
					$var = $this->consTructorSQLMetodo('update',$this->cuarto,null);
					return $var;
					break;
				case 'seleccionar':
					$var = $this->consTructorSQLMetodo('select',$this->quinto,$this->tercero);
					return $var;
					break;
				default:
					//print_r($parametro);
					break;
			}
		} catch (Exception $e) {
			echo 'Excepción capturada: ', $e->getMessage(), "\n";
		}
	}
}


