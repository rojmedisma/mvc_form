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
    private function getArrPermisos(){
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
}