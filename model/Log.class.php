<?php
/**
 * Clase modelo para insertar un registro de log
 * @author Ismael Rojas
 *
 */
class Log extends ModeloBase{
	public function __construct(){
		parent::__construct();
	}
	/**
	 * Crea un registro de log a partir de los argumentos llenados
	 * @param string $cmp_id_nom
	 * @param string $cmp_id_val
	 * @param string $evento
	 * @param string $estatus
	 * @param string $descripcion
	 */
	public function setRegLog($cmp_id_nom, $cmp_id_val, $evento, $estatus, $descripcion){
		$cat_usuario_id = $this->getUsuarioId();
		$arr_insert = array(
				"fecha"=>"CURDATE()",
				"hora"=>"CURTIME()",
				"cat_usuario_id"=>txt_sql($cat_usuario_id),
				"cmp_id_nom"=>txt_sql($cmp_id_nom),
				"cmp_id_val"=>txt_sql($cmp_id_val),
				"evento"=>txt_sql($evento),
				"estatus"=>txt_sql($estatus),
				"descripcion"=>txt_sql($descripcion),
				"remote_addr"=> txt_sql($_SERVER['REMOTE_ADDR'])
		);
		$this->bd->ejecutaQryInsertDeArr($arr_insert, 'log');
	}
}