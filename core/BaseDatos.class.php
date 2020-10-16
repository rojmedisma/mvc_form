<?php
/**
 * Contiene todos los métodos necesarios para realizar consultas y modificaciones a la base de datos
 * @author Ismael Rojas
 *
 */
class BaseDatos extends Ayuda{
	/** @var string $bd	Nombre de la base de datos  */
	private $bd = '';
	/** @var string $mysqli	Variable de conexión a la base de datos  */
	private $mysqli;
	public function __construct(){
	    global $globales, $mysqli;
		$this->bd = $globales['conexion']['bd'];
		//$conexion = new Conectar();
		$this->mysqli = $mysqli;//$conexion->getConexion();
	}
	/**
	 * Para cambiar la base de datos predefinida
	 * @param string $bd
	 */
	public function setBD($bd){
		$this->bd = $bd;
	}
	/**
	 * Cambia el tipo de conexión actual a la del usuario asignado en los parámetros de configuración <strong>adm_usr</strong>
	 */
	public function setAdminConexion(){
		//$this->mysqli->close();
		$conexion = new Conectar();
		$this->mysqli = $conexion->getConexion(true);
	}
	
	/**
	 * Nombre de la base de datos usada
	 * @return string
	 */
	public function getBD(){
		return $this->bd;
	}
	/**
	 * Devuelve la sentencia query almacenada
	 * @return string
	 */
	public function getQry(){
		return $this->qry;
	}
	/**
	 * Mensaje tipo alerta con la información del error de ejecución al ejecutar la consulta a la base de datos
	 * @param string $qry
	 * @param string $sin_libreria
	 * @return string
	 */
	public function getTagErrorSQL($qry, $sin_libreria=false, $txt_adicional=''){
		$txt_tit = 'Error en consulta';
		$txt_desc = '<br><strong>Error en query:</strong><br>'.$qry.'<br>'.$this->mysqli->errno.'<br>'.$this->mysqli->error;
		$txt_desc .= $this->getTxtAdicionalError();
		if(!$sin_libreria){
			$txt_desc .= '<p class="text-center"><a href="'.url_controlador(CONTROLADOR_DEFECTO,ACCION_DEFECTO,'',false).'" class="btn btn-primary">Ir a página principal</a></p>';
		}		
		return $this->getTagError($txt_tit, $txt_desc, $sin_libreria);
	}
	/**
	 * Ejecuta la sentencia query y devuelve el resultado
	 * @return object
	 */
	public function getRes($qry){
		return $this->mysqli->query($qry);
	}
	/**
	 * Ejecuta una sentencia query de una operación o variable cuyo resultado sea una linea o valor
	 * @param string $id	Nombre de la variable o alias de la operación
	 * @param string $qry	Sentencia query
	 * @return string
	 */
	public function get1erElemQry($id, $qry){
		$rs = $this->mysqli->query($qry);
		if(!$rs)	die($this->getTagErrorSQL($qry));
		$row = $rs->fetch_assoc();
		$campo = $row[$id];
		$rs->close();
		$this->qry = $qry;
		return $campo;
	}
	/**
	 * Devuelve un arreglo de los campos de la tabla especificada en el argumento
	 * @param string $tabla	Nombre de la tabla
	 * @return array
	 */
	public function getArrCmpsTbl($tabla){
		$arr_cmps = array();
		$qry = "SHOW FIELDS FROM `".$this->getBD()."`.`".$tabla."`";
		$rs = $this->mysqli->query($qry);
		if(!$rs)	die($this->getTagErrorSQL($qry));
		while($row = $rs->fetch_object()){
			$arr_cmps[] = array("Field"=>$row->Field, "Type"=>$row->Type);
		}
		
		$rs->close();
		$this->qry = $qry;
		return $arr_cmps;
	}
	/**
	 * Devuelve un arreglo de registros contenidos en la tabla especificada en el argumento
	 * @param string $tabla	Nombre de la tabla
	 * @param string $and	Complemento del query despues del where (AND, ORDER BY...)
	 * @param string $cmp_id	Campo Id único o llave, si viene vacío, el arreglo se regresa sin indices
	 * @param boolean $imprimir_query	En la revisión. Para imprimir en pantalla el query que se está ejecutando
	 * @return array
	 */
	public function getArrDeTabla($tabla, $and="", $cmp_id="", $imprimir_query=false){
		$arr_cmps = $this->getArrCmpsTbl($tabla);
		$arr_t = array();
		$qry = "SELECT * FROM `".$this->getBD()."`.`".$tabla."` WHERE 1 ".$and;
		if($imprimir_query){
			echo "<br>".$qry."<br>";
		}
		$rs = $this->mysqli->query($qry);
		if(!$rs)	die($this->getTagErrorSQL($qry));
		while($row = $rs->fetch_array()){
			$arr_sh = array();
			foreach ($arr_cmps as $arr_det){
				
				$cmp_nom = $arr_det['Field'];
				$arr_sh[$cmp_nom] = $row[$cmp_nom];
			}
			if($cmp_id!=""){
				$cmp_id_val = $row[$cmp_id];
				$arr_t[$cmp_id_val] = $arr_sh;
			}else{
				$arr_t[] = $arr_sh;
			}
			
		}
		$rs->close();
		$this->qry = $qry;
		return $arr_t;
	}
	/**
	 * Devuelve el registro de la tabla a partir del id llave del argumento
	 * @param string $tabla	Nombre de la tabla
	 * @param string $cmp_id_nom	Nombre del campo Id Llave
	 * @param mixed $cmp_id_val	Valor del campo Id Llave
	 * @return array
	 */
	public function getArrRegDeTabla($tabla, $cmp_id_nom, $cmp_id_val){
		$and = " AND `".$cmp_id_nom."` = '".$cmp_id_val."'";
		$arr_tbl = $this->getArrDeTabla($tabla, $and);
		$arr_reg = (count($arr_tbl))? $arr_tbl[0] : NULL;
		return $arr_reg;
	}
	/**
	 * Regresa un arreglo con el "DISTINCT" de la tabla y campo especificados en los argumentos
	 * @param string $tabla
	 * @param string $cmp_nom	Nombre del campo al que se le va a hacer el distinct
	 * @param string $and
	 * @param string $sin_nulos	true = No incluir en el resultado valores nulos
	 * @return array
	 */
	public function getArrDistinctDeTabla($tabla, $cmp_nom, $and="", $sin_nulos=true){
		$arr_dis = array();
		$and_d = ($sin_nulos)? $and." AND `".$cmp_nom."` IS NOT NULL " : $and;
		$qry = "SELECT DISTINCT `".$cmp_nom."` AS 'cmp_dis' FROM `".$this->getBD()."`.`".$tabla."` WHERE 1 ".$and_d." ORDER BY `".$cmp_nom."` ASC ";
		$rs = $this->ejecutaQry($qry);
		while($row = $rs->fetch_object()){
			$arr_dis[] = $row->cmp_dis;
		}
		return $arr_dis;
	}
	public function getArrDeQuery($query, $cmp_id=""){
		$rs = $this->ejecutaQry($query);
		$arr_qry = array();
		while($row = $rs->fetch_array()){
			if($cmp_id!=""){
				$arr_qry[$row[$cmp_id]] = $row;
			}else{
				$arr_qry[] = $row;
			}
			
		}
		return $arr_qry;
	}											   
	/**
	 * Ejecuta una sentencia query
	 * @param string $qry
	 * @return mixed
	 */
	public function ejecutaQry($qry){
		$rs = $this->mysqli->query($qry);
		$this->qry = $qry;
		if(!$rs){
			die($this->getTagErrorSQL($qry));
		}else{
			return $rs;
		}
	}
	/**
	 * Ejecuta una sentencia query tipo <strong>INSERT</strong> y devuelve el Id Llave con autoincremento
	 * @param string $qry
	 * @return integer
	 */
	public function ejecutaQryInsert($qry){
		$rs = $this->mysqli->query($qry);
		$this->qry = $qry;
		if(!$rs){
			die($this->getTagErrorSQL($qry));
		}else{
			return $this->mysqli->insert_id;
		}
	}
	/**
	 * Ejecuta una sentencia query tipo <strong>INSERT</strong> mandando com argumento el arreglo de campos con valores y la tabla
	 * @param array $arr_cmps Arreglo de campos (nombre => valor)
	 * @param string $tbl_nom Nombre de la tabla
	 * @return number
	 */
	public function ejecutaQryInsertDeArr($arr_cmps, $tbl_nom){
		$qry_act = "INSERT INTO `".$this->getBD()."`.`".$tbl_nom."` (".implode(",",array_keys($arr_cmps)).") VALUES (".implode(",",array_values($arr_cmps)).");";
		return $this->ejecutaQryInsert($qry_act);
	}
	/**
	 * Marca como borrado un registro de la tabla y id especificados en los argumentos
	 * @param string $tbl_nom
	 * @param mixed $cmp_id_val
	 * @param string $cmp_id_nom
	 * @return mixed
	 */
	public function borrar_reg($tbl_nom, $cmp_id_val, $cmp_id_nom=""){
		$cmp_id_nom = ($cmp_id_nom=="")? $tbl_nom."_id" : $cmp_id_nom;
		$qry = "UPDATE  `".$this->getBD()."`.`".$tbl_nom."` SET  `borrar` =  '1' WHERE  `".$cmp_id_nom."` ='".$cmp_id_val."' LIMIT 1;";
		return $this->ejecutaQry($qry);
	}
	/**
	 * Conforme se van identificando los errores, en esta función se pueden agregar textos adicionales que complementen el mensaje de error para que sea mas descriptivo
	 * @return string
	 */
	private function getTxtAdicionalError(){
		if($this->mysqli->errno == 1062){
			$txt_adicional = "<br><br>Al intentar insertar el registro en la tabla, se generó un conflicto de llaves duplicadas con los campos que el error describe.";
		}else{
			$txt_adicional = "";
		}
		return $txt_adicional;
	}
}