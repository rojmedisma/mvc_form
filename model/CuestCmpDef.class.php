<?php
/**
 * Clase modelo para obtener información de la tabla cues_cmp_def
 * La tabla contiene los datos de definicion de los campos pertenecientes a las 7 secciones de preguntas (No se incluye la sección de datos generales)
 * Nota: Esta clase y tabla se crearon exclusivamente para el Cuestionario de autoevaluación diagnóstica
 * @author Ismael Rojas
 *
 */
class CuestCmpDef extends ModeloBase{
	private $cat_cuestionario_id;
	public function __construct($cat_cuestionario_id){
		parent::__construct();
		$this->tbl_nom = "cues_cmp_def";
		$this->cmp_id_nom = $this->tbl_nom."_id";
		$this->cat_cuestionario_id = $cat_cuestionario_id;
	}
	/**
	 * Genera el arreglo que contiene el detalle de los registros de la tabla cues_cmp_def
	 * @param string $and
	 */
	public function setArrTblDef($and="") {
		$and_ccid = " AND `cat_cuestionario_id` = '".$this->cat_cuestionario_id."' ".$and;
		$this->setArrTbl($and_ccid);
	}
	/**
	 * Genera el arreglo de los registros de la tabla cues_cmp_def del cat_cuest_modulo_id indicado en el argumento
	 * @param integer $cat_cuest_modulo_id
	 */
	public function setArrRegsXCatCuestModuloId($cat_cuest_modulo_id){
		$and = " AND `".$this->cmp_id_nom."` = '".$cat_cuest_modulo_id."'";
		$this->setArrTblDef($and);
	}
	
}