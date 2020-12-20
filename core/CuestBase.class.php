<?php
/**
 * Descripción de CuestBase
 *
 * @author Ismael Rojas
 */
class CuestBase extends TableroBase{
	protected $cat_cuestionario_id;
	protected $permiso;
	protected $cat_cuestionario;
	public function __construct() {
		parent::__constructTablero();
		$this->cat_cuestionario_id = (isset($_REQUEST['cat_cuestionario_id']))? intval($_REQUEST['cat_cuestionario_id']) : "";
		if($this->cat_cuestionario_id==""){
			$this->redireccionaErrorAccion("sin_arg_cat_cuestionario_id");
		}
		$this->cat_cuestionario = new CatCuestionario();
		$this->permiso = new Permiso();
		$this->permiso->setArrPermisosCuestXAlias($this->cat_cuestionario_id);
		$this->setArrPermisos($this->permiso->getArrPermisos());
		
		$this->setConMenuLateralFijo(true);
		$this->setArrHTMLTagLiNavItemCuest();
		$this->setArrDatoVistaDeCatCuest();
	}
	/**
	 * Regresa una lista de campos ocultos con información del cuestionario actual
	 * Sirve para poder ser enviada mediante un formulario a otro controlador y tener esos datos
	 */
	public function getHTMLCamposOcultosBase(){
		$arr_tag = array();
		$arr_tag[] = '<input type="hidden" name="controlador_fuente" value="'.$this->getControlador().'">';
		$arr_tag[] = '<input type="hidden" name="accion_fuente" value="'.$this->getAccion().'">';
		return tag_string($arr_tag);
	}
	/**
	 * Devuelve el valor de la variable cat_cuestionario_id
	 * @return string|number
	 */
	public function getCatCuestionarioId(){
		return $this->cat_cuestionario_id;
	}
	/**
	 * Asigna los valores necesarios del registro de la tabla cat_cuestionario al arreglo de datos para la vista
	 */
	private function setArrDatoVistaDeCatCuest(){
		$this->cat_cuestionario->setArrReg($this->getCatCuestionarioId());
		$arr_reg_cat_c = $this->cat_cuestionario->getArrReg();
		$cat_cuestionario_desc = valorEnArreglo($arr_reg_cat_c, 'descripcion');
		$this->setArrDatoVistaValor('cat_cuestionario_desc', $cat_cuestionario_desc);
		$this->setArrDatoVistaValor('cc_definicion', valorEnArreglo($arr_reg_cat_c, 'definicion'));
	}
}
