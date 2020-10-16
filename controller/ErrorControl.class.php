<?php
/**
 * Controlador para desplegar una pantalla de error específica
 * @author Ismael Rojas
 *
 */
class ErrorControl extends ControladorBase{
	private $tit_error;
	private $txt_error;
	private $txt_error_comple;
	public function __construct(){
		$this->setPaginaDistintivos();
		$this->setAutentificar(false);
		$this->setMostrarVista('Error.php');
	}
	/**
	 * Devuelve el título del error
	 * @return string
	 */
	public function getTitError(){
		return $this->tit_error;
	}
	/**
	 * Devuelve el texto de la descripción del error
	 * @return string
	 */
	public function getTxtError(){
		return $this->txt_error;
	}
	/**
	 * Acción predeterminada en caso de ser llamado el controlador y no poder identificar el error
	 */
	public function inicio(){
		$this->tit_error= (isset($_REQUEST['tit_error']))? $_REQUEST['tit_error'] : 'Error interno';
		$this->txt_error= (isset($_REQUEST['txt_error']))? $_REQUEST['txt_error'] : 'No se pudo identificar el error';
	}
	/**
	 * Acción que despliega el error al tratar de identificar una ruta de archivo. Esto sucede cuando el nombre del archivo que se obtiene a partir del controlador y usado para también identificar la ruta, no coincide o no existe
	 */
	public function sin_ruta(){
	    $ruta = (isset($_REQUEST['ruta']))? $_REQUEST['ruta'] : '[Vacio]';
		$this->tit_error= 'Error interno';
		$this->txt_error= 'Se produjo un error al intentar identificar la ruta ('.$ruta.') a partir del argumento del controlador. Favor de contactar al administrador del sistema';
	}
	/**
	 * Acción que despliega el error producido tras no encontrar el nombre de la clase indicado a partir del nombre del controlador
	 */
	public function sin_obj(){
		$objeto= (isset($_REQUEST['objeto']))? $_REQUEST['objeto'] : '[Vacio]';
		$this->tit_error= 'Error interno';
		$this->txt_error= 'Se produjo un error al intentar identificar el objeto del controlador con el nombre: "'.$objeto.'". Favor de contactar al administrador del sistema';
	}
	/**
	 * Acción que despliega el error producido al no encontrarse ninguna función o método con el nombre obtenido a a partir del nombre de la acción para la clase indicada en el nombre del controlador
	 */
	public function sin_metodo(){
		$metodo= (isset($_REQUEST['metodo']))? $_REQUEST['metodo'] : '[Vacio]';
		$this->tit_error= 'Error interno';
		$this->txt_error= 'Se produjo un error al intentar identificar el método del controlador con el nombre: "'.$metodo.'". Favor de contactar al administrador del sistema';
	}
	/**
	 * Acción que despliega el error producido al ejecutar un URL incompleto y que no contiene todos los argumentos necesarios para ejecutar la acción requerida
	 */
	public function faltan_args(){
		$argumento= (isset($_REQUEST['argumento']))? $_REQUEST['argumento'] : '[Vacio]';
		$this->tit_error= 'Argumentos insuficientes';
		$this->txt_error= 'El argumento "'.$argumento.'" no pudo ser identificado';
	}
	/**
	 * Acción que despliega el error producido al revisar la información del usuario el el catálogo de usuario y verificar si cuenta con los datos necesarios para poder ingresar al módulo o sección actual
	 */
	public function usr_dato_vacio(){
	    $cmp_desc = (isset($_REQUEST['cmp_desc']))? $_REQUEST['cmp_desc'] : '[Vacio]';
	    $this->tit_error= 'Dato sin llenar en catálogo de usuario';
	    $this->txt_error= 'Para el usuario actual, es requerido que en el catálogo de usuarios tenga información en: '.$cmp_desc;
	}
	/**
	 * Acción que despliega el error producido cuando no se tienen definidos los distintivos o atributos de la página actual. Revisar la función modelo: Distintivos.class.php
	 */
	public function sin_distintivos_pagina() {
		$controlador_d = (isset($_REQUEST['controlador_d']))? $_REQUEST['controlador_d'] : '[Vacio]';
		$accion_d = (isset($_REQUEST['accion_d']))? $_REQUEST['accion_d'] : '[Vacio]';
		$this->tit_error= 'No se han definido los distintivos de la página actual';
		$this->txt_error='Cada página tiene sus distintivos o características, como son el título de la página. Para el controlador "'.$controlador_d.'" y acción "'.$accion_d.'", no existen distintivos';
	}
	/**
	 * Acción que despliega el error producido cuando en la ruta de dirección no se manda el argumento cat_cuestionario_id
	 */
	public function sin_arg_cat_cuestionario_id() {
		$this->tit_error = "Argumento <em>cat_cuestionario_id</em> no identificado";
		$this->txt_error = "Para la ruta previamente especificada, es necesario mandar el argumento <em>cat_cuestionario_id</em>.";
	}
	/**
	 * Acción que despliega el error producido cuando en la ruta de dirección no se manda el argumento cat_cuest_modulo_id
	 */
	public function sin_arg_cat_cuest_modulo_id() {
		$this->tit_error = "Argumento <em>cat_cuest_modulo_id</em> no identificado";
		$this->txt_error = "Para la ruta previamente especificada, es necesario mandar el argumento <em>cat_cuest_modulo_id</em>.";
	}
	
	/**
	 * Acción que despliega el error producido cuando no se tienen los permisos necesarios para realizar la acción ejecutada, también es posible que apareza si la sesión ha caducado.
	 */
	public function sin_permisos() {
		$tit_accion = (isset($_REQUEST['tit_accion']))? $_REQUEST['tit_accion'] : '[Sin Accion]';
		$this->tit_error = "Acción denegada";
		$this->txt_error = "<p>No cuenta con los permisos necearios para realizar la acción: <strong>".$tit_accion."</strong>.</p>";
		$this->txt_error .= "<p>También es posible que la sesión haya caducado. Si cree contar con los permisos necesarios, cierre su sesión, vuelva a ingresar e inténtelo de nuevo.</p>";
	}
	/**
	 * Acción que despliega el error producido cuando el valor del estado se perdió en el cuestionario
	 */
	public function sin_cat_estado_id(){
		$this->tit_error = "Sin valor en Estado";
		$this->txt_error = "Por motivos descosidos, el valor del campo estado se perdió, este dato es heredado a partir del campo estado del usuario que creó el cuestionario.";
	}
	/**
	 * Acción que despliega el error de campo vacío cuando el valor de dicho campo es requerido
	 */
	public function valor_de_campo_vacio(){
		$tbl_nom = (isset($_REQUEST['tbl_nom']))? $_REQUEST['tbl_nom'] : '[Nombre de tabla no identificada ]';
		$cmp_nom = (isset($_REQUEST['cmp_nom']))? $_REQUEST['cmp_nom'] : '[Nombre de campo no identificado]';
		$this->tit_error = "Valor de campo vacío";
		$this->txt_error = "El valor del campo <em>".$cmp_nom."</em> perteneciente a la tabla <em>".$tbl_nom."</em>; se encuentra vacío y se requiere que tenga información.";
	}
	public function sin_id_cuest(){
		$this->tit_error = "Id de cuestionario no identificado";
		$this->txt_error = "No se pudo identificar el Id del cuestionario que se desea abrir";
	}
	/**
	 * Esta función se ejecuta al final del llamado de la respectiva acción de error
	 */
	public function __destruct() {
		$log = new Log();
		$txt_error = "Error: ".$this->tit_error.", Descripción: ".$this->txt_error;
		$log->setRegLog('', '', 'Error', 'Error', $txt_error);
	}
	
}