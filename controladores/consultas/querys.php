<?php
/**
 *
 */
include_once $_SERVER['DOCUMENT_ROOT'] . '/modelos/conexion.php';
if (!isset($_SESSION)) {
	session_name('PQRUSER');
	session_start();
}
class Querys {

	private $conexion;

	public function consultarQuery($post, $idObjetoAfectado,$segundoObjetoAfectado = null) {
		try {
			$this->conexion = new conexion();
			switch ($post) {
			case 'pqr':
				$sql = "SELECT * FROM pqr WHERE id = :id";
				$retorno = $this->conexion->consulQuery($sql);
				$retorno['resultado']->bindParam(':id', $idObjetoAfectado, PDO::PARAM_INT);
				if ($retorno['resultado']->execute()) {
					$datos = $retorno['resultado']->fetchAll();
					return $datos;
				} else {
					print_r($retorno['resultado']->errorInfo());
				}
				break;
			case 'pqr_filtrado':
				$sql = "SELECT
					pqr_filtrado.id,
					pqr_filtrado.fecha,
					usuarios.nombre_usuario AS usuario,
					distritos.nombre AS distrito,
					areas.id AS idArea,
					areas.nombre AS area,
					tipo_pqr.tipo,
					distritos.id as idDistrito,
                    tipo_pqr.id as tipoPqr
					FROM pqr_filtrado
					INNER JOIN distritos ON pqr_filtrado.id_distrito = distritos.id
					INNER JOIN areas ON pqr_filtrado.id_area = areas.id
					INNER JOIN tipo_pqr ON pqr_filtrado.id_tipo = tipo_pqr.id
					INNER JOIN usuarios ON pqr_filtrado.id_usuario = usuarios.id
					WHERE pqr_filtrado.id_pqr = :idpqr AND usuarios.id";
				$retorno = $this->conexion->consulQuery($sql);
				$retorno['resultado']->bindParam(':idpqr', $idObjetoAfectado, PDO::PARAM_INT);
				if ($retorno['resultado']->execute()) {
					$datos = $retorno['resultado']->fetchAll();
					return $datos;
				} else {
					print_r($retorno['resultado']->errorInfo());
				}
				break;
			case 'usuarios_area':
				$id = $_SESSION['idUsuario'];
				if($_SESSION['rolUsuario'] == 6 || $_SESSION['rolUsuario'] == 7){
					$sql_usuarios_areas = "SELECT * FROM usuarios WHERE not exists (SELECT * FROM pqr_gestionadores WHERE usuarios.id = pqr_gestionadores.id_usuario  AND id_pqr = :idPqr) AND status = 1 AND rol = 8";
				}else{
					$sql_usuarios_areas = "SELECT * FROM usuarios WHERE not exists (SELECT * FROM pqr_gestionadores WHERE usuarios.id = pqr_gestionadores.id_usuario  AND id_pqr = :idPqr) AND status = 1 AND(rol = 4 OR rol = 2)  AND usuarios.id <> $id OR exists (SELECT * FROM pqr_gestionadores WHERE usuarios.id = pqr_gestionadores.id_usuario  AND id_pqr = :idPqr AND status = 0)";
				}
				$explode = explode("-", $idObjetoAfectado);
				$retorno = $this->conexion->consulQuery($sql_usuarios_areas);
				$retorno['resultado']->bindParam(':idPqr', $explode[1], PDO::PARAM_INT);
				if ($retorno['resultado']->execute()) {
					$datos = $retorno['resultado']->fetchAll();
					return $datos;
				} else {
					print_r($retorno['resultado']->errorInfo());
				}
				break;
			case 'usuarios_con_pqr':
				$sql = "SELECT pqr_gestionadores.*,
						usuarios.nombre_usuario
						FROM pqr_gestionadores
						INNER JOIN usuarios ON pqr_gestionadores.id_usuario = usuarios.id
						WHERE pqr_gestionadores.id_pqr = :idPqr
						AND pqr_gestionadores.status = 1";
				$retorno = $this->conexion->consulQuery($sql);
				$retorno['resultado']->bindParam(':idPqr',$idObjetoAfectado,PDO::PARAM_INT);
				if ($retorno['resultado']->execute()) {
					$datos = $retorno['resultado']->fetchAll();
					return $datos;	
				} else {
					print_r($retorno['resultado']->errorInfo());
				}
				break;
			case 'id_area_por_pqr':
				$sql = "SELECT id_area FROM pqr_filtrado WHERE id_pqr = :idPqr";
				$retorno = $this->conexion->consulQuery($sql);
				$retorno['resultado']->bindParam(':idPqr', $idObjetoAfectado, PDO::PARAM_INT);
				if ($retorno['resultado']->execute()) {
					$datos = $retorno['resultado']->fetchAll();
					return $datos;
				} else {
					print_r($retorno['resultado']->errorInfo());
				}
				break;
			case 'pqr_solucion':
				$sql = "SELECT pqr_solucion.*,
				pqr.identidicacion,
				usuarios.nombre_usuario,
				usuarios.email
				FROM pqr_solucion
				inner JOIN pqr ON pqr_solucion.id_pqr = pqr.id
				INNER JOIN usuarios ON pqr_solucion.id_usuario = usuarios.id
				WHERE pqr_solucion.id_pqr = :idPqr";
				$retorno = $this->conexion->consulQuery($sql);
				$retorno['resultado']->bindParam(':idPqr', $idObjetoAfectado, PDO::PARAM_INT);
				if ($retorno['resultado']->execute()) {
					$datos = $retorno['resultado']->fetchAll();
					return $datos;
				} else {
					print_r($retorno['resultado']->errorInfo());
				}
				break;
			case 'pqr_solucion_imagenes':
				$sql = "SELECT * FROM pqrsolucion_archivos WHERE id_pqr = :idPqr GROUP BY id ";
				$retorno = $this->conexion->consulQuery($sql);
				$retorno['resultado']->bindParam(':idPqr', $idObjetoAfectado, PDO::PARAM_INT);
				if ($retorno['resultado']->execute()) {
					$datos = $retorno['resultado']->fetchAll();
					return $datos;
				} else {
					print_r($retorno['resultado']->errorInfo());
				}
				break;
			case 'pqr_cerrado':
				$slq_general = "SELECT * FROM pqr WHERE identidicacion = :codigo AND status = 1";
				$retorno_general = $this->conexion->consulQuery($slq_general);
				$retorno_general['resultado']->bindParam(':codigo', $idObjetoAfectado, PDO::PARAM_STR);
				if ($retorno_general['resultado']->execute()) {
					$datos_general = $retorno_general['resultado']->fetch(PDO::FETCH_ASSOC);
					if ($datos_general['estado'] == 'abierto' || $datos_general['estado'] == 'proceso') {
						return $datos_general;
					} else {
						$sql = "SELECT pqr.identidicacion,
								pqr.estado,
								pqr_solucion.fecha,
								pqr.id
								FROM pqr
								INNER JOIN pqr_solucion ON pqr.id = pqr_solucion.id_pqr
								WHERE identidicacion = :codigo
								GROUP BY pqr.identidicacion";
						$retorno = $this->conexion->consulQuery($sql);
						$retorno['resultado']->bindParam(':codigo', $idObjetoAfectado, PDO::PARAM_STR);
						if ($retorno['resultado']->execute()) {
							$datos = $retorno['resultado']->fetch(PDO::FETCH_ASSOC);
							return $datos;
						} else {
							print_r($retorno['resultado']->errorInfo());
						}
					}
				} else {
					print_r($retorno_general['resultado']->errorInfo());
				}

				break;
			case 'usuario_info':
				$sql_usuarios = "SELECT
					usuarios.id,
					usuarios.nombre_usuario,
					usuarios.primer_nombre,
					usuarios.segundo_nombre,
					usuarios.primer_apellido,
					usuarios.segundo_apellido,
					usuarios.email,
					usuarios.ciudad,
					usuarios.documento,
					distritos.nombre as distrito,
					areas.nombre as area,
					roles.tipo
					FROM usuarios
					INNER JOIN distritos ON usuarios.distrito = distritos.id
					INNER JOIN areas ON usuarios.area = areas.id
					INNER JOIN roles ON usuarios.rol = roles.id
					WHERE usuarios.id = :id
					AND usuarios.status = 1";
				$retorno = $this->conexion->consulQuery($sql_usuarios);
				$retorno['resultado']->bindParam(':id', $idObjetoAfectado, PDO::PARAM_STR);
				if ($retorno['resultado']->execute()) {
					$datos = $retorno['resultado']->fetch(PDO::FETCH_ASSOC);
					return $datos;
				} else {
					print_r($retorno['resultado']->errorInfo());
				}
				break;
			case 'pqr_respondidos':
				$sql_respondido = "SELECT * FROM pqr_respuesta_usuario WHERE random = :random LIMIT 1";
				$retorno = $this->conexion->consulQuery($sql_respondido);
				$retorno['resultado']->bindParam(':random',$idObjetoAfectado,PDO::PARAM_INT);
				if ($retorno['resultado']->execute()) {
					$datos = $retorno['resultado']->fetch(PDO::FETCH_ASSOC);
					return $datos;
				} else {
					print_r($retorno['resultado']->errorInfo());
				}
				break;
			case 'pqr_cerrado_general':
				$sql_respondido = "SELECT 
								pqr_cerrado.*,
								usuarios.nombre_usuario
								FROM pqr_cerrado
								LEFT OUTER JOIN usuarios ON pqr_cerrado.id_usuario = usuarios.id WHERE pqr_cerrado.id_pqr = :id ";
				$retorno = $this->conexion->consulQuery($sql_respondido);
				$retorno['resultado']->bindParam(':id',$idObjetoAfectado,PDO::PARAM_INT);
				if ($retorno['resultado']->execute()) {
					$datos = $retorno['resultado']->fetchAll();
					return $datos;
				} else {
					print_r($retorno['resultado']->errorInfo());
				}
				break;
			case 'usuarios_especificos':
				$sql = "SELECT * FROM usuarios WHERE area = :area AND rol = 2";
				$retorno = $this->conexion->consulQuery($sql);
				$retorno['resultado']->bindParam(':area',$idObjetoAfectado,PDO::PARAM_INT);
				if ($retorno['resultado']->execute()) {
					$datos = $retorno['resultado']->fetchAll();
					return $datos;
				} else {
					print_r($retorno['resultado']->errorInfo());
				}
				break;
			case 'pqr_respuesta_usuario':
				$sql = "SELECT 
					pqr_respuesta_usuario.*,
					usuarios.nombre_usuario 
					FROM pqr_respuesta_usuario 
					INNER JOIN usuarios ON pqr_respuesta_usuario.id_usuario = usuarios.id
					WHERE id_pqr = :idPqr GROUP BY random ORDER BY id asc";
				$retorno = $this->conexion->consulQuery($sql);
				$retorno['resultado']->bindParam(':idPqr',$idObjetoAfectado,PDO::PARAM_INT);
				if ($retorno['resultado']->execute()) {
					$datos = $retorno['resultado']->fetchAll();
					return $datos;
				} else {
					print_r($retorno['resultado']->errorInfo());
				}
				break;
			case 'respuestas_para_usuario':
				$sql = "SELECT 
					pqr_respuesta_usuario.*,
					usuarios.nombre_usuario 
					FROM pqr_respuesta_usuario 
					INNER JOIN usuarios ON pqr_respuesta_usuario.id_usuario = usuarios.id
					WHERE id_pqr = :idPqr GROUP BY random";
				$retorno = $this->conexion->consulQuery($sql);
				$retorno['resultado']->bindParam(':idPqr',$idObjetoAfectado,PDO::PARAM_INT);
				if ($retorno['resultado']->execute()) {
					$datos = $retorno['resultado']->fetchAll();
					return $datos;
				} else {
					print_r($retorno['resultado']->errorInfo());
				}
				break;
			case 'consultar_ultimo_respondido':
				$sql = "SELECT * FROM pqr_respuesta_usuario WHERE id_pqr = :idPqr ORDER BY id desc limit 1";
				$retorno = $this->conexion->consulQuery($sql);
				$retorno['resultado']->bindParam(':idPqr',$idObjetoAfectado,PDO::PARAM_INT);
				if ($retorno['resultado']->execute()) {
					$datos = $retorno['resultado']->fetchAll();
					return $datos;
				}else{
					print_r($retorno['resultado']->errorInfo());
				}
				break;
			case 'pqr_gestionadores':
				$sql = "SELECT * FROM pqr_gestionadores WHERE id_pqr = :idPqr AND id_usuario = :idUsuario";
				$retorno = $this->conexion->consulQuery($sql);
				$retorno['resultado']->bindParam(':idPqr',$idObjetoAfectado,PDO::PARAM_INT);
				$retorno['resultado']->bindParam(':idUsuario',$segundoObjetoAfectado,PDO::PARAM_INT);
				if ($retorno['resultado']->execute()) {
					$datos = $retorno['resultado']->fetchAll();
					return $datos;
				}else{
					print_r($retorno['resultado']->errorInfo());
				}
				break;
			case 'pqr_gestionadores_indi':
				$sql = "SELECT 
						pqr_gestionadores.*,
						usu.nombre_usuario,
						us.nombre_usuario as usuarioAfecta
						FROM pqr_gestionadores
						inner join usuarios as usu on pqr_gestionadores.id_usuario = usu.id
						inner join usuarios as us on pqr_gestionadores.id_usuarioser = us.id
						WHERE pqr_gestionadores.id_pqr = :idPqr";
				$retorno = $this->conexion->consulQuery($sql);
				$retorno['resultado']->bindParam(':idPqr',$idObjetoAfectado,PDO::PARAM_INT);
				if ($retorno['resultado']->execute()) {
					$datos = $retorno['resultado']->fetchAll();
					return $datos;
				}else{
					print_r($retorno['resultado']->errorInfo());
				}
				break;
			case 'pqr_rechazado':
				$sql = "SELECT * FROM pqr_rechazado WHERE id_pqr = :idPqr";
				$retorno = $this->conexion->consulQuery($sql);
				$retorno['resultado']->bindParam(':idPqr',$idObjetoAfectado,PDO::PARAM_INT);
				if ($retorno['resultado']->execute()) {
					$datos = $retorno['resultado']->fetchAll();
					return $datos;
				} else {
					print_r($retorno['resultado']->errorInfo());
				}
				break;
			case 'consultaPlataforma':
				$sql = "SELECT * FROM pqr_respuesta_usuario WHERE tipo = 1 AND id_pqr = :idPqr";
				$retorno = $this->conexion->consulQuery($sql);
				$retorno['resultado']->bindParam(':idPqr',$idObjetoAfectado,PDO::PARAM_INT);
				if ($retorno['resultado']->execute()) {
					$datos = $retorno['resultado']->fetchAll();
					return $datos;
				} else {
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