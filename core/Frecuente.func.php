<?php
/**
 * Contiene todas las funciones necesarias en diferentes módulos o clases
 */

/**
 * Convierte un arreglo que contiene codigo HTML en texto separado por salto de linea.
 * @param array $arr_tag
 * @return string
 */
function tag_string($arr_tag){
	return str_replace("	", "", implode("\n", $arr_tag));
}
/**
 * Provee la sentencia con los argumentos necesarios para el redireccionamiento
 * @param string $controlador	Nombre del controlador
 * @param string $accion	Nombre de la acción
 * @param array $arr_url_arg	Arreglo de argumentos complementarios (nombre=>valor)
 * @param boolean $por_js	Si la sentencia va a ser a través de Javascript, se activa el atributo
 * @param boolean $campo_x_arg	Si se activa, en la forma donde se hace el submit debe exitir un campo por cada valor en el arreglo $arr_url_arg, ademas de tener en true $por_js
 * @return string
 */
function url_controlador($controlador=CONTROLADOR_DEFECTO,$accion=ACCION_DEFECTO, $arr_url_arg=array(), $por_js=true, $campo_x_arg=false){
	$arg_url_arg = (is_array($arr_url_arg) && count($arr_url_arg))? "&".http_build_query($arr_url_arg) : "";
	$campo_x_arg_encode = "";
	if($por_js){
		$urlString="javaScript:f_ir_a_controlador('frm_cero', '".$controlador."','".$accion."', '".$arg_url_arg."', $campo_x_arg)";
	}else{
		$urlString="index.php?controlador=".$controlador."&accion=".$accion.$arg_url_arg;
	}
	
	return $urlString;
}
/**
 * Ejecuta HTTP header para redireccionar en ese instante
 * @param string $controlador	Nombre del controlador, por defecto es el que se encuentra almacenado en la variable global <strong>CONTROLADOR_DEFECTO</strong> 
 * @param string $accion	Nombre de la acción del controlador, por defecto es el que se encuentra almacenado en la variable global <strong>ACCION_DEFECTO</strong>
 * @param string $url_arg	Parametros URL
 * @param string $url_uri	Parametro URL que guarda la dirección a donde dirigirse despues de autentificarse
 */
function redireccionar($controlador=CONTROLADOR_DEFECTO,$accion=ACCION_DEFECTO, $arr_url_arg=array(), $url_uri=""){
	if($_REQUEST['controlador'] == $controlador && $_REQUEST['accion'] == $accion){
	    if($controlador =='error' && $accion=='sin_ruta'){
	        $tag_li_err = '<li>Revisar que en la carpeta <em>view</em> correspondiente, exista el archivo <em>Error.php</em></li>';
	    }
	    $arr_tag = array();
	    $arr_tag[] = '<!DOCTYPE html>';
	    $arr_tag[] = '<html>';
	    $arr_tag[] = ' <head>';
	    $arr_tag[] = '  <title>Error - Ciclado de página</title>';
	    $arr_tag[] = ' </head>';
	    $arr_tag[] = ' <body>';
	    $arr_tag[] = '  <h1>Error</h1>';
	    $arr_tag[] = '  <h2>Se est&aacute; ciclando el redireccionamiento</h2>';
	    $arr_tag[] = '  <p>Los argumentos de redireccion y los de la ruta actual son los mismos. En donde:</p>';
	    $arr_tag[] = '  <ul>';
	    $arr_tag[] = '      <li>Argumento controlador es: <em>'.$controlador.'</em> y argumento accion es: <em>'.$accion.'</em>.</li>';
	    $arr_tag[] = '      '.$tag_li_err;
	    $arr_tag[] = '  </ul>';
	    $arr_tag[] = '  <p>Favor de informar al administrador del sistema. Gracias.</p>';
	    $arr_tag[] = ' </body>';
	    $arr_tag[] = '</html>';
	    echo tag_string($arr_tag);
		die();
	}
	
	$arg_url_arg = (is_array($arr_url_arg) && count($arr_url_arg))? "&".http_build_query($arr_url_arg,'','&') : "";
	$arg_url_uri = ($url_uri!="")? "&url_uri=".urlencode($url_uri) : "";
	
	
	$location = "index.php?controlador=".$controlador."&accion=".$accion.$arg_url_arg.$arg_url_uri;
	header("Location:".$location);
}
/**
 * Devuelve la concatenación separada por un espacio, de los argumentos en la función
 * @param string $nombre
 * @param string $ap_paterno
 * @param string $ap_materno
 * @return string
 */
function concatena_nombre($nombre, $ap_paterno, $ap_materno){
	return trim($ap_paterno." ".trim($ap_materno." ".$nombre));
}
/**
 * Devuelve el valor perteneciente al atributo <strong>indice</strong> si se encuentra contenido en el arreglo pertenenciente al atributo <strong>arreglo</strong>
 * @param array $arreglo
 * @param string $indice
 * @return string
 */
function valorEnArreglo($arreglo, $indice){
	if(isset($arreglo[$indice])){
		return $arreglo[$indice];
	}else{
		return '';
	}
}
/**
 * Encierra el valor de un campo para su ejecución en una sentencia query, con la sentencia o caracteres necesarios dependiendo del tipo de dato
 * @param string $valor
 * @param boolean $es_string	(true=string, false=diferente de string)
 * @return string
 */
function txt_sql($valor, $es_string=true){
	$txt_sql = "";
	if($valor==="" || is_null($valor))	$txt_sql = "NULL";
	else{
		$valor_c = str_replace("'", "\'", $valor);
		$txt_sql = ($es_string)? "TRIM('".$valor_c."')" : "'".$valor_c."'";
	}
	return $txt_sql;
}
/**
 * A partir del argumento <strong>cat_cuestionario_id</strong> cuyo valor es el Id de cuestionario, regresa un identificador o clave que se usa para nombrar (principalmente a modo de prefijo) archivos, variables o tablas que identifican al cuestionario actual.
 * @param integer $cat_cuestionario_id
 * @return string
 */
function cuest_cve($cat_cuestionario_id){
    return "c".str_pad($cat_cuestionario_id, 2, '0', STR_PAD_LEFT);
}
/**
 * Devuelve el nombre de la tabla que almacena el resultado de los indicadores a nivel cuestionario
 * @param integer $cat_cuestionario_id
 * @return string
 */
function nom_tbl_ind($cat_cuestionario_id){
	return "ind_".cuest_cve($cat_cuestionario_id);
}
/**
 * Utilizando la función <strong>number_format</strong> devuelve un número con formato
 * @param mixed $numero
 * @param integer $decimales
 * @return string
 */
function formato_miles($numero, $decimales){
	if($numero== "" || !is_numeric($numero)){
		return "";
	}else{
		return number_format($numero,$decimales,'.',',');
	}
}
/**
 * Funcion para hacer compatible htmlentities entre las versiones del xampp 1.6.6a y 7.2.18
 * @param string $texto
 * @return string
 */
function htmlentities_codificar($texto) {
	$es_xampp_1_6 = (defined('ES_XAMPP_1_6'))? ES_XAMPP_1_6 : false;
	if($es_xampp_1_6){
		return htmlentities(utf8_decode($texto));
	}else{
		return htmlentities($texto);
	}
}
/**
 * Funcion para hacer compatible html_entity_decode entre las versiones del xampp 1.6.6a y 7.2.18
 * @param string $texto
 * @return string
 */
function html_entity_decodificar($texto) {
	$es_xampp_1_6 = (defined('ES_XAMPP_1_6'))? ES_XAMPP_1_6 : false;
	if($es_xampp_1_6){
		return utf8_encode(html_entity_decode($texto));
	}else{
		return html_entity_decode($texto);
	}
}
function formato_folio($numero){
	if($numero){
		return str_pad($numero, 4, "0", STR_PAD_LEFT);
	}else{
		return "";
	}
}
/**
 * Dividendo / Divisor
 * @param double $dividendo
 * @param double $divisor
 */
function op_division($dividendo, $divisor){
	if(!is_numeric($dividendo) || !is_numeric($divisor)){
		return 0;
	}elseif($divisor==0){
		return 0;
	}else{
		return $dividendo/$divisor;
	}
}
?>