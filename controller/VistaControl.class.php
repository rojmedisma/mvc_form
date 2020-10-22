<?php

/**
 * Descripción de VistaControl
 *
 * @author Ismael Rojas
 */
class VistaControl extends ControladorBase{
	public function cuestionario() {
		$this->setPaginaDistintivos();
		$this->setConMenuLateralFijo(true);
		$this->setArrHTMLTagLiNavItemTablero();
		$this->defineVista("VistaCuestionario.php");
	}
}
