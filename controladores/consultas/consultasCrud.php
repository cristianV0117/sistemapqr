<?php
/**
 *
 */
include_once $_SERVER['DOCUMENT_ROOT'] . '/modelos/conexion.php';
class consultasCrud {

	private $conexion;

	public function consultarTablas($post, $idUsuarioActivo = null) {
		try {
			$this->conexion = new conexion();
			switch ($post) {
			case 'areas':
				if ($_SESSION['idUsuario'] == 1) {
					$sql_areas = "SELECT * FROM areas WHERE status = 1 ORDER BY id DESC";
				}elseif ($_SESSION['rolUsuario'] == 6 || $_SESSION['rolUsuario'] == 8) {
					$sql_areas = "SELECT * FROM areas WHERE status = 1 AND id = 0 ORDER BY id DESC";
				}else {
					$sql_areas = "SELECT * FROM areas WHERE status = 1 AND id <> 0 ORDER BY id DESC";
				}
				$retorno = $this->conexion->consulQuery($sql_areas);
				if ($retorno['resultado']->execute()) {
					$datos = $retorno['resultado']->fetchAll();
					return $datos;
				} else {
					print_r($retorno['resultado']->errorInfo());
				}
				break;
			case 'distritos':
				if ($_SESSION['rolUsuario'] == 6) {
					$sql = "SELECT * FROM distritos WHERE status = 1 AND id = 0 ORDER BY id DESC";
				} else {
					$sql = "SELECT * FROM distritos WHERE status = 1 ORDER BY id DESC";
				}
				$retorno = $this->conexion->consulQuery($sql);
				if ($retorno['resultado']->execute()) {
					$datos = $retorno['resultado']->fetchAll();
					return $datos;
				} else {
					print_r($retorno['resultado']->errorInfo());
				}
				break;
			case 'pqr_abiertos':
				$sql = "SELECT * FROM pqr WHERE status = 1 AND estado = 'abierto' ORDER BY id DESC";
				$retorno = $this->conexion->consulQuery($sql);
				if ($retorno['resultado']->execute()) {
					$datos = $retorno['resultado']->fetchAll();
					return $datos;
				} else {
					print_r($retorno['resultado']->errorInfo());
				}
				break;
			case 'pqr_filtrados':
				if ($_SESSION['rolUsuario'] == 1 || $_SESSION['rolUsuario'] == 3) {
					$sql = "SELECT pqr_filtrado.*,
					pqr.estado,
					pqr.identidicacion
					FROM pqr_filtrado
					INNER JOIN pqr ON pqr_filtrado.id_pqr = pqr.id
					WHERE pqr_filtrado.id_distrito <> 0
					AND pqr.estado = 'proceso'
					OR pqr.estado = 'vencido'
					OR pqr.estado = 'rechazado'
					ORDER BY id DESC";
					$retorno = $this->conexion->consulQuery($sql);
				} elseif ($_SESSION['rolUsuario'] == 2) {
					$sql = "SELECT 
						pqr_filtrado.*,
						pqr.id AS idpqr,
						pqr.identidicacion, 
						pqr.estado 
						FROM pqr_filtrado 
						INNER JOIN pqr ON pqr_filtrado.id_pqr = pqr.id  
						WHERE pqr.status = 1 
						AND pqr_filtrado.id_area = :idArea 
						AND pqr_filtrado.grupo = 1 
						AND pqr.estado = 'proceso' 
						OR pqr.estado = 'vencido'
						OR pqr.estado = 'rechazado' 
						ORDER BY id DESC";
					$retorno = $this->conexion->consulQuery($sql);
					$idAreaActual = $_SESSION['areaUsuario'];
					$retorno['resultado']->bindParam(':idArea', $idAreaActual, PDO::PARAM_INT);
				} elseif ($_SESSION['rolUsuario'] == 4 || $_SESSION['rolUsuario'] == 8) {
					$sql = "SELECT pqr_gestionadores.*,pqr_filtrado.status as pqrstatus, pqr.identidicacion,pqr.estado FROM pqr_gestionadores
							INNER JOIN pqr_filtrado ON pqr_gestionadores.id_pqr = pqr_filtrado.id_pqr
							INNER JOIN pqr ON pqr_gestionadores.id_pqr = pqr.id
							WHERE 
							pqr_gestionadores.status = 1
                            AND
                            pqr_gestionadores.id_usuario = :idUsuario AND pqr.estado = 'proceso'
							OR pqr.estado = 'vencido'
							OR pqr.estado = 'rechazado'
							ORDER BY id DESC";
					$retorno = $this->conexion->consulQuery($sql);
					$idUsuario = $_SESSION['idUsuario'];
					$retorno['resultado']->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
				}elseif ($_SESSION['rolUsuario'] == 6 || $_SESSION['rolUsuario'] == 7) {
					$sql = "SELECT pqr_filtrado.*,
					pqr.estado,
					pqr.identidicacion
					FROM pqr_filtrado
					INNER JOIN pqr ON pqr_filtrado.id_pqr = pqr.id
					WHERE pqr_filtrado.id_distrito = 0
					AND
					pqr.estado = 'proceso'
					OR pqr.estado = 'vencido'
					OR pqr.estado = 'rechazado'
					ORDER BY id DESC";
					$retorno = $this->conexion->consulQuery($sql);
				}
				if ($retorno['resultado']->execute()) {
					$datos = $retorno['resultado']->fetchAll();
					return $datos;
				} else {
					print_r($retorno['resultado']->errorInfo());
				}
				break;
			case 'roles':
				if ($_SESSION['idUsuario'] == 1) {
					$sql = "SELECT * FROM roles";
				}elseif ($_SESSION['rolUsuario'] == 6) {
					$sql = "SELECT * FROM roles WHERE id <> 1 AND id <> 2 AND id <> 3 AND id <> 4 AND id <> 5";
				}else {
					$sql = "SELECT * FROM roles WHERE id <> 6 AND id <> 7 AND id <> 8";
				}
				
				
				$retorno = $this->conexion->consulQuery($sql);
				if ($retorno['resultado']->execute()) {
					$datos = $retorno['resultado']->fetchAll();
					return $datos;
				} else {
					print_r($retorno['resultado']->errorInfo());
				}
				break;
			case 'usuarios':
				if ($_SESSION['idUsuario'] == 1) {
					$sql = "SELECT * FROM usuarios WHERE id <> :idUsuarioActivo AND id <> 1 AND id <> 2 AND status = 1 ORDER BY id DESC";
				}elseif ($_SESSION['rolUsuario'] == 6) {
					$sql = "SELECT * FROM usuarios WHERE id <> :idUsuarioActivo AND id <> 1 AND status = 1 AND id <> 2 AND rol = 6 OR rol = 7 OR rol = 8 ORDER BY id DESC";
				}else {
					$sql = "SELECT * FROM usuarios WHERE id <> :idUsuarioActivo AND id <> 1 AND area <> 0 AND id <> 2 AND status = 1 ORDER BY id DESC";
				}
				$retorno = $this->conexion->consulQuery($sql);
				$retorno['resultado']->bindParam(':idUsuarioActivo', $idUsuarioActivo, PDO::PARAM_INT);
				if ($retorno['resultado']->execute()) {
					$datos = $retorno['resultado']->fetchAll();
					return $datos;
				} else {
					print_r($retorno['resultado']->errorInfo());
				}
				break;
			case 'tipopqr':
				if ($_SESSION['idUsuario'] == 1) {
					$sql = "SELECT * FROM tipo_pqr";
				}else{
					$sql = "SELECT * FROM tipo_pqr WHERE id <> 4";
				}
				$retorno = $this->conexion->consulQuery($sql);
				if ($retorno['resultado']->execute()) {
					$datos = $retorno['resultado']->fetchAll();
					return $datos;
				} else {
					print_r($retorno['resultado']->errorInfo());
				}
				break;
			case 'pqr_cerrados':
				if ($_SESSION['rolUsuario'] == 6 || $_SESSION['rolUsuario'] == 7 ) {
					$sql = "SELECT * FROM pqr WHERE status = 0 AND estado = 'finalizado' AND fmv = 1";
				}elseif ($_SESSION['rolUsuario'] == 1) {
					$sql = "SELECT * FROM pqr WHERE status = 0 AND estado = 'finalizado'";
				} 
				else {
					$sql = "SELECT * FROM pqr WHERE status = 0 AND estado = 'finalizado' AND fmv = 0";
				}
				$retorno = $this->conexion->consulQuery($sql);
				if ($retorno['resultado']->execute()) {
					$datos = $retorno['resultado']->fetchAll();
					return $datos;
				} else {
					print_r($retorno['resultado']->errorInfo());
				}
				break;
			case 'pqrVencimiento':
				$sql = "SELECT * FROM tipo_pqr_vencimiento";
				$retorno = $this->conexion->consulQuery($sql);
				if ($retorno['resultado']->execute()) {
					$datos = $retorno['resultado']->fetchAll();
					return $datos;
				} else {
					print_r($retorno['resultado']->errorInfo());
				}
				break;
			case 'pqr_vencido':
				$sql = "SELECT 
					pqr_filtrado_vencimiento_vista.id,
					pqr_filtrado_vencimiento_vista.id_pqr,
					pqr_filtrado_vencimiento_vista.fecha,
					tipo_pqr_vencimiento.tipo,
					tipo_pqr_vencimiento.dias_vencimiento,
					tipo_pqr_vencimiento.dias_recordatorio,
					pqr.estado
					FROM pqr_filtrado_vencimiento_vista
					INNER JOIN tipo_pqr_vencimiento ON pqr_filtrado_vencimiento_vista.tipo = tipo_pqr_vencimiento.tipo
					INNER JOIN pqr ON pqr_filtrado_vencimiento_vista.id_pqr = pqr.id";
				$retorno = $this->conexion->consulQuery($sql);
				if ($retorno['resultado']->execute()) {
					$datos = $retorno['resultado']->fetchAll();
					return $datos;
				} else {
					print_r($retorno['resultado']->errorInfo());
				}
				break;
			case 'pqr_vencido_respuesta':
				$sql = "SELECT 
						pqr_filtrado_vencimiento_respuesta_vista.*,
						 tipo_pqr_vencimiento.dias_vencimiento_usuario
						FROM 
						pqr_filtrado_vencimiento_respuesta_vista
						INNER JOIN tipo_pqr_vencimiento ON pqr_filtrado_vencimiento_respuesta_vista.tipo = tipo_pqr_vencimiento.tipo";
				$retorno = $this->conexion->consulQuery($sql);
				if ($retorno['resultado']->execute()) {
					$datos = $retorno['resultado']->fetchAll();
					return $datos;
				} else {
					print_r($retorno['resultado']->errorInfo());
				}
				break;
			case 'informes':
				$sql = "SELECT pqr_filtrado.*, 
						pqr.identidicacion,
						pqr.estado,
						pqr.nombre,
						pqr.fecha_subida,
						pqr.id
						FROM pqr_filtrado 
						INNER JOIN pqr ON pqr_filtrado.id_pqr = pqr.id
						WHERE 1" . $idUsuarioActivo['B'] . $idUsuarioActivo['C'] . $idUsuarioActivo['A'] . $idUsuarioActivo['fecha'];
				$retorno = $this->conexion->consulQuery($sql);
				if ($retorno['resultado']->execute()) {
					$datos = $retorno['resultado']->fetchAll();
					return $datos;
				}else{
					print_r($retorno['resultado']->errorInfo());
				}
				break;
			case 'asignados_coor':
				$sql = "SELECT pqr_gestionadores.id_pqr,
					pqr.identidicacion,
					pqr.estado,
                    pqr_filtrado.status
					FROM pqr_gestionadores 
					INNER JOIN pqr ON pqr_gestionadores.id_pqr = pqr.id
                    INNER JOIN pqr_filtrado ON  pqr_gestionadores.id_pqr =  pqr_filtrado.id_pqr
					WHERE pqr_gestionadores.id_usuario = :idUsuario
					AND pqr_gestionadores.status = 1";
				$retorno = $this->conexion->consulQuery($sql);
				$retorno['resultado']->bindParam(':idUsuario',$idUsuarioActivo,PDO::PARAM_INT);
				if ($retorno['resultado']->execute()) {
					$datos = $retorno['resultado']->fetchAll();
					return $datos;
				}else{
					print_r($retorno['resultado']->errorInfo());
				}
				break;
			case 'ingresos_salidas':
				$sql = "SELECT ingresos_salidas.*,
						usuarios.nombre_usuario 
						FROM ingresos_salidas
						INNER JOIN usuarios ON ingresos_salidas.usuario = usuarios.id
						ORDER BY id DESC";
				$retorno = $this->conexion->consulQuery($sql);
				if ($retorno['resultado']->execute()) 
				{
					$datos = $retorno['resultado']->fetchAll();
					return json_encode($datos);
				}
				else
				{
					print_r($retorno['resultado']->errorInfo());
				}
				break;
			default:
				# code...
				break;
			}
		} catch (Exception $e) {
			echo 'Excepción capturada: ', $e->getMessage(), "\n";
		} finally {
			$this->conexion = NULL;
		}
	}

}