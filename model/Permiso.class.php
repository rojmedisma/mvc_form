<?php
/**
 * Clase modelo para la consulta de permisos del usuario actual
 * @author Ismael
 *
 */
class Permiso{
    private $arr_permisos;
    public function __construct($cat_usuario_id="") {
        $usuario = new Usuario();
        $usuario->setArrUsuario($cat_usuario_id);
        $arr_usr = $usuario->getArrUsuario();
        $cat_grupo_id = $arr_usr['cat_grupo_id'];
        
        $bd = new BaseDatos();
        $arr_tbl = $bd->getArrDeTabla("grupo", " AND `cat_grupo_id` = '".$cat_grupo_id."' AND `activo` = 1");
        
        $arr_permisos = array();
        foreach ($arr_tbl as $arr_det){
            $arr_permisos[] = $arr_det['cat_permiso_cve'];
        }
        $this->arr_permisos = $arr_permisos;
    }
    /**
     * Devuelve el arreglo de los permisos del usuario actual
     * @return array
     */
    public function getArrPermisos(){
        return $this->arr_permisos;
    }
    /**
     * Indica si el usuario tiene el permiso que se manda como argumento como clave permiso
     * @param string $cat_permiso_cve
     * @return boolean
     */
    public function tiene_permiso($cat_permiso_cve){
        $arr_permisos = $this->getArrPermisos();
        if(in_array($cat_permiso_cve, $arr_permisos)){
            return true;
        }else{
            return false;
        }
    }
	/**
	 * Para cada permiso de cuestionario (Identificados por un prefijo definido), agrega un permiso alias para una identificación más sencilla
	 * @param int $cat_cuestionario_id
	 */
	public function setArrPermisosCuestXAlias($cat_cuestionario_id) {
		$arr_alias = array(
			"ae"=>"escritura",
			"al"=>"lectura",
			"nuevo"=>"nuevo_cuest",
			"aprob"=>"aprobar",
			"nac"=>"ver_todo",
			"asig"=>"ver_asignados",
		);
		$cuet_cve = cuest_cve($cat_cuestionario_id);
		$arr_permisos = $this->arr_permisos;
		foreach($arr_permisos as $nom_permiso){
			if(substr($nom_permiso,0,3) == $cuet_cve){
				$p_sin_prefijo = substr($nom_permiso,4);
				$permiso_alias = (isset($arr_alias[$p_sin_prefijo]))? $arr_alias[$p_sin_prefijo] : $p_sin_prefijo;
				array_push($this->arr_permisos, $permiso_alias);
			}
		}
	}
}