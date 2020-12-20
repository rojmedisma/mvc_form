<?php
/**
 * Extensión para todas las clases dentro de la carpeta <strong>model</strong>
 * @author Ismael
 *
 */
class ModeloBase extends Ayuda{
	private $arr_tbl;
	private $arr_reg;
	protected $bd;
	protected $tbl_nom;
	protected $cmp_id_nom;
	protected $cmp_id_val;
	protected function __construct(){
		$this->bd = new BaseDatos();
	}
	/**
	 * Genera el arreglo que contiene el detalle de los registros de la tabla definida en tbl_nom
	 * @param string $and	Complemento del query despues del where (AND, ORDER BY...)
	 */
	public function setArrTbl($and="") {
		$and_ft = " AND `borrar` IS NULL ".$and;
		
		$arr_tbl = $this->bd->getArrDeTabla($this->tbl_nom, $and_ft, $this->cmp_id_nom);
		$this->arr_tbl = $arr_tbl;
	}
	/**
	 * Genera un arreglo con el contenido del registro indicado a partir del valor llave indicado en el argumento
	 * @param string $cmp_id_val	Valor del campo llave del registro
	 */
	public function setArrReg($cmp_id_val, $and=""){
		$this->arr_reg = $this->bd->getArrRegDeTabla($this->tbl_nom, $this->cmp_id_nom, $cmp_id_val, $and);
	}
	/**
	 * Devuelve el arreglo generado en la función setArrTbl
	 * @return array
	 */
	public function getArrTbl(){
		return $this->arr_tbl;
	}
	/**
	 * Devuelve el arreglo generado en la función setArrReg
	 * @return array
	 */
	public function getArrReg(){
		return $this->arr_reg;
	}
	public function setGuardarReg($arr_cmps, $cmp_id_val) {
		if($cmp_id_val){
			//Modificar registro
			$arr_act = array();
			foreach($arr_cmps as $cmp_nom => $cmp_val){
				if($cmp_nom!=$cmp_id_nom){
					$arr_act[] = "`".$cmp_nom."` = ".$cmp_val;
				}
			}
			$qry_act = "UPDATE `".$this->bd->getBD()."`.`".$this->tbl_nom."` SET ".implode(",", array_values($arr_act))." WHERE `".$this->cmp_id_nom."` ='".$cmp_id_val."' LIMIT 1;";
			$this->bd->ejecutaQry($qry_act);
		}else{
			//Nuevo registro
			$qry_act = "INSERT INTO `".$this->bd->getBD()."`.`".$this->tbl_nom."` (".implode(",",array_keys($arr_cmps)).") VALUES (".implode(",",array_values($arr_cmps)).");";
			$cmp_id_val = $this->bd->ejecutaQryInsert($qry_act);
		}
		$this->cmp_id_val = $cmp_id_val;
	}
	public function getCmpIdVal() {
		return $this->cmp_id_val;
	}
	/**
	 * Devuelve el valor del campo especificado en el argumento del contenido en el arreglo arr_reg
	 * @param string $cmp_nom
	 * @return variant
	 */
	public function getValCmpReg($cmp_nom) {
		$arr_reg = $this->arr_reg;
		if(!empty($arr_reg) && isset($arr_reg[$cmp_nom])){
			return $arr_reg[$cmp_nom];
		}else{
			return null;
		}
	}
	/**
	 * Devuelve un arreglo de los campos de la tabla definida en tbl_nom
	 * @return array
	 */
	public function getArrCmpsTbl() {
		return $this->bd->getArrCmpsTbl($this->tbl_nom);
	}
}