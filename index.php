<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/vistas/home.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controladores/clases/carga_archivos_clase.php';
?>
<?php
if (!isset($_SESSION['bool'])) {
	?>
<!DOCTYPE html>
<!--
             +yhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh+
             hhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh+
             hhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh+
             -------------:/ohhhhhhhhhhhhhhhhMBhhhhh+
                             +hhhhhhhhhhhhhhhhhhyo/.
             .------..``    -yhhhhhhhhhhhhhhs+:.
            shh:.......-:/oyhhhhhhhhhhhhs/-`
             :os/. `-/shhhhhhhhhhhhyo:.                      `````````````
               .ohyhhhhhhhhhhhhs+:`             ::-`       -ohhhhhhhhhhhs.
          `-/oyhhhhhhhhhhhhs/-`  .:::::::::`     `.:/++/./shhhhhhhhhhy+.
      `:+shhhhhhhhhhhhhy/..::::-./hhhhhhhhh-          .oyhhhhhhhhhhy:`
  ./oyhhhhhhhhhhhhhhhhh.        .+hhhhhhhhh-        -shhhhhhhhhhhys:.
ohhhhhhhhhhhhhhhhhhhhhho-````````/hhhhhhhhh/---.../yhhhhhhhhhhy+.ohhhyo/.
hhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhho-::+hhhhhhhs.
hhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh--:://+++++++/`
hhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh+`
-:::::-`````````````````````:::::ohhhhhhhhh+::::/+yhhhhhhhhhhh+.
                                 /hhhhhhhhh-       -shhhhhhhhhhhs:`
                                 /hhhhhhhhh-         .+oyhhhhhhhhhy/`
                                 -sssssssss.             :shhhhhhhhhho-
                                                           .+hhhhhhhhhhy/`
                                                             `+hhhhhhhhhhy+.
                                                               -shhhhhhhhhhhs:
                                                                 .+hhhhhhhhhhhy+
 -->
<html lang="en">
<head>
	<title>Sistema de PQR</title>
	<link rel="stylesheet" type="text/css" href="/externos/mdb/mdb.min.css">
	<link rel="stylesheet" type="text/css" href="/node_modules/bootstrap/dist/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="/publico/css/estilos.css">
	<link rel="stylesheet" type="text/css" href="/node_modules/@fortawesome/fontawesome-free/css/all.css">
	<link rel="stylesheet" type="text/css" href="/publico/css/toastr.min.css">
	<link rel="stylesheet" type="text/css" href="/externos/editor/dist/trumbowyg.min.css">
	<link media="all" rel="stylesheet" type="text/css" href="/node_modules/bootstrap-fileinput/css/fileinput.css">
	<link rel="icon" href="/archivos/favicon.png" type="image/x-icon"/>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>
<body>
<?php
	$obj = new homePrincipal();
	$obj->menuNavegacion();
	$obj->formularioLogeo();
	$objcarga = new carga(0);
?>
</body>
</html>
<?php
} else {
	header('location:../vistas/panel_control_vista.php');
}
?>

