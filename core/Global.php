<?php
/**
 * Archivo que contiene todas las variables globales, la mayoría sacadas del archivo config
 */

global $globales;
global $mysqli;
$es_conexion_hosting = false;

if($es_conexion_hosting){
	$dir_confg = $_SERVER['DOCUMENT_ROOT'].'/config/';
	$arc_config = 'mvc_forma_hosting.ini';
}else{
	$dir_confg = str_replace('htdocs', '', $_SERVER['DOCUMENT_ROOT']) . 'config/';
	$arc_config = 'mvc_forma.ini';
}
$global = @parse_ini_file($dir_confg . $arc_config, true);
if($global==false){
	echo "<h2>Error al buscar archivo ini</h2>";
	echo "<p>Archivo de configuración no encontrado</p>";
	echo "Ver archivo: global.php";
	die();
}

foreach ($global as $grupo => $valores) {
	foreach ($valores as $campo => $valor)
		$globales[$grupo][$campo] = $valor;
}
foreach ($globales["configuracion"] as $var_nom => $var_val){
	define(strtoupper($var_nom), $var_val);
}