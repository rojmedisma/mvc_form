<?php
/**
 * Clase modelo para obtener información de la tabla <strong>cat_cuestionario</strong>, la cual contiene todos los parámetros de identificación de cada uno de los cuestionarios existentes
 * @author Ismael Rojas
 *
 */
class CatCuestionario{
	private $cat_cuestionario_id;
	private $arr_cat_cuestionario = array();
	public function __construct($cat_cuestionario_id) {
		$this->cat_cuestionario_id = intval($cat_cuestionario_id);
	}
	/**
	 * Devuelve el Id del cuestionario
	 * @return number
	 */
	public function getCatCuestionarioId() {
		return $this->cat_cuestionario_id;
	}
	/**
	 * Modifica el arreglo que contiene el detalle del registro de la tabla <strong>cat_cuestionario</strong>
	 * @param string $and
	 */
	public function setArrCatCuestionario($and="") {
		$bd = new BaseDatos();
		
		$and_cc = " AND `cat_cuestionario_id` = '".$this->getCatCuestionarioId()."' ".$and;
		$arr_tbl = $bd->getArrDeTabla("cat_cuestionario", $and_cc, 'cat_cuestionario_id');
		$this->arr_cat_cuestionario = $arr_tbl[$this->getCatCuestionarioId()];
	}
	/**
	 * Devuelve el arreglo que contiene el detalle del registro de la tabla <strong>cat_cuestionario</strong>
	 * @return array
	 */
	public function getArrCatCuestionario(){
		return $this->arr_cat_cuestionario;
	}
	/**
	 * Devuelve el valor del campo especificado en el argumento y que pertenece al registro de la tabla cat_cuestionario
	 * @param string $cmp_nom
	 * @return string
	 */
	public function get_val_campo($cmp_nom){
		$arr_cat_cuestionario = $this->getArrCatCuestionario();
		if(isset($arr_cat_cuestionario[$cmp_nom])){
			return $arr_cat_cuestionario[$cmp_nom];
		}else{
			return "";
		}
	}
	/**
	 * Devuelve el valor del campo <strong>lista_tablas</strong> en forma arreglo. El campo contiene la lista de las tablas usadas para cada cuestionario
	 * @return array
	 */
	public function getArrTablas(){
		$lista_tablas = $this->get_val_campo('lista_tablas');
		$arr_li_tablas = ($lista_tablas!="")? explode(",", $lista_tablas) : array();
		return array_merge(array('c00'), $arr_li_tablas);
	}
}