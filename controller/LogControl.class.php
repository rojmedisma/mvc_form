<?php
/**
 * Controlador para el registro de Log
 * @author Ismael
 *
 */
class LogControl extends ControladorBase{
	private $arr_tbl = array();
	/**
	 * Acción para abrir la vista o consulta de registros del registro de log
	 */
	public function inicio(){
		$this->setPaginaDistintivos();
		$this->setArrTblContenido();
		$this->setMostrarVista('Log.php');
	}
	/**
	 * Modifica el arreglo que contiene todo el detalle de registros de log, obteniendo la información a partir de la vista <strong>v_log</strong>
	 */
	private function setArrTblContenido(){
		$bd = new BaseDatos();
		$this->arr_tbl = $bd->getArrDeTabla('v_log');
	}
	/**
	 * Devuelve el arreglo que contiene todo el detalle de registros de log, obteniendo la información a partir de la vista <strong>v_log</strong>
	 * @return array
	 */
	public function getArrTblContenido(){
		return $this->arr_tbl;
	}
}