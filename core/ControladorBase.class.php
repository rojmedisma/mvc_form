<?php
/**
 * Extensión para todas las clases dentro de la carpeta controller
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
	private $arr_datos_vista = array();	//Arreglo para asignar variables con valores que se van a mostrar en la vista
	private $usar_lib_toastr = false;	//Activa la librería Toastr para permitir mostrar sus alertas
	private $usar_lib_vista = false;	//Activa las librerias necesarias para desplegar las vistas con formato DataTable
	private $usar_lib_forma = false;	//Activa las librerias necesarias para la funcionalidad de los formularios
	protected $arr_cmps_frm = array();	//Arreglo para los formularios
	protected $llamado_por_ajax = false;	//Si el controlador está siendo llamado usando ajax, se debe activar esta bandera
	protected $arr_html_tag = array();
	private $con_menu_lateral_fijo = false;	//Si se va mostrar el menú lateral, esta bandera permite activar las librerías necesarias
	protected $es_nuevo = true;	//Es documento/formulario nuevo
	
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
	 * Asigna el nombre de la vista a mostrar al ejecutarse el controlador
	 * @param string $nombre_vista	Nombre de la vista
	 */
	protected function defineVista($nombre_vista){
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
	/**
	 * Se llena el arreglo de permisos (Previamente definidios) al arreglo arr_permisos para poder ser manejados
	 * @param type $arr_permisos
	 */
	protected function setArrPermisos($arr_permisos) {
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
	 * Devuelve el arreglo que contiene todas las variables creadas para mostrarse en la vista
	 * @return array
	 */
	private function getArrDatosVista() {
		return $this->arr_datos_vista;
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
	 * Del arreglo de variables con valores para la vista, devuelve el valor del de la variable indicada en el arreglo
	 * @param string $cmp_nom
	 * @return variant
	 */
	public function getDatoVistaValor($var_nom) {
		$arr_datos_vista = $this->getArrDatosVista();
		return valorEnArreglo($arr_datos_vista, $var_nom);
	}
	/**
	 * Asigna un valor a una variable, ambas definidas en los argumentos, para asignarse a un arreglo cuyos valores van a poder ser consultados en los archivos vista
	 * @param type $var_nom
	 * @param type $var_val
	 */
	protected function setArrDatoVistaValor($var_nom, $var_val) {
		$arr_datos_vista = $this->getArrDatosVista();
		$arr_datos_vista[$var_nom] = $var_val;
		//array_push($arr_datos_vista, array($var_nom=>$var_val));
		$this->arr_datos_vista = $arr_datos_vista;
		//echo json_encode($this->arr_datos_vista);
	}
	
	
	/**
	 * Regresa el string que contiene el tag html previamente definidos en el controlador actual
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
	 * Función redireccionar para desplegar pantalla de error
	 * @param string $tit_error	Título del error
	 * @param string $txt_error	Texto o descripción del error
	 * @param boolean $es_error_interno	Bandera para identificar si el error es interno o no, y así adjuntar el texto pertinente
	 */
	public function redireccionaError($tit_error, $txt_error, $es_error_interno=true) {
		$txt_origen = '<br><br>Controlador: <strong>'.$this->getControlador().'</strong><br>';
		$txt_origen .= 'Acción: <strong>'.$this->getAccion().'</strong><br>';
		$arr_url_arg = array(
			'tit_error'=>$tit_error,
			'txt_error'=>$txt_error.$txt_origen,
		);
		$this->redireccionaErrorDeArr($arr_url_arg, $es_error_interno);
	}
	/**
	 * Función redireccionar para desplegar pantalla de error. Misma funcionalidad a redireccionaError, con la diferencia a que recibe un arreglo con los textos de error
	 * @param array $arr_url_arg	Arreglo con los textos a desplegar del error
	 * @param boolean $es_error_interno	Bandera para identificar si el error es interno o no, y así adjuntar el texto pertinente
	 */
	public function redireccionaErrorDeArr($arr_url_arg, $es_error_interno=true) {
		$accion = ($es_error_interno)? 'interno':'inicio';
		$this->redireccionaErrorAccion($accion, $arr_url_arg);
	}
	/**
	 * Función redireccionar para el llamado un error predeterminado o previamente definido en el controlador Error
	 * @param string $accion
	 * @param array $arr_url_arg
	 */
	public function redireccionaErrorAccion($accion, $arr_url_arg=array()) {
		redireccionar('error', $accion, $arr_url_arg);
		die();
	}
	/**
	 * Activa/Desactiva la variable usada para declarar las librerías cuando se usa el menú lateral fijo
	 * @param boolean $bandera
	 */
	protected function setConMenuLateralFijo($bandera=true){
		$this->con_menu_lateral_fijo = $bandera;
	}
	/**
	 * Devuelve el valor de la variable usada para declarar las librerías cuando se usa el menú lateral fijo
	 * @return boolean
	 */
	public function getConMenuLateralFijo() {
		return $this->con_menu_lateral_fijo;
	}
	public function getUsarLibToastr() {
		return $this->usar_lib_toastr;
	}
	/**
	 * Función para hacer switch con la bandera privada usar_lib_vista
	 * @param boolean $bandera
	 */
	protected function setUsarLibVista($bandera=true) {
		$this->usar_lib_vista = $bandera;
	}
	/**
	 * Devuelve el valor de la bandera usar_lib_vista
	 * @return boolean
	 */
	public function getUsarLibVista() {
		return $this->usar_lib_vista;
	}
	/**
	 * Función para hacer switch con la bandera privada usar_lib_forma
	 * @param boolean $bandera
	 */
	protected function setUsarLibForma($bandera=true) {
		$this->usar_lib_forma = $bandera;
	}
	/**
	 * Devuelve el valor de la bandera usar_lib_forma
	 * @return boolean
	 */
	public function getUsarLibForma() {
		return $this->usar_lib_forma;
	}
	/**
	 * Regresa una lista de campos ocultos con la información del controlador actualmente desplegado, cuya información se puede obtener a partir de varibles declaradas en ControladorBase.
	 * Sirve para poder ser enviada mediante un formulario a otro controlador y tener esos datos
	 * Campos:
	 *	controlador_fuente: Nombre del controlador actual
	 *	accion_fuente:	Nombre de la acción actual
	 */
	public function getHTMLCamposOcultosBase(){
		$arr_tag = array();
		$arr_tag[] = '<input type="hidden" name="controlador_fuente" value="'.$this->getControlador().'">';
		$arr_tag[] = '<input type="hidden" name="accion_fuente" value="'.$this->getAccion().'">';
		return tag_string($arr_tag);
	}
	/**
	 * Asigna en una variable de sesión un mensaje que puede ser usado o mostrado en otra vista llamada por otro controlador distinto al actual
	 * @param string $mensaje
	 * @param string $nom_var_ses
	 */
	public function setSesionMsjTmp($mensaje, $nom_var_ses="SES_MSG_EXITO") {
		$_SESSION[$nom_var_ses] = $mensaje;
	}
	/**
	 * Obtiene el mensaje almacenado en la variable de sesión usada para guardar mensajes
	 * @param string $nom_var_ses
	 * @return string
	 */
	public function getSesionMsjTmp($nom_var_ses="SES_MSG_EXITO"){
		if(isset($_SESSION[$nom_var_ses])){
			$val_var_ses = $_SESSION[$nom_var_ses];
			unset($_SESSION[$nom_var_ses]);
			return $val_var_ses;
		}else{
			return "";
		}
	}
}
