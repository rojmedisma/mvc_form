<?php
class CatCuestModulo extends ModeloBase{
	private $arr_lista_tablas;
	private $cat_cuestionario_id;
	public function __construct($cat_cuestionario_id){
		parent::__construct();
		$this->cat_cuestionario_id = $cat_cuestionario_id;
	}
	/**
	 * Genera el arreglo que contiene el detalle de los registros de la tabla cat_cuest_modulo
	 * @param string $and
	 */
	public function setArrTblCat($and="") {
		$and_c = " AND `cat_cuestionario_id` = '".$this->getCatCuestionarioId()."' ".$and;
		$this->setArrTbl("cat_cuest_modulo", $and_c);
	}
	/**
	 * Genera el arreglo con el contenido del registro de la tabla cat_cuest_modulo a partir del id cat_cuest_modulo_id
	 * @param integer $cat_cuest_modulo_id
	 */
	public function setArrRegCat($cat_cuest_modulo_id){
		$this->setArrReg("cat_cuest_modulo", "cat_cuest_modulo_id", $cat_cuest_modulo_id);
	}
	/**
	 * Genera arreglo con la lista de tablas del campo lista_tablas
	 * @param integer $cat_cuest_modulo_id
	 */
	public function setArrCmpListaTablas($cat_cuest_modulo_id=""){
		if($cat_cuest_modulo_id!=""){
			//Si viene cat_cuest_modulo_id el arreglo es únicamente de ese registro
			$this->setArrRegCat($cat_cuest_modulo_id);
			$arr_reg_cat = $this->getArrReg();
			$lista_tablas =trim(valorEnArreglo($arr_reg_cat, 'lista_tablas'));
			$this->arr_lista_tablas = ($lista_tablas!="")? explode(",", $lista_tablas) : array();
		}else{
			//Si no viene cat_cuest_modulo_id el arreglo es de todos los módulos del cuestionario
			$this->setArrTblCat();
			$arr_tbl_cat = $this->getArrTbl();
			$arr_lista_tablas_consol = array();
			foreach ($arr_tbl_cat as $arr_det){
				$cat_cuest_modulo_id_paso = $arr_det['cat_cuest_modulo_id'];
				if($cat_cuest_modulo_id_paso!=""){
					//Se llama a la misma función pero ahora con argumento $cat_cuest_modulo_id
					$this->setArrCmpListaTablas($cat_cuest_modulo_id_paso);
					$arr_lista_tablas = $this->getArrCmpListaTablas();
					$arr_lista_tablas_consol = array_merge($arr_lista_tablas_consol, $arr_lista_tablas);
				}
			}
			$this->arr_lista_tablas = $arr_lista_tablas_consol;
		}
	}
	/**
	 * Devuelve como arreglo el valor del campo lista_tablas
	 * @return array
	 */
	public function getArrCmpListaTablas(){
		return $this->arr_lista_tablas;
	}
	/**
	 * Devuelve el valor de la variable <strong>cat_cuestionario_id</strong>
	 * @return string|number
	 */
	private function getCatCuestionarioId(){
		return $this->cat_cuestionario_id;
	}
}