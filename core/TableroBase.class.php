<?php
/**
 * Descripción de TableroBase
 *
 * @author Ismael Rojas
 */
class TableroBase extends ControladorBase{
	/**
	 * Función propia del proyecto siap_igei
	 * Constructor para el funcionamiento de las opciones del tablero
	 */
	protected function __constructTablero(){
		$this->setArrRegUsuario();	//Se crea el arreglo con el detalle de datos del usuario
		$this->setConMenuLateralFijo(true);
	}
	/**
	 * Para el menú lateral fijo, llama las funciones que crean las entradas de menú
	 * NOTA: En las funciones que son acciones, siempre se debe llamar al final, debido a que requiere de todos los valores previamente calculados en la función acción
	 */
	protected function setArrHTMLTagLiNavItem() {
		$this->setArrHTMLTagLiNavItemTablero();
		$this->setArrHTMLTagLiNavItemCat();
	}
	/**
	 * Opciones del menú lateral fijo izquierdo para el Tablero
	 */
	protected function setArrHTMLTagLiNavItemTablero(){
		$arr_li_nav_item = array();
		
		$alte3_html = new ALTE3HTML();
		$alte3_html->setHTMLLiNavItem(CONTROLADOR_DEFECTO, ACCION_DEFECTO, '<i class="nav-icon fas fa-home"></i>&nbsp;<p>Inicio</p>', 
			($this->getControlador()==CONTROLADOR_DEFECTO && $this->getAccion() == ACCION_DEFECTO)? array('a_class'=>'nav-link active') : array()
		);
		$arr_li_nav_item[] = $alte3_html->getHTMLContenido();
		
		$this->arr_html_tag['li_ni_sb_tablero'] = $arr_li_nav_item;
	}
	/**
	 * Opciones de consulta de registros de tipo catálogo del menú lateral fijo izquierdo para el Tablero
	 */
	protected function setArrHTMLTagLiNavItemCat(){
		$cat_usuario_id = (isset($_REQUEST['cat_usuario_id']))? intval($_REQUEST['cat_usuario_id']) : 0;
		$cat_grupo_id = (isset($_REQUEST['cat_grupo_id']))? intval($_REQUEST['cat_grupo_id']) : 0;
		$alte3_html = new ALTE3HTML();
		$alte3_html->setArrHTMLTagLiNavItemCat('cat_usuario_id', $cat_usuario_id, $this->getControlador(), $this->getAccion(), 'tablaconsulta', 'cat_usuario', 'catforma', 'cat_usuario', $this->es_nuevo, 'fas fa-user', 'Usuarios');
		$tag_lnic_cat_usuario = $alte3_html->getHTMLContenido();
		$alte3_html->setArrHTMLTagLiNavItemCat('cat_grupo_id', $cat_grupo_id, $this->getControlador(), $this->getAccion(), 'tablaconsulta', 'cat_grupo', 'catforma', 'cat_grupo', $this->es_nuevo, 'fas fa-users', 'Grupos');
		$tag_lnic_cat_grupo = $alte3_html->getHTMLContenido();
		$this->arr_html_tag['li_ni_sb_cat'] = array(
			$tag_lnic_cat_usuario,
			$tag_lnic_cat_grupo
		);
	}
	/**
	 * Opciones del menú lateral fijo izquierdo para el Tablero
	 */
	protected function setArrHTMLTagLiNavItemCuest(){
		$cat_cuestionario_id = (isset($_REQUEST['cat_cuestionario_id']))? intval($_REQUEST['cat_cuestionario_id']) : "";
		$arr_li_nav_item = array();
		
		$alte3_html = new ALTE3HTML();
		$arr_li_nav_item[] = $alte3_html->getHTMLContenido();
		$arr_li_nav_item[] = '<li class="nav-header">CUESTIONARIO</li>';
		$alte3_html->setHTMLLiNavItemExt('cuestvista', 'inicio', false, 
				array("cat_cuestionario_id"=>$cat_cuestionario_id), 
				false, 
				'<i class="nav-icon fas fa-list-alt"></i>&nbsp;<p>Consulta</p>', 
				($this->getControlador()=='cuestvista' && $this->getAccion() == 'inicio')? array('a_class'=>'nav-link active') : array()
		);
		
		$arr_li_nav_item[] = $alte3_html->getHTMLContenido();
		
		$this->arr_html_tag['li_ni_sb_cuest'] = $arr_li_nav_item;
	}
}
