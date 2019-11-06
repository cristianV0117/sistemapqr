<?php

include_once __DIR__ . '/../../modelos/conexion.php';
/**
 * 
 */
class VencimientoRespuestaAutomatico
{
    
    private $cerrar = "Se ha cerrado el PQR automaticamente";
    private $ahora;
    private $dia;
    private $mes;
    private $fin;
    private $feriado = false;

    public function __construct()
    {
        try {
            
            $this->diasExcp();
            $this->vencimientoAreaPeticion();
            $this->vencimientoAreaQueja();
            $this->vencimientoAreaReclamo();
        } catch (Exception $e) {
            die('Excepción capturada: ' . $e->getMessage() . "\n");
        }
    }

    private function diasExcp()
    {
        try {
            $this->ahora = date('Y-m-d H:i:s');
            $this->dia   = date("j");
            $this->mes   = date("m");
            $this->fin   = date("D");
            $feriados = $this->feriados();
            for ($i=0; $i < count($feriados); $i++) { 
                if ($this->mes === $feriados[$i]['meses'] && $this->dia === $feriados[$i]['dias']) {
                    $this->feriado = true;
                }
            }
        } catch (Exception $e) {
            die('Excepción capturada: ' . $e->getMessage() . "\n");
        }
    }

    private function vencimientoAreaPeticion()
    {
        try {
            $this->conexion = new conexion();
            $pqrVencido = "SELECT tipo_pqr_vencimiento_area.*,
                pqr_filtrado.id_pqr,
                pqr_filtrado.fecha
                FROM tipo_pqr_vencimiento_area
                INNER JOIN pqr_filtrado ON tipo_pqr_vencimiento_area.id_area = pqr_filtrado.id_area
                INNER JOIN pqr_respuesta_usuario ON pqr_filtrado.id_pqr = pqr_respuesta_usuario.id_pqr
                WHERE tipo_pqr_vencimiento_area.tipo = 'peticion' AND pqr_filtrado.status <> 3 AND pqr_filtrado.id_tipo = 1";
            $retornoSql = $this->conexion->consulQuery($pqrVencido);
            if ($retornoSql['resultado']->execute()) {
                $datos = $retornoSql['resultado']->fetchAll();
                $this->verificarFecha($datos);
            } else {
                echo json_encode($retornoSql['resultado']->errorInfo());
            }
        } catch (Exception $e) {
            die('Excepción capturada: ' . $e->getMessage() . "\n");
        }
    }

    private function vencimientoAreaQueja()
    {
        try {
            $this->conexion = new conexion();
            $pqrVencido = "SELECT tipo_pqr_vencimiento_area.*,
                pqr_filtrado.id_pqr,
                pqr_filtrado.fecha
                FROM tipo_pqr_vencimiento_area
                INNER JOIN pqr_filtrado ON tipo_pqr_vencimiento_area.id_area = pqr_filtrado.id_area
                INNER JOIN pqr_respuesta_usuario ON pqr_filtrado.id_pqr = pqr_respuesta_usuario.id_pqr
                WHERE tipo_pqr_vencimiento_area.tipo = 'queja' AND pqr_filtrado.status <> 3 AND pqr_filtrado.id_tipo = 2";
            $retornoSql = $this->conexion->consulQuery($pqrVencido);
            if ($retornoSql['resultado']->execute()) {
                $datos = $retornoSql['resultado']->fetchAll();
                $this->verificarFecha($datos);
            } else {
                echo json_encode($retornoSql['resultado']->errorInfo());
            }
        } catch (Exception $e) {
            die('Excepción capturada: ' . $e->getMessage() . "\n");
        }
    }

    private function vencimientoAreaReclamo()
    {
        try {
            $this->conexion = new conexion();
            $pqrVencido = "SELECT tipo_pqr_vencimiento_area.*,
                pqr_filtrado.id_pqr,
                pqr_filtrado.fecha
                FROM tipo_pqr_vencimiento_area
                INNER JOIN pqr_filtrado ON tipo_pqr_vencimiento_area.id_area = pqr_filtrado.id_area
                INNER JOIN pqr_respuesta_usuario ON pqr_filtrado.id_pqr = pqr_respuesta_usuario.id_pqr
                WHERE tipo_pqr_vencimiento_area.tipo = 'reclamo' AND pqr_filtrado.status <> 3 AND pqr_filtrado.id_tipo = 3";
            $retornoSql = $this->conexion->consulQuery($pqrVencido);
            if ($retornoSql['resultado']->execute()) {
                $datos = $retornoSql['resultado']->fetchAll();
                $this->verificarFecha($datos);
            } else {
                echo json_encode($retornoSql['resultado']->errorInfo());
            }
        } catch (Exception $e) {
            die('Excepción capturada: ' . $e->getMessage() . "\n");
        }
    }

    private function feriados()
    {
        try {
            $this->conexion = new conexion();
            $sql = "SELECT * FROM feriados";
            $retornoSql = $this->conexion->consulQuery($sql);
            if ($retornoSql['resultado']->execute()) {
                $datos = $retornoSql['resultado']->fetchAll();
                return $datos;
            } else {
                echo json_encode($retornoSql['resultado']->errorInfo());
            }
        } catch (Exception $e) {
            die('Excepción capturada: ' . $e->getMessage() . "\n");
        }
    }

    private function verificarFecha($datos)
    {
        try {
            $this->conexion = new conexion();
            for ($i=0; $i < count($datos); $i++) { 
                if ($this->feriado === true || $this->fin == 'Sat' || $this->fin == "Sun") {
                    $datos[$i]['dias_vencimiento_usuario'] = $datos[$i]['dias_vencimiento_usuario'] + 1;
                    $fechaDiasSumada = date("Y-m-d H:i:s",strtotime($datos[$i]['fecha']."+ ".$datos[$i]['dias_vencimiento_usuario']." days"));
                } else {
                    $fechaDiasSumada = date("Y-m-d H:i:s",strtotime($datos[$i]['fecha']."+ ".$datos[$i]['dias_vencimiento_usuario']." days"));
                }
                if ($this->ahora > $fechaDiasSumada) {
                    $sqlCerrado = "INSERT INTO pqr_cerrado (id_pqr,id_usuario,fecha,mensaje) VALUES (:id_pqr,0,CURRENT_TIMESTAMP,:mensaje)";
                    $retornoCerrado = $this->conexion->consulQuery($sqlCerrado);
                    $retornoCerrado['resultado']->bindParam(':id_pqr', $datos[$i]['id_pqr'], PDO::PARAM_INT);
                    $retornoCerrado['resultado']->bindParam(':mensaje', $this->cerrar, PDO::PARAM_STR);
                    if ($retornoCerrado['resultado']->execute()) {
                        $sqlFiltrado = "UPDATE pqr_filtrado SET status = 3 WHERE id_pqr = :idPqr";
                        $retornoFiltrado = $this->conexion->consulQuery($sqlFiltrado);
                        $retornoFiltrado['resultado']->bindParam(':idPqr', $datos[$i]['id_pqr'], PDO::PARAM_INT);
                        if ($retornoFiltrado['resultado']->execute()) {
                            $sqlPqr = "UPDATE pqr SET status = 0, estado = 'finalizado' WHERE id = :idPqr";
                            $retornPqr = $this->conexion->consulQuery($sqlPqr);
                            $retornPqr['resultado']->bindParam(':idPqr', $datos[$i]['id_pqr'], PDO::PARAM_INT);
                            if ($retornPqr['resultado']->execute()) {
                                echo "PQR's Editados";
                            } else {
                                echo json_encode($retornPqr['resultado']->errorInfo());
                            }
                        } else {
                            echo json_encode($retornoFiltrado['resultado']->errorInfo());
                        }
                    } else {
                        echo json_encode($retornoCerrado['resultado']->errorInfo());
                    }
                } else {
                    echo 'Se ha ejecutado los que no han cumplido el vencimiento del recordatorio' . "\n";
                }
            }
        } catch (Exception $e) {
            die('Excepción capturada: ' . $e->getMessage() . "\n");
        }
    }
}
new VencimientoRespuestaAutomatico();