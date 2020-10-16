<?php
class Adjunto extends ModeloBase{
	private $adjunto_id;
	public function __construct(){
		parent::__construct();
	}
	public function setRegistrar($adjunto_tipo, $ruta_archivo, $nom_arc_real, $nom_arc_sist){
		$cat_usuario_id = $this->getUsuarioId();
		$arr_cmps_upd = array(
				"`adjunto_id`"=>"NULL",
				"`adjunto_tipo`" =>txt_sql($adjunto_tipo),
				"`cat_usuario_id`"=>txt_sql($cat_usuario_id, false),
				"`ruta_archivo`"=>txt_sql($ruta_archivo),
				"`nom_arc_real`"=>txt_sql($nom_arc_real),
				"`nom_arc_sist`"=>txt_sql($nom_arc_sist),
				"`fecha`"=>"CURDATE()",
				"`hora`"=>"CURTIME()",
		);
		$this->adjunto_id = $this->bd->ejecutaQryInsertDeArr($arr_cmps_upd, "adjunto");
	}
	public function getAdjuntoId() {
		return $this->adjunto_id;
	}
}

