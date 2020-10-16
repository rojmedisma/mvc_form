<?php
/**
 * Clase modelo utilizada para la validación del usuario y sus permisos
 * @author Ismael
 *
 */
class Autentificar extends Ayuda{
	private $usuario = '';
	private $clave = '';
	private $bd;
	private $and_usuario;
	public function __construct(){
		$this->bd = new BaseDatos();
		$this->and_usuario = " AND `borrar` IS NULL AND `activo` = 1";
	}
	/**
	 * Modifica los atributos usuario y clave a partir de la información enviada mediante los argumentos
	 * @param string $usuario
	 * @param string $clave
	 */
	public function setValidar($usuario, $clave){
		$this->usuario = $usuario;
		$this->clave = $clave;
	}
	/**
	 * Devuelve el nombre del usuario
	 * @return string
	 */
	private function getUsuario(){
		return $this->usuario;
	}
	/**
	 * Devuelve el valor codificado de la clave
	 * @return string
	 */
	private function getClave(){
		return $this->clave;
	}
	/**
	 * Devuelve la indicación de si el usuario capturado es un usuario válido
	 * @return boolean
	 */
	private function getValUsuario(){
		if($this->getUsuario()==""){
			$this->setError('Nombre de usuario vacío', 'Favor de registrar el nombre de usuario');
			return false;
		}else{
			$qry_cu = "SELECT COUNT(*) AS 'total' FROM `".$this->bd->getBD()."`.`cat_usuario` WHERE `usuario` LIKE '".$this->getUsuario()."'".$this->and_usuario;
			$total = $this->bd->get1erElemQry('total', $qry_cu);
			if($total){
				return true;
			}else{
				return false;
			}
		}
	}
	/**
	 * Devuleve la indicación de si la clave registrada es una clave válida
	 * @return boolean
	 */
	private function getValClave(){
		if($this->getClave()==""){
			$this->setError('Clave vacía', 'Favor de registrar la clave');
			return false;
		}else{
			$arr_tbl = $this->bd->getArrDeTabla('cat_usuario', " AND `usuario` LIKE '".$this->getUsuario()."'".$this->and_usuario);
			$arr_cu = $arr_tbl[0];
			
			$clave = $arr_cu['clave'];
			$cat_usuario_id = $arr_cu['cat_usuario_id'];
			$usuario = $arr_cu['usuario'];
			$ll = CLAVE_LLAVE_1.'|'.$cat_usuario_id.'|'.$usuario.'|'.CLAVE_LLAVE_2;
			
			
			$cr = new Encriptar();
			$pw_cr = $cr->encode($this->getClave(),$ll);
			if (!empty($pw_cr)){
				if($pw_cr===$clave){
					$_SESSION['cat_usuario_id'] = $cat_usuario_id;
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
			
		}
	}
	/**
	 * Devuelve la indicación de si el usuario y clave registrados son válidos
	 * @return boolean
	 */
	public function getEsValido(){
		$es_valido = false;
		if($this->getValUsuario() && $this->getValClave()){
			$es_valido = true;
		}
		return $es_valido;
	}
	/**
	 * Actualiza la clave registrada en el catálogo de usuarios, aplicando el encriptado previamente
	 * @param integer $cat_usuario_id
	 * @param string $clave
	 */
	public function setActualizaClave($cat_usuario_id, $clave){
		$arr_tbl = $this->bd->getArrDeTabla("cat_usuario", " AND `cat_usuario_id` = '".$cat_usuario_id."'");
		$arr_tbl_usr = $arr_tbl[0];
		
		$usuario = (isset($arr_tbl_usr['usuario']))? $arr_tbl_usr['usuario'] :'';
		if($usuario!="" && $clave!=""){
			$ll = CLAVE_LLAVE_1.'|'.$cat_usuario_id.'|'.$usuario.'|'.CLAVE_LLAVE_2;
			$cr = new Encriptar();
			$pw_cr = $cr->encode($clave,$ll);
			$qry = "UPDATE `".$this->bd->getBD()."`.`cat_usuario` SET `clave` = '".$pw_cr."' WHERE `cat_usuario`.`cat_usuario_id` = '".$cat_usuario_id."';";
			$this->bd->ejecutaQry($qry);
		}else{
			die($this->getTagError("Información incompleta", "Favor de capturar los campos de usuario y contraseña"));
		}
	}
}