<?php
/**
 * Clase modelo para el cálculo de los indicadores
 * @author Ismael Rojas
 *
 */
class ResultadoGen{
	private $cat_cuestionario_id;
	private $arr_cmps_frm = array();
	private $arr_res_cuest_modulo = array();
	private $arr_res_reg = array();
	/**
	 * Cálculo por registro. Paso 1.
	 * Se definen las variables necesarias por registro
	 * @param integer $cat_cuestionario_id
	 * @param array $arr_cmps_frm	Arreglo con el detalle de todos los campos del registro de cuestionario
	 */
	public function setCuestionario($cat_cuestionario_id, $arr_cmps_frm){
		$this->cat_cuestionario_id = $cat_cuestionario_id;
		$this->arr_cmps_frm = $arr_cmps_frm;
	}
	/**
	 * Cálculo por registro. Paso 2.
	 * Se genera el arreglo con el resultado del cálculo de los indicadores por registro y por cada cat_cuest_modulo_id indicado en el argumento
	 * @param integer $cat_cuest_modulo_id
	 */
	public function setArrResultadoCuestModulo($cat_cuest_modulo_id){
		if(count($this->arr_cmps_frm)){
			$this->arr_res_cuest_modulo = array();
			$cat_cuestionario_id = intval($this->cat_cuestionario_id);
			switch ($cat_cuestionario_id){
				case 1:
					switch(intval($cat_cuest_modulo_id)){
						case 2:
							$this->setArrResultadoC01M02();
							break;
						case 3:
							$this->setArrResultadoC01M03();
							break;
						case 4:
							$this->setArrResultadoC01M04();
							break;
						case 5:
							$this->setArrResultadoC01M05();
							break;
						case 6:
							$this->setArrResultadoC01M06();
							break;
						case 7:
							$this->setArrResultadoC01M07();
							break;
						case 8:
							$this->setArrResultadoC01M08();
							break;
					}
					break;
			}
		}
	}
	/**
	 * Genera arreglo de resultados por registro
	 * @param integer $cat_cuestionario_id
	 * @param array $arr_cmps_frm
	 * @param array $arr_tbl_cat_cuest_modulo
	 */
	public function setArrResReg($cat_cuestionario_id, $arr_cmps_frm, $arr_tbl_cat_cuest_modulo=array()){
		$this->setCuestionario($cat_cuestionario_id, $arr_cmps_frm);
		//Si no se manda valor en el argumento, se calcula
		if(empty($arr_tbl_cat_cuest_modulo)){
			$cat_cuest_modulo = new CatCuestModulo($cat_cuestionario_id);
			$cat_cuest_modulo->setArrTblCat();
			//Se crea arreglo del contenido de la tabla cat_cuest_modulo
			$arr_tbl_cat_cuest_modulo = $cat_cuest_modulo->getArrTbl();
		}
		$arr_res_reg = array();
		foreach ($arr_tbl_cat_cuest_modulo as $arr_cat_cuest_modulo){
			$cat_cuest_modulo_id = (isset($arr_cat_cuest_modulo['cat_cuest_modulo_id']))? intval($arr_cat_cuest_modulo['cat_cuest_modulo_id']) : 0;
			$cat_cuest_modulo_desc = (isset($arr_cat_cuest_modulo['descripcion']))? $arr_cat_cuest_modulo['descripcion'] : "";
			$valor_maximo = (isset($arr_cat_cuest_modulo['valor_maximo']))? $arr_cat_cuest_modulo['valor_maximo'] : "";
			//No se toma en cuenta el primer modulo debido a que es el de Datos Generales
			if($cat_cuest_modulo_id>1){
				$this->setArrResultadoCuestModulo($cat_cuest_modulo_id);
				$arr_res_cuest_modulo = $this->getArrResCuestModulo();
				$valor_x_cmpte = 0;
				$valoracion_tot = 0;
				if(isset($arr_res_cuest_modulo["tot"]["valoracion"])){
					$valoracion_tot = $arr_res_cuest_modulo["tot"]["valoracion"];
					$valor_x_cmpte =$valoracion_tot*$valor_maximo;
					
				}
				//$porcentaje=@(($valor_x_cmpte/$valor_maximo)*100);
				$porcentaje = op_division($valor_x_cmpte, $valor_maximo) * 100;
				$arr_res_reg[] = array(
						"cat_cuest_modulo"=>array(
								"cat_cuest_modulo_id"=>$cat_cuest_modulo_id,
								"descripcion"=>$cat_cuest_modulo_desc,
								"valoracion_tot"=>$valoracion_tot,
								"valor_maximo"=>$valor_maximo,
								"valor_x_cmpte"=>$valor_x_cmpte,
								"porcentaje"=>$porcentaje
						),
						"arr_res_cuest_modulo"=>$arr_res_cuest_modulo,
				);
			}
		}
		$this->arr_res_reg = $arr_res_reg;
	}
	
	
	/**
	 * Se genera el arreglo con el resultado del cálculo de los indicadores por cada cat_cuest_modulo_id = 2
	 * Pestaña 1. Planificación estratégica
	 */
	private function setArrResultadoC01M02(){
		
		$arr_cmps_frm = $this->arr_cmps_frm;
		extract($arr_cmps_frm, EXTR_OVERWRITE);
		//1. Diseño de objetivos estratégicos
		$m1cmpte1_peso = 10;
		$m1cmpte1_suma = $m1p1+$m1p2;
		$m1cmpte1_valo = op_division($m1cmpte1_suma,$m1cmpte1_peso);
		$this->agregaArrSubResultado("1", "1. Diseño de objetivos estratégicos", $m1cmpte1_peso, $m1cmpte1_suma);
		
		//2. Planificación estratégica
		$m1cmpte2_peso = 25;
		$m1cmpte2_suma = $m1p3+$m1p4+$m1p5+$m1p6+$m1p7;
		$m1cmpte2_valo = op_division($m1cmpte2_suma,$m1cmpte2_peso);
		$this->agregaArrSubResultado("2", "2. Planificación estratégica", $m1cmpte2_peso, $m1cmpte2_suma);
		
		//3. Implementación estrátegica
		$m1cmpte3_peso = 10;
		$m1cmpte3_suma = $m1p8+$m1p9;
		$m1cmpte3_valo = op_division($m1cmpte3_suma,$m1cmpte3_peso);
		$this->agregaArrSubResultado("3", "3. Implementación estrátegica", $m1cmpte3_peso, $m1cmpte3_suma);
		
		//Total
		$m1_peso_tot = 45;
		$m1_suma_tot = $m1cmpte1_suma+$m1cmpte2_suma+$m1cmpte3_suma;
		$m1_valo_tot = op_division($m1_suma_tot,$m1_peso_tot);
		$this->agregaArrSubResultado("", "", $m1_peso_tot, $m1_suma_tot);
	}
	/**
	 * Se genera el arreglo con el resultado del cálculo de los indicadores por cada cat_cuest_modulo_id = 3
	 * Pestaña 2. Operación e infraestructura
	 */
	private function setArrResultadoC01M03(){
		$arr_cmps_frm = $this->arr_cmps_frm;
		extract($arr_cmps_frm, EXTR_OVERWRITE);
		
		//1. Instalaciones
		$m2cmpte1_peso = 40;
		$m2cmpte1_suma = $m2p1 + $m2p2 + $m2p3 + $m2p4 + $m2p5r1 + $m2p5r2 + $m2p5r3 + $m2p5r4;
		$this->agregaArrSubResultado("1", "1. Instalaciones", $m2cmpte1_peso, $m2cmpte1_suma);
		
		//2. Operación
		$m2cmpte2_peso = 295;
		$m2cmpte2_suma = $m2p6r1 + $m2p6r2 + $m2p6r3 + $m2p6r4 + $m2p6r5 + $m2p6r6 + $m2p6r7 + $m2p6r8 + $m2p7r1 + $m2p7r2 + $m2p7r3 + $m2p7r4 + $m2p7r5 + $m2p7r6 + $m2p8r1 + $m2p8r2 + $m2p8r3 + $m2p8r4 + $m2p8r5 + $m2p8r6 + $m2p8r7 + $m2p8r8 + $m2p8r9 + $m2p8r10 + $m2p9r1 + $m2p9r2 + $m2p9r3 + $m2p9r4 + $m2p9r5 + $m2p9r6 + $m2p10r1 + $m2p10r2 + $m2p10r3 + $m2p10r4 + $m2p10r5 + $m2p10r6 + $m2p10r7 + $m2p11r1 + $m2p11r2 + $m2p11r3 + $m2p11r4 + $m2p11r5 + $m2p11r6 + $m2p12r1 + $m2p12r2 + $m2p12r3 + $m2p12r4 + $m2p12r5 + $m2p12r6 + $m2p13r1 + $m2p13r2 + $m2p13r3 + $m2p13r4 + $m2p14r1 + $m2p14r2 + $m2p14r3 + $m2p14r4 + $m2p14r5 + $m2p14r6;
		$this->agregaArrSubResultado("2", "2. Operación", $m2cmpte2_peso, $m2cmpte2_suma);
		
		//Total
		$m2_peso_tot = 335;
		$m2_suma_tot = $m2cmpte1_suma+$m2cmpte2_suma;
		$this->agregaArrSubResultado("", "", $m2_peso_tot, $m2_suma_tot);
	}
	/**
	 * Se genera el arreglo con el resultado del cálculo de los indicadores por cada cat_cuest_modulo_id = 4
	 * Pestaña 3. Admon. y org. empresarial
	 */
	private function setArrResultadoC01M04(){
		$arr_cmps_frm = $this->arr_cmps_frm;
		extract($arr_cmps_frm, EXTR_OVERWRITE);
		
		//1. Estructura y Organigrama
		$m3cmpte1_peso = 10;
		$m3cmpte1_suma = $m3p1 + $m3p2;
		$this->agregaArrSubResultado("1", "1. Estructura y Organigrama", $m3cmpte1_peso, $m3cmpte1_suma);
		
		//2. Puestos y perfiles
		$m3cmpte2_peso = 5;
		$m3cmpte2_suma = $m3p3;
		$this->agregaArrSubResultado("2", "2. Puestos y perfiles", $m3cmpte2_peso, $m3cmpte2_suma);
		
		//3. Manual de procedimientos
		$m3cmpte3_peso = 15;
		$m3cmpte3_suma = $m3p4 + $m3p5 + $m3p6;
		$this->agregaArrSubResultado("3", "3. Manual de procedimientos", $m3cmpte3_peso, $m3cmpte3_suma);
		
		//4. Manejo de recursos humanos en los centros de acopio
		$m3cmpte4_peso = 20;
		$m3cmpte4_suma = $m3p7 + $m3p8 + $m3p9 + $m3p10;
		$this->agregaArrSubResultado("4", "4. Manejo de recursos humanos en los centros de acopio", $m3cmpte4_peso, $m3cmpte4_suma);
		//Total
		$m3_peso_tot = 50;
		$m3_suma_tot = $m3cmpte1_suma + $m3cmpte2_suma + $m3cmpte3_suma + $m3cmpte4_suma;
		$this->agregaArrSubResultado("", "", $m3_peso_tot, $m3_suma_tot);
	}
	/**
	 * Se genera el arreglo con el resultado del cálculo de los indicadores por cada cat_cuest_modulo_id = 5
	 * Pestaña 4. Gobierno corporativo
	 */
	private function setArrResultadoC01M05(){
		$arr_cmps_frm = $this->arr_cmps_frm;
		extract($arr_cmps_frm, EXTR_OVERWRITE);
		
		if(intval($persona_tipo)){
			if(intval($persona_tipo)==1){
				//1. Junta de gobierno
				$m4cmpte1_peso = 5;
				$m4cmpte1_suma = $m4p1;
				$this->agregaArrSubResultado("1", "1. Junta de gobierno", $m4cmpte1_peso, $m4cmpte1_suma);
				
				//2. Transparencia
				$m4cmpte2_peso = 20;
				$m4cmpte2_suma = $m4p2r1 + $m4p2r2 + $m4p2r3 + $m4p2r4;
				$this->agregaArrSubResultado("2", "2. Transparencia", $m4cmpte2_peso, $m4cmpte2_suma);
				
				//3. Conformación legal
				$m4cmpte3_peso = 5;
				$m4cmpte3_suma = $m4p3;
				$this->agregaArrSubResultado("3", "3. Conformación legal", $m4cmpte3_peso, $m4cmpte3_suma);
				
				//Total
				$m4_peso_tot = 30;
				$m4_suma_tot = $m4cmpte1_suma + $m4cmpte2_suma + $m4cmpte3_suma;
				$this->agregaArrSubResultado("", "", $m4_peso_tot, $m4_suma_tot);
				
				
			}elseif(intval($persona_tipo)==2){
				//1. Órganos representativos
				$m4cmpte1_peso = 15;
				$m4cmpte1_suma = $m4p4r1 + $m4p4r2 + $m4p4r3;
				$this->agregaArrSubResultado("1", "1. Órganos representativos", $m4cmpte1_peso, $m4cmpte1_suma);
				
				//2. Control y gobierno
				$m4cmpte2_peso = 15;
				$m4cmpte2_suma = $m4p5r1 + $m4p5r2 + $m4p5r3;
				$this->agregaArrSubResultado("2", "2. Control y gobierno", $m4cmpte2_peso, $m4cmpte2_suma);
				
				//3. Transparencia
				$m4cmpte3_peso = 5;
				$m4cmpte3_suma = $m4p6;
				$this->agregaArrSubResultado("3", "3. Transparencia", $m4cmpte3_peso, $m4cmpte3_suma);
				
				//Total
				$m4_peso_tot = 30;
				$m4_suma_tot = $m4cmpte1_suma + $m4cmpte2_suma + $m4cmpte3_suma;
				$this->agregaArrSubResultado("", "", $m4_peso_tot, $m4_suma_tot);
				
			}
			
		}
		
	}
	/**
	 * Se genera el arreglo con el resultado del cálculo de los indicadores por cada cat_cuest_modulo_id = 6
	 * Pestaña 5. Modelo de negocio
	 */
	private function setArrResultadoC01M06(){
		$arr_cmps_frm = $this->arr_cmps_frm;
		extract($arr_cmps_frm, EXTR_OVERWRITE);
		
		//1. Dinámica de mercado
		$m5cmpte1_peso = 15;
		$m5cmpte1_suma = $m5p1 + $m5p2 + $m5p3;
		$this->agregaArrSubResultado("1", "1. Dinámica de mercado", $m5cmpte1_peso, $m5cmpte1_suma);
		
		//2. Trazabilidad
		$m5cmpte2_peso = 5;
		$m5cmpte2_suma = $m5p4;
		$this->agregaArrSubResultado("2", "2. Trazabilidad", $m5cmpte2_peso, $m5cmpte2_suma);
		
		//3. Conservación y almacenamiento
		$m5cmpte3_peso = 5;
		$m5cmpte3_suma = $m5p5;
		$this->agregaArrSubResultado("3", "3. Conservación y almacenamiento", $m5cmpte3_peso, $m5cmpte3_suma);
		
		//4. Documentación de salida del producto
		$m5cmpte4_peso = 5;
		$m5cmpte4_suma = $m5p6;
		$this->agregaArrSubResultado("4", "4. Documentación de salida del producto", $m5cmpte4_peso, $m5cmpte4_suma);
		
		//5. Propuesta de valor
		$m5cmpte5_peso = 10;
		$m5cmpte5_suma = $m5p7 + $m5p8;
		$this->agregaArrSubResultado("5", "5. Propuesta de valor", $m5cmpte5_peso, $m5cmpte5_suma);
		
		//6. Relación con proveedores
		$m5cmpte6_peso = 5;
		$m5cmpte6_suma = $m5p9;
		$this->agregaArrSubResultado("6", "6. Relación con proveedores", $m5cmpte6_peso, $m5cmpte6_suma);
		
		//Total
		$m5_peso_tot = 45;
		$m5_suma_tot = $m5cmpte1_suma + $m5cmpte2_suma + $m5cmpte3_suma + $m5cmpte4_suma + $m5cmpte5_suma + $m5cmpte6_suma;
		$this->agregaArrSubResultado("", "", $m5_peso_tot, $m5_suma_tot);
	}
	/**
	 * Se genera el arreglo con el resultado del cálculo de los indicadores por cada cat_cuest_modulo_id = 7
	 * Pestaña 6. Evaluación financiera
	 */
	private function setArrResultadoC01M07(){
		$arr_cmps_frm = $this->arr_cmps_frm;
		extract($arr_cmps_frm, EXTR_OVERWRITE);
		
		//1. Recursos financieros
		$m6cmpte1_peso = 25;
		$m6cmpte1_suma = $m6p1 + $m6p2 + $m6p3 + $m6p4 + $m6p5;
		$this->agregaArrSubResultado("1", "1. Recursos financieros", $m6cmpte1_peso, $m6cmpte1_suma);
		
		//2. Estructura del área administrativa, financiera y contable
		$m6cmpte2_peso = 15;
		$m6cmpte2_suma = $m6p6 + $m6p7 + $m6p8;
		$this->agregaArrSubResultado("2", "2. Estructura del área administrativa, financiera y contable", $m6cmpte2_peso, $m6cmpte2_suma);
		
		//3. Análisis de estados financieros
		$m6cmpte3_peso = 20;
		$m6cmpte3_suma = $m6p9 + $m6p10 + $m6p11r1 + $m6p11r2;
		$this->agregaArrSubResultado("3", "3. Análisis de estados financieros", $m6cmpte3_peso, $m6cmpte3_suma);
		
		//4. Historial crediticio
		$m6cmpte4_peso = 15;
		$m6cmpte4_suma = $m6p12 + $m6p13 + $m6p14;
		$this->agregaArrSubResultado("4", "4. Historial crediticio", $m6cmpte4_peso, $m6cmpte4_suma);
		
		//5. Administración de riesgos
		$m6cmpte5_peso = 25;
		$m6cmpte5_suma = $m6p15 + $m6p16r1 + $m6p16r2 + $m6p17 + $m6p18;
		$this->agregaArrSubResultado("5", "5. Administración de riesgos", $m6cmpte5_peso, $m6cmpte5_suma);
		//Total
		$m6_peso_tot = 100;
		$m6_suma_tot = $m6cmpte1_suma + $m6cmpte2_suma + $m6cmpte3_suma + $m6cmpte4_suma + $m6cmpte5_suma;
		$this->agregaArrSubResultado("", "", $m6_peso_tot, $m6_suma_tot);
	}
	/**
	 * Se genera el arreglo con el resultado del cálculo de los indicadores por cada cat_cuest_modulo_id = 8
	 * Pestaña 7. Sistemas de control y riesgo
	 */
	private function setArrResultadoC01M08(){
		$arr_cmps_frm = $this->arr_cmps_frm;
		extract($arr_cmps_frm, EXTR_OVERWRITE);
		
		
		//1. Sistema administrativo
		$m7cmpte1_peso = 20;
		$m7cmpte1_suma = $m7p1 + $m7p2 + $m7p3 + $m7p4;
		$this->agregaArrSubResultado("1", "1. Sistema administrativo", $m7cmpte1_peso, $m7cmpte1_suma);
		
		//2. Sistema de mantenimiento preventivo y correctivo
		$m7cmpte2_peso = 25;
		$m7cmpte2_suma = $m7p5 + $m7p6 + $m7p7 + $m7p8 + $m7p9;
		$this->agregaArrSubResultado("2", "2. Sistema de mantenimiento preventivo y correctivo", $m7cmpte2_peso, $m7cmpte2_suma);
		
		//3. Sistema contable
		$m7cmpte3_peso = 35;
		$m7cmpte3_suma = $m7p10 + $m7p11 + $m7p12 + $m7p13 + $m7p14 + $m7p15 + $m7p16;
		$this->agregaArrSubResultado("3", "3. Sistema contable", $m7cmpte3_peso, $m7cmpte3_suma);
		
		//4. Sistema de control de inventarios
		$m7cmpte4_peso = 30;
		$m7cmpte4_suma = $m7p17 + $m7p18 + $m7p19 + $m7p20 + $m7p21 + $m7p22;
		$this->agregaArrSubResultado("4", "4. Sistema de control de inventarios", $m7cmpte4_peso, $m7cmpte4_suma);
		
		//5. Sistema de trazabilidad de los proveedores
		$m7cmpte5_peso = 20;
		$m7cmpte5_suma = $m7p23 + $m7p24 + $m7p25 + $m7p26;
		$this->agregaArrSubResultado("5", "5. Sistema de trazabilidad de los proveedores", $m7cmpte5_peso, $m7cmpte5_suma);
		
		//6. Sistema de información de los clientes
		$m7cmpte6_peso = 35;
		$m7cmpte6_suma = $m7p27 + $m7p28 + $m7p29 + $m7p30 + $m7p31 + $m7p32 + $m7p33;
		$this->agregaArrSubResultado("6", "6. Sistema de información de los clientes", $m7cmpte6_peso, $m7cmpte6_suma);
		
		//7. Control de riesgos
		$m7cmpte7_peso = 5;
		$m7cmpte7_suma = $m7p34;
		$this->agregaArrSubResultado("7", "7. Control de riesgos", $m7cmpte7_peso, $m7cmpte7_suma);
		//Total
		$m7_peso_tot = 170;
		$m7_suma_tot = $m7cmpte1_suma + $m7cmpte2_suma + $m7cmpte3_suma + $m7cmpte4_suma + $m7cmpte5_suma + $m7cmpte6_suma + $m7cmpte7_suma;
		$this->agregaArrSubResultado("", "", $m7_peso_tot, $m7_suma_tot);
		
	}
	/**
	 * Devuelve el arreglo con el detalle de resultados a partir del cat_cuest_modulo calculado
	 * @return array
	 */
	public function getArrResCuestModulo(){
		return $this->arr_res_cuest_modulo;
	}
	public function getArrResReg(){
		return $this->arr_res_reg;
	}
	
	/**
	 * Va generando el arreglo arr_res_cuest_modulo conforme se van calculando las variables por componente dentro de cada función
	 * @param string $cmpte_id
	 * @param string $cmpte_tit
	 * @param integer $peso
	 * @param integer $suma
	 * @param integer $valoracion
	 */
	private function agregaArrSubResultado($cmpte_id, $cmpte_tit, $peso, $suma, $valoracion=NULL){
		$arr_res_cuest_modulo = $this->arr_res_cuest_modulo;
		//Si cmpte_id significa que es el arreglo total
		if($cmpte_id==""){
			$cmpte_id = "tot";
			$cmpte_tit = "Total";
		}
		//Si no se manda el argumento de valoración, entonces se hace la operación básica (suma/peso)
		if($valoracion==NULL){
			$valoracion = op_division($suma, $peso);
		}
		$arr_sub_res = array(
				"cmpte_tit"=>$cmpte_tit,
				"peso"=>$peso,
				"suma"=>$suma,
				"valoracion"=>$valoracion
		);
		$arr_res_cuest_modulo[$cmpte_id] = $arr_sub_res;
		$this->arr_res_cuest_modulo = $arr_res_cuest_modulo;
	}
}