<?php
/**
 * Clase modelo para el manejo del campo json_parametros almacenado en la tabla de cuestionario c00
 * @author Ismael Rojas
 *
 */
class ParametrosJSON{
	private $cuestionario_id;
	private $bd;
	private $o_json_parametros;
	public function __construct(){
		$this->bd = new BaseDatos();
		$this->o_json_parametros = (object)[];
	}
	/**
	 * Se genera el objeto de o_json_parametros obteniendo el valor json_parametros de la tabla c00
	 * @param integer $cuestionario_id
	 */
	public function setJSON($cuestionario_id){
		$this->cuestionario_id=$cuestionario_id;
		$qry = "SELECT `json_parametros` FROM `".$this->bd->getBD()."`.`c00` WHERE `cuestionario_id` LIKE '".$this->getCuestionarioId()."' ";
		$json_parametros = $this->bd->get1erElemQry("json_parametros", $qry);
		$this->o_json_parametros = ($json_parametros!="")? json_decode($json_parametros) : (object)[];
	}
	/**
	 * Devuelve el objeto json_parametros
	 * @return object
	 */
	public function getJSON(){
		return $this->o_json_parametros;
	}
	/**
	 * Devuelve el valor del parametro identificado con la llave asignada en los argumentos
	 * @param string $llave_nom	Nombre de la llave que se desea obtener su valor
	 * @return variant	Si no existe la llave regresa un null
	 */
	public function getValor($llave_nom){
		$o_json_parametros = $this->getJSON();
		$parametro_val = null;
		if(!empty($o_json_parametros)){
			if(isset($o_json_parametros->$llave_nom)){
				return $o_json_parametros->$llave_nom;
			}
		}
		return $parametro_val;
	}
	/**
	 * Asigna o modifica un parametro indicando la llave y el valor en los argumentos
	 * @param string $llave_nom
	 * @param variant $llave_val
	 */
	public function modificaValor($llave_nom, $llave_val){
		$o_json_parametros = $this->getJSON();
		$o_json_parametros->$llave_nom = $llave_val;
	}
	/**
	 * Guarda el objeto de json_parametros en la tabla c00 
	 */
	public function guardar(){
		$json_parametros = json_encode($this->getJSON());
		$qry_act = "UPDATE `".$this->bd->getBD()."`.`c00` SET `json_parametros` = ".txt_sql($json_parametros)." WHERE `cuestionario_id` = '".$this->getCuestionarioId()."';";
		$this->bd->ejecutaQry($qry_act);
	}
	/**
	 * Devuelve el cuestionario_id del registro donde se esta obteniendo la variable json_parametros
	 * @return integer
	 */
	private function getCuestionarioId(){
		return $this->cuestionario_id;
	}
}