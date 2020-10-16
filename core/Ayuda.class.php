<?php
/**
 * Clases necesarias como extension para algunas clases ubicadas dentro de la carpeta <strong>core</strong>
 * @author Ismael Rojas
 */
class Ayuda{
	private $es_error= false;
	private $arr_error = array();
	private function setEsErrorTrue(){
		$this->es_error=true;
	}
	/**
	 * Devuelve la indicación cuando en el proceso actual se produjo un error
	 * @return boolean
	 */
	protected function getEsError(){
		return $this->es_error;
	}
	/**
	 * Activa la bandera es_error, indicando que se generó un error y asigna el título y la descripción del error a un arreglo de errores
	 * @param string $tit_error
	 * @param string $txt_error
	 */
	protected function setError($tit_error, $txt_error){
		$this->setArrError($tit_error, $txt_error);
	}
	/**
	 * Modifica el arreglo del atributo <strong>arr_error</strong> con la información necesaria para señalar el error producido
	 * @param string $tit_error
	 * @param string $txt_error
	 */
	private function setArrError($tit_error, $txt_error){
		if($tit_error!="" && $txt_error!=""){
			$this->setEsErrorTrue();
			$arr_error = $this->getArrError();
			$arr_error[] = array('tit_error'=>$tit_error, 'txt_error'=>$txt_error);
			$this->arr_error = $arr_error;
		}
	}
	/**
	 * Devuelve el arreglo generado tras haberse producido un error en la ejecución
	 * @return array
	 */
	protected function getArrError(){
		return $this->arr_error;
	}
	/**
	 * Devuelve un cuadro de alerta en formato HTML para informar del error producido
	 * @param string $txt_tit
	 * @param string $txt_desc
	 * @param string $sin_libreria
	 * @return string
	 */
	protected function getTagError($txt_tit, $txt_desc, $sin_libreria=false){
		$alerta = new AlertaGenerica($sin_libreria);
		return $alerta->getAlerta($txt_tit, $txt_desc, 'danger');
	}
	protected function getUsuarioId(){
		$usuario_id = (isset($_SESSION['usuario_id']))? $_SESSION['usuario_id'] : "";
		return $usuario_id;
	}
}
?>