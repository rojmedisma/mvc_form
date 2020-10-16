<?php
/**
 * Clase modelo para el manejo de Los campos con contenido JSON almacenados en la tabla de cuestionario c00
 * @author Ismael Rojas
 *
 */
class CampoJSON{
	private $cuestionario_id;
	private $bd;
	private $o_json_campo;
	private $json_cmp_nom;	//Nombre del campo json a interactuar
	public function __construct($json_cmp_nom){
		$this->bd = new BaseDatos();
		$this->json_cmp_nom = $json_cmp_nom;
		$this->o_json_campo = (object)[];
	}
	/**
	 * Se genera el objeto de o_json_campo obteniendo el valor del campo asignado en json_cmp_val de la tabla c00
	 * @param integer $cuestionario_id
	 */
	public function setJSONCampo($cuestionario_id){
		$this->cuestionario_id=$cuestionario_id;
		$qry = "SELECT `".$this->json_cmp_nom."` FROM `".$this->bd->getBD()."`.`c00` WHERE `cuestionario_id` LIKE '".$this->getCuestionarioId()."' ";
		$json_cmp_val = $this->bd->get1erElemQry($this->json_cmp_nom, $qry);
		$this->o_json_campo = ($json_cmp_val!="")? json_decode($json_cmp_val) : (object)[];
	}
	/**
	 * Devuelve el objeto json_cmp_val
	 * @return object
	 */
	public function getJSONCampo(){
		return $this->o_json_campo;
	}
	/**
	 * Devuelve el valor del parametro identificado con la llave asignada en los argumentos
	 * @param string $llave_nom	Nombre de la llave que se desea obtener su valor
	 * @return variant	Si no existe la llave regresa un null
	 */
	public function getValor($llave_nom){
		$o_json_campo = $this->getJSONCampo();
		$parametro_val = null;
		if(!empty($o_json_campo)){
			if(isset($o_json_campo->$llave_nom)){
				$parametro_val = $o_json_campo->$llave_nom;
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
		$o_json_campo = $this->getJSONCampo();
		$o_json_campo->$llave_nom = $llave_val;
	}
	/**
	 * Guarda el objeto de json_cmp_val en la tabla c00
	 */
	public function guardar(){
		$json_cmp_val = json_encode($this->getJSONCampo());
		$qry_act = "UPDATE `".$this->bd->getBD()."`.`c00` SET `".$this->json_cmp_nom."` = ".txt_sql($json_cmp_val)." WHERE `cuestionario_id` = '".$this->getCuestionarioId()."';";
		$this->bd->ejecutaQry($qry_act);
	}
	/**
	 * Devuelve el cuestionario_id del registro donde se esta obteniendo la variable json_cmp_val
	 * @return integer
	 */
	private function getCuestionarioId(){
		return $this->cuestionario_id;
	}
}