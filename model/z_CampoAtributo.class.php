<?php
class CampoAtributo{
	private array $arr_atrib_usuario = array();
	public function setArrAtribUsuario(array $arr_atrib_usuario):void {
		$arr_atrib_usuario['class'] = 'form-control';
		
		if(isset($arr_atrib_usuario['readonly']) && $arr_atrib_usuario['readonly']===true){
			$arr_atrib_usuario['readonly'] = 'readonly';
			$arr_atrib_usuario['class'] = 'form-control-plaintext';
		}
		
		$this->arr_atrib_usuario = $arr_atrib_usuario; 
	}
	public function getArrAtribUsuario():array {
		return $this->arr_atrib_usuario;
	}
	public function getAtributo(string $atrib_nombre){
		$arr_atrib_usuario = $this->getArrAtribUsuario();
		if(isset($arr_atrib_usuario[$atrib_nombre])){
			return $arr_atrib_usuario[$atrib_nombre];
		}else{
			return "";
		}
	}
}
