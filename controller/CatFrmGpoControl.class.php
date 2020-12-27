<?php
/**
 * Descripción de CatFrmGpoControl
 * Controlador para el formulario Catálogo de grupo
 *
 * @author Ismael Rojas
 */
class CatFrmGpoControl extends TableroBase{
	public object $frm_al3;
	public object $frm_sel;
	private int $cat_grupo_id;
	private string $ver_vista;
	private int $cat_cuestionario_id;
	private string $html_aviso_cuest_corrector="";
	public function __construct() {
		parent::__constructTablero();
		$permiso = new Permiso();
		$this->setArrPermisos($permiso->getArrPermisos());
		
		$this->cat_grupo_id = (isset($_REQUEST['cat_grupo_id']))? intval($_REQUEST['cat_grupo_id']) : 0;
		$this->ver_vista = (isset($_REQUEST['ver_vista']))? $_REQUEST['ver_vista'] : '';
		$this->cat_cuestionario_id = (isset($_REQUEST['cat_cuestionario_id']))? intval($_REQUEST['cat_cuestionario_id']) : 1;
	}
	/**
	 * Controlador de formulario para el Catálogo de grupos
	 */
	public function cat_grupo() {
		$this->setPaginaDistintivos();
		$this->setUsarLibForma(true);
		$this->setUsarLibVista(true);
		$this->defineVista("Tablero.php");
		
		$this->setArrDatoVistaValor('cat_grupo_id', $this->cat_grupo_id);
		$this->setArrDatoVistaValor('tit_forma', 'Catálogo de grupos');
		$this->setArrDatoVistaValor('ver_vista', $this->ver_vista);
		if($this->cat_grupo_id){
			$this->cargarFrmGrupo();
			$this->cargarVistaPermiso();
			if($this->ver_vista=='cuestionario' && $this->cat_cuestionario_id){
				$this->revisaPermisosCuest();
			}
			$this->es_nuevo = false;
		}
		//Campos para el formuario de grupo
		$this->frm_al3 = new FormularioALTE3($this->arr_cmps_frm);
		//Formulario para el campo de selección de cuestionario
		$this->frm_sel = new FormularioALTE3(array("cat_cuestionario_id"=>$this->cat_cuestionario_id));
		parent::setArrHTMLTagLiNavItem();	//Se crean los items del tablero
	}
	public function activar() {
		$cat_permiso_cve = (isset($_REQUEST['cat_permiso_cve']))? $_REQUEST['cat_permiso_cve'] : '';
		$grupo = new Grupo();
		$grupo->activar_permiso($this->cat_grupo_id, $cat_permiso_cve);
		$arr_url_arg = array(
			"cat_grupo_id"=>$this->cat_grupo_id,
			"ver_vista"=>$this->ver_vista,
			"cat_cuestionario_id"=>$this->cat_cuestionario_id,
		);
		redireccionar('catfrmgpo', 'cat_grupo', $arr_url_arg);
	}
	public function corrige_lista() {
		$cat_permiso = new CatPermiso();
		$cat_permiso->corrigeListaPermCuest($this->cat_cuestionario_id);
		
		$arr_url_arg = array(
			"cat_grupo_id"=>$this->cat_grupo_id,
			"ver_vista"=>$this->ver_vista,
			"cat_cuestionario_id"=>$this->cat_cuestionario_id,
		);
		redireccionar('catfrmgpo', 'cat_grupo', $arr_url_arg);
	}
	/**
	 * Genera la información necesario para desplegar en el formulario de Catálogo de grupos, como el arreglo arr_cmps_frm
	 * @param int $cat_grupo_id
	 */
	private function cargarFrmGrupo() {
		$cat_grupo_id = $this->cat_grupo_id;
		$cat_grupo = new CatGrupo();
		$cat_grupo->setArrReg($cat_grupo_id);
		$this->arr_cmps_frm = $cat_grupo->getArrReg();
		
	}
	private function cargarVistaPermiso(){
		$cat_grupo_id = $this->cat_grupo_id;
		$ver_vista = $this->ver_vista;
		//Variables para mostrar activa la pestaña definida de vista permisos
		if($ver_vista=="cuestionario"){
			$this->setArrDatoVistaValor('activa_cuest', ' active');
			$and_c_per = $this->getAndCuest();
			
		}else{
			$this->setArrDatoVistaValor('activa_gen', ' active');
			$and_c_per = " AND `tipo` LIKE 'g' "; 
		}
		//Arreglo de permisos
		$cat_permiso = new CatPermiso();
		
		$cat_permiso->setArrTbl($and_c_per." ORDER BY `orden` ASC ");
		$arr_cat_permiso = $cat_permiso->getArrTbl();
		
		//Arreglo de permisos por grupo
		$grupo = new Grupo();
		$grupo->setArrTblGrupoDeCatGpoId($cat_grupo_id);
		$arr_permisos_gpo = $grupo->getArrTbl();
		
		$arr_tabla = array();
		foreach($arr_cat_permiso as $arr_det_permiso){
			$cat_permiso_cve = $arr_det_permiso['cat_permiso_cve'];
			$activo = isset($arr_permisos_gpo[$cat_permiso_cve]['activo'])? $arr_permisos_gpo[$cat_permiso_cve]['activo'] : '';
			
			$arr_tabla[$cat_permiso_cve] = array_merge($arr_det_permiso, array(
				'activo'=>$activo,
				'html_btn_activo'=> htmlentities($this->getHTMBtnActivo($cat_permiso_cve, $activo))
				)
			);
		}
		
		$this->arr_tabla = $arr_tabla;
	}
	private function getAndCuest(){
		if($this->cat_cuestionario_id){
			$tipo = cuest_cve($this->cat_cuestionario_id);
			$and_cuest = " AND `tipo` LIKE '".$tipo."' ";
		}else{
			$and_cuest = " AND FALSE ";
		}
		return $and_cuest;
	}
	private function getHTMBtnActivo($cat_permiso_cve, $activo){
		
		if(intval($activo)){
			$tag_btn_activo = '<button type="submit" class="btn btn-primary btn-sm">Activado</button>';
		}else{
			$tag_btn_activo = '<button type="submit" class="btn btn-default btn-sm">Desactivado</button>';
		}
		
		$arr_tag = array();
		$arr_tag[] = '<form class="d-inline frm_activar" action="'.define_controlador('catfrmgpo', 'activar').'" method="post">';
		$arr_tag[] = '	'.$this->getHTMLCamposOcultosSubmit();
		$arr_tag[] = '	<input type="hidden" name="cat_permiso_cve" value="'.$cat_permiso_cve.'">';
		$arr_tag[] = '	'.$tag_btn_activo;
		$arr_tag[] = '</form>';
		return tag_string($arr_tag);
	}
	private function getHTMLCamposOcultosSubmit(){
		$arr_tag = array();
		$arr_tag[] = $this->getHTMLCamposOcultosBase();
		$arr_tag[] = '<input type="hidden" name="cat_grupo_id" value="'.$this->cat_grupo_id.'">';
		$arr_tag[] = '<input type="hidden" name="ver_vista" value="'.$this->ver_vista.'">';
		$arr_tag[] = '<input type="hidden" name="cat_cuestionario_id" value="'.$this->cat_cuestionario_id.'">';
		return tag_string($arr_tag);
	}
	private function revisaPermisosCuest(){
		$cat_permiso = new CatPermiso();
		$cat_permiso->setEsListaValPermCuest($this->cat_cuestionario_id);
		
		if(!$cat_permiso->getEsListaValPermCuest()){
			$this->setHTMLAvisoCuestCorrector(1);
		}
		
	}
	private function setHTMLAvisoCuestCorrector($tipo_aviso){
		if($tipo_aviso==1){
			$accion = 'corrige_lista';
			$titulo = 'Aviso! Lista de permisos incompleta';
			$desc = 'La lista de permisos para el cuestionario seleccionado no coincide con la lista de permisos de los otros cuestionarios. Presione el botón <strong>Corregir problema</strong> para solucionarlo. Gracias.';
			$tag_info_adic = '<p class="text-info small">La corrección aplicará para todos los grupos, debido a esto, no será necesario hacer la corrección por grupo.</p>';
		}
		
		$arr_tag = array();
		$arr_tag[] = '<form class="d-inline frm_aviso_cuest" action="'.define_controlador('catfrmgpo', $accion).'" method="post">';
		$arr_tag[] = '	'.$this->getHTMLCamposOcultosSubmit();
		$arr_tag[] = '	<div class="callout callout-warning">';
		$arr_tag[] = '		<h5>'.$titulo.'</h5>';
		$arr_tag[] = '		<p>'.$desc.'</p>';
		$arr_tag[] = '		<p><button type="submit" class="btn btn-outline-warning">Corregir problema</button></p>';
		$arr_tag[] = '		'.$tag_info_adic;
		$arr_tag[] = '	</div>';
		$arr_tag[] = '</form>';
		$this->html_aviso_cuest_corrector = tag_string($arr_tag);
	}
	public function getHTMLAvisoCuestCorrector(){
		return $this->html_aviso_cuest_corrector;
	}
}
