<?php
include_once __DIR__ . '/../../modelos/conexion.php';

class recreoDB
{
	
	private $conexion;

	public function __construct()
	{
		try {
			if (isset($_SERVER['argv'][1])) {
				if (password_verify($_SERVER['argv'][1],'$2y$10$Budv1N05LWX/h0LRYrppj./UiNRAvBdhlKr5eFYr3.DnzdMFWgDGW')) {
					$this->recrearDB();
					$this->usuarios();
				}else{
					echo 'lo siento :( x2';
					exit;
				}
			}else{
				echo 'lo siento :(';
				exit;
			}
		} catch (Exception $e) {
			echo 'Excepción capturada: ', $e->getMessage(), "\n";
		}
	}

	private function recrearDB()
	{
		try {
			$this->conexion = new conexion();
			$pqr_solucion = "TRUNCATE TABLE pqr_solucion";
			$pqr_cerrado = "TRUNCATE TABLE pqr_cerrado";
			$pqr_filtrado = "TRUNCATE TABLE pqr_filtrado";
			$pqr = "TRUNCATE TABLE pqr";
			$pqr_gestionadores = "TRUNCATE TABLE pqr_gestionadores";
			$pqr_respuesta_usuario = "TRUNCATE TABLE pqr_respuesta_usuario";
			$pqrsolucion_archivos = "TRUNCATE TABLE pqrsolucion_archivos";
			$rutas_respuestas_archivos = "TRUNCATE TABLE rutas_respuestas_archivos";
			$re_pqr_solucion = $this->conexion->consulQuery($pqr_solucion);
			$re_pqr_cerrado = $this->conexion->consulQuery($pqr_cerrado);
			$re_pqr_filtrado = $this->conexion->consulQuery($pqr_filtrado);
			$re_pqr = $this->conexion->consulQuery($pqr);
			$re_pqr_gestionadores = $this->conexion->consulQuery($pqr_gestionadores);
			$re_pqr_respuesta_usuario = $this->conexion->consulQuery($pqr_respuesta_usuario);
			$re_pqrsolucion_archivos = $this->conexion->consulQuery($pqrsolucion_archivos);
			$re_rutas_respuestas_archivos = $this->conexion->consulQuery($rutas_respuestas_archivos);
			if($re_pqr_solucion->execute() && $re_pqr_cerrado->execute() && $re_pqr_filtrado->execute() && $re_pqr->execute() && $re_pqr_gestionadores->execute() && $re_pqr_respuesta_usuario->execute() && $re_pqrsolucion_archivos->execute() && $re_rutas_respuestas_archivos->execute()){
				echo 'ejecutado DB';
			}
		} catch (Exception $e) {
			echo 'Excepción capturada: ', $e->getMessage(), "\n";
		}finally{
			$this->conexion = NULL;
		}
	}

	private function usuarios()
	{
		try {
			$this->conexion = new conexion();
			$admin = 'admin';
			$contra = '$2y$10$dbMZjRqo.8.zfn7UM/JC4uLFIoYBKnrlQK73RIJDIZWPv9EQYCkJG';
			$documento = 0;
			$distrito = 0;
			$area = 0;
			$rol = 1;
			$status = 1;
			$usuarios_truncate = "TRUNCATE TABLE usuarios";
			$re_usuarios_truncate = $this->conexion->consulQuery($usuarios_truncate);
			if ($re_usuarios_truncate->execute()) {
				$insert_ad = "INSERT INTO usuarios (nombre_usuario, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, email, ciudad, documento, distrito, area, rol, status, contrasena) VALUES (:nombre_usuario,:primer_nombre,:segundo_nombre,:primer_apellido,:segundo_apellido,:email,:ciudad,:documento,:distrito,:area,:rol,:status,:contrasena)";
				$re_insert_ad = $this->conexion->consulQuery($insert_ad);
				$re_insert_ad->bindParam(':nombre_usuario',$admin,PDO::PARAM_STR);
				$re_insert_ad->bindParam(':primer_nombre',$admin,PDO::PARAM_STR);
				$re_insert_ad->bindParam(':segundo_nombre',$admin,PDO::PARAM_STR);
				$re_insert_ad->bindParam(':primer_apellido',$admin,PDO::PARAM_STR);
				$re_insert_ad->bindParam(':segundo_apellido',$admin,PDO::PARAM_STR);
				$re_insert_ad->bindParam(':email',$admin,PDO::PARAM_STR);
				$re_insert_ad->bindParam(':ciudad',$admin,PDO::PARAM_STR);
				$re_insert_ad->bindParam(':documento',$documento,PDO::PARAM_INT);
				$re_insert_ad->bindParam(':distrito',$distrito,PDO::PARAM_INT);
				$re_insert_ad->bindParam(':area',$area,PDO::PARAM_INT);
				$re_insert_ad->bindParam(':rol',$rol,PDO::PARAM_INT);
				$re_insert_ad->bindParam(':status',$status,PDO::PARAM_INT);
				$re_insert_ad->bindParam(':contrasena',$contra,PDO::PARAM_STR);
				if ($re_insert_ad->execute()) {
					$sistema = 'sistema';
					$contraSis = 'sistema';
					$documentoSis = 0;
					$distritoSis = 0;
					$areaSis = 0;
					$rolSis = 0;
					$statusSis = 1;
					$insert_sis = "INSERT INTO usuarios (nombre_usuario, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, email, ciudad, documento, distrito, area, rol, status, contrasena) VALUES (:nombre_usuario,:primer_nombre,:segundo_nombre,:primer_apellido,:segundo_apellido,:email,:ciudad,:documento,:distrito,:area,:rol,:status,:contrasena)";
					$re_insert_sis = $this->conexion->consulQuery($insert_sis);
					$re_insert_sis->bindParam(':nombre_usuario',$sistema,PDO::PARAM_STR);
					$re_insert_sis->bindParam(':primer_nombre',$sistema,PDO::PARAM_STR);
					$re_insert_sis->bindParam(':segundo_nombre',$sistema,PDO::PARAM_STR);
					$re_insert_sis->bindParam(':primer_apellido',$sistema,PDO::PARAM_STR);
					$re_insert_sis->bindParam(':segundo_apellido',$sistema,PDO::PARAM_STR);
					$re_insert_sis->bindParam(':email',$sistema,PDO::PARAM_STR);
					$re_insert_sis->bindParam(':ciudad',$sistema,PDO::PARAM_STR);
					$re_insert_sis->bindParam(':documento',$documentoSis,PDO::PARAM_INT);
					$re_insert_sis->bindParam(':distrito',$distritoSis,PDO::PARAM_INT);
					$re_insert_sis->bindParam(':area',$areaSis,PDO::PARAM_INT);
					$re_insert_sis->bindParam(':rol',$rolSis,PDO::PARAM_INT);
					$re_insert_sis->bindParam(':status',$statusSis,PDO::PARAM_INT);
					$re_insert_sis->bindParam(':contrasena',$contraSis,PDO::PARAM_STR);
					if ($re_insert_sis->execute()) {
						echo 'ejecutado usuario y sistema';
					} else {
						print_r($re_insert_sis->errorInfo());
					}
				} else {
					print_r($re_insert_ad->errorInfo());
				}
			} else {
				print_r($re_usuarios_truncate->errorInfo());
			}
		} catch (Exception $e) {
			echo 'Excepción capturada: ', $e->getMessage(), "\n";
		}finally{
			$this->conexion = NULL;
		}
	}
}
(php_sapi_name() == 'cli') ? new recreoDB() : die('error');
