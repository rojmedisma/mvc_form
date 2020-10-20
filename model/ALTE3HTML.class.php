<?php
/**
 * Descripción de ALTE3HTML
 * Genera tags HTML con atributos a partir de la plantilla AdminLTE3
 * @author Ismael Rojas
 */
class ALTE3HTML{
	private $html_contenido;
	private $arr_atrib_li_nav_item = array();
	private $arr_atrib;
	public function __construct() {
		$this->arr_atrib_li_nav_item = array(
			"class"=>"nav-item"
		);
	}
	/**
	 * Lista para crear el menú horizontal derecho para ul clase navbar-nav. Clase nav-item
	 */
	public function setHTMLLiNavItem($controlador, $accion, $a_contenido, $arr_atrib_usu=array()) {
		$this->setArrAtrib($this->arr_atrib_li_nav_item, $arr_atrib_usu);
		$arr_tag = array();
		$arr_tag[] = '<li class="'.$this->getAtrib('class').'">';
		$arr_tag[] = '	<a href="'. url_controlador($controlador, $accion).'" class="nav-link">'.$a_contenido.'</a>';
		$arr_tag[] = '</li>';
		$this->html_contenido = tag_string($arr_tag);
	}
	public function getHTMLContenido() {
		return $this->html_contenido;
	}
	private function setArrAtrib($arr_atrib_default, $arr_atrib_usu){
		$this->arr_atrib = array_merge($arr_atrib_default,$arr_atrib_usu);
	}
	private function getAtrib($nom_atrib) {
		$arr_atrib = $this->arr_atrib;
		if(isset($arr_atrib[$nom_atrib])){
			return $arr_atrib[$nom_atrib];
		}else{
			return "";
		}
	}
}
