<?php
/**
 * Clase modelo para obtener información del usuario sacada del catálogo de usuarios
 * @author Ismael Rojas
 *
 */
class Usuario {
	private $cat_usuario_id;
	private $arr_usuario = array();
	private $arr_tbl_cat_usr = array();
	/**
	 * Devuelve el id del catálogo de usuarios
	 * @return integer
	 */
	public function getCatUsuarioId(){
		return $this->cat_usuario_id;
	}
	/**
	 * Modifica el arreglo que contiene la información sacada del catálogo de usuarios del usuario indicado en argumento mediante su Id
	 * @param string $cat_usuario_id
	 */
	public function setArrUsuario($cat_usuario_id=""){
		if($cat_usuario_id!=""){
			$this->cat_usuario_id = $cat_usuario_id;
		}else{
			$this->cat_usuario_id = (isset($_SESSION['cat_usuario_id']))? $_SESSION['cat_usuario_id'] : "";
		}
		$arr_usuario = array();
		if($this->getCatUsuarioId()!=""){
			$this->setArrTblCatUsuario(" AND `cat_usuario_id` LIKE '".$this->getCatUsuarioId()."'");
			$arr_tbl = $this->getArrTblCatUsuario();
			$arr_usuario = $arr_tbl[0];
			$arr_usuario['nombre_completo'] = trim($arr_usuario['ap_paterno'].' '.trim($arr_usuario['ap_materno'].' '.trim($arr_usuario['nombre'])));
		}
		$this->arr_usuario = $arr_usuario;
	}
	/**
	 * Devuelve el arreglo que contiene la información sacada del catálogo de usuarios del usuario indicado en argumento mediante su Id
	 * @return array
	 */
	public function getArrUsuario(){
		return $this->arr_usuario;
	}
	/**
	 * Modifica el arreglo que contiene el detalle de registros del catálogo de usuarios
	 * @param string $and
	 */
	public function setArrTblCatUsuario($and=""){
		$bd = new BaseDatos();
		$and_cat = " AND `borrar` IS NULL ".$and;
		$arr_tbl = $bd->getArrDeTabla('cat_usuario', $and_cat);
		$this->arr_tbl_cat_usr = $arr_tbl;
	}
	/**
	 * Devuelve el arreglo que contiene el detalle de registros del catálogo de usuarios
	 * @return array
	 */
	public function getArrTblCatUsuario(){
		return $this->arr_tbl_cat_usr;
	}
	/**
	 * Devuelve el valor del nombre del campo indicado en el argumento, obteniendo la información del catálogo de usuarios del usuario actual
	 * @param string $cmp_nom
	 * @return mixed
	 */
	public function get_val_campo($cmp_nom) {
	    $arr_usuario = $this->getArrUsuario();
	    if(isset($arr_usuario[$cmp_nom])){
	        return $arr_usuario[$cmp_nom];
	    }else{
	        return "";
	    }
	}
}
?>