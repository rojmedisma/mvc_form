<?php
/**
 * Controlador Autentificar
 * Se usa para crear una sesión de usuario mediante su nombre y contraseña, ayudandose de la vista de autentificación
 * @author Ismael Rojas
 *
 */
class AutentificarControl extends ControladorBase{
	private $es_info_incorrecta = false;
	private $url_uri = '';
	private $url_arg = '';
	public function __construct(){
		$this->url_uri = (isset($_REQUEST["url_uri"]))? $_REQUEST["url_uri"] : '';
		$this->url_arg = (isset($_REQUEST["url_arg"]))? $_REQUEST["url_arg"] : '';
		$this->setAutentificar(false);
	}
	/**
	 * Devuelve la ruta URL a donde se debe de redireccionar una vez se haya realizado la autentificación
	 * @return string
	 */
	public function getUrlUri(){
		return $this->url_uri;
	}
	/**
	 * Argumentos URL (No se utiliza)
	 * @return string
	 */
	public function getUrlArg(){
		return $this->url_arg;
	}
	/**
	 * Devuelve la indicación de si los datos son correctos en el momento en que se ejecuta la validación de usuario y contraseña
	 * @return boolean
	 */
	public function getEsInfoIncorrecta(){
		return $this->es_info_incorrecta;
	}
	/**
	 * Acción que redirecciona a la pagina donde se encuentra la vista de autentificación
	 */
	public function inicio(){
		$this->setPaginaDistintivos();
		$this->setMostrarVista('Autentificar.php');
	}
	/**
	 * Acción para validar si la información de usuario y contraseña es correcta
	 */
	public function autentificar(){
		$usuario = (isset($_REQUEST["usuario"]))? $_REQUEST["usuario"] : '';
		$clave= (isset($_REQUEST["clave"]))? $_REQUEST["clave"] : '';
		
		$autentificar = new Autentificar();
		$autentificar->setValidar($usuario, $clave);
		if($autentificar->getEsValido()){
			$url_uri = $this->getUrlUri();
			if($url_uri!=""){
				header('Location:'.$url_uri);
			}else{
				redireccionar(CONTROLADOR_DEFECTO,ACCION_DEFECTO);
			}
			$log = new Log();
			$log->setRegLog('usuario', $usuario, 'autentificar', 'Aviso', 'Inició sesión');
		}else{
			$this->es_info_incorrecta = true;
			$this->setMostrarVista('Autentificar.php');
			//$this->setMostrarVista('Autentificar.php');
		}
	}
	
}