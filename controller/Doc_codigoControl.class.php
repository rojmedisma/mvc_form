<?php
/**
 * Controlador de documentación de código
 * @author Ismael Rojas
 *
 */
class Doc_codigoControl extends ControladorBase{
	public function __construct(){
		$this->setPaginaDistintivos();
	}
	/**
	 * Acción que redirecciona a la pagina donde se encuentra la documentación del código
	 */
	public function inicio(){
		$this->setMostrarVista('DocCodigo.php');
	}
}