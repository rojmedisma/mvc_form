<?php
/**
 * Descripción de TblCamposControl
 * Controlador para convertir el arreglo json arr_cmp_atrib generado por la clase modelo FormularioALTE3 en una tabla
 * Ayuda a generar un archivo Excel con la lista de campos necesarios y sus parámetros para generar los querys para crear o modificar la tabla fuente de datos
 * @author Ismael Rojas
 */
class TblCamposControl extends ControladorBase{
	private array $arr_cmp_tbl = array();
	private $cmp_json;
	public function __construct() {
		$cmp_json = filter_input(INPUT_POST, "cmp_json", FILTER_DEFAULT);
		$this->cmp_json = $cmp_json;
	}
	public function inicio() {
		$this->defineVista("TblCampos.php");
	}
	public function crear() {
		if($this->cmp_json!=""){
			$this->setArrCmpTbl();
		}
		$this->defineVista("TblCampos.php");
	}
	
	public function getArrCmpTbl() {
		return $this->arr_cmp_tbl;
	}
	public function getCmpJson() {
		return $this->cmp_json;
	}
	private function setArrCmpTbl() {
		$arr_cmp_json = json_decode($this->cmp_json);

		$arr_cmp_tbl = array();
		foreach($arr_cmp_json as $cmp_id_nom=>$arr_det){
			$arr_cmp_tbl_det = (object)[];
			$arr_cmp_tbl_det->cmp_id_nom = $cmp_id_nom;
			$arr_cmp_tbl_det->cmp_tipo = $this->valorEnObjeto($arr_det, 'cmp_tipo');
			$arr_cmp_tbl_det->type = $this->valorEnObjeto($arr_det, 'type');
			$arr_cmp_tbl_det->xls_tipo_id = $this->getXlsTipoId($arr_det, $cmp_id_nom);

			$arr_cmp_tbl[] = $arr_cmp_tbl_det;
		}		
		$this->arr_cmp_tbl = $arr_cmp_tbl;
	}
	private function valorEnObjeto($arr_obj, $nom_cmp) {
		return (isset($arr_obj->$nom_cmp))? $arr_obj->$nom_cmp : "";
	}
	private function getXlsTipoId($arr_obj, $cmp_id_nom){
		$cmp_tipo = $this->valorEnObjeto($arr_obj, 'cmp_tipo');
		$cmp_id_nom = $cmp_id_nom;
		$xls_tipo_id = '';
		switch($cmp_tipo){
			case 'oculto':
				$xls_tipo_id = $this->paraOculto($arr_obj, $cmp_id_nom);
				break;
			case 'text':
				$xls_tipo_id = 'txt_255';
				break;
			case 'email':
				$xls_tipo_id = 'txt_55';
				break;
			case 'password':
				$xls_tipo_id = 'txt_55';
				break;
			case 'textarea':
				$xls_tipo_id = 'text';
				break;
			case 'select':
				$xls_tipo_id = 'int';
				break;
			case 'checkbox':
				$xls_tipo_id = 'checkbox';
				break;
			case 'radio':
				$xls_tipo_id = 'radio';
				break;
			case 'num':
				$xls_tipo_id = $this->paraNum($arr_obj);
				break;
			default:
				$xls_tipo_id = '';
				
		}
		
		return $xls_tipo_id;
	}
	private function paraOculto($arr_obj, $cmp_id_nom) {
		$t_desc = strtolower(substr($cmp_id_nom, -5));
		$tipo = '';
		if($t_desc==='_desc'){
			$tipo = $t_desc;
		}elseif(substr($cmp_id_nom, -3) == '_id'){
			$tipo = 'c_id';
		}else{
			$tipo = 'txt_255';
		}
		return $tipo;
	}
	private function paraNum($arr_obj) {
		$decimales = intval($this->valorEnObjeto($arr_obj, 'decimales'));
		$tipo = '';
		if($decimales==0){
			$tipo = 'int';
		}elseif($decimales>0){
			$tipo = 'doble';
		}
		return $tipo;
	}
}
