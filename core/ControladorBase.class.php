<?php
/**
 * Extensión para todas las clases dentro de la carpeta <strong>controller</strong>
 * @author Ismael Rojas
 */
class ControladorBase{
	private $cargar_vista = false;
	private $nombre_vista = '';
	private $autentificar = true;
	private $titulo_pagina = '';
	private $arr_pag_anterior = array();
	private $arr_reg_usuario = array();
	private $arr_permisos = array();
	protected $arr_cmps_frm = array();	//Arreglo para los formularios
	protected $arr_html_tag = array();
	public function __constructINAES(){
		$this->setArrRegUsuario();	//Se crea el arreglo con el detalle de datos del usuario
		$permiso = new Permiso();
		$this->setArrPermiso("escritura", $permiso->tiene_permiso('mml_ae'));
	}
	
	/**
	 * Modifica el título principal de la página actual (Función obsoleta, ahora se usa setPaginaDistintivos)
	 * @param string $titulo_pagina	Título principal de la página actual
	 */
	public function setTituloPagina($titulo_pagina){
		$this->titulo_pagina = $titulo_pagina;
	}
	/**
	 * Devuelve el título principal de la página actual
	 * @return string
	 */
	public function getTituloPagina(){
		return $this->titulo_pagina;
	}
	/**
	 * Activas/desactiva el atributo que permita ejecutar la validación de la sesión actual
	 * @param boolean $autentificar
	 */
	public function setAutentificar($autentificar){
		$this->autentificar = $autentificar;
	}
	/**
	 * Devuelve la indicación para permitir validar la sesión actual
	 * @return boolean
	 */
	public function getAutentificar(){
		return $this->autentificar;
	}
	/**
	 * Devuelve el nombre del controlador de la página que se está consultando, cuyo argumento también aparece la URL
	 * @return string
	 */
	public function getControlador(){
		return (isset($_REQUEST['controlador']))? $_REQUEST['controlador'] : CONTROLADOR_DEFECTO;
	}
	/**
	 * Devuelve el nombre de la acción de la página que se está consultando, cuyo argumento también aparece la URL
	 * @return string
	 */
	public function getAccion(){
		return (isset($_REQUEST['accion']))? $_REQUEST['accion'] : ACCION_DEFECTO;
	}
	/**
	 * Redirecciona a la página de autenficar usuario, cerrado previamente la sesión actual
	 */
	public function setValidaSesion(){
		$cat_usuario_id = (isset($_SESSION['cat_usuario_id']))? $_SESSION['cat_usuario_id'] : '';
		$controlador = $this->getControlador();
		if($cat_usuario_id=="" && $this->getAutentificar()){
			$url_uri = ($controlador!="")? $_SERVER['REQUEST_URI'] : "";
			
			//Antes de autentificar, se va a desautentificar para eliminar lo que haya quedado de la variable de sessión
			redireccionar('desautentificar', 'inicio', '', $url_uri);
		}
	}
	/**
	 * Devuelve la indicación de permitir cargar la página asignada en el atributo <strong>nombre_vista</strong>
	 * @return boolean
	 */
	public function getCargarVista(){
		return $this->cargar_vista;
	}
	/**
	 * Devuelve el nombre del archivo de la página a mostrar, este archivo puede ser cualquiera de los que se encuentran dentro de la carpeta <strong>view</strong>
	 * @return string
	 */
	public function getNombreVista(){
		return $this->nombre_vista;
	}
	/**
	 * Modifica el atributo con el nombre del archivo de la página a mostrar
	 * @param string $nombre_vista
	 */
	protected function setMostrarVista($nombre_vista){
		$this->cargar_vista = ($nombre_vista!="")? true : false;
		$this->nombre_vista = $nombre_vista;
	}
	/**
	 * Define el arreglo con el detalle del registro de usuario actual si es que no se envía el cat_usuario_id del argumento
	 * @param string $cat_usuario_id
	 */
	protected function setArrRegUsuario($cat_usuario_id=""){
		$usuario = new Usuario();
		$usuario->setArrUsuario($cat_usuario_id);
		$arr_usuario = array();
		if($usuario->getCatUsuarioId()!=""){
			$arr_usuario= $usuario->getArrUsuario();
		}
		$this->arr_reg_usuario = $arr_usuario;
	}
	/**
	 * Devuelve el arreglo con el detalle del registro de usuario
	 * @return array
	 */
	protected function getArrRegUsuario(){
		return $this->arr_reg_usuario;
	}
	/**
	 * Del arreglo con el detalle del registro de usuario almacenado en arr_reg_usuario, devuelve el nombre del campo definido como dato en el argumento
	 * @param string $dato	Nombre de la variable en arr_reg_usuario
	 * @return string
	 */
	public function usuario_dato($dato){
		$arr_reg_usuario = $this->getArrRegUsuario();
		
		$usr_dato = "";
		if(count($arr_reg_usuario)){
			if(isset($arr_reg_usuario[$dato])){
				$usr_dato = $arr_reg_usuario[$dato];
			}
		}
		return $usr_dato;
	}
	/**
	 * Devuelve un arreglo con la información del usuario actual del catálogo de usuarios
	 * Info:	Función obsoleta, se sugiere utilizar setArrRegUsuario y getArrRegUsuario
	 * @return array
	 */
	public function getArrUsuario(){
		$usuario = new Usuario();
		$usuario->setArrUsuario();
		$arr_usuario = array();
		if($usuario->getCatUsuarioId()!=""){
			$arr_usuario= $usuario->getArrUsuario();
		}
		return $arr_usuario;
	}
	/**
	 * Devuelve un arreglo con los datos necesarios para permitir regresar a la página previamente visitada
	 * @return array
	 */
	public function getArrPagAnterior(){
		return $this->arr_pag_anterior;
	}
	/**
	 * Devuelve los datos de la pagina anterior donde
	 * @param int $indice	es el indice del arreglo
	 * @param string $variable	es la variable (controlador, accion, titulo_pagina)
	 * @return string
	 */
	public function getPaginaAnterior($indice, $variable) {
		$arr_pag_anterior = $this->getArrPagAnterior();
		$valor_variable = "";
		if(isset($arr_pag_anterior[$indice][$variable])){
			$valor_variable = $arr_pag_anterior[$indice][$variable];
		}
		return $valor_variable;
	}
	/**
	 * A partir del controlador y la acción se asignan en variables los distintivos de cada página, como el título o la navegación
	 * @param string $controlador
	 * @param string $accion
	 */
	public function setPaginaDistintivos($controlador="", $accion=""){
		$controlador_actual = ($controlador=="")? $this->getControlador() : $controlador;
		if($controlador_actual=="error"){
			$accion_actual = "__construct";
		}else{
			$accion_actual = ($accion=="")? $this->getAccion() : $accion;
		}
		if($controlador_actual!="" && $accion_actual!=""){
			
			$distintivos = new Distintivos();
			$distintivos->setArrDistintivosPagina($controlador_actual, $accion_actual);
			$arr_pag_distintivos = $distintivos->getArrDistintivosPagina();
			
			if(isset($arr_pag_distintivos['titulo_pagina']) && isset($arr_pag_distintivos['arr_pagina_anterior'])){
				$this->titulo_pagina = $arr_pag_distintivos['titulo_pagina'];
				$this->arr_pag_anterior = $arr_pag_distintivos['arr_pagina_anterior'];
			}elseif($this->getControlador()!="error"){	//Para que no se cicle
				redireccionar('error','sin_distintivos_pagina', array("controlador_d"=>$controlador_actual, "accion_d"=>$accion_actual));
			}
			
		}
	}
	/**
	 * Agrega el permiso específico al arreglo de permisos que rigen la forma actual del usuario actual
	 * @param string $nom_permiso	Nombre específico del permiso en la forma actual
	 * @param boolean $agregar	Agregar permiso (true,false)
	 */
	protected function setArrPermiso($nom_permiso, $agregar=true) {
		$arr_permisos = $this->arr_permisos;
		if(!isset($arr_permisos[$nom_permiso]) && $agregar){
			array_push($arr_permisos, $nom_permiso);
		}
		$this->arr_permisos = $arr_permisos;
	}
	/**
	 * Devuelve el arreglo de permisos
	 * @return array
	 */
	protected function getArrPermisos(){
		return $this->arr_permisos;
	}
	/**
	 * Indica si el permiso actual pertenece a la lista de permisos del arreglo arr_permisos
	 * @param string $nom_permiso
	 * @return boolean
	 */
	public function tienePermiso($nom_permiso){
		$arr_permisos = $this->getArrPermisos();
		if(in_array($nom_permiso, $arr_permisos, true)){
			return true;
		}else{
			return false;
		}
	}
	/**
	 * Devuelve el arreglo que contiene todos los campos del formulario actual
	 * @return array
	 */
	public function getArrCmpsForm(){
		return $this->arr_cmps_frm;
	}
	/**
	 * Del arreglo de campos de la forma actual, devuelve el valor del campo indicado en el argumento
	 * @param string $cmp_nom	Nombre del campo
	 * @return string
	 */
	public function getCampoValor($cmp_nom){
		$arr_cmps_frm = $this->getArrCmpsForm();
		return valorEnArreglo($arr_cmps_frm, $cmp_nom);
	}
	/**
	 * Regresa el arreglo que contiene los tags html previamente definidos en el controlador actual asignado en el arreglo arr_html_tag
	 * @param string $nom_html_tag
	 * @return array
	 */
	private function getArrHTMLTag($nom_html_tag) {
		$arr_html_tag = $this->arr_html_tag;
		if(isset($arr_html_tag[$nom_html_tag])){
			return $arr_html_tag[$nom_html_tag];
		}else{
			return array();
		}
	}
	/**
	 * Regresa el string que contiene el tags html previamente definidos en el controlador actual
	 * @param string $nom_html_tag
	 * @return string
	 */
	public function getHTMLTag($nom_html_tag) {
		$arr_tag = array();
		foreach($this->getArrHTMLTag($nom_html_tag) as $html_tag){
			$arr_tag[] = $html_tag;
		}
		return tag_string($arr_tag);
	}
	public function redireccionaError($tit_error, $txt_error, $es_error_interno=true) {
		$arr_url_arg = array(
			'tit_error'=>$tit_error,
			'txt_error'=>$txt_error
		);
		$accion = ($es_error_interno)? 'interno':'inicio';
		$this->redireccionaErrorAccion($accion, $arr_url_arg);
	}
	public function redireccionaErrorAccion($accion, $arr_url_arg=array()) {
		redireccionar('error', $accion, $arr_url_arg);
		die();
	}
}
