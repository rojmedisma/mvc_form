<?php
/**
 * Descripción de Cuestionario
 *
 * @author Ismael Rojas
 */
class Cuestionario extends ModeloBase{
	private $cat_cuestionario_id;
	private $arr_tbl_cuestionario = array();
	private $arr_reg_cuestionario = array();
	private $arr_tablas_cuest = array();
	private $str_query = "";
	public function __construct($cat_cuestionario_id){
		parent::__construct();
		$this->cat_cuestionario_id = $cat_cuestionario_id;
		//Sentencia AND básica para consultar los cuestionarios activos o válidos para consulta y cálculo
		$this->and_tbl_c00 = " AND `c00`.`inhabilitar` IS NULL AND `c00`.`borrar` IS NULL AND `c00`.`cat_cuestionario_id` = '".$this->cat_cuestionario_id."' ";
	}
	/**
	 * Genera el arreglo que contiene el detalle formado por todas las tablas pertenecientes al cuestionario actual
	 * @param type $and	Complemento del query despues del where (AND, ORDER BY...)
	 * @param type $con_cat_detalle	Bandera para indicar si el query va a contener detalle obtenido de las tablas de catálogo
	 * @param type $con_and_tbl_c00	Bandera para incluir la sentencia query AND básica para consultar los cuestionarios activos o válidos para consulta y cálculo
	 */
	public function setArrTblCuestionario($and="", $con_cat_detalle=false, $con_and_tbl_c00=true) {
		$this->setArrTablasCuest();
		
		$str_query = "SELECT";
		$str_query .= " `c00`.*,";
		if($con_cat_detalle){
			$str_query .= " `cat_cuestionario`.`descripcion` AS `cat_cuestionario_desc`,";
			$str_query .= " `cat_estado`.`descripcion` AS `cat_estado_desc`,";
			$str_query .= " `cat_usuario`.`usuario` AS `autor_usuario`,";
			$str_query .= " CONCAT_WS(' ', `cat_usuario`.`ap_paterno`, `cat_usuario`.`ap_materno`, `cat_usuario`.`nombre`) AS `autor_nombre`,";
		}
		$str_query .= " ".$this->getQrySelCmpsTablas();
		$str_query .= " FROM `".$this->bd->getBD()."`.`c00`";
		if($con_cat_detalle){
			$str_query .= " LEFT JOIN `".$this->bd->getBD()."`.`cat_cuestionario` ON(`c00`.`cat_cuestionario_id` = `cat_cuestionario`.`cat_cuestionario_id`)";
			$str_query .= " LEFT JOIN `".$this->bd->getBD()."`.`cat_estado` ON(`c00`.`cat_estado_id` = `cat_estado`.`cat_estado_id`)";
			$str_query .= " LEFT JOIN `".$this->bd->getBD()."`.`cat_usuario` ON(`c00`.`cat_usuario_id` = `cat_usuario`.`cat_usuario_id`)";
		}
		$str_query .= " ".$this->getQryJoinTablas();
		$str_query .= " WHERE 1";
		if($con_and_tbl_c00){
			$str_query .= " ".$this->and_tbl_c00;
		}
		$str_query .= " ".$and;
		$this->str_query = $str_query;
		
		$this->arr_tbl_cuestionario = $this->bd->getArrDeQuery($str_query, 'cuestionario_id');
	}
	/**
	 * Genera el arreglo que contiene el detalle del registro con id indicado en el argumento $cuestionario_id
	 * @param variant $cuestionario_id
	 * @param boolean $con_cat_detalle
	 * @param boolean $con_and_tbl_c00
	 */
	public function setArrRegCuestionario($cuestionario_id, $con_cat_detalle=false, $con_and_tbl_c00=true) {
		if($cuestionario_id){
			$and = " AND `c00`.`cuestionario_id` = '".$cuestionario_id."' ";
			$this->setArrTblCuestionario($and, $con_cat_detalle, $con_and_tbl_c00);
			$arr_tbl_cuest = $this->getArrTblCuestionario();
			$this->arr_reg_cuestionario = $arr_tbl_cuest[$cuestionario_id];
		}else{
			$this->arr_reg_cuestionario = array();
		}
		
	}
	
	/**
	 * Genera arreglo arr_tablas_cuest que contiene las tablas declaradas en el campo lista_tablas
	 */
	private function setArrTablasCuest(){
		$cat_cuest_modulo = new CatCuestModulo($this->cat_cuestionario_id);
		$cat_cuest_modulo->setArrCmpListaTablas();
		$this->arr_tablas_cuest = $cat_cuest_modulo->getArrCmpListaTablas();
	}

	private function getQrySelCmpsTablas() {
		$arr_qry_param = array(
			"query"=>"`%nom_tbl%`.*",
			"separador"=>", "
		);
		return $this->getQryStrDeTablas($arr_qry_param);
	}
	private function getQryJoinTablas(){
		$arr_qry_param = array(
			"query"=>" LEFT JOIN `".$this->bd->getBD()."`.`%nom_tbl%` ON(`c00`.`cuestionario_id` = `%nom_tbl%`.`cuestionario_id`)",
			"separador"=>" "
		);
		return $this->getQryStrDeTablas($arr_qry_param);
	}
	private function getQryStrDeTablas($arr_qry_param){
		$qry_sel = "";
		$arr_tablas_cuest = $this->arr_tablas_cuest;
		if(!empty($arr_tablas_cuest) && isset($arr_qry_param['query']) && isset($arr_qry_param['separador'])){
			$arr_qry_sel = array();
			foreach($arr_tablas_cuest as $nom_tbl_c){
				$arr_qry_sel[] = str_replace("%nom_tbl%", $nom_tbl_c, $arr_qry_param['query']);
			}
			$qry_sel = implode($arr_qry_param['separador'], array_values($arr_qry_sel));
		}
		return $qry_sel;
	}
	public function crearCuest($cat_usuario_id) {
		$cat_usuario = new Usuario();
		$cat_usuario->setArrUsuario($cat_usuario_id);
		$cat_estado_id = $cat_usuario->get_val_campo('cat_estado_id');
		
		$arr_cmps_c00 = array(
			'`cuestionario_id`'=>txt_sql(""),
			'`cat_cuestionario_id`'=>txt_sql($this->cat_cuestionario_id, false),
			'`cat_usuario_id`'=>txt_sql($cat_usuario_id, false),
			'`cat_estado_id`'=>txt_sql($cat_estado_id, false),
			'`cat_cader_id`'=>txt_sql(""),
			'`estatus_cuest`'=>txt_sql(""),
			'`creacion_fecha`'=>txt_sql(""),
			'`creacion_hora`'=>txt_sql(""),
			'`modifica_fecha`'=>txt_sql(""),
			'`modifica_hora`'=>txt_sql(""),
			'`inhabilitar`'=>txt_sql("1"),
			'`borrar`'=>txt_sql(""),
		);
		
		
		$qry_act = "INSERT INTO `".$this->bd->getBD()."`.`c00` (".implode(",",array_keys($arr_cmps_c00)).") VALUES (".implode(",",array_values($arr_cmps_c00)).");";
		$cuestionario_id = $this->bd->ejecutaQryInsert($qry_act);
		
		$cat_cuest_modulo = new CatCuestModulo($this->cat_cuestionario_id);
		$cat_cuest_modulo->setArrCmpListaTablas();
		
		$arr_tablas_cuest = $cat_cuest_modulo->getArrCmpListaTablas();
		//Se inserta el registro en el resto de las tablas
		foreach($arr_tablas_cuest as $tbl_nom){
			$arr_cmps_ins = array('cuestionario_id'=>txt_sql($cuestionario_id));
			$qry_act = "INSERT INTO `".$this->bd->getBD()."`.`".$tbl_nom."` (".implode(",",array_keys($arr_cmps_ins)).") VALUES (".implode(",",array_values($arr_cmps_ins)).");";
			$this->bd->ejecutaQry($qry_act);
		}
		
		$log = new Log();
		$log->setRegLog('cuestionario_id', $cuestionario_id, 'guardar', 'Aviso', "Se creó cuestionario en modo inhabilitado");
		return $cuestionario_id;
	}
	public function actualizarCuest($arr_cmps, $cuestionario_id) {
		$log = new Log();
		if(!$cuestionario_id){
			$this->setError("Argumento cuestionario_id vacío", "En función actualizarCuest se mandó el argumento cuestionario_id vacío.");
			return false;
		}
		foreach($arr_cmps as $tbl_nom => $arr_cmp_det){
			$arr_act = array();
			foreach ($arr_cmp_det as $cmp_nom => $cmp_val){
				if($cmp_nom!='cuestionario_id'){
					$arr_act[] = "`".$tbl_nom."`.`".$cmp_nom."` = ".$cmp_val;
				}
			}
			$qry_act = "UPDATE `".$this->bd->getBD()."`.`".$tbl_nom."` SET ".implode(",", array_values($arr_act))." WHERE `cuestionario_id` ='".$cuestionario_id."' LIMIT 1;";
			$this->bd->ejecutaQry($qry_act);
		}
		$this->actualizaTblC00($cuestionario_id);
		return true;
	}
	/**
	 * Se actualizan los valores necesarios en la tabla c00
	 * @param integer $cuestionario_id
	 */
	private function actualizaTblC00($cuestionario_id){
		$arr_act = array(
			"`creacion_fecha` = IFNULL(`creacion_fecha`, CURDATE())",
			"`creacion_hora` = IFNULL(`creacion_hora`, CURTIME())",
			"`modifica_fecha` = CURDATE()",
			"`modifica_hora`= CURTIME()",
			"`inhabilitar`= NULL",
		);
		$qry_act = "UPDATE `".$this->bd->getBD()."`.`c00` SET ".implode(",", array_values($arr_act))." WHERE `cuestionario_id` = '".$cuestionario_id."';";
		$this->bd->ejecutaQry($qry_act);
	}
	/**
	 * Regresa el arreglo que contiene el detalle formado por todas las tablas pertenecientes al cuestionario actual
	 * @return string
	 */
	public function getArrTblCuestionario() {
		return $this->arr_tbl_cuestionario;
	}
	/**
	 * Regresa el arreglo que contiene el detalle del registro de cuestionario creado en setArrRegCuestionario
	 * @return type
	 */
	public function getArrRegCuestionario() {
		return $this->arr_reg_cuestionario;
	}
	/**
	 * Regresa el query generado y asignado en ciertas funciones
	 * @return string
	 */
	public function getStrQuery() {
		return $this->str_query;
	}
}
