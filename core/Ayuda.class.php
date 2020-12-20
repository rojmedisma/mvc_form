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
	 * Modifica el arreglo del atributo arr_error con la información necesaria para señalar el error producido
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
	 * Regresa el primer arreglo de error generado con la intención de ser impreso en pantalla mediante el rediereccionamiento al controlador de Error.
	 * @return array
	 */
	public function getArr1erError() {
		$arr_error = $this->arr_error;
		if(count($arr_error)){
			return $arr_error[0];
		}else{
			return array('tit_error'=>'Error al llamado a función getArr1erError', 'txt_error'=>'El error no pudo ser desplegado al generarse un error en la función getArr1erError. <br>Verificar primera haber insertado un return true al final de la función que llama a setError.');
		}
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