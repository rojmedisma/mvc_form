<?php
/**
 * Crea la conexión a la base de datos
 * @author Ismael Rojas
 *
 */
class Conectar{
	/**
	 * Crea y devuelve la conexión a la base de datos
	 * @param string $es_admin	Si se desea hacer la conexión con el usuario registrado en el parámetro global <strong>adm_usr</strong>
	 * @return mysqli	Conexión
	 */
	public function getConexion($es_admin=false){
		global $globales;
		$mysqli = null;
		if(isset($globales)){
			$host=(isset($globales['conexion']['servidor']))? $globales['conexion']['servidor']: 'localhost';
			if($es_admin){
				$username=(isset($globales['conexion']['adm_usr']))? $globales['conexion']['adm_usr']: '';
				$passwd=(isset($globales['conexion']['adm_cve']))? $globales['conexion']['adm_cve']: '';
				if($username=="" || $passwd==""){
					die("En el archivo <strong>config</strong> falta informaci&oacute;n para <strong>adm_usr</strong> o <strong>adm_cve</strong>");
				}
			}else{
				$username=(isset($globales['conexion']['usuario']))? $globales['conexion']['usuario']: '';
				$passwd=(isset($globales['conexion']['clave']))? $globales['conexion']['clave']: '';
			}
			$dbname=(isset($globales['conexion']['siap_mml']))? $globales['conexion']['siap_mml']: '';
			$mysqli = new mysqli($host, $username, $passwd, $dbname);
			if ($mysqli->connect_errno) {
				echo "Fallo al contenctar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
			}
			if($globales['conexion']['codifica_utf8']){
				$mysqli->set_charset("utf8");
			}
		}
		return $mysqli;
	}
}