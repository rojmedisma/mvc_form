<?php
/**
 * Descripción de PruebasControl
 *
 * @author Ismael Rojas
 */
class PruebasControl extends ControladorBase{
	public function inicio() {
		$cat_estado = new CatEstado();
		$cat_estado->setArrTbl();
		//echo json_encode($cat_estado->getArrTbl());
		$this->defineVista("Pruebas.php");
	}
}
