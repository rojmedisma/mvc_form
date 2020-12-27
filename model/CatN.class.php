<?php
/**
 * Descripción de CatN
 * Clase genérica para la consulta de tablas de catálogo
 * @author Ismael Rojas
 */
class CatN extends ModeloBase{
	public function __construct($tbl_nom, $cmp_id_nom=""){
		parent::__construct();
		$this->tbl_nom = $tbl_nom;
		$this->cmp_id_nom = ($cmp_id_nom=="")? $tbl_nom."_id" : $cmp_id_nom;
	}
}