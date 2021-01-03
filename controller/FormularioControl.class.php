<?php
class FormularioControl extends ControladorBase{
	public object $frm_bs4;
	public object $frm_al3;
	private array $arr_atrib_campos=array();
	public array $arr_tmp_options = array();
	private array $arr_reglas_val = array();
	private string $tbl_cuest_nom;
	
	public function forma() {
		$this->tbl_cuest_nom = "cuestionario";
		$this->setArrHTMLTagLiNavItem();
		
		$this->setArrCmpsFrm();
		
		$lectura = false;
		$readonly = false;
		$usar_div_agrupar = true;
		$ver_nombre_campo = false;
		$this->arr_atrib_campos = array(
			"p1"=>array(
				"readonly"=>$readonly,
			),
			"p2"=>array(
				"readonly"=>$readonly,
			),
			"p3"=>array(
				"readonly"=>$readonly,
			),
			"p4"=>array(
				"readonly"=>$readonly,
			),
			"p5"=>array(
				"readonly"=>$readonly,
			),
			"p6"=>array(
				"readonly"=>$readonly,
			),
			"p7"=>array(
				"readonly"=>$readonly,
			),
			"p8"=>array(
				"readonly"=>$readonly,
				"con_select2"=>true
			),
			"p9r1"=>array(
				"readonly"=>$readonly,
			),
			"p9r2"=>array(
				"readonly"=>$readonly,
			),
			"p9r3"=>array(
				"readonly"=>$readonly,
			),
			"p10r1"=>array(
				"readonly"=>$readonly,
				"div_group_class"=>"form-check-inline"
			),
			"p10r2"=>array(
				"readonly"=>$readonly,
				"div_group_class"=>"form-check-inline"
			),
			"p11"=>array(
				"readonly"=>$readonly,
			),
			"p12"=>array(
				"readonly"=>$readonly,
				"div_group_class"=>"form-check-inline"
			),
			"p13"=>array(
				"readonly"=>$readonly,
			),
			
		);
		//$this->arr_atrib_campos = array();
		
		$this->arr_tmp_options = array(
			array('id_val'=>1, 'desc_val'=>'Valor 1'),
			array('id_val'=>2, 'desc_val'=>'Valor 2'),
			array('id_val'=>3, 'desc_val'=>'Valor 3'),
			array('id_val'=>4, 'desc_val'=>'Valor 4'),
			array('id_val'=>5, 'desc_val'=>'Valor 5'),
			array('id_val'=>6, 'desc_val'=>'Valor 6'),
			array('id_val'=>7, 'desc_val'=>'Valor 7'),
			array('id_val'=>8, 'desc_val'=>'Valor 8'),
		);
		
		$this->setArrReglasVal();
		$valida_cuest = new ValidarCuest($this->arr_cmps_frm);
		$valida_cuest->setArrValidaciones($this->arr_reglas_val);
		$arr_validaciones = $valida_cuest->getArrValidaciones();
		
		
		$this->frm_al3 = new FormularioALTE3($this->arr_cmps_frm);
		$this->frm_al3->asignaValidaciones($arr_validaciones);
		$this->frm_al3->setLectura($lectura);
		$this->frm_al3->setUsarDivAgrupar($usar_div_agrupar);
		$this->frm_al3->setVerNombreCampo($ver_nombre_campo);
		
		$this->defineVista('Forma.php');
	}
	public function forma_propiedades() {
		$this->tbl_cuest_nom = "cuest_prop";
		$this->setArrHTMLTagLiNavItem();
		
		$this->setArrCmpsFrm();
		$this->frm_al3 = new FormularioALTE3($this->arr_cmps_frm);
		$this->frm_al3->setConSelect2(true);
		$this->defineVista('Forma.php');
	}
	public function guardar(){
		$controlador_origen = (isset($_REQUEST['controlador_fuente']))? $_REQUEST['controlador_fuente'] : '';
		$accion_origen = (isset($_REQUEST['accion_fuente']))? $_REQUEST['accion_fuente'] : '';
		if($accion_origen==""){
			$this->redireccionaError("Acción origen no definida", "Declarar dentro del formulario el método <strong>getHTMLCamposOcultosBase</strong>");
		}
		switch (strtolower($accion_origen)){
			case 'forma':				$this->tbl_cuest_nom = 'cuestionario'; break;
			case 'forma_propiedades':	$this->tbl_cuest_nom = 'cuest_prop'; break;
			default:	$this->tbl_cuest_nom = 'cuestionario'; break;
		}
		$bd = new BaseDatos();
		$arr_cmps_cu = $bd->getArrCmpsTbl($this->tbl_cuest_nom);
		foreach ($arr_cmps_cu as $arr_cmps_cu_det){
			$cmp_nom = $arr_cmps_cu_det['Field'];
			switch($cmp_nom){
				case $this->tbl_cuest_nom.'_id':
					break;
				default:
					$arr_cmps[$cmp_nom] = (isset($_REQUEST[$cmp_nom]))? txt_sql($_REQUEST[$cmp_nom]) : "NULL";
					break;
			}
		}
		
		$guardar = new Guardar();
		$guardar->setGuardaCatalogo($arr_cmps, $this->tbl_cuest_nom, '1');
		
		redireccionar($controlador_origen,$accion_origen);
	}
	public function getArrAtributoCmp(string $cmp_id_nom):array{
		$arr_atrib_campos = $this->arr_atrib_campos;
		if(isset($arr_atrib_campos[$cmp_id_nom])){
			return $arr_atrib_campos[$cmp_id_nom];
		}else{
			return array();
		}
	}
	private function setArrReglasVal(){
		//$arr_cmps_frm = $this->getArrCmpsForm();
		//extract($arr_valor, EXTR_OVERWRITE);
		
		$arr_reglas_val = array();
		$arr_reglas_val['p1'] = array('regla'=>'requerido');
		$arr_reglas_val['p2'] = array('regla'=>'requerido');
		$arr_reglas_val['p3'] = array('regla'=>'requerido');
		$arr_reglas_val['p4'] = array('regla'=>'requerido');
		$arr_reglas_val['p5'] = array('regla'=>'requerido');
		$arr_reglas_val['p6'] = array('regla'=>'requerido');
		$arr_reglas_val['p7'] = array('regla'=>'requerido');
		
		
		$this->arr_reglas_val = $arr_reglas_val;
	}
	private function setArrHTMLTagLiNavItem(){
		$arr_li_nav_item = array();
		$alte3_html = new ALTE3HTML();
		if($this->tienePermiso('cat_usuario')){
			$alte3_html->setHTMLLiNavItem('cat_usuario', 'vista', 'Catálogo de usuarios');
			$arr_li_nav_item[] = $alte3_html->getHTMLContenido();
		}
		$alte3_html->setHTMLLiNavItem('formulario', 'forma_propiedades', 'Mas propiedades en Formulario');
		$arr_li_nav_item[] = $alte3_html->getHTMLContenido();
		$alte3_html->setHTMLLiNavItem('tblcampos', 'inicio', 'Generar tabla de campos');
		$arr_li_nav_item[] = $alte3_html->getHTMLContenido();
		$alte3_html->setHTMLLiNavItem('pruebas', 'inicio', 'Pruebas');
		$arr_li_nav_item[] = $alte3_html->getHTMLContenido();
		
		$this->arr_html_tag['li_nav_item'] = $arr_li_nav_item;
	}
	private function setArrCmpsFrm() {
		$bd = new BaseDatos();
		$arr_tbl =  $bd->getArrDeTabla($this->tbl_cuest_nom, " AND `".$this->tbl_cuest_nom."_id` = 1");
		$arr_cmps = $arr_tbl[0];
		$this->arr_cmps_frm = $arr_cmps;
	}
}