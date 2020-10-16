<?php
/**
 * Clase modelo para los métodos que hacen consulta a las tablas de tipo catálogo
 * @author Ismael
 *
 */
class Catalogo{
	private $bd;
	private $arr_tbl_cat = array();
	private $lista_opciones;
	public function __construct(){
		$this->bd = new BaseDatos();
	}
	/**
	 * Modifica el arreglo de catálogo con el contenido de la tabla <strong>cat_estado</strong>
	 * @param string $and
	 */
	public function setArrTblCatEstado($and=""){
		$this->arr_tbl_cat = $this->bd->getArrDeTabla("cat_estado",$and,"cat_estado_id");
	}
	/**
	 * Devuelve el arreglo de la tabla de catálogo definida previamente, todos los métodos usan la misma variable tipo arreglo
	 * @return array
	 */
	public function getArrTblCat(){
		return $this->arr_tbl_cat;
	}
	/**
	 * Marca como borrado el registro con el id y nombre de la tabla de catálogo indicados en los argumentos
	 * @param string $tbl_cat	Nombre de la tabla de catálogo
	 * @param string $id_reg	Id del registro a marcar como borrado
	 */
	public function borrar($tbl_cat, $id_reg){
		$qry = "UPDATE `".$this->bd->getBD()."`.`".$tbl_cat."` SET `borrar` = '1' WHERE `".$tbl_cat."_id` = '".$id_reg."';";
		$this->bd->ejecutaQry($qry);
	}
	/**
	 * Genera texto html con las opciones combo del catálogo de municipios
	 * @param string $cat_estado_id
	 */
	public function setListaOpcCatMunicipio($cat_estado_id){
		$and = " AND `cat_estado_id` LIKE '".$cat_estado_id."' ORDER BY `descripcion` ASC ";
		$arr_cat_municipio = $this->bd->getArrDeTabla("cat_municipio",$and);
		$lista_opciones = '<option value="" data-desc_val="" data-esp_val="">[Seleccionar]</option>';
		foreach ($arr_cat_municipio as $arr_det_mpo){
			$cat_municipio_id = $arr_det_mpo['cat_municipio_id'];
			$desc = $arr_det_mpo['descripcion'];
			$lista_opciones .= '<option value="'.$cat_municipio_id.'" data-desc_val="'.$desc.'" data-esp_val="">'.$desc.'</option>';
		}
		$this->lista_opciones = $lista_opciones;
	}
	/**
	 * Genera texto html con las opciones combo del catálogo de localidades
	 * @param string $cat_municipio_id
	 */
	public function setListaOpcCatLocalidad($cat_municipio_id) {
		$cat_estado_id = substr($cat_municipio_id, 0,2);
		$municipio_cve = substr($cat_municipio_id, -3);
		$and = " AND `cat_estado_id` LIKE '".$cat_estado_id."' AND `municipio_cve` LIKE '".$municipio_cve."' ";
		
		$arr_cat_localidad = $this->bd->getArrDeTabla("cat_localidad",$and);
		$lista_opciones = '<option value="" data-desc_val="" data-esp_val="">[SELECCIONAR]</option>';
		foreach ($arr_cat_localidad as $arr_det_loc){
			$cat_localidad_id = $arr_det_loc['cat_localidad_id'];
			$desc = $arr_det_loc['descripcion'];
			$lista_opciones .= '<option value="'.$cat_localidad_id.'" data-desc_val="'.$desc.'" data-esp_val="">'.$desc.'</option>';
		}
		$this->lista_opciones = $lista_opciones;
	}
	/**
	 * Devuelve texto html con las opciones combo creadas
	 * @return string
	 */
	public function getListaOpciones() {
		return $this->lista_opciones;
	}
}