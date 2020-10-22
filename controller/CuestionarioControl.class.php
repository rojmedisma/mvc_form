<?php
/**
 * Controlador para los cuestionarios
 * Se utiliza en todos los cuestioanarios, ya que la funcionalidad y las acciones son las mismas, lo que cambia es el contenido.
 * @author Ismael Rojas
 *
 */
class CuestionarioControl extends ControladorBase{
	public $tag_campo = null;
	public $permiso;
	public $parametros_JSON;
	public $and_estado="";
	public $ver_res_gen = false;
	public $ver_res_cmpte = false;
	public $ver_semaforo = false;
	private $cat_cuestionario_id;
	private $cuestionario_id;
	private $arr_tbl_cue = array();
	private $arr_tbl_cat_cuest_modulo = array();
	private $cat_cuest_modulo_id=0;
	private $arr_cuest_cmp_def=array();
	private $arr_res_indicador = array();
	private $arr_res_semaforo = array();
	private $validaciones_JSON;
	private $arr_validaciones = array();
	private $arr_txt_validaciones = array();
	private $es_lectura = false;
	private $arr_cmps_cat_cuest_modulo = array();	//Detalle de campos de la tabla cat_cuest_modulo del cat_cuest_modulo_id actual
	private $arr_cat_cuest_modulo_control = array();
	private $arr_usuarios_cap = array();
	public function __construct(){
		$this->cat_cuestionario_id = (isset($_REQUEST['cat_cuestionario_id']))? intval($_REQUEST['cat_cuestionario_id']) : "1";
		$this->cuestionario_id = (isset($_REQUEST['cuestionario_id']))? $_REQUEST['cuestionario_id'] : "";
		$this->cat_cuest_modulo_id = (isset($_REQUEST['cat_cuest_modulo_id']))? $_REQUEST['cat_cuest_modulo_id'] :0;
		
		$this->parametros_JSON = new CampoJSON("json_parametros");	//Clase para el campo json_parametros
		
		
		$this->setArrRegUsuario();	//Se crea el arreglo con el detalle de datos del usuario
		
		$this->permiso = new Permiso();
		$cuet_cve = cuest_cve($this->getCatCuestionarioId());
		$this->setArrPermiso("escritura", $this->permiso->tiene_permiso($cuet_cve.'_ae'));
		$this->setArrPermiso("nuevo_cuest", $this->permiso->tiene_permiso($cuet_cve.'_nuevo'));
		$this->setArrPermiso("aprobar", $this->permiso->tiene_permiso($cuet_cve.'_aprob'));
		$this->setArrPermiso("exportar", $this->permiso->tiene_permiso($cuet_cve.'_exportar'));
		$this->setArrPermiso("borrar", $this->permiso->tiene_permiso($cuet_cve.'_borrar'));
		
		$this->setArrPermiso("ver_todo", $this->permiso->tiene_permiso($cuet_cve.'_nac'));
		$this->setArrPermiso("ver_asignados", $this->permiso->tiene_permiso($cuet_cve.'_asig'));
		
		
		$this->setArrPermiso("ver_cmp_nom", $this->permiso->tiene_permiso('ver_cmp_nom'));
		$this->setArrPermiso("cat_usuario", $this->permiso->tiene_permiso('al_usuario'));
		
	}
	/**
	 * Acción para abrir la vista o consulta de registros de cuestionario
	 */
	public function vista(){
		$this->setPaginaDistintivos();
		if($this->getCatCuestionarioId()!=""){
			//Se limpia cat_cuest_modulo_id para que al abrir en forma, salga al principio
			$this->cat_cuest_modulo_id = 0;
			$this->cuestionario_id = "";
			$cuestionario = new Cuestionario($this->getCatCuestionarioId());
			//$this->setAndCuest();
			
			
			if($this->tienePermiso("ver_todo")){
				$and_c = "";
			}elseif($this->tienePermiso("ver_asignados")){
				$arr_reg_usuario = $this->getArrRegUsuario();
				$cat_usuario_id = $arr_reg_usuario['cat_usuario_id'];
				$and_c = " AND `cat_usuario_id` = '".$cat_usuario_id."' ";
			}else{
				$and_c = " AND FALSE";
			}
			$cuestionario->setArrTblCuestionario($and_c);
			$this->arr_tbl_cue = $cuestionario->getArrTblCuestionario();
			
			$this->setArrUsuariosCap();	//Se genera arreglo de usuarios de captura
			
			
			$nom_arc_vista = strtoupper(cuest_cve($this->getCatCuestionarioId()))."Vista.php";
			$this->defineVista($nom_arc_vista);
		}else{
			$this->redireccionaErrorAccion("sin_arg_cat_cuestionario_id");
		}
	}
	/**
	 * Acción para abrir la forma cuestionario y desplegar toda la funcionalidad necesaria para su captura en caso de ser un cuestionario nuevo, adenás de mostrar la información ya capturada en caso de ser un cuestionario capturado.
	 */
	public function forma(){
		$de_tab = (isset($_REQUEST['de_tab']))? $_REQUEST['de_tab'] : false;	//Valor para identificar cuando se manda llamar la forma desde el tab o pestaña
		$this->setPaginaDistintivos();
		
		//Subfuncion para esta acción forma
		$this->setForma();
		
		//Se obtiene el arreglo de definición de campos de los módulos del cuestionario
		if($this->getCatCuestModuloId()){
			$cuest_cmp_def = new CuestCmpDef($this->getCatCuestionarioId());
			$cuest_cmp_def->setArrRegsXCatCuestModuloId($this->getCatCuestModuloId());
			$this->arr_cuest_cmp_def = $cuest_cmp_def->getArrTbl();
			
		}
		
		//NOTA: En setForma() se define el arreglo arr_cmps_frm (getArrCmpsForm())
		$p_es_modulo_activo = false;
		$arr_validaciones = array();
		if($this->getCuestionarioId()){
			$llave_p_es_modulo_activo = "p_es_mod".$this->getCatCuestModuloId()."_activo";
			$p_es_modulo_activo = $this->parametros_JSON->getValor($llave_p_es_modulo_activo);
			
			if($p_es_modulo_activo){
				$llave_ccm_val = "ccm_id".$this->getCatCuestModuloId();
				$arr_validaciones = (array) $this->validaciones_JSON->getValor($llave_ccm_val);
			}
			
		}else{
			$this->redireccionaErrorAccion("sin_id_cuest");
		}
		$this->arr_validaciones = $arr_validaciones;
		
		//echo "<br>".json_encode($arr_validaciones)."<br>";
		if($this->getCatCuestModuloId()==1){
			$ubica_estado = valorEnArreglo($this->getArrCmpsForm(), 'ubica_estado');
			$this->and_estado = ($ubica_estado!="")? " AND `cat_estado_id` LIKE '".$ubica_estado."' ORDER BY `descripcion` ASC" : "";
		}
		
		//Es lectura: Si la forma se está llamando desde el tab o pestaña y el módulo actual ya está activo y no se presionó el botón de editar
		$es_lectura = ($de_tab && $p_es_modulo_activo)? true : false;
		$this->es_lectura = $es_lectura;
		
		
		$this->tag_campo = new Campos();
		$this->tag_campo->setVerNombreCampo(true);
		$this->tag_campo->setConSelect2(true);
		$this->tag_campo->setValorCampos($this->getArrCmpsForm());
		$this->tag_campo->setLectura($es_lectura);
		if(count($arr_validaciones)){
			$this->tag_campo->setArrValidaciones($arr_validaciones);
			$this->setArrTxtValidaciones();
		}
		
		$nom_arc_vista = strtoupper(cuest_cve($this->getCatCuestionarioId()))."Forma.php";
		$this->defineVista($nom_arc_vista);
	}
	
	/**
	 * Acción para guardar el módulo del cuestionario actual
	 */
	public function guardar(){
		$ccm_siguiente = (isset($_REQUEST['ccm_siguiente']))? intval($_REQUEST['ccm_siguiente']) : "";
		
		if(!$this->getCatCuestModuloId()){
			$this->redireccionaErrorAccion("sin_arg_cat_cuest_modulo_id");
		}
		if($this->tienePermiso("escritura")){
			$bd = new BaseDatos();
			
			$cat_cuest_modulo = new CatCuestModulo($this->getCatCuestionarioId());
			$cat_cuest_modulo->setArrCmpListaTablas($this->getCatCuestModuloId());
			$arr_lista_tablas = $cat_cuest_modulo->getArrCmpListaTablas();
			if(empty($arr_lista_tablas)){
				$this->redireccionaErrorAccion("valor_de_campo_vacio", array("tbl_nom"=>"cat_cuest_modulo", "cmp_nom"=>"lista_tablas"));
			}
			$arr_cmps = array();
			foreach ($arr_lista_tablas as $tabla){
				$arr_cmps_cu = $bd->getArrCmpsTbl($tabla);
				foreach ($arr_cmps_cu as $arr_cmps_cu_det){
					$cmp_nom = $arr_cmps_cu_det['Field'];
					switch($cmp_nom){
						case 'cuestionario_id':
							break;
						default:
							$arr_cmps[$tabla][$cmp_nom] = (isset($_REQUEST[$cmp_nom]))? txt_sql($_REQUEST[$cmp_nom]) : "NULL";
							break;
					}
				}
			}
			//Se guarda el registro actualizando los valores y tablas indicadas en el arreglo $arr_cmps
			$guardar = new Guardar();
			$guardar->setGuardaCuest($arr_cmps, $this->getCatCuestionarioId(), $this->getCuestionarioId());
			$this->cuestionario_id = $guardar->getCmpIdVal();
			
			//Se actualiza el campo json_parametros
			$this->actualizaParametrosJSON($this->getCuestionarioId(), $this->getCatCuestModuloId());
			
			$this->actualizaValidacionesJSON();
		}else{
			$this->redireccionaErrorAccion("sin_permisos", array('tit_accion'=>'Guardar cuestionario'));
		}
		//$nom_arc_vista = strtoupper(cuest_cve($this->getCatCuestionarioId()))."Forma.php";
		
		if($ccm_siguiente && $this->siguienteCatCuestModulo()){
			//Si despues de guardar, se indicó con la bandera ccm_siguiente pasar al siguiente módulo
			$sig_ccm_id = intval($this->getCatCuestModuloId())+1;
			redireccionar('cuestionario','forma', $this->arrRedirecForma($sig_ccm_id));
		}else{
			redireccionar('cuestionario','forma', $this->arrRedirecForma());
		}
		
		
	}
	public function nuevo(){
		$cat_usuario_id = (isset($_REQUEST['cat_usuario_id']))? intval($_REQUEST['cat_usuario_id']) : 0;
		if(!$this->tienePermiso("nuevo_cuest") || !$this->tienePermiso("escritura")){
			$this->redireccionaErrorAccion("sin_permisos", array('tit_accion'=>'Guardar cuestionario'));
		}
		if($this->getCatCuestionarioId()==""){
			$this->redireccionaErrorAccion("sin_arg_cat_cuestionario_id");
		}
		if(!$cat_usuario_id){
			$this->redireccionaError("Cuestionario sin usuario asignado", "No se pudo crear el cuestionario debido a que no se asignó el usuario responsable para su captura", false);
		}
		
		$cat_cuest_modulo = new CatCuestModulo($this->getCatCuestionarioId());
		$cat_cuest_modulo->setArrCmpListaTablas();
		$arr_lista_tablas = $cat_cuest_modulo->getArrCmpListaTablas();
		if(empty($arr_lista_tablas)){
			$this->redireccionaErrorAccion("valor_de_campo_vacio", array("tbl_nom"=>"cat_cuest_modulo", "cmp_nom"=>"lista_tablas"));
		}
		
		//Se guarda el registro actualizando los valores y tablas indicadas en el arreglo $arr_cmps
		$guardar = new Guardar();
		$guardar->setNuevoCuestionario($arr_lista_tablas, $this->getCatCuestionarioId(), $cat_usuario_id);
		$this->cuestionario_id = $guardar->getCmpIdVal();
		redireccionar('cuestionario','forma', $this->arrRedirecForma());
	}
	/**
	 * Acción para mostrar la pestaña o tab de Resultados General
	 */
	public function resultado(){
		$this->ver_res_gen = true;	//Para activar la pestaña o tab de Resultados Generales
		$this->setResultado();
	}
	public function res_cmpte(){
		$this->ver_res_cmpte = true;	//Para activar la pestaña o tab de Respuestas por componente
		$this->setResultado();
	}
	
	private function setResultado(){
		$this->setPaginaDistintivos();
		$this->cat_cuest_modulo_id = 0;	//Se limpia por si las dudas y permita seleccionarse la pestaña Resultados
		
		//Subfuncion para esta acción forma
		$this->setForma();
		
		$indicador = new ResultadoGen();
		$indicador->setArrResReg($this->getCatCuestionarioId(), $this->getArrCmpsForm());
		$arr_res_reg = $indicador->getArrResReg();
		$this->arr_res_indicador = $arr_res_reg;
		
		$nom_arc_vista = strtoupper(cuest_cve($this->getCatCuestionarioId()))."Forma.php";
		$this->defineVista($nom_arc_vista);
	}
	/**
	 * Acción para mostrar la pestaña o tab de Semáforo
	 */
	public function semaforo(){
		$this->setPaginaDistintivos();
		$this->ver_semaforo = true;
		$this->cat_cuest_modulo_id = 0;	//Se limpia por si las dudas y permita seleccionarse la pestaña Semáforo
		
		//Subfuncion para esta acción forma
		$this->setForma();
		
		$semaforo = new Semaforo();
		$semaforo->setArrResSemaforo($this->getArrCmpsForm());
		$this->arr_res_semaforo = $semaforo->getArrResSemaforo();
		
		//echo "<br>".json_encode($this->arr_res_semaforo)."<br>";
		$nom_arc_vista = strtoupper(cuest_cve($this->getCatCuestionarioId()))."Forma.php";
		$this->defineVista($nom_arc_vista);
	}
	/**
	 * Acción para exportar todos los registros de cuestionario
	 */
	public function exportar(){
		$formato = (isset($_REQUEST['formato']))? $_REQUEST['formato'] : "";
		$cuestionario = new Cuestionario($this->getCatCuestionarioId());
		$and_c = " AND `cat_cuestionario_id` = '".$this->getCatCuestionarioId()."' AND `borrar` IS NULL";
		
		
		
		$ahora = date('Ymd_hi');
		$archivo = cuest_cve($this->getCatCuestionarioId()).'_'.$ahora;
		$arr_cmps_excluir = array("cat_estado_id","json_parametros","json_validaciones","m3p2","m3p2_desc");
		switch($formato){
			case 'xls':
				
				header('Content-type: application/vnd.ms-excel');
				header('Content-Disposition: attachment; filename='.$archivo.'.xls');
				header('Pragma: no-cache');
				header('Expires: 0');
				
				$cuestionario->exportarExcel($and_c, $arr_cmps_excluir);
				break;
			case 'csv':
				
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Content-type: text/x-csv');
				header('Content-Disposition: attachment; filename='.$archivo.'.csv');
				
				$cuestionario->exportarCSV($and_c, $arr_cmps_excluir);
				
				break;
		}
		
	}
	/**
	 * Acción para marcar como borrado el cuestionario seleccionado
	 */
	public function borrar(){
		if($this->tienePermiso("borrar")){
			$cuestionario = new Cuestionario($this->getCatCuestionarioId());
			$cuetionario_id = $this->getCuestionarioId();
			$cuestionario->borrar($cuetionario_id);
			$log = new log();
			$log->setRegLog("cuetionario_id", $cuetionario_id, "borrar", "Aviso", "Se borró cuestionario");
			redireccionar('cuestionario','vista',array('cuestionario_id'=>$cuetionario_id));
		}else{
			$this->redireccionaErrorAccion("sin_permisos",array('tit_accion'=>'Borrar cuestionario'));
		}
	}
	/**
	 * Devuelve el valor de la variable <strong>cuestionario_id</strong>
	 * @return integer
	 */
	public function getCuestionarioId(){
		return $this->cuestionario_id;
	}
	/**
	 * Devuelve el valor de la variable <strong>cat_cuestionario_id</strong>
	 * @return string|number
	 */
	public function getCatCuestionarioId(){
		return $this->cat_cuestionario_id;
	}
	/**
	 * Devuelve el valor de la variable que contiene el arreglo de registros de cuestionarios a motrar
	 * @return array
	 */
	public function getArrTblContenido(){
		return $this->arr_tbl_cue;
	}
	
	/**
	 * Devuelve el arreglo de la tabla de módulos por cuestionario
	 * @return array
	 */
	public function getArrTblCatCuestModulo() {
		return $this->arr_tbl_cat_cuest_modulo;
	}
	
	
	/**
	 * Devuelve el arreglo de detalle de campos de la tabla cat_cuest_modulo del id cat_cuest_modulo_id actual
	 * @return array
	 */
	private function getArrCmpsCatCuestModulo(){
		return $this->arr_cmps_cat_cuest_modulo;
	}
	/**
	 * Del arreglo de detalle de campos de cat_cuest_modulo, devuelve el valor del campo indicado en el argumento
	 * @param string $cmp_nom	Nombre del campo
	 * @return string
	 */
	public function getCmpCatCuestModuloVal($cmp_nom){
		$arr_cmps_frm = $this->getArrCmpsCatCuestModulo();
		return valorEnArreglo($arr_cmps_frm, $cmp_nom);
	}
	/**
	 * Devuelve el Id del módulo que en este momento se despliega en la forma
	 * @return integer
	 */
	public function getCatCuestModuloId() {
		return $this->cat_cuest_modulo_id;
	}
	
	/**
	 * Devuelve el valor de definicion del campo especificado en el argumento
	 * @param string $cmp_llave	Nombre del campo de cuestionario pertenciente a un módulo o sección
	 * @param string $cmp_nom	Nombre del campo almacenado en la tabla cues_cmp_def
	 * @return string
	 */
	public function getCuesCmpDef($cmp_llave, $cmp_nom){
		$arr_cuest_cmp_def = $this->arr_cuest_cmp_def;
		
		if(isset($arr_cuest_cmp_def[$cmp_llave][$cmp_nom])){
			return $arr_cuest_cmp_def[$cmp_llave][$cmp_nom];
		}else{
			return "";
		}
	}
	/**
	 * Devuelve el arreglo con el detalle de variables usadas para el cálculo de resultados para las gráficas
	 * @return array
	 */
	public function getArrResIndicador(){
		return $this->arr_res_indicador;
	}
	/**
	 * Devuelve el arreglo con el detalle de variables usadas para el cálculo del valor de semáforo
	 * @return array
	 */
	public function getArrResSemaforo() {
		return $this->arr_res_semaforo;
	}
	/**
	 * Del arreglo arr_res_semaforo, devuelve el valor de la llave indicada en el argumento
	 */
	public function getValorSemaforo($llave){
		$arr_res_semaforo = $this->getArrResSemaforo();
		return valorEnArreglo($arr_res_semaforo, $llave);
	}
	/**
	 * Subfuncion de la acción forma. Contiene el código necesario para el funcionamiento de la vista Forma sin necesidad del despliegue de campos.
	 * Las operaciones definidas en esta función no deben ser las usadas para el despliegue de formulario
	 * Útil para ser llamada por otra acción que no requiera el despliegue del formulario pero es necesario mostrar el resultado en la vista de Formulario, como es el caso de la pestaña de resultados
	 */
	private function setForma(){
		if($this->getCatCuestionarioId()==""){
			$this->redireccionaErrorAccion("sin_arg_cat_cuestionario_id");
		}
		
		$cat_cuest_modulo = new CatCuestModulo($this->getCatCuestionarioId());
		$cat_cuest_modulo->setArrTblCat();
		//Se crea arreglo del contenido de la tabla cat_cuest_modulo para generar las pestañas en la forma
		$arr_tbl_cat_cuest_modulo = $cat_cuest_modulo->getArrTbl();
		$this->arr_tbl_cat_cuest_modulo = $arr_tbl_cat_cuest_modulo;
		$arr_cmps_cat_cuest_modulo = array();
		foreach ($arr_tbl_cat_cuest_modulo as $arr_ccm_det){
			$ccm_id = $arr_ccm_det['cat_cuest_modulo_id'];
			if($ccm_id==$this->getCatCuestModuloId()){
				$arr_cmps_cat_cuest_modulo = $arr_ccm_det;
			}
		}
		$this->arr_cmps_cat_cuest_modulo = $arr_cmps_cat_cuest_modulo;
		
		
		if($this->getCuestionarioId()){
			$arr_cmps_frm = array();
			$cuestionario = new Cuestionario($this->getCatCuestionarioId());
			$cuestionario->setArrCuestionario($this->getCuestionarioId());	//Arreglo con el detalle de todos los campos del registro de cuestionario identificado mediante el argumento cuestionario_id
			$arr_cmps_frm = $cuestionario->getArrCuestionario();
			$this->arr_cmps_frm = $arr_cmps_frm;
			$this->validaciones_JSON = new CampoJSON("json_validaciones");
			$this->validaciones_JSON->setJSONCampo($this->getCuestionarioId());
			
			$this->parametros_JSON->setJSONCampo($this->getCuestionarioId());
			$this->setArrCatCuestModuloControl();
			
		}
		
		
		
	}
	/**
	 * Regresa un arreglo con los parámetros o argumentos necesarios para redireccionarse a la acción forma.
	 * Nota. Éste arreglo debe estar conformado con las variables obtenidas mediante el arreglo $_REQUEST en el constructor, además que dentro del formulario; también deberían estar dentro de los campos usados declarados para frm_cero
	 */
	private function arrRedirecForma($cat_cuest_modulo_id=""){
		$cat_cuest_modulo_id=($cat_cuest_modulo_id=="")? $this->getCatCuestModuloId() : $cat_cuest_modulo_id;
		return array(
				'cuestionario_id'=>$this->getCuestionarioId(),
				'cat_cuestionario_id'=>$this->getCatCuestionarioId(),
				'cat_cuest_modulo_id'=>$cat_cuest_modulo_id
		);
	}
	/**
	 * Se actualiza el campo json_parametros
	 * @param int $cuestionario_id
	 * @param int $cat_cuest_modulo_id
	 */
	private function actualizaParametrosJSON($cuestionario_id, $cat_cuest_modulo_id){
		$this->parametros_JSON->setJSONCampo($cuestionario_id);
		$llave_p_es_modulo_activo = "p_es_mod".$cat_cuest_modulo_id."_activo";
		$this->parametros_JSON->modificaValor($llave_p_es_modulo_activo, 1);
		$this->parametros_JSON->guardar();
	}
	/**
	 * Se actualiza el campo json_validaciones
	 */
	private function actualizaValidacionesJSON(){
		$this->setForma();	//Para que se genere el campo arr_cmps_frm y el objeto validaciones_JSON
		
		if($this->getCatCuestionarioId()==""){
			$this->redireccionaErrorAccion("sin_arg_cat_cuestionario_id");
		}elseif($this->getCatCuestModuloId()==""){
			$this->redireccionaErrorAccion("sin_arg_cat_cuest_modulo_id");
		}
		$arr_cmps_frm = $this->getArrCmpsForm();
		if(empty($arr_cmps_frm)){
			$this->redireccionaError("Arreglo <em>arr_cmps_frm</em> vacío", "Surgió un problema al tratar de obtener el contenido del arreglo con la variable nombre arr_cmps_frm.");
		}
		
		
		$validar = new Validar();
		$validar->serArrReglasDeCuestionario($this->getCatCuestionarioId(), $this->getCatCuestModuloId(), $this->getArrCmpsForm());
		$validar->setArrValidaciones();
		$arr_validaciones = $validar->getArrValidaciones();
		//echo "<br>".json_encode($arr_validaciones)."<br>";
		//die();
		
		
		$llave_ccm_val = "ccm_id".$this->getCatCuestModuloId();
		$this->validaciones_JSON->modificaValor($llave_ccm_val, $arr_validaciones);
		$this->validaciones_JSON->guardar();
	}
	/**
	 * Genera arreglo informativo respecto a los campos que tienen alertas
	 */
	private function setArrTxtValidaciones(){
		$arr_validaciones = $this->arr_validaciones;
		$arr_cuest_cmp_def = $this->arr_cuest_cmp_def;
		$arr_txt_validaciones = array();
		if(count($arr_validaciones) && count($arr_cuest_cmp_def)){
			foreach ($arr_validaciones as $cmp_nom=>$arr_val_det){
				$alerta = (isset($arr_val_det->alerta))? $arr_val_det->alerta : "";
				
				if($alerta!=""){
					$lbl_txt = isset($arr_cuest_cmp_def[$cmp_nom]["lbl_txt"])? $arr_cuest_cmp_def[$cmp_nom]["lbl_txt"] : "[Sin descripción]";
					$arr_txt_validaciones[] = array(
							"txt_pregunta"=>$lbl_txt,
							"cmp_nom"=>$cmp_nom
					);
				}
				
			}
		}
		$this->arr_txt_validaciones = $arr_txt_validaciones;
	}
	/**
	 * Devuelve el arreglo informativo de alertas
	 * @return array
	 */
	public function getArrTxtValidaciones() {
		return $this->arr_txt_validaciones;
	}
	
	public function esModuloActivo($cat_cuest_modulo){
		$p_es_modulo_activo = 0;
		if($cat_cuest_modulo!=""){
			$llave_p_es_modulo_activo = "p_es_mod".$cat_cuest_modulo."_activo";
			$p_es_modulo_activo = $this->parametros_JSON->getValor($llave_p_es_modulo_activo);
		}
		return $p_es_modulo_activo;
	}
	public function esLectura() {
		return $this->es_lectura;
	}
	private function setArrCatCuestModuloControl(){
		$json_validaciones = $this->getCampoValor("json_validaciones");
		$arr_json_validaciones = json_decode($json_validaciones);
		$arr_tbl_cat_cuest_modulo = $this->getArrTblCatCuestModulo();
		
		$arr_cat_cuest_modulo_control = array();
		foreach ($arr_tbl_cat_cuest_modulo as $arr_tccm_det){
			$cat_cuest_modulo_id = $arr_tccm_det['cat_cuest_modulo_id'];
			
			//Total de alertas por módulo
			$tot_alertas = 0;
			$llave_ccm_val = "ccm_id".$cat_cuest_modulo_id;
			if(isset($arr_json_validaciones->$llave_ccm_val)){
				$arr_jv_ccml = (array) $arr_json_validaciones->$llave_ccm_val;
				$tot_alertas = count($arr_jv_ccml);
			}
			$arr_cat_cuest_modulo_control[$cat_cuest_modulo_id] = array(
					"tot_alertas"=>$tot_alertas,
			);
		}
		$this->arr_cat_cuest_modulo_control = $arr_cat_cuest_modulo_control;
	}
	public function getValorCatCuestModuloControl($cat_cuest_modulo_id, $llave){
		$arr_cat_cuest_modulo_control = $this->arr_cat_cuest_modulo_control;
		if(isset($arr_cat_cuest_modulo_control[$cat_cuest_modulo_id])){
			return valorEnArreglo($arr_cat_cuest_modulo_control[$cat_cuest_modulo_id], $llave);
		}else{
			return "";
		}
	}
	public function siguienteCatCuestModulo($cat_cuest_modulo_id=""){
		$cat_cuest_modulo_id = ($cat_cuest_modulo_id=="")? $this->getCatCuestModuloId() : $cat_cuest_modulo_id;
		$arr_cat_cuest_modulo_control = $this->arr_cat_cuest_modulo_control;
		$cat_cuest_modulo_id_sig = $cat_cuest_modulo_id + 1;
		if(isset($arr_cat_cuest_modulo_control[$cat_cuest_modulo_id_sig])){
			return $arr_cat_cuest_modulo_control[$cat_cuest_modulo_id_sig];
		}else{
			return 0;
		}
	}
	/**
	 * Genera arreglo con la lista de usuarios que pueden ser asignados para capturar cuestionarios
	 */
	private function setArrUsuariosCap(){
		$usuario = new Usuario();
		$and = " AND `cat_grupo_id` = 4 AND `activo` = 1 ORDER BY `cat_usuario`.`ap_paterno` ASC, `cat_usuario`.`ap_materno` ASC, `cat_usuario`.`nombre` ASC";
		$usuario->setArrTblCatUsuario($and);
		$this->arr_usuarios_cap = $usuario->getArrTblCatUsuario();
	}
	public function getArrUsuariosCap(){
		return $this->arr_usuarios_cap;
	}
}