<?php
/**
 * Descripción de CuestFormaControl
 *
 * @author Ismael Rojas
 */
class CuestFormaControl extends CuestBase{
	private $cuestionario_id;
	private $cat_cuest_modulo_id;
	private $es_aprobado = false;
	public object $frm_al3;
	public function __construct() {
		parent::__construct();
		$this->cuestionario_id = (isset($_REQUEST['cuestionario_id']))? $_REQUEST['cuestionario_id'] : "";
		$this->cat_cuest_modulo_id = $this->defineCatCuestModuloId();
	}
	public function inicio() {
		$this->setPaginaDistintivos();
		$this->setUsarLibForma(true);
		$this->defineVista("Tablero.php");
		
		if(!$this->cuestionario_id){
			$this->cuestionario_id = $this->nuevo();
		}
		$cuestionario = new Cuestionario($this->getCatCuestionarioId());
		$cuestionario->setArrRegCuestionario($this->cuestionario_id, false, false);
		$this->arr_cmps_frm = $cuestionario->getArrRegCuestionario();
		
		$inhabilitar = intval($this->getCampoValor('inhabilitar'));
		$this->es_nuevo = ($inhabilitar)? true : false;
		
		$this->setArrHTMLTagLiNavItemCuestFrm();
		
		$this->setFormulario();
		parent::setArrHTMLTagLiNavItem();	//Se crean los items del tablero
	}
	public function actualizar() {
		if(!$this->permiteGuardar()){
			$this->redireccionaErrorAccion('sin_permisos', array("tit_accion"=>"Guardar"));
		}
		$cat_cuest_modulo = new CatCuestModulo($this->getCatCuestionarioId());
		$cat_cuest_modulo->setArrCmpListaTablas($this->cat_cuest_modulo_id);
		$arr_lista_tablas = $cat_cuest_modulo->getArrCmpListaTablas();
		if(empty($arr_lista_tablas)){
			$this->redireccionaErrorAccion("valor_de_campo_vacio", array("tbl_nom"=>"cat_cuest_modulo", "cmp_nom"=>"lista_tablas"));
		}
		$bd = new BaseDatos();
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
		
		$cuestionario = new Cuestionario($this->getCatCuestionarioId());
		if(!$cuestionario->actualizarCuest($arr_cmps, $this->cuestionario_id)){
			$this->redireccionaErrorDeArr($cuestionario->getArr1erError(), true);
		}
		redireccionar('cuestforma','inicio', $this->getArrRedirecForma());
	}
	/**
	 * Devuelve la ruta donde se encuentra el archivo que contiene la forma de cuestionario actual. Se usa dentro de un include_once en la vista view/ALTE3/modulos/Cuest/Forma.php
	 * @return string
	 */
	public function getSubRutaVista() {
		$carpeta = "C".str_pad($this->getCatCuestionarioId(), 2, "0", STR_PAD_LEFT);
		$archivo = "Modulo".str_pad($this->cat_cuest_modulo_id, 2, "0", STR_PAD_LEFT).".php";
		return $carpeta."/".$archivo;
	}
	/**
	 * Regresa una lista de campos ocultos con la información obtenida en el controldador base CuestBase
	 * Sirve para poder ser enviada mediante un formulario a otro controlador y tener esos datos
	 */
	public function getHTMLCamposOcultosCuest(){
		$arr_tag = array();
		$arr_tag[] = '<input type="hidden" name="cat_cuestionario_id" value="'.$this->getCatCuestionarioId().'">';
		$arr_tag[] = '<input type="hidden" name="cat_cuest_modulo_id" value="'.$this->cat_cuest_modulo_id.'">';
		$arr_tag[] = '<input type="hidden" name="cuestionario_id" value="'.$this->cuestionario_id.'">';
		
		return tag_string($arr_tag);
	}
	public function getHTMLIntroduccion() {
		return $this->getHTMLInfoLink('cuest_intro', 'Introducción', $this->getDatoVistaValor('cc_definicion'));
	}
	public function getHTMLInfoLink($tag_id, $titulo, $txt_contenido) {
		$alte3_html = new ALTE3HTML();
		$alte3_html->setHTMLInfoLinkCollapse($tag_id, $titulo, $txt_contenido);
		return $alte3_html->getHTMLContenido();
	}
	public function getHTMLBtnGuardar() {
		if($this->permiteGuardar()){
			$arr_tag = array();
			$arr_tag[] = '<button type="submit" class="btn btn-info"><i class="fas fa-save"></i> Guardar</button>';
			return tag_string($arr_tag);
		}else{
			return '';
		}
	}
	private function nuevo() {
		$cat_usuario_id_rq = (isset($_REQUEST['cat_usuario_id']))? intval($_REQUEST['cat_usuario_id']) : 0;
		$cat_usuario_id = ($cat_usuario_id_rq)? $cat_usuario_id_rq: $this->usuario_dato('cat_usuario_id');
		
		if(!$cat_usuario_id){
			$this->redireccionaError("Cuestionario sin usuario asignado", "No se pudo crear el cuestionario debido a que no se asignó el usuario responsable para su captura");
		}
		
		if(!$this->tienePermiso("nuevo_cuest") || !$this->tienePermiso("escritura")){
			$this->redireccionaErrorAccion('sin_permisos', array('tit_accion'=>'Alta cuestionario'));
		}
		
		$cuetionario = new Cuestionario($this->getCatCuestionarioId());
		return $cuetionario->crearCuest($cat_usuario_id);
	}
	private function setArrHTMLTagLiNavItemCuestFrm(){
		$alte3_html = new ALTE3HTML();
		
		$cat_cuest_modulo = new CatCuestModulo($this->getCatCuestionarioId());
		$cat_cuest_modulo->setArrTblCat(" ORDER BY `cat_cuest_modulo`.`orden` ASC ");
		$arr_cat_cuest_modulo = $cat_cuest_modulo->getArrTbl();
		
		$arr_tag_li_nav_item = array();
		foreach($arr_cat_cuest_modulo as $arr_det_ccm){
			$arr_url_arg = array(
				"cat_cuestionario_id"=>$this->getCatCuestionarioId(),
				"cat_cuest_modulo_id"=>$arr_det_ccm['cat_cuest_modulo_id'],
				"cuestionario_id"=>$this->cuestionario_id,
			);
			$a_contenido = '<i class="far fa-circle nav-icon"></i><p>'.$arr_det_ccm['tit_corto'].'</p>';
			$arr_atrib_usu = ($this->cat_cuest_modulo_id == $arr_det_ccm['cat_cuest_modulo_id'])? array('a_class'=>'nav-link active') : array();
			$alte3_html->setHTMLLiNavItemExt("cuestforma", "inicio", false, $arr_url_arg, false, $a_contenido, $arr_atrib_usu);
			$arr_tag_li_nav_item[] = $alte3_html->getHTMLContenido();
		}
		$tag_li_nav_item = tag_string($arr_tag_li_nav_item);
		
		if($this->es_nuevo){
			$reg_tit_i = 'nav-icon fa fa-fw fa-file';
			$reg_tit_p = 'Nuevo registro';
		}else{
			$reg_tit_i = 'nav-icon fa fa-fw fa-file-alt';
			$reg_tit_p = 'Registro Id: '.$this->cuestionario_id;
		}
		$arr_li_nav_item[] = '<li class="nav-item has-treeview menu-open">';
		$arr_li_nav_item[] = '	<a href="#" class="nav-link active">';
		$arr_li_nav_item[] = '		<i class="'.$reg_tit_i.'"></i>';
		$arr_li_nav_item[] = '		<p>';
		$arr_li_nav_item[] = '			'.$reg_tit_p;
		$arr_li_nav_item[] = '			<i class="right fas fa-angle-left"></i>';
		$arr_li_nav_item[] = '		</p>';
		$arr_li_nav_item[] = '	</a>';
		$arr_li_nav_item[] = '	<ul class="nav nav-treeview">';
		$arr_li_nav_item[] = '		'.$tag_li_nav_item;
		$arr_li_nav_item[] = '	</ul>';
		$arr_li_nav_item[] = '</li>';
		$this->arr_html_tag['li_ni_sb_frm'] = $arr_li_nav_item;
	}
	private function defineCatCuestModuloId() {
		$cat_cuest_modulo_id = (isset($_REQUEST['cat_cuest_modulo_id']))? $_REQUEST['cat_cuest_modulo_id'] : 0;
		if(!$cat_cuest_modulo_id){
			$cat_cuest_modulo = new CatCuestModulo($this->getCatCuestionarioId());
			$cat_cuest_modulo->setCatCuestModuloIdIni();
			$cat_cuest_modulo_id = $cat_cuest_modulo->getCatCuestModuloId();
		}
		if(!$cat_cuest_modulo_id){
			$this->redireccionaError("Dato cat_cuest_modulo_id no identificado", "No se pudo identificar el valor cat_cuest_modulo_id", true);
		}
		return $cat_cuest_modulo_id;
	}
	private function setFormulario(){
		$this->frm_al3 =new FormularioALTE3($this->arr_cmps_frm);
		//$this->frm_al3->asignaValidaciones($arr_validaciones);
		//$this->frm_al3->setLectura($lectura);
		//$this->frm_al3->setUsarDivAgrupar($usar_div_agrupar);
		//$this->frm_al3->setVerNombreCampo($ver_nombre_campo);
	}
	private function permiteGuardar() {
		return ($this->tienePermiso('escritura') && !$this->es_aprobado)? true : false;
	}
	/**
	 * Regresa un arreglo con los parámetros o argumentos necesarios para redireccionarse a la acción forma.
	 * Nota. Éste arreglo debe estar conformado con las variables obtenidas mediante el arreglo $_REQUEST en el constructor, además que dentro del formulario; también deberían estar dentro de los campos usados declarados para frm_cero
	 */
	private function getArrRedirecForma($cat_cuest_modulo_id=""){
		$cat_cuest_modulo_id=($cat_cuest_modulo_id=="")? $this->cat_cuest_modulo_id : $cat_cuest_modulo_id;
		return array(
				'cuestionario_id'=>$this->cuestionario_id,
				'cat_cuestionario_id'=>$this->getCatCuestionarioId(),
				'cat_cuest_modulo_id'=>$cat_cuest_modulo_id
		);
	}
}
