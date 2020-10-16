<?php
/**
 * Clase modelo para el cálculo de las validaciones o mensajes de alerta en los cuestionarios
 * @author Ismael
 *
 */
class ValidarCuest{
    private $arr_valor = array();
    private $arr_validaciones = array();
	
	public function __construct($arr_valor) {
		$this->arr_valor = $arr_valor;
	}
	
    /**
     * Devuelve el arreglo de campos enviado por argumento desde el método <strong>serArrReglasDeCuestionario</strong>
     * @return array
     */
    private function getArrValorCampos() {
        return $this->arr_valor;
    }
	private function getValorCampo($cmp_nom){
		$arr_valor = $this->getArrValorCampos();
		if(isset($arr_valor[$cmp_nom])){
			return $arr_valor[$cmp_nom];
		}else{
			return "";
		}
	}
    /**
     * Modifica el arreglo de validaciones a partir de una serie de reglas predefinidas, en donde mediante el arreglo de reglas de validación del cuestionario, regresa otro arreglo pero con las validaciones que fueron aplicadas
     */
    public function setArrValidaciones($arr_reglas) {
       
        $arr_valor = $this->getArrValorCampos();
        $arr_validaciones = array();
        //echo '<br>'. json_encode($arr_reglas);
        
        foreach($arr_reglas as $campo=>$arr_param){
            $alerta = "";
            switch($arr_param['regla']){
                case 'requerido':
                    if($this->getValorCampo($campo) == ""){
                        if(isset($arr_param['desc'])){
                            $alerta = '<strong>'.$arr_param['desc'].'</strong> Es requerido';
                        }else{
                            $alerta = 'Dato requerido';
                        }
                        
                    }
                    break;
                case 'al_menos_n_chk':
                    //Al menos N opciones en campos chk y posiblemente combos
                    if(isset($arr_param['arr_cmp_nom']) && isset($arr_param['val_n'])){
                        $tot_sel = 0;
                        foreach ($arr_param['arr_cmp_nom'] as $cmp_nom){
                            if($arr_valor[$cmp_nom]==1 || $arr_valor[$cmp_nom]!=0){
                                $tot_sel ++;
                            }
                        }
                        $val_n = intval($arr_param['val_n']);
                        if($tot_sel<$val_n){
                            foreach ($arr_param['arr_cmp_nom'] as $cmp_nom){
                                $arr_validaciones[$cmp_nom] = array("alerta"=>'[sin_desc]');
                            }
                            if(isset($arr_param['desc'])){
                                $alerta = ($val_n==1)? 'En <strong>'.$arr_param['desc'].'</strong>, seleccionar al menos una opción' : 'En <strong>'.$arr_param['desc'].'</strong>, seleccionar al menos '.$val_n.' opciones';
                            }else{
                                $alerta = ($val_n==1)? 'Seleccionar al menos una opción' : 'Seleccionar al menos '.$val_n.' opciones';
                            }
                        }
                    }
                    break;
                case 'al_menos_1_cmp':
                	if(isset($arr_param['arr_cmp_nom'])){
                		$al_menos_1_cmp = false;
                		foreach ($arr_param['arr_cmp_nom'] as $cmp_nom){
                			if($arr_valor[$cmp_nom]!=""){
                				$al_menos_1_cmp = true;
                			}
                		}
                		if(!$al_menos_1_cmp){
                			if(isset($arr_param['desc'])){
                				$alerta = $arr_param['desc'];
                			}else{
                				$alerta = 'Llenar al menos uno de los campos';
                			}
                		}
                	}
                	break;
                case 'suma_igual_a_N':
                    //Si la suma da igual a N
                    if(isset($arr_param['arr_cmp_nom']) && isset($arr_param['val_n'])){
                        $suma = 0;
                        foreach ($arr_param['arr_cmp_nom'] as $cmp_nom){
                            $suma += $arr_valor[$cmp_nom];
                        }
                        if($arr_param['val_n'] != $suma){
                            foreach ($arr_param['arr_cmp_nom'] as $cmp_nom){
                                $arr_validaciones[$cmp_nom] = array("alerta"=>'[sin_desc]');
                            }
                            if(isset($arr_param['desc'])){
                                $alerta = 'En <strong>'.$arr_param['desc'].'</strong>, el total es '.$suma.' y debe ser igual a '.$arr_param['val_n'];
                            }else{
                                $alerta = 'El total es '.$suma.' y debe ser igual a '.$arr_param['val_n'];
                            }
                        }
                    }
                    break;
            }
            if($alerta!=""){
            	$arr_validaciones[$campo] = array("alerta"=>htmlentities($alerta));
            }
            
        }
        
        $this->arr_validaciones = $arr_validaciones;
       
    }
    /**
     * Devuelve el arreglo de validaciones obtenidas para el cuestionario actual
     * @return array
     */
    public function getArrValidaciones(){
        return $this->arr_validaciones;
    }
}