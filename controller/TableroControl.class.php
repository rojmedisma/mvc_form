<?php
/**
 * Descripción de TableroControl
 * Controlador defecto
 * @author Ismael Rojas
 */
class TableroControl extends TableroBase{
	private $arr_cat_cuestionario = array();
	public function __construct() {
		parent::__constructTablero();
	}
	public function inicio() {
		$this->defineVista("Tablero.php");
		
		$cat_cuestionario = new CatCuestionario();
		$cat_cuestionario->setArrTbl();
		$this->arr_cat_cuestionario = $cat_cuestionario->getArrTbl();
		parent::setArrHTMLTagLiNavItem();	//Se crean los items del tablero
	}
	public function getArrCatCuestionario() {
		return $this->arr_cat_cuestionario;
	}
}
