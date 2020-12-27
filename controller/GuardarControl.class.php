<?php
/**
 * Descripción de GuardarControl
 *
 * @author Ismael Rojas
 */
class GuardarControl extends ControladorBase{
	private $controlador_destino;
	private $accion_destino;
	public function __construct() {
		$this->controlador_destino = isset($_REQUEST['controlador_fuente'])? $_REQUEST['controlador_fuente'] : "";
		$this->accion_destino = isset($_REQUEST['accion_fuente'])? $_REQUEST['accion_fuente'] : "";
		$permiso = new Permiso();
		$this->setArrPermisos($permiso->getArrPermisos());
	}
	public function cat_usuario() {
		$cat_usuario_id = (isset($_REQUEST['cat_usuario_id']))? intval($_REQUEST['cat_usuario_id']) : 0;
		$clave= (isset($_REQUEST['clave']))? $_REQUEST['clave'] : "";
		if(!$this->tienePermiso('ae-usuario')){
			$this->redireccionaErrorAccion('sin_permisos', array('tit_accion'=>'Guardar usuario'));
		}
		$cat_usuario = new CatUsuario();
		$arr_cmps_cu = $cat_usuario->getArrCmpsTbl();
		$arr_cmps = array();
		foreach ($arr_cmps_cu as $arr_cmps_cu_det){
			$cmp_nom = $arr_cmps_cu_det['Field'];
			switch($cmp_nom){
				case 'cat_usuario_id':
				case 'clave':	//No se guarda la clave ingresada, esa se encripta y guarda con la clase Autentificar
				case 'borrar':
					break;
				default:
					$arr_cmps[$cmp_nom] = txt_sql($_REQUEST[$cmp_nom]);
					break;
			}
		}
		$log = new Log();
		$cat_usuario->setGuardarReg($arr_cmps, $cat_usuario_id);
		$cat_usuario_id = $cat_usuario->getCmpIdVal();
		$log->setRegLog('cat_usuario_id', $cat_usuario_id, 'Guardar', 'Aviso', 'Se guardó registro de Catálogo de usuario');
		if($clave!=""){
			$autentificar = new Autentificar();
			$autentificar->setActualizaClave($cat_usuario_id, $clave);
			$log->setRegLog('cat_usuario_id', $cat_usuario_id, 'Guardar', 'Aviso', 'Se actualizó clave en registro de Catálogo de usuario');
		}
		redireccionar($this->controlador_destino, $this->accion_destino, array('cat_usuario_id'=>$cat_usuario_id));
	}
	public function cat_grupo() {
		$cat_grupo_id = (isset($_REQUEST['cat_grupo_id']))? intval($_REQUEST['cat_grupo_id']) : 0;
		if(!$this->tienePermiso('ae-grupo')){
			$this->redireccionaErrorAccion('sin_permisos', array('tit_accion'=>'Guardar grupo'));
		}
		$cat_grupo = new CatN('cat_grupo');
		$arr_cmps_cu = $cat_grupo->getArrCmpsTbl();
		$arr_cmps = array();
		
		foreach($arr_cmps_cu as $arr_cmps_cu_det){
			$cmp_nom = $arr_cmps_cu_det['Field'];
			switch($cmp_nom){
				case 'cat_grupo_id':
				case 'borrar':
					break;
				default:
					$arr_cmps[$cmp_nom] = txt_sql($_REQUEST[$cmp_nom]);
					break;
			}
		}
		$log = new Log();
		$cat_grupo->setGuardarReg($arr_cmps, $cat_grupo_id);
		$cat_grupo_id = $cat_grupo->getCmpIdVal();
		$log->setRegLog('cat_grupo_id', $cat_grupo_id, 'Guardar', 'Aviso', 'Se guardó registro de Catálogo de grupo');
		redireccionar($this->controlador_destino, $this->accion_destino, array('cat_grupo_id'=>$cat_grupo_id));
	}
	
}
