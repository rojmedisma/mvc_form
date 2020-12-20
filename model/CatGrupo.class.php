<?php
/**
 * Descripción de CatGrupo
 *
 * @author Ismael Rojas
 */
class CatGrupo extends ModeloBase{
	public function __construct(){
		parent::__construct();
		$this->tbl_nom = "cat_grupo";
		$this->cmp_id_nom = $this->tbl_nom."_id";
	}
}
