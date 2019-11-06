<?php
session_name('PQRUSER');
session_start();
if (isset($_SESSION['bool'])) {
	include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/pqr_solucion_controlador.php';
	$obj = new pqrSolucion();
	$obj->listadPqrFiltrados();
} else {
	header('location:../index.php');
}