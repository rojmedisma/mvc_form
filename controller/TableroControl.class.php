<?php
/**
 * Descripción de TableroControl
 * Controlador defecto
 * @author Ismael Rojas
 */
class TableroControl extends ControladorBase{
	public function __construct() {
		parent::__constructTablero();
	}
	public function inicio() {
		$this->setPaginaDistintivos();
		$this->setConMenuLateralFijo(true);
		$this->setArrHTMLTagLiNavItemTablero();
		$this->defineVista("Tablero.php");
	}
}
