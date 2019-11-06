<?php
session_name('PQRUSER');
session_start();
if (isset($_SESSION['bool'])) {
	include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/panel_control_controlador.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/clases/plantilla_clase.php';
	$objplantilla = new plantilla();
	$objplantilla->cabezera();
	$objplantilla->cuerpo();
	$objplantilla->menuNavegacion();
	$objplantilla->menuLateral();
	$obj = new panelControl();
	$objplantilla->pie();
} else {
	header('location:../index.php');
}
