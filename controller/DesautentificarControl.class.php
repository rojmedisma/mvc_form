<?php
/**
 * Controlador para cerrar sesión o desautentificar usuario
 * @author Ismael Rojas
 *
 */
class DesautentificarControl extends ControladorBase{
	public function __construct(){
		$this->setAutentificar(false);	//Para que en el index no entre a la condición para validar usuario
	}
	/**
	 * Acción que elimina la variable de sesión actual y redirecciona a la página de autentificación
	 */
	public function inicio(){
		session_destroy();
		$url_uri = (isset($_REQUEST["url_uri"]))? $_REQUEST["url_uri"] : '';
		$log = new Log();
		$log->setRegLog('', '', 'Desautentificar', 'Aviso', 'Cerró sesión');
		redireccionar('autentificar', 'inicio', '', $url_uri);
	}
	/**
	 * Acción que elimina la variable de sesión actual y redirecciona al cotrolador defecto
	 */
	public function con_ini_ses(){
		session_destroy();
		$url_uri = (isset($_REQUEST["url_uri"]))? $_REQUEST["url_uri"] : '';
		$log = new Log();
		$log->setRegLog('', '', 'Desautentificar', 'Aviso', 'Cerró sesión');
		redireccionar(CONTROLADOR_DEFECTO, ACCION_DEFECTO, array("ini_ses"=>INI_SES), $url_uri);
	}
}
?>