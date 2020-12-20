<?php
/**
 * Clase modelo para obtener información de la tabla cat_cuestionario, la cual contiene todos los parámetros de identificación de cada uno de los cuestionarios existentes
 * @author Ismael Rojas
 *
 */
class CatCuestionario extends ModeloBase{
	public function __construct() {
		parent::__construct();
		$this->tbl_nom = "cat_cuestionario";
		$this->cmp_id_nom = $this->tbl_nom."_id";
	}
}