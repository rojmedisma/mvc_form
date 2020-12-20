<?php
/**
 * Descripción de CuestVistaControl
 *
 * @author Ismael Rojas
 */
class CuestVistaControl extends CuestBase{
	private $arr_tbl_cuestionario = array();
	public function inicio() {
		$this->setPaginaDistintivos();
		$this->setUsarLibVista(true);
		
		
		$cuestionario = new Cuestionario($this->getCatCuestionarioId());
		$cuestionario->setArrTblCuestionario("", true);
		$this->arr_tbl_cuestionario = $cuestionario->getArrTblCuestionario();
		
		$this->defineVista("Tablero.php");
		parent::setArrHTMLTagLiNavItem();	//Se crean los items del tablero
	}
	/**
	 * Regresa el arreglo que contiene el detalle formado por todas las tablas pertenecientes al cuestionario actual 
	 * @return array
	 */
	public function getArrTblCuestionario() {
		return $this->arr_tbl_cuestionario;
	}
	/**
	 * Regresa los botones que se muestran en la vista en la columna Opciones
	 * @param string $cuestionario_id
	 * @return string
	 */
	public function getHTMLBotones($cuestionario_id) {
		$arr_tag = array();
		$arr_tag[] = '<a href="'.define_controlador('cuestforma', 'inicio', false, array('cat_cuestionario_id'=>$this->getCatCuestionarioId(), 'cuestionario_id'=>$cuestionario_id)).'" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i> Abrir</a>';
		if($this->tienePermiso("borrar_usr")){
			$arr_tag[] = '<form class="d-inline frm_borrar" action="'.define_controlador('borrar', 'cuestionario').'" method="post">';
			$arr_tag[] = '	'.$this->getHTMLCamposOcultosBase();
			$arr_tag[] = '	<input type="hidden" name="reg_id" value="'.$cuestionario_id.'">';
			$arr_tag[] = '	<button type="submit" class="btn btn-danger btn-sm btn_borrar"><i class="fas fa-trash-alt"></i> Borrar</button>';
			$arr_tag[] = '</form>';
		}
		return tag_string($arr_tag);
	}
}
