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
		$this->cat_cuestionario_id = $cat_cuestionario_id;
	}
	/**
	 * Genera el arreglo que contiene el detalle de los registros de la tabla cues_cmp_def
	 * @param string $and
	 */
	public function setArrTblDef($and="") {
		$and_ccid = " AND `cat_cuestionario_id` = '".$this->cat_cuestionario_id."' ".$and;
		$this->setArrTbl("cues_cmp_def", $and_ccid, "cues_cmp_def_id");
	}
	/**
	 * Genera el arreglo de los registros de la tabla cues_cmp_def del cat_cuest_modulo_id indicado en el argumento
	 * @param integer $cat_cuest_modulo_id
	 */
	public function setArrRegsXCatCuestModuloId($cat_cuest_modulo_id){
		$and = " AND `cat_cuest_modulo_id` = '".$cat_cuest_modulo_id."'";
		$this->setArrTblDef($and);
	}
	
}