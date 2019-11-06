<?php
session_name('PQRUSER');
session_start();
if (isset($_SESSION['bool'])) {
	include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/pqr_controlador.php';
	$obj = new pqr();
	$obj->pqrcerradosvista();
} else {
	header('location:../index.php');
}