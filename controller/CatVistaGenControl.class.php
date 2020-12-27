<?php
/**
 * Descripción de TablaConsulControl
 *
 * Controlador para vistas de consulta de registros
 * @author Ismael Rojas
 */
class CatVistaGenControl extends TableroBase{
	
	private string $cat_frm_controlador;
	public function __construct() {
		parent::__constructTablero();
		$this->setPaginaDistintivos();
		$this->setUsarLibVista(true);
		$permiso = new Permiso();
		$this->setArrPermisos($permiso->getArrPermisos());
		$this->defineVista("Tablero.php");
		
		
	}
	/**
	 * Controlador de consulta de registros del catálogo de usuarios
	 */
	public function cat_usuario() {
		$this->arr_param = array(
			'permiso_borrar'=>'borrar-usr',
			'cmp_id_nom'=>'cat_usuario_id',
			'controlador_frm'=>'catfrmgen'
		);
		
		$this->setArrDatoVistaValor('tit_tabla', 'Catálogo de usuarios');
		$cat_usuario = new CatUsuario();
		$cat_usuario->setArrTbl();
		$this->arr_tabla = $cat_usuario->getArrTbl();
		parent::setArrHTMLTagLiNavItem();	//Se crean los items del tablero
	}
	/**
	 * Controlador de consulta de registros del catálogo de grupos
	 */
	public function cat_grupo() {
		$this->arr_param = array(
			'permiso_borrar'=>'borrar-grupo',
			'cmp_id_nom'=>'cat_grupo_id',
			'controlador_frm'=>'catfrmgpo'
		);
		
		$this->setArrDatoVistaValor('tit_tabla', 'Catálogo de grupos');
		$cat_grupo = new CatGrupo();
		$cat_grupo->setArrTbl();
		$this->arr_tabla = $cat_grupo->getArrTbl();
		parent::setArrHTMLTagLiNavItem();	//Se crean los items del tablero
	}
	public function getHTMLBtnAbrir($cmp_id_val) {
		return '<a href="'.define_controlador($this->getParametro('controlador_frm'), $this->getAccion(), false, array($this->getParametro('cmp_id_nom')=>$cmp_id_val)).'" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i> Abrir</a>';
	}
	public function getHTMLBtnBorrar($cmp_id_val) {
		$arr_tag = array();
		if($this->tienePermiso($this->getParametro('permiso_borrar'))){
			$arr_tag[] = '<form class="d-inline frm_borrar" action="'.define_controlador('borrar', 'cuestionario').'" method="post">';
			$arr_tag[] = '	'.$this->getHTMLCamposOcultosBase();
			$arr_tag[] = '	<input type="hidden" name="'.$this->getParametro('cmp_id_nom').'" value="'.$cmp_id_val.'">';
			$arr_tag[] = '	<button type="submit" class="btn btn-danger btn-sm btn_borrar"><i class="fas fa-trash-alt"></i> Borrar</button>';
			$arr_tag[] = '</form>';
		}
		return tag_string($arr_tag);
	}
	public function getHTMLBtnAlta() {
		return '<a href="'.define_controlador($this->getParametro('controlador_frm'), $this->getAccion()).'" class="btn btn-info btn-sm"><i class="fa fa-fw fa-file"></i> Alta registro</a>';
	}
}
