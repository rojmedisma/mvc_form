<?php
class AdjuntoControl extends ControladorBase{
	private $ruta_archivo = "adjuntos/";
	private $cmp_arc_nom = 'archivo_subir';	//Nombre del campo tipo file
	private $solo_imagenes = false;	//Para validar que solo se pueda subir archivos de imagen
	private $max_tam_bytes = 10485760; //Tamaño máximo permitido para subir archivos (10485760 Bytes = 10 MB)
	private $revisar_extensiones = true;
	private $arr_extensiones = array("doc","docx","xls","xlsx","ppt","pptx","jpg","png","gif","bmp","jpeg","pdf","txt","csv","xml","mp3","mp4");	//Arreglo de formatos/extensiones permitidos
	private $nom_arc_sist;
	public function adjuntar(){
		/**
		 * Nota: controlador_fuente y accion_fuente son variables que pueden ser llamadas desde la vista fuente usando la función getHTMLCamposOcultosBase perteneciente a ControladorBase
		 *	- El argumento controlador_actual se convierte en variable controlador_destino.
		 *	- El argumento accion_actual se convierte en variable accion_destino
		 */
		$controlador_destino = isset($_REQUEST['controlador_fuente'])? $_REQUEST['controlador_fuente'] : "";
		$accion_destino = isset($_REQUEST['accion_fuente'])? $_REQUEST['accion_fuente'] : "";
		
		$adjunto_tipo = isset($_REQUEST['adjunto_tipo'])? $_REQUEST['adjunto_tipo'] : "";
		$this->cmp_arc_nom  = isset($_REQUEST['cmp_arc_nom'])? $_REQUEST['cmp_arc_nom'] : "";
		if($adjunto_tipo=="" || $this->cmp_arc_nom=="" || $controlador_destino=="" || $accion_destino==""){
			$this->mostrarError("Error Interno", "No fue posible identificar el valor de alguno de los siguientes argumentos: [adjunto_tipo, cmp_arc_nom, controlador_fuente, accion_fuente]. Favor de notificar al administrador del sistema. Gracias.");
		}
		
		$archivo_nombre = $_FILES[$this->cmp_arc_nom]["name"];
		if(!isset($_FILES[$this->cmp_arc_nom]["name"]) || $_FILES[$this->cmp_arc_nom]["name"]==""){
			$this->mostrarError('Nombre de archivo sin identificar', 'Favor de seleccionar el archivo que desea subir');
		}
		
		if($this->solo_imagenes){
			$this->revisaEsImg();
		}
		$this->revisaMaxTam();
		if($this->revisar_extensiones){
			$this->revisaExtensiones();
		}
		//Se crea el nombre de archivo como se va a conocer dentro del sistema
		$this->setNomArcSist();
		$nom_arc_sist = $this->nom_arc_sist;
		$target_file = $this->ruta_archivo . basename($nom_arc_sist);
		if(!move_uploaded_file($_FILES[$this->cmp_arc_nom]["tmp_name"], $target_file)) {
			$this->mostrarError("Error al intentar subir el archivo", "Se presentó un problema al intentar subir el archivo, favor de volve a intentarlo. Gracias");
		}
		$nom_arc_real = $_FILES[$this->cmp_arc_nom]["name"];
		$adjunto = new Adjunto();
		$adjunto->setRegistrar($adjunto_tipo, $this->ruta_archivo, $nom_arc_real, $nom_arc_sist);
		$adjunto_id = $adjunto->getAdjuntoId();
		redireccionar($controlador_destino, $accion_destino, array("adjunto_id"=>$adjunto_id));
		
	}
	private function revisaEsImg(){
		$check = getimagesize($_FILES[$this->cmp_arc_nom]["tmp_name"]);
		if($check===false){
			$this->mostrarError("Archivo no es de tipo imagen", "El archivo seleccionado no es un archivo de tipo imagen.");
		}
	}
	private function revisaMaxTam(){
		if ($_FILES[$this->cmp_arc_nom]["size"] > $this->max_tam_bytes) {
			$max_tam_megas = $this->max_tam_bytes/(1024*1024);
			$this->mostrarError("Tamaño de archivo no permitido", "El tamaño del archivo seleccionado es mayor al permitido (".$max_tam_megas." MB)");
		}
	}
	private function revisaExtensiones(){
		$arr_extensiones = $this->arr_extensiones;
		if(count($arr_extensiones)){
			$fileType = strtolower(pathinfo($_FILES[$this->cmp_arc_nom]["name"],PATHINFO_EXTENSION));
			if(!in_array($fileType, $arr_extensiones)){
				$this->mostrarError("Tipo de archivo no permitido", "No se permiten archivos con extensión: <strong>".$fileType."</strong>.<br>Extensiones permitidas: ".implode(", ", $arr_extensiones));
			}
		}
	}
	private function setNomArcSist(){
		$nom_arc_sist = "t_".time()."_r_".rand(0,200).".".strtolower(pathinfo($_FILES[$this->cmp_arc_nom]["name"],PATHINFO_EXTENSION));
		$target_file = $this->ruta_archivo . basename($nom_arc_sist);
		if(file_exists($target_file)) {
			$this->setNomArcSist();
		}else{
			$this->nom_arc_sist = $nom_arc_sist;
		}
	}
	private function mostrarError($tit_error, $txt_error){
		$arr_url_arg = array(
			'tit_error'=>$tit_error,
			'txt_error'=>$txt_error
		);
		redireccionar('error', 'inicio', $arr_url_arg);
		die();
	}
}