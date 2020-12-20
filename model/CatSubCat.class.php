<?php
/**
 * Descripción de CatSubCat
 *
 * @author Ismael Rojas
 */
class CatSubCat extends ModeloBase{
	public function __construct(){
		parent::__construct();
		$this->tbl_nom = "cat_sub_cat";
		$this->cmp_id_nom = $this->tbl_nom."_id";
	}
}

