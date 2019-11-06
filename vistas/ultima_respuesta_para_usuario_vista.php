<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/respuesta_usuario.php';
$obj = new respuestaUsuario();
$obj->ultimaRespuestaUsuario();