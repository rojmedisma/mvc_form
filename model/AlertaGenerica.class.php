<?php
/**
 * Permite desplegar un mensaje de alerta en formato HTML con todas las propiedades básica de un framework <strong>bootstrap</strong> en caso de ser necesario mostrar un mensaje con formato para los casos en donde todavía no se llega a la parte de despliegue de la página
 * @author Ismael Rojas
 *
 */
class AlertaGenerica{
	private $sin_libreria = true;
	public function __construct($sin_libreria = true){
		$this->sin_libreria = $sin_libreria;
	}
	/**
	 * Devuelve la indicación de mostrar la alerta con declaración de librerías o sin ellas, dependiendo el lugar y momento en que la alerta está siendo desplegada
	 * @return boolean
	 */
	private function getSinLibreria(){
		return $this->sin_libreria;
	}
	/**
	 * Devuelve la parte HTML del tag head que contiene las librerías y demás configuraciones
	 * @return string
	 */
	private function getHead(){
		$arr_tag = array();
		$arr_tag[] = '<head>';
		$arr_tag[] = '	<meta charset="'.HTML_CODIFICACION.'">';
		$arr_tag[] = '	<meta name="viewport" content="width=device-width, initial-scale=1">';
		$arr_tag[] = '	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">';
		$arr_tag[] = '	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>';
		$arr_tag[] = '	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>';
		$arr_tag[] = '</head>';
		return tag_string($arr_tag);
	}
	/**
	 * Devuelve la parte HTML del tag div con la alerta
	 * @param string $txt_tit	Título de la alerta
	 * @param string $txt_desc	Descripción de la alerta
	 * @param string $alerta	tipo de alerta (success, info, warning, danger)
	 * @return string
	 */
	private function getTagAlerta($txt_tit, $txt_desc, $alerta){
		$arr_tag= array();
		$arr_tag[] = '<div class="alert alert-'.$alerta.' mt-5">';
		$arr_tag[] = '	<strong>';
		$arr_tag[] = '		'.$txt_tit;
		$arr_tag[] = '	</strong>';
		$arr_tag[] = '	'.$txt_desc;
		$arr_tag[] = '</div>';
		return tag_string($arr_tag);
	}
	/**
	 * Devuelve todo el HTML necesario para desplegar la alerta
	 * @param string $txt_tit	Título de la alerta
	 * @param string $txt_desc	Descripción de la alerta
	 * @param string $alerta	tipo de alerta (success, info, warning, danger)
	 * @return string
	 */
	public function getAlerta($txt_tit, $txt_desc, $alerta='danger'){
		$tag_alerta = $this->getTagAlerta($txt_tit, $txt_desc, $alerta);
		
		$arr_tag= array();
		if($this->getSinLibreria()){
			$arr_tag[] = $tag_alerta; 
		}else{
			$arr_tag[] = '<!DOCTYPE html>';
			$arr_tag[] = '<html lang="es">';
			$arr_tag[] = '	'.$this->getHead();
			$arr_tag[] = '	<body>';
			$arr_tag[] = '		<div class="container">';
			$arr_tag[] = '			'.$tag_alerta;
			$arr_tag[] = '		</div>';
			$arr_tag[] = '	</body>';
			$arr_tag[] = '</html>';
		}
		return tag_string($arr_tag);
	}
}