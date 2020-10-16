<?php
/**
 * Clase modelo para todas las plantillas de campos usados en los formularios. Cada método de campo regresa un stringo con los tags necesarios para desplegar el campo requerido
 * @author Ismael
 *
 */
class Campos extends Ayuda{
	private $arr_valor = array();
	private $lectura = false;
	private $arr_alertas = array();
	private $con_select2 = true;   //Campos select con las propiedades de la clase select2
	private $ver_nombre_campo = true;
	private $arr_validaciones = array();
	/**
	 * Modifica el arreglo que contiene todos los valores de todos los campos necesarios en la forma actual, arreglo enviado a través del argumento
	 * @param array $arr_valor
	 */
	public function setValorCampos($arr_valor){
		$this->arr_valor = $arr_valor;
	}
	/**
	 * Modifica el arreglo que contiene todos los mensajes de validación categorizados por el nombre del campo, arreglo enviado a través del argumento
	 * @param array $arr_validaciones
	 */
	public function setArrValidaciones($arr_validaciones){
	    $this->arr_validaciones = $arr_validaciones;
	}
	/**
	 * Devuelve el arreglo que que contiene todos los mensajes de validación categorizados por el nombre del campo
	 * @return array
	 */
	private function getArrValidaciones(){
	    return $this->arr_validaciones;
	}
	/**
	 * Devuelve el valor del campo especificado en el argumento, el dato es obtenido del arreglo <strong>arr_valor</strong>
	 * @param string $cmp_id_nom	Nombre del campo
	 * @return string
	 */
	public function getValor($cmp_id_nom){
		$arr_valor = $this->arr_valor;
		if(isset($arr_valor[$cmp_id_nom])){
			return $this->arr_valor[$cmp_id_nom];
		}else{
			return '';
		}
	}
	/**
	 * Modifica la variable que indica si los campos a ser desplegados van estar o no en modo lectura, su valor se toma como valor predeterminado, ya que puede ser modificado mediante el arreglo de argumentos dentro de cada declaración de campo.
	 * @param boolean $lectura
	 */
	public function setLectura($lectura){
		$this->lectura = $lectura;
	}
	/**
	 * Este metodo se usuaba para otra plantilla bootstrap
	 * @param array $arr_alertas
	 * @ignore
	 */
	public function setArrAlertas($arr_alertas){
		$this->arr_alertas = $arr_alertas;
	}
	/**
	 * Este metodo se usuaba para otra plantilla bootstrap
	 * @param string $cmp_id_nom	Nombre del campo
	 * @return string
	 * @ignore
	 */
	private function getAlerta($cmp_id_nom){
		$arr_alertas = $this->arr_alertas;
		if(isset($arr_alertas[$cmp_id_nom])){
			return $arr_alertas[$cmp_id_nom];
		}else{
			return '';
		}
		
	}
	/**
	 * Modifica la variable que indica si se va a utilizar la clase Select2 de AdminLTE, dicha clase viene con un buscar integrado en el campo
	 * @param string $bandera
	 */
	public function setConSelect2($bandera){
	    $this->con_select2 = $bandera;
	}
	/**
	 * Devuelve el valor de la variable que indica si se va a utilizar la clase Select2 de AdminLTE
	 * @return boolean
	 */
	public function getConSelect2(){
	    return $this->con_select2;
	}
	/**
	 * Devuelve el valor de la variable que indica si los campos a ser desplegados van estar o no en modo lectura
	 * @return boolean
	 */
	private function getLectura(){
		return $this->lectura;
	}
	/**
	 * Modifica la variable que indica si se va a mostrar o no el nombre del campo debajo del campo y así poder tener la nomenclatura del cuestionario
	 * @param boolean $ver_nombre_campo
	 */
	public function setVerNombreCampo($ver_nombre_campo){
	    $this->ver_nombre_campo = $ver_nombre_campo;
	}
	/**
	 * Devuelve la variable que indica si se va a mostrar o no el nombre del campo debajo del campo
	 * @return boolean
	 */
	private function getVerNombreCampo(){
	    return $this->ver_nombre_campo;
	}
	/**
	 * Devuelve el tag para los campos creados a partir de un campo de texto
	 * @param string $cmp_id_nom	Nombre del campo
	 * @param array $arr_atrib_usu	Arreglo con todos los atributos específicos para el campo
	 * @param string $tipo_campo
	 * @return string
	 */
	private function getInputText($cmp_id_nom, $arr_atrib_usu, $tipo_campo){
		$lectura = (isset($arr_atrib_usu['lectura']))? $arr_atrib_usu['lectura'] : $this->getLectura();
		if($lectura){
			return $this->cmpTextoLectura($cmp_id_nom, $arr_atrib_usu);
		}else{
			$arr_atrib = $this->defineAtributos($cmp_id_nom, $arr_atrib_usu, $tipo_campo);
			$arr_tag = array();
			$arr_tag[] = (isset($arr_atrib['lbl_txt']))? $this->label($arr_atrib['lbl_txt'], $cmp_id_nom, $arr_atrib_usu) : '';
			$arr_tag[] = '	<input type="text" name="'.$cmp_id_nom.'" id="'.$cmp_id_nom.'" class="'.$arr_atrib['class'].'" value="'.$arr_atrib['value'].'">';
			$arr_tag[] = $arr_atrib['tag_note_error'];
			return $this->div_form_group(tag_string($arr_tag), $arr_atrib);
		}
	}
	/**
	 * Devuelve el tag del campo tipo texto
	 * @param string $cmp_id_nom	Nombre del campo
	 * @param array $arr_atrib_usu	Arreglo con todos los atributos específicos para el campo
	 * @return string
	 */
	public function cmpTexto($cmp_id_nom, $arr_atrib_usu=array()){
		return $this->getInputText($cmp_id_nom, $arr_atrib_usu, "texto");
	}
	/**
	 * Devuelve el tag del campo oculto
	 * @param string $cmp_id_nom	Nombre del campo
	 * @param array $arr_atrib_usu	Arreglo con todos los atributos específicos para el campo
	 * @return string
	 */
	public function cmpOculto($cmp_id_nom, $arr_atrib_usu=array()){
		$arr_atrib = $this->defineAtributos($cmp_id_nom, $arr_atrib_usu, "oculto");
		$arr_tag = array();
		$arr_tag[] = '<input type="hidden" id="'.$cmp_id_nom.'" name="'.$cmp_id_nom.'" value="'.$arr_atrib['value'].'">';
		return tag_string($arr_tag);
	}
	/**
	 * Devuelve el tag del campo de texto, pero en modo lectura
	 * @param string $cmp_id_nom	Nombre del campo
	 * @param array $arr_atrib_usu	Arreglo con todos los atributos específicos para el campo
	 * @return string
	 */
	public function cmpTextoLectura($cmp_id_nom, $arr_atrib_usu=array()){
		$arr_atrib = $this->defineAtributos($cmp_id_nom, $arr_atrib_usu, "texto_lectura");
		$arr_tag = array();
		$arr_tag[] = (isset($arr_atrib['lbl_txt']))? $this->label($arr_atrib['lbl_txt'], $cmp_id_nom, $arr_atrib_usu) : '';
		$arr_tag[] = $this->textoLectura($arr_atrib['value']);
		$arr_tag[] = '<input type="hidden" name="'.$cmp_id_nom.'" id="'.$cmp_id_nom.'" value="'.$arr_atrib['value'].'">';
		$arr_tag[] = $arr_atrib['tag_note_error'];
		return $this->div_form_group(tag_string($arr_tag), $arr_atrib);
	}
	/**
	 * Devuelve el tag del campo numérico, junto con todas sus propiedades necesarias
	 * @param string $cmp_id_nom	Nombre del campo
	 * @param int $decimales
	 * @param array $arr_atrib_usu	Arreglo con todos los atributos específicos para el campo
	 * @return string
	 */
	public function cmpNum($cmp_id_nom, $decimales, $arr_atrib_usu=array()){
		if(!is_numeric($decimales)){
			die($this->getTagError("cmpNum", "Argumento decimales no es numérico",true));
		}
		$arr_atrib = array_merge($arr_atrib_usu,array("decimales"=>$decimales));
		return $this->getInputText($cmp_id_nom, $arr_atrib, "num");
	}
	/**
	 * Devuelve el tag del campo para fecha, permitiendo mostrar a modo de selección la fecha a través de un calendario
	 * @param string $cmp_id_nom	Nombre del campo
	 * @param array $arr_atrib_usu	Arreglo con todos los atributos específicos para el campo
	 * @return string
	 */
	public function cmpFecha($cmp_id_nom, $arr_atrib_usu=array()){
		$arr_atrib = array_merge($arr_atrib_usu,array("frm_group_class"=>"input-group date"));
		return $this->getInputText($cmp_id_nom, $arr_atrib, "fecha");
	}
	/**
	 * Devuelve el tago del campo tipo password
	 * @param string $cmp_id_nom	Nombre del campo
	 * @param array $arr_atrib_usu	Arreglo con todos los atributos específicos para el campo
	 * @return string
	 */
	public function cmpContrasenia($cmp_id_nom, $arr_atrib_usu=array()){
		$lectura = (isset($arr_atrib_usu['lectura']))? $arr_atrib_usu['lectura'] : $this->getLectura();
		if($lectura){
			$arr_atrib = $this->defineAtributos($cmp_id_nom, $arr_atrib_usu, "texto_lectura");
			$arr_tag = array();
			$arr_tag[] = (isset($arr_atrib['lbl_txt']))? $this->label($arr_atrib['lbl_txt'], $cmp_id_nom, $arr_atrib_usu) : '';
			$arr_tag[] = $this->cmpOculto($cmp_id_nom);
			return $this->div_form_group(tag_string($arr_tag), $arr_atrib);
		}else{
			$arr_atrib = $this->defineAtributos($cmp_id_nom, $arr_atrib_usu, 'contrasenia');
			$arr_tag = array();
			$arr_tag[] = (isset($arr_atrib['lbl_txt']))? $this->label($arr_atrib['lbl_txt'], $cmp_id_nom, $arr_atrib_usu) : '';
			$arr_tag[] = '	<input type="password" name="'.$cmp_id_nom.'" id="'.$cmp_id_nom.'" class="'.$arr_atrib['class'].'" value="'.$arr_atrib['value'].'">';
			$arr_tag[] = $arr_atrib['tag_note_error'];
			return $this->div_form_group(tag_string($arr_tag), $arr_atrib);
		}
	}
	/**
	 * Devuelve el tag del campo tipo combo, obteniendo las opciones del combo mediante la tabla <strong>cat_sub_cat</strong>, especificando en los argumentos el sub-catálogo de opciones a desplegar
	 * @param string $cmp_id_nom	Nombre del campo
	 * @param string $cat_nombre	Nombre del sub-catálogo dentro de la tabla <strong>cat_sub_cat</strong>
	 * @param array $arr_atrib_usu	Arreglo con todos los atributos específicos para el campo
	 * @return string
	 */
	public function cmpSelectDeSubCat($cmp_id_nom, $cat_nombre, $arr_atrib_usu=array()){
		$and_tbl = " AND `cat_nombre` LIKE '".$cat_nombre."' ORDER BY `orden` ASC ";
		return $this->cmpSelectDeTbl($cmp_id_nom, 'cat_sub_cat', 'opc_id', 'opc_descripcion', $and_tbl, $arr_atrib_usu);
	}
	/**
	 * Devuelve el tag del campo tipo combo, obteniendo las opciones del combo mediante una tabla especificada en los argumentos, además de otros datos necesarios para identificar el campo y el fitrado de la información 
	 * @param string $cmp_id_nom	Nombre del campo
	 * @param string $tbl_nom	Nombre de la tabla
	 * @param string $id_val_nom	Nombre del campo que se va a tomar como Id
	 * @param string $desc_val_nom	Nombre del campo que se a a usar como la descripción de las opciones
	 * @param string $and_tbl	Variable que contiene la sentencia query con el filtro de busqueda dentro de la tabla
	 * @param array $arr_atrib_usu	Arreglo con todos los atributos específicos para el campo
	 * @return string
	 */
	public function cmpSelectDeTbl($cmp_id_nom, $tbl_nom, $id_val_nom, $desc_val_nom, $and_tbl, $arr_atrib_usu=array()){
		$db = new BaseDatos();
		$arr_tbl = $db->getArrDeTabla($tbl_nom, $and_tbl, $id_val_nom);
		
		$arr_opt = array();
		foreach ($arr_tbl as $id_val=>$arr_det){
			$desc_val = (isset($arr_det[$desc_val_nom]))? $arr_det[$desc_val_nom] : "";
			$es_esp = (isset($arr_det['es_esp']))? $arr_det['es_esp'] : "";
			$arr_opt[] = array("id_val"=>$id_val, "desc_val"=>$desc_val, "es_esp"=>$es_esp);
		}
		
		$arr_atrib_usu['arr_options'] = $arr_opt;
		return $this->cmpSelect($cmp_id_nom, $arr_atrib_usu);
	}
	/**
	 * Devuelve el tag del campo tipo combo, se usa como base para el resto de los métodos que regresan un combo, los cuales mandan todas las especificaciones de sus argumentos dentro del arreglo <strong>arr_atrib_usu</strong> 
	 * @param string $cmp_id_nom	Nombre del campo
	 * @param array $arr_atrib_usu	Arreglo con todos los atributos específicos para el campo
	 * @return string
	 */
	public function cmpSelect($cmp_id_nom, $arr_atrib_usu=array()){
		$lectura = (isset($arr_atrib_usu['lectura']))? $arr_atrib_usu['lectura'] : $this->getLectura();
		$cmp_desc_nom = $cmp_id_nom."_desc";
		
		if($lectura){
			return $this->cmpSelectLectura($cmp_id_nom, $arr_atrib_usu);
		}else{
			$arr_atrib = $this->defineAtributos($cmp_id_nom, $arr_atrib_usu, "select");
			$tag_options = tag_string($arr_atrib['arr_tag_options']);
			$arr_tag = array();
			$arr_tag[] = (isset($arr_atrib['lbl_txt']))? $this->label($arr_atrib['lbl_txt'], $cmp_id_nom, $arr_atrib_usu) : '';
			$arr_tag[] = $this->cmpOculto($cmp_desc_nom);
			$arr_tag[] = '	<select name="'.$cmp_id_nom.'" id="'.$cmp_id_nom.'" class="'.$arr_atrib['class'].'">';
			$arr_tag[] = '		'.$tag_options;
			$arr_tag[] = '	</select>';
			$arr_tag[] = $arr_atrib['tag_note_error'];
			return $this->div_form_group(tag_string($arr_tag), $arr_atrib);
		}
	}
	/**
	 * Devuelve el tag del campo tipo combo, pero en modo lectura
	 * @param string $cmp_id_nom	Nombre del campo
	 * @param array $arr_atrib_usu	Arreglo con todos los atributos específicos para el campo
	 * @return string
	 */
	public function cmpSelectLectura($cmp_id_nom, $arr_atrib_usu=array()){
		$cmp_desc_nom = $cmp_id_nom."_desc";
		$arr_atrib = $this->defineAtributos($cmp_id_nom, $arr_atrib_usu, "texto_lectura");
		
		$arr_atrib_desc = $this->defineAtributos($cmp_desc_nom, $arr_atrib_usu, "texto_lectura");
		
		$arr_tag = array();
		$arr_tag[] = $this->cmpTextoLectura($cmp_desc_nom, $arr_atrib_desc);
		$arr_tag[] = '<input type="hidden" name="'.$cmp_id_nom.'" id="'.$cmp_id_nom.'" value="'.$arr_atrib['value'].'">';
		return tag_string($arr_tag);
	}
	/**
	 * Devuelve el tag del campo textarea
	 * @param string $cmp_id_nom	Nombre del campo
	 * @param array $arr_atrib_usu	Arreglo con todos los atributos específicos para el campo
	 * @return string
	 */
	public function cmpTextArea($cmp_id_nom, $arr_atrib_usu=array()){
		$lectura = (isset($arr_atrib_usu['lectura']))? $arr_atrib_usu['lectura'] : $this->getLectura();
		if($lectura){
			return $this->cmpTextAreaLectura($cmp_id_nom, $arr_atrib_usu);
		}else{
			$arr_atrib = $this->defineAtributos($cmp_id_nom, $arr_atrib_usu, "textarea");
			$arr_tag = array();
			$arr_tag[] = (isset($arr_atrib['lbl_txt']))? $this->label($arr_atrib['lbl_txt'], $cmp_id_nom, $arr_atrib_usu) : '';
			$arr_tag[] = '	<textarea rows="'.$arr_atrib['rows'].'" name="'.$cmp_id_nom.'" id="'.$cmp_id_nom.'" class="'.$arr_atrib['class'].'">'.$arr_atrib['value'].'</textarea>';
			$arr_tag[] = $arr_atrib['tag_note_error'];
			
			return $this->div_form_group(tag_string($arr_tag), $arr_atrib);
		}
	}
	/**
	 * Devuelve el tag del campo textarea, pero en modo lectura
	 * @param string $cmp_id_nom	Nombre del campo
	 * @param array $arr_atrib_usu	Arreglo con todos los atributos específicos para el campo
	 * @return string
	 */
	private function cmpTextAreaLectura($cmp_id_nom, $arr_atrib_usu=array()){
		$arr_atrib = $this->defineAtributos($cmp_id_nom, $arr_atrib_usu, "texto_lectura");
		$arr_tag = array();
		$arr_tag[] = (isset($arr_atrib['lbl_txt']))? $this->label($arr_atrib['lbl_txt'], $cmp_id_nom, $arr_atrib_usu) : '';
		$arr_tag[] = nl2br($this->textoLectura($arr_atrib['value']));
		$arr_tag[] = '<textarea name="'.$cmp_id_nom.'" id="'.$cmp_id_nom.'" style="display:none">'.$arr_atrib['value'].'</textarea>';
		$arr_tag[] = $arr_atrib['tag_note_error'];
		return $this->div_form_group(tag_string($arr_tag), $arr_atrib);
	}
	/**
	 * Devuelve el tag del campo para adjuntar
	 * @param string $cmp_id_nom	Nombre del campo
	 * @param array $arr_atrib_usu	Arreglo con todos los atributos específicos para el campo
	 * @return string
	 */
	public function cmpAdjunto($cmp_id_nom, $arr_atrib_usu=array()){
		$lectura = (isset($arr_atrib_usu['lectura']))? $arr_atrib_usu['lectura'] : $this->getLectura();
		if($lectura){
			return $this->cmpAdjuntoLectura($cmp_id_nom, $arr_atrib_usu);
		}else{
			$value = (isset($arr_atrib['value']))? $arr_atrib['value'] : "";
			$arr_atrib = $this->defineAtributos($cmp_id_nom, $arr_atrib_usu, "adjunto");
			$arr_tag = array();
			$arr_tag[] = '<div class="'.$arr_atrib['frm_group_class'].'">';
			$arr_tag[] = '	'.(isset($arr_atrib['lbl_txt']))? $this->label($arr_atrib['lbl_txt'], $cmp_id_nom.'_f', $arr_atrib_usu) : '';
			$arr_tag[] = '	<div class="input-group">';
			$arr_tag[] = '		<div class="custom-file">';
			$arr_tag[] = '			<input type="file" id="'.$cmp_id_nom.'_f" name="'.$cmp_id_nom.'_f" value="">';
			$arr_tag[] = '			<label class="custom-file-label" for="'.$cmp_id_nom.'_f">Seleccionar archivo</label>';
			$arr_tag[] = '		</div>';
			$arr_tag[] = '	</div>';
			$arr_tag[] = '</div>';
			$arr_tag[] = '<input type="hidden" name="'.$cmp_id_nom.'" id="'.$cmp_id_nom.'" value="'.$value.'">';
			return tag_string($arr_tag);
		}
		
	}
	/**
	 * Devuelve el tag del campo para adjuntar, pero en modo lectura
	 * @param string $cmp_id_nom	Nombre del campo
	 * @param array $arr_atrib_usu	Arreglo con todos los atributos específicos para el campo
	 * @return string
	 */
	private function cmpAdjuntoLectura($cmp_id_nom, $arr_atrib_usu=array()){
		$arr_atrib = $this->defineAtributos($cmp_id_nom, $arr_atrib_usu, "texto_lectura");
		$arr_tag = array();
		$arr_tag[] = (isset($arr_atrib['lbl_txt']))? $this->label($arr_atrib['lbl_txt'], $cmp_id_nom, $arr_atrib_usu) : '';
		$arr_tag[] = $arr_atrib['tag_note_error'];
		return tag_string($arr_tag);
	}
	/**
	 * Devuelve el tag del campo tipo checkbox
	 * @param string $cmp_id_nom	Nombre del campo
	 * @param string $texto	Texto del campo
	 * @param array $arr_atrib_usu	Arreglo con todos los atributos específicos para el campo
	 * @return string
	 */
	public function cmpCheckbox($cmp_id_nom, $texto="", $arr_atrib_usu=array()){
		if(!is_string($texto)){
			die($this->getTagError("cmpCheckbox", "Segundo argumento no es texto",true));
		}
		$lectura = (isset($arr_atrib_usu['lectura']))? $arr_atrib_usu['lectura'] : $this->getLectura();
		if($lectura){
			return $this->cmpCheckboxLectura($cmp_id_nom, $texto, $arr_atrib_usu);
		}else{
			$arr_atrib = $this->defineAtributos($cmp_id_nom, $arr_atrib_usu, "checkbox");
			$arr_tag = array();
			$arr_tag[] = (isset($arr_atrib['lbl_txt']))? $this->label($arr_atrib['lbl_txt'], $cmp_id_nom, $arr_atrib_usu) : '';
			$arr_tag[] = '<input name="'.$cmp_id_nom.'" id="'.$cmp_id_nom.'_h" type="hidden" value="'.$arr_atrib['unchecked_val'].'">';
			$arr_tag[] = '<div class="form-check" id="div_'.$cmp_id_nom.'">';
			$arr_tag[] = '		<input type="checkbox" class="form-check-input" name="'.$cmp_id_nom.'" id="'.$cmp_id_nom.'" value="'.$arr_atrib['value'].'" '.$arr_atrib['checked'].' >';
			$arr_tag[] = '		<label class="form-check-label">'.$texto.'</label>';
			$arr_tag[] = '</div>';
			return $this->div_form_group(tag_string($arr_tag), $arr_atrib);
		}
	}
	/**
	 * Devuelve el tag del campo tipo checkbox, pero en modo lectura
	 * @param string $cmp_id_nom	Nombre del campo
	 * @param string $texto	Texto del campo
	 * @param array $arr_atrib_usu	Arreglo con todos los atributos específicos para el campo
	 * @return string
	 */
	private function cmpCheckboxLectura($cmp_id_nom, $texto, $arr_atrib_usu=array()){
		$arr_atrib = $this->defineAtributos($cmp_id_nom, $arr_atrib_usu, "checkbox_lectura");
		if($this->getValor($cmp_id_nom)==$arr_atrib['checked_val']){
			$tag_chk_ico = '<i class="fa fa-fw fa-check-square-o"></i>&nbsp;';
		}else{
			$tag_chk_ico = '<i class="fa fa-fw fa-square-o"></i>&nbsp;';
		}
		$arr_tag = array();
		$arr_tag[] = (isset($arr_atrib['lbl_txt']))? $this->label($arr_atrib['lbl_txt'],'',$arr_atrib_usu) : '';
		$arr_tag[] = '<div>'.$this->textoLectura($tag_chk_ico.$texto).'</div>';
		$arr_tag[] = '<input type="hidden" name="'.$cmp_id_nom.'" id="'.$cmp_id_nom.'" value="'.$arr_atrib['value'].'">';
		$arr_tag[] = $arr_atrib['tag_note_error'];
		return $this->div_form_group(tag_string($arr_tag), $arr_atrib);
	}
	/**
	 * Devuelve el tag para mostrar el valor del campo en modo lectura
	 * @param string $texto
	 * @return string
	 */
	public function textoLectura($texto){
		$arr_tag = array();
		$arr_tag[] = '<p class="text-navy">'.$texto.'</p>';
		return tag_string($arr_tag);
	}
	/**
	 * Devuelve el tag usado para mostrar el título del campo
	 * @param string $texto
	 * @param string $for	Nombre del campo al que hace referencia
	 * @return string
	 */
	public function label($texto, $for="", $arr_atrib_usu=array()){
		$class_usu = (isset($arr_atrib_usu['lbl_class']))? ' '.$arr_atrib_usu['lbl_class'] : '';

		$arr_tag = array();
		$tag_for = ($for!="")? ' for="'.$for.'"' : '';
		$arr_tag[] = '<label class="label-control'.$class_usu.'"'.$tag_for.'>'.$texto.'</label>';
		return tag_string($arr_tag);
	}
	/**
	 * Devuelve el tag que encierra a todo tipo de campo
	 * @param string $tag_campo
	 * @param string $arr_atrib	Arreglo de atributos del campo
	 * @return string
	 */
	private function div_form_group($tag_campo, $arr_atrib){
		$arr_tag = array();
		$tag_nom_cmp = "";
		$cmp_id_nom = $arr_atrib['cmp_id_nom'];
		
		if($cmp_id_nom==""){
		    die($this->getTagError("div_form_group", "Argumento cmp_id_nom viene vacío",true));
		}
		
		if($arr_atrib['ver_nombre_campo']){
		    //Nota: en la clase tiene la propiedad display:none
		    $tag_nom_cmp = '<p class="campo_nombre">'.$cmp_id_nom.'</p>';
		}
		$tag_alerta = (isset($arr_atrib['tag_alerta']))? $arr_atrib['tag_alerta'] : '';
		
		if($arr_atrib['usar_div_group']){
			$arr_tag[] = '<div class="'.$arr_atrib['frm_group_class'].'">';
			$arr_tag[] = '	'.$tag_campo;
			$arr_tag[] = $tag_nom_cmp;
			$arr_tag[] = $tag_alerta;
			$arr_tag[] = '</div>';
		}else{
			$arr_tag[] = $tag_campo;
		}
		return tag_string($arr_tag);
	}
	/**
	 * Devuelve un arreglo con la lista de opciones a desplegar en el campo combo
	 * @param string $cmp_id_nom	Nombre del campo
	 * @param string $arr_atrib_usu
	 * @return array
	 */
	private function defineSelectOptions($cmp_id_nom, $arr_atrib_usu){
		$arr_tag_options = array('<option value="" data-desc_val="" data-esp_val="">[Seleccionar]</option>');
		
		$valor_act = $this->getValor($cmp_id_nom);
		if(isset($arr_atrib_usu['arr_options'])){
			foreach($arr_atrib_usu['arr_options'] as $id=>$arr_detalle){
				$selected = ($arr_detalle['id_val']== $valor_act && $valor_act!="")? 'selected="selected"' : '';
				$arr_tag_options[] = '<option value="'.$arr_detalle['id_val'].'" data-desc_val="'.$arr_detalle['desc_val'].'" data-es_esp="'.$arr_detalle['es_esp'].'" '.$selected.'>'.$arr_detalle['desc_val'].'</option>';
			}
		}elseif(isset($arr_atrib_usu['qry_options'])){
		    /*
			$query = new Query();
			$arr_options = array();
			$qry = $arr_atrib_usu['qry_options'];
			$query->setQuery($qry);
			$rs = $query->eject();
			while($rw = $rs->fetch_object()){
				$arr_options[$rw->id_val] = array('desc_val'=>$rw->desc_val);
			}
			$rs->close();
			$arr_atrib_usu['arr_options'] = $arr_options;
			$arr_tag_options = $this->defineSelectOptions($cmp_id_nom, $arr_atrib_usu);
			*/
			
		}
		return $arr_tag_options;
	}
	/**
	 * Devuelve un arreglo con todas las especificaciones del campo a desplegar, usadas en el método principal que regresa el tag del campo
	 * @param string $cmp_id_nom	Nombre del campo
	 * @param array $arr_atrib_usu	Arreglo con todos los atributos específicos para el campo
	 * @param string $tipo_campo	Las diferentes categorías para identificar a los diferentes tipos de campo usados en la clase
	 * @return array
	 */
	private function defineAtributos($cmp_id_nom, $arr_atrib_usu, $tipo_campo){
	    if(!is_array($arr_atrib_usu)){
	        die($this->getTagError("arr_atrib_usu", "No es arreglo",true));
	    }
	    $arr_atrib = $arr_atrib_usu;
		
	    
	    if(!isset($arr_atrib['cmp_id_nom']))	$arr_atrib['cmp_id_nom'] = $cmp_id_nom;
		if(!isset($arr_atrib['usar_div_group']))	$arr_atrib['usar_div_group'] = true;
		if(!isset($arr_atrib['frm_group_class']))	$arr_atrib['frm_group_class'] = 'form-group';
		if(!isset($arr_atrib['ver_nombre_campo']))	$arr_atrib['ver_nombre_campo'] = $this->getVerNombreCampo();
		
		$arr_validaciones = $this->getArrValidaciones();
		
		if(isset($arr_validaciones[$cmp_id_nom]->alerta) && $arr_validaciones[$cmp_id_nom]->alerta!=""){
			$alerta = $arr_validaciones[$cmp_id_nom]->alerta;
		    $arr_atrib['frm_group_class'] = $arr_atrib['frm_group_class'].' has-error';
		    //Si únicamente se quiere marcar el campo, pero no mostrar mensaje, en la alerta viene el texto [sin_desc]. Se usa para la regla de seleccionar al menos una opción
		    $arr_atrib['tag_alerta'] = ($alerta!="[sin_desc]")? '<span class="help-block">'.$alerta.'</span>' :'';
		}else{
		    $arr_atrib['tag_alerta'] = '';
		}
		
		
		$arr_atrib['tag_note_error'] = '';
		if($this->getAlerta($cmp_id_nom) != ""){
			if(isset($arr_atrib['lbl_input_class'])){
				$arr_atrib['lbl_input_class'] = $arr_atrib['lbl_input_class'].' state-error';
			}else{
				$arr_atrib['lbl_input_class'] = 'state-error';
			}
			$alertas = $this->getAlerta($cmp_id_nom);
			$arr_atrib['tag_note_error'] = '<div class="callout callout-danger"><p>'.html_entity_decodificar($alertas).'</p></div>';
			
		}
		switch($tipo_campo){
			case 'texto':
			case 'texto_lectura':
			case 'oculto':
			case 'contrasenia':
				if(!isset($arr_atrib['value']))	$arr_atrib['value'] = $this->getValor($cmp_id_nom);
				break;
			case 'num':
				if(!isset($arr_atrib['value']))	$arr_atrib['value'] = $this->getValor($cmp_id_nom);
				$decimales = intval($arr_atrib['decimales']);
				$class_decimales = ($decimales===0)? 'positive text-right' : 'decimal-'.$decimales.'-places text-right';
				$arr_atrib['class'] = $class_decimales;
				break;
			case 'fecha':
				if(!isset($arr_atrib['value']))	$arr_atrib['value'] = $this->getValor($cmp_id_nom);
				//$arr_atrib['class'] = 'fecha pull-right';	//Por si se quiere meter el icono de fecha
				$arr_atrib['class'] = 'fecha';
				break;
			case 'select':
				$arr_atrib['arr_tag_options'] = $this->defineSelectOptions($cmp_id_nom, $arr_atrib);
				//Se coloca la condicion en el caso que se quiera NO utilizar la funcionalidad "select2" para casos con el problema en el modal que parece ser que no funciona bien
				$arr_atrib['class'] = (!isset($arr_atrib['class']) && $this->getConSelect2())? 'select2' : '';
				
				break;
			case 'textarea':
				if(!isset($arr_atrib['rows']))	$arr_atrib['rows'] = 4;
				if(!isset($arr_atrib['value']))	$arr_atrib['value'] = $this->getValor($cmp_id_nom);
				break;
			case 'checkbox':
			case 'checkbox_lectura':
				if(!isset($arr_atrib['checked_val']))	$arr_atrib['checked_val'] = 1;
				if(!isset($arr_atrib['unchecked_val']))	$arr_atrib['unchecked_val'] = 0;
				if($tipo_campo=='checkbox_lectura'){
					if(!isset($arr_atrib['value']))	$arr_atrib['value'] = $this->getValor($cmp_id_nom);
				}else{
					if(!isset($arr_atrib['value']))	$arr_atrib['value'] = $arr_atrib['checked_val'];
				}
				
				$arr_atrib['checked'] = ($this->getValor($cmp_id_nom)==$arr_atrib['value'])? 'checked="checked"' :'';
				break;
		}
		$arr_atrib['class'] =(!isset($arr_atrib['class']))? 'form-control' : 'form-control '.$arr_atrib['class'];
		return $arr_atrib;
	}
}