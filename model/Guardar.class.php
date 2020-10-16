<?php
/**
 * Clase modelo para ejecutar el guardado del registro actual
 * @author Ismael
 *
 */
class Guardar{
	private $bd;
	private $cmp_id_val;
	public function __construct() {
		$this->bd = new BaseDatos();
	}
	/**
	 * Ejecuta el guardado para cuando el registro es de tipo catálogo
	 * @param array $arr_cmps	Arreglo de campos a ser actualizados o insertados
	 * @param string $tbl_cat_nom
	 * @param string $cmp_id_val
	 */
	public function setGuardaCatalogo($arr_cmps, $tbl_cat_nom, $cmp_id_val){
		$cmp_id_nom = $tbl_cat_nom."_id";	//cat_usuario + _id
		if($cmp_id_val){
			//Modificar registro
			$arr_act = array();
			foreach($arr_cmps as $cmp_nom => $cmp_val){
				if($cmp_nom!=$cmp_id_nom){
					$arr_act[] = "`".$cmp_nom."` = ".$cmp_val;
				}
			}
			$qry_act = "UPDATE `".$this->bd->getBD()."`.`".$tbl_cat_nom."` SET ".implode(",", array_values($arr_act))." WHERE `".$cmp_id_nom."` ='".$cmp_id_val."' LIMIT 1;";
			$this->bd->ejecutaQry($qry_act);
		}else{
			//Nuevo registro
			$qry_act = "INSERT INTO `".$this->bd->getBD()."`.`".$tbl_cat_nom."` (".implode(",",array_keys($arr_cmps)).") VALUES (".implode(",",array_values($arr_cmps)).");";
			$cmp_id_val = $this->bd->ejecutaQryInsert($qry_act);
		}
		$this->cmp_id_val = $cmp_id_val;
	}
	/**
	 * Ejecuta el guardado para cuando el registro es de tipo cuestionario
	 * @param string $arr_cmps	Arreglo de campos a ser actualizados o insertados
	 * @param string $cat_cuestionario_id
	 * @param string $cuestionario_id
	 */
	public function setGuardaCuest($arr_cmps, $cat_cuestionario_id, $cuestionario_id){
		$log = new Log();
		$txt_log = "";
		if($cuestionario_id!=""){
			//Modificar registro
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
			//Se actualiza la información de la tabla C00
			$this->actualizaEnTblC00($cuestionario_id);
			$txt_log = "Se actualizó cuestionario";
		}else{
			$log->setRegLog('cuestionario_id', $cuestionario_id, 'setGuardaCuest', 'Error', "Id cuestionario vacío");
		}
		$this->cmp_id_val = $cuestionario_id;
		$log->setRegLog('cuestionario_id', $cuestionario_id, 'setGuardaCuest', 'Aviso', $txt_log);
	}
	public function setNuevoCuestionario($arr_lista_tablas, $cat_cuestionario_id, $cat_usuario_id){
		//Nuevo registro
		$log = new Log();
		$cuestionario_id = $this->crearCuestionarioId($cat_cuestionario_id);
		
		
		//Se inserta el registro de la tabla c00
		$arr_cmps_c00 = array(
				'cuestionario_id'=>txt_sql($cuestionario_id),
				'cat_cuestionario_id'=>txt_sql($cat_cuestionario_id),
				'cat_usuario_id'=>txt_sql($cat_usuario_id),
				'cat_estado_id'=>txt_sql(""),
				'estatus_cuest'=>txt_sql(""),
				'creacion_fecha'=>"IFNULL(`creacion_fecha`, CURDATE())",
				'creacion_hora'=>"IFNULL(`creacion_hora`, CURTIME())",
				'borrar'=>txt_sql(""),
		);
		$qry_act = "INSERT INTO `".$this->bd->getBD()."`.`c00` (".implode(",",array_keys($arr_cmps_c00)).") VALUES (".implode(",",array_values($arr_cmps_c00)).");";
		$this->bd->ejecutaQry($qry_act);
		
		//Se inserta el registro en el resto de las tablas
		foreach($arr_lista_tablas as $tbl_nom){
			$arr_cmps_ins = array('cuestionario_id'=>txt_sql($cuestionario_id));
			$qry_act = "INSERT INTO `".$this->bd->getBD()."`.`".$tbl_nom."` (".implode(",",array_keys($arr_cmps_ins)).") VALUES (".implode(",",array_values($arr_cmps_ins)).");";
			$this->bd->ejecutaQry($qry_act);
		}
		$txt_log = "Se creó cuestionario";
		
		$this->cmp_id_val = $cuestionario_id;
		$log->setRegLog('cuestionario_id', $cuestionario_id, 'guardar', 'Aviso', $txt_log);
	}
	
	/**
	 * Devuelve el Id del registro insertado, cuando el guardado realizó un insert
	 * @return mixed
	 */
	public function getCmpIdVal(){
		return $this->cmp_id_val;
	}
	/**
	 * Crea el id para los registros de cuestioanrio
	 * @param integer $cat_cuestionario_id
	 * @return string
	 */
	private function crearCuestionarioId($cat_cuestionario_id){
		$parte1 = str_pad($cat_cuestionario_id, 2, '0', STR_PAD_LEFT);
		$parte2 = time();
		$usuario = new Usuario();
		$usuario->setArrUsuario();
		$parte3 = $usuario->getCatUsuarioId();
		$cadena = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$parte4 = "";
		for ($i = 0; $i < 3; $i++){
			$parte4.= substr($cadena, rand(0,strlen($cadena)-1),1);
		}
		return $parte1.$parte2.$parte3.$parte4;
	}
	/**
	 * Se actualizan los valores necesarios en la tabla c00
	 * @param integer $cuestionario_id
	 */
	private function actualizaEnTblC00($cuestionario_id){
		$arr_act = array(
				"`modifica_fecha` = CURDATE()",
				"`modifica_hora`= CURTIME()",
				"`borrar`= NULL",
		);
		$qry_act = "UPDATE `".$this->bd->getBD()."`.`c00` SET ".implode(",", array_values($arr_act))." WHERE `cuestionario_id` = '".$cuestionario_id."';";
		$this->bd->ejecutaQry($qry_act);
	}
	
}