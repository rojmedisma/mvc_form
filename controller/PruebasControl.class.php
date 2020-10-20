<?php
/**
 * Descripción de PruebasControl
 *
 * @author Ismael Rojas
 */
class PruebasControl extends ControladorBase{
	public function inicio() {
		$this->redireccionaError("Prueba", "Para ver si jalaba");
		$this->setMostrarVista("Pruebas.php");
	}
}
