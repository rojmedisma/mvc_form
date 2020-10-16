<?php
/**
 * Clase modelo para el manejo de los permiso por grupo
 * @author Ismael Rojas
 *
 */
class Grupo{
	private $arr_grupo = array();
	private $arr_tbl_cg = array();
	private $arr_permiso_grupo = array();
	/**
	 * Modifica el arreglo que contiene el detalle de los registros de la tabla <strong>cat_grupo</strong>
	 * @param string $cat_grupo_id
	 * @param string $and
	 */
	public function setArrTblCatGrupo($cat_grupo_id="", $and=""){
		$bd = new BaseDatos();
		if($cat_grupo_id){
			$and_cat = " AND `borrar` IS NULL AND `cat_grupo_id` = '".$cat_grupo_id."'";
			$arr_tbl = $bd->getArrDeTabla('cat_grupo', $and_cat, 'cat_grupo_id');
			$arr_cat = $arr_tbl[$cat_grupo_id];
		}else{
			$and_cat = " AND `borrar` IS NULL ".$and;
			$arr_cat = $bd->getArrDeTabla('cat_grupo', $and_cat, 'cat_grupo_id');
		}
		$this->arr_tbl_cg = $arr_cat;
	}
	/**
	 * Devuelve el arreglo que contiene el detalle de los registros de la tabla <strong>cat_grupo</strong>
	 * @param string $cat_grupo_id
	 * @param string $and
	 * @return array
	 */
	public function getArrTblCatGrupo($cat_grupo_id="", $and=""){
		return $this->arr_tbl_cg;
	}
	/**
	 * Modifica el arreglo que contiene los registro de la vista <strong>v_grupo</strong>
	 * @param string $and
	 * @param string $activo
	 */
	public function setArrViGrupo($and="", $activo=true){
		$bd = new BaseDatos();
		$and_activo = ($activo)? " AND `activo` = 1" : " AND `activo` IS NULL OR `activo` = 0 ";
		$and_tbl = $and.$and_activo;
		
		$qry = "SELECT 
		`grupo`.*,
		`cat_grupo`.`tit_corto` AS `cg_tit_corto`,
		`cat_grupo`.`descripcion` AS `cg_descripcion`,
		`cat_permiso`.`tipo` AS `cp_tipo`,
		`cat_permiso`.`tit_corto` AS `cp_tit_corto`,
		`cat_permiso`.`descripcion` AS `cp_descripcion`,
		`cat_permiso`.`orden` AS `cp_orden` 
		FROM `".$bd->getBD()."`.`grupo`
		LEFT JOIN `".$bd->getBD()."`.`cat_grupo` ON(`cat_grupo`.`cat_grupo_id` = `grupo`.`cat_grupo_id`)
		LEFT JOIN `".$bd->getBD()."`.`cat_permiso` ON(`cat_permiso`.`cat_permiso_cve` = `grupo`.`cat_permiso_cve`)
		WHERE 1 ".$and_tbl."
		ORDER BY `cat_grupo`.`tit_corto`,`cat_permiso`.`tit_corto`;";
		$arr_gpo= $bd->getArrDeQuery($qry);
		$this->arr_grupo = $arr_gpo;
	}
	/**
	 * Devuelve el arreglo que contiene los registro de la vista <strong>v_grupo</strong>
	 * @return array
	 */
	public function getArrViGrupo(){
		return $this->arr_grupo;
	}
	/**
	 * Modifica el arreglo que contiene el detalle de permisos de la tabla <strong>cat_permiso</strong> categorizado por los grupos de la tabla <strong>grupo</strong>
	 * @param integer $cat_grupo_id
	 */
	public function setArrPermisoGrupo($cat_grupo_id){
		$bd = new BaseDatos();
		$arr_cat_permiso =  $bd->getArrDeTabla("cat_permiso");
		$arr_grupo = $bd->getArrDeTabla("grupo", " AND `cat_grupo_id` = '".$cat_grupo_id."'", "cat_permiso_cve");
		
		$arr_permiso_gpo = array();
		foreach ($arr_cat_permiso as $arr_cp_det){
			$cat_permiso_cve = $arr_cp_det["cat_permiso_cve"];
			$activo = (isset($arr_grupo[$cat_permiso_cve]))? intval($arr_grupo[$cat_permiso_cve]["activo"]) : 0;
			if($activo){
				$activo_txt = "Activado";
				$cls_color = "btn-success";
			}else{
				$activo_txt = "Desactivado";
				$cls_color = "btn-default";
			}
			$arr_permiso_gpo[] = array(
					"cat_permiso_cve"=>$cat_permiso_cve,
					"tit_corto"=>$arr_cp_det["tit_corto"],
					"descripcion"=>$arr_cp_det["descripcion"],
					"activo"=>$activo,
					"activo_txt"=>$activo_txt,
					"cls_color"=>$cls_color,
			);
		}
		$this->arr_permiso_grupo = $arr_permiso_gpo;
	}
	/**
	 * Devuelve el arreglo que contiene el detalle de permisos de la tabla <strong>cat_permiso</strong> categorizado por los grupos de la tabla <strong>grupo</strong>
	 * @return array
	 */
	public function getArrPermisoGrupo(){
		return $this->arr_permiso_grupo;
	}
}