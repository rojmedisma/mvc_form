<?php
/**
 * Descripción de CatPermiso
 *
 * @author Ismael Rojas
 */
class CatPermiso extends ModeloBase{
	private array $arr_cve_permisos_cuest = array();
	private bool $es_lista_val_perm_cuest;
	private string $and_tipo_cuest;
	public function __construct(){
		parent::__construct();
		$this->tbl_nom = "cat_permiso";
		$this->cmp_id_nom = "cat_permiso_cve";
		$this->and_tipo_cuest = " AND `borrar` IS NULL AND `tipo` LIKE 'c__' ";
	}
	/**
	 * Usando el campo llave "cat_permiso_cve" pero sin el prefijo identificador de cuestionario "cN_" para obtener la lista de permisos creados para los cuestionarios, 
	 * se valida que la lista de permisos del cuestionario identificado en el argumento con cat_cuestionario_id, 
	 * contenga todos los permisos definidos en el resto de cuestionarios, permitiendo así hacer saber cuando un nuevo permiso creado no fue asignado a todos los cuestionarios 
	 * existentes en el sistema.
	 * @param int $cat_cuestionario_id
	 */
	public function setEsListaValPermCuest($cat_cuestionario_id) {
		$arr_result = $this->getArrPermisosAgregar($cat_cuestionario_id);
		$es_lista_val_perm_cuest = true;
		if(count($arr_result)){
			$es_lista_val_perm_cuest = false; 
		}
		$this->es_lista_val_perm_cuest = $es_lista_val_perm_cuest;
	}
	/**
	 * Devuelve el valor de la bandera es_lista_val_perm_cuest
	 * @return bool
	 */
	public function getEsListaValPermCuest() {
		return $this->es_lista_val_perm_cuest;
	}
	private function getArrPermisosAgregar($cat_cuestionario_id){
		$this->setArrCvePermisosCuest();
		$arr_cve_per_cuest = $this->getArrCvePermisosCuest();
		$this->setArrCvePermisosCuest($cat_cuestionario_id);
		$arr_cve_per_cuest_n = $this->getArrCvePermisosCuest();
		
		return array_diff($arr_cve_per_cuest, $arr_cve_per_cuest_n);
	}
	public function corrigeListaPermCuest($cat_cuestionario_id) {
		$arr_per_agregar = $this->getArrPermisosAgregar($cat_cuestionario_id);
		
		if(!count($arr_per_agregar)){
			return false;
		}
		foreach($arr_per_agregar as $per_agregar){
			$qry_permiso_copiar = "SELECT * FROM `".$this->bd->getBD()."`.`".$this->tbl_nom."` WHERE `".$this->cmp_id_nom."` LIKE '____".$per_agregar."' ".$this->and_tipo_cuest." ORDER BY `orden` DESC LIMIT 1;";
			$rs = $this->bd->ejecutaQry($qry_permiso_copiar);
			$row = $rs->fetch_assoc();
			
			$arr_cmps = array(
				"`".$this->cmp_id_nom."`" => txt_sql(cuest_cve($cat_cuestionario_id)."-".$per_agregar),
				"`tipo`" => txt_sql(cuest_cve($cat_cuestionario_id)),
				"`tit_corto`" => txt_sql($row["tit_corto"]),
				"`descripcion`" => txt_sql($row["descripcion"]),
				"`orden`" => txt_sql($row["orden"]),
				"`borrar`" => "NULL"
			);
			$this->bd->ejecutaQryInsertDeArr($arr_cmps, $this->tbl_nom);
		}
		$this->reordenar();
	}
	/**
	 * Define el arreglo con la lista única de permisos de cat_permiso_cve (sin el prefijo identificador de cuestionario) creados para los cuestionarios
	 * @param int $cat_cuestionario_id
	 */
	private function setArrCvePermisosCuest($cat_cuestionario_id="") {
		$and_tipo = ($cat_cuestionario_id)? " AND `tipo` LIKE '".cuest_cve($cat_cuestionario_id)."' " : "";
		$query = "SELECT DISTINCT SUBSTRING(`".$this->cmp_id_nom."`, 5) AS  `permiso_cuest_dist` FROM `".$this->bd->getBD()."`.`".$this->tbl_nom."` ";
		$query .= " WHERE 1 ".$this->and_tipo_cuest." ".$and_tipo." ORDER BY `permiso_cuest_dist`  DESC";
		$rs = $this->bd->ejecutaQry($query);
		$arr_cve_per = array();
		while($row = $rs->fetch_row()){
			$arr_cve_per[] = $row[0];
		}
		$this->arr_cve_permisos_cuest = $arr_cve_per;
	}
	/**
	 * Devuelve el contenido del arreglo arr_cve_permisos_cuest
	 * @return array
	 */
	private function getArrCvePermisosCuest(){
		return $this->arr_cve_permisos_cuest;
	}
}
