<?php
/**
 * Clase modelo que contiene los distintivos de todas las páginas del sistema
 * @author Ismael
 *
 */
class Distintivos{
	private $arr_distintivos;
	private $arr_distintivos_pagina = array();
	private $arr_pagina_anterior = array();
	public function __construct() {
		$this->arr_distintivos = array(
				"error"=>array(
						"__construct"=>array(
								"titulo_pagina"=>"Error",
								array("controlador"=>"desautentificar","accion"=>"inicio")
						)
				),
				
				"autentificar"=>array(
						"inicio"=>array(
								"titulo_pagina"=>TIT_LARGO,
								"arr_pag_ant"=>array()
						)
				),
				"tablero"=>array(
						"inicio"=>array(
								"titulo_pagina"=>"Inicio",
								"arr_pag_ant"=>array()
						)
				),
				"cuestionario"=>array(
						"vista"=>array(
								"titulo_pagina"=>"Vista cuestionario",
								"arr_pag_ant"=>array()
						),
						"forma"=>array(
								"titulo_pagina"=>"Cuestionario",
								"arr_pag_ant"=>array(
										array("controlador"=>"cuestionario","accion"=>"vista")
								)
						),
						"resultado"=>array(
								"titulo_pagina"=>"Cuestionario",
								"arr_pag_ant"=>array(
										array("controlador"=>"cuestionario","accion"=>"vista")
								)
						),
						"res_cmpte"=>array(
								"titulo_pagina"=>"Cuestionario",
								"arr_pag_ant"=>array(
										array("controlador"=>"cuestionario","accion"=>"vista")
								)
						),
						"semaforo"=>array(
								"titulo_pagina"=>"Cuestionario",
								"arr_pag_ant"=>array(
										array("controlador"=>"cuestionario","accion"=>"vista")
								)
						)
				),
				
				"cat_usuario"=>array(
						"vista"=>array(
								"titulo_pagina"=>"Catálogo de usuarios",
								"arr_pag_ant"=>array(
										array("controlador"=>"cuestionario","accion"=>"vista")
								)
						),
						"forma"=>array(
								"titulo_pagina"=>"Formulario de usuario",
								"arr_pag_ant"=>array(
										array("controlador"=>"cat_usuario","accion"=>"vista"),
										array("controlador"=>"cuestionario","accion"=>"vista")
								)
						)
				),
				"cat_grupo"=>array(
						"inicio"=>array(
								"titulo_pagina"=>"Catálogo de grupos",
								"arr_pag_ant"=>array(
										array("controlador"=>"tablero","accion"=>"inicio")
								)
						),
						"abrir"=>array(
								"titulo_pagina"=>"Formulario de grupo",
								"arr_pag_ant"=>array(
										array("controlador"=>"cat_grupo","accion"=>"inicio")
								)
						)
				),
				"log"=>array(
						"inicio"=>array(
								"titulo_pagina"=>"Vista registros de log",
								"arr_pag_ant"=>array(
										array("controlador"=>"tablero","accion"=>"inicio")
								)
						)
				),
				
				"doc_codigo"=>array(
						"inicio"=>array(
								"titulo_pagina"=>"Documentación de código fuente",
								"arr_pag_ant"=>array(
										array("controlador"=>"tablero","accion"=>"inicio")
								)
						)
				),
		);
	}
	/**
	 * Devuelve el arreglo declarado en el contructor con los distintivos de las páginas
	 * @return array
	 */
	private function getArrDistintivos($controlador="", $accion=""){
		if($controlador=="" && $accion==""){
			return $this->arr_distintivos;
		}else{
			$arr_distintivos = $this->arr_distintivos;
			if(isset($arr_distintivos[$controlador][$accion])){
				return $arr_distintivos[$controlador][$accion];
			}else{
				return array();
			}
		}
		
	}
	/**
	 * Crea el arreglo distintivo de la página indentificada en con el controlador y acción definidos en el argumento
	 * @param string $controlador
	 * @param string $accion
	 */
	public function setArrDistintivosPagina($controlador, $accion){
		$arr_distintivos = $this->getArrDistintivos($controlador, $accion);
		
		$arr_distintivos_pag = array();
		if(count($arr_distintivos)){
			$this->setArrPaginaAnterior($controlador, $accion);
			$arr_pagina_anterior = $this->getArrPaginaAnterior();
			$arr_distintivos['arr_pagina_anterior'] = $arr_pagina_anterior;
			$arr_distintivos_pag = $arr_distintivos;
		}else{
			$arr_distintivos_pag =  array();
		}
		$this->arr_distintivos_pagina = $arr_distintivos_pag;
	}
	/**
	 * Devuelve el arreglo de distintivos de la página
	 * @return array
	 */
	public function getArrDistintivosPagina(){
		return $this->arr_distintivos_pagina;
	}
	
	/**
	 * A partir del arreglo generado con la función recursiva generaArrHistoricoPagAnt, cada arreglo se complementa con su titulo_pagina
	 * @param string $controlador
	 * @param string $accion
	 */
	private function setArrPaginaAnterior($controlador, $accion){
		//Se genera el historico de pagina anterior
		$arr_historico_pag_ant = $this->generaArrHistoricoPagAnt($controlador, $accion);
		$arr_pagina_anterior = array();
		foreach ($arr_historico_pag_ant as $arr_det_historico){
			$controlador_h = $arr_det_historico["controlador"];
			$accion_h = $arr_det_historico["accion"];
			$arr_distintivos = $this->getArrDistintivos($controlador_h, $accion_h);
			$titulo_pagina_h = (count($arr_distintivos) && isset($arr_distintivos["titulo_pagina"]))? $arr_distintivos["titulo_pagina"] : "[sin título]";
			$arr_pagina_anterior[] = array(
					"controlador"=>$controlador_h,
					"accion"=>$accion_h,
					"titulo_pagina"=>$titulo_pagina_h
					
			);
		}
		$this->arr_pagina_anterior = $arr_pagina_anterior;
	}
	/**
	 * Devuelve el arreglo histórico de paginas anteriores
	 * @return array
	 */
	private function getArrPaginaAnterior(){
		return $this->arr_pagina_anterior;
	}
	
	/**
	 * Función recursiva que complementa el arreglo arr_pag_ant con sus arreglos respectivos buscados recursivamente
	 * @param string $controlador
	 * @param string $accion
	 * @return array
	 */
	private function generaArrHistoricoPagAnt($controlador, $accion) {
		$arr_distintivos = $this->getArrDistintivos();
		if(isset($arr_distintivos[$controlador][$accion]['arr_pag_ant'])){
			$arr_pag_ant = $arr_distintivos[$controlador][$accion]['arr_pag_ant'];
			if(count($arr_pag_ant)>0){
				$arr_pag_ant_tmp = $arr_pag_ant;
				$arr_pag_ant_ultimo = array_pop($arr_pag_ant_tmp);
				$controlador_ultimo = $arr_pag_ant_ultimo['controlador'];
				$accion_ultimo = $arr_pag_ant_ultimo['accion'];
				return array_merge($arr_pag_ant, $this->generaArrHistoricoPagAnt($controlador_ultimo,$accion_ultimo));
			}else{
				return array();
			}
		}else{
			return array();
		}
	}
}