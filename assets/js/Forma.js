/**
 * Funciones generales para dar funcionalidad a los distintos tipos de campos dentro de un formulario con plantilla AdminLTE
 */
var Forma = function(){
	/**
	 * Para los campos numéricos, permite la captura de decimales.
	 */
	function cmpNum(){
		$(".positive").numeric({ decimal: false, negative: false });
		$(".decimal-1-places").numeric({ decimalPlaces: 1, negative: false });
		$(".decimal-2-places").numeric({ decimalPlaces: 2, negative: false });
		$(".decimal-3-places").numeric({ decimalPlaces: 3, negative: false });
		$(".decimal-4-places").numeric({ decimalPlaces: 4, negative: false });
	}
	//Para los campos de fecha
	/**
	 * Para los campos de tipo fecha, despliega el calendario
	 */
	function cmpFecha(){
		$('.fecha').daterangepicker({
			autoclose: true,
			format:'yyyy-mm-dd'
	    });
	}
	/**
	 * Para los campos combo o select, Muestra funcionalidad de búsqueda y despliega el campo de especifique para las opciones "otros"
	 */
	function cmpSelect(){
		$(".select2").select2();
		$("select[class~='form-control']").on("change", function(){
			//console.log("Para select:"+this.name);
			llenarCmpDesc(this);
			desplegarEsp(this);
		});
	}
	/**
	 * Despliega valor de la opción seleccionada del campo "select" en el/los tag(s) tipo div que contengan la clase con el nombre tipo: "div_"+[nombre de campo desc]+"_desp".
	 * Dentro del div debe existir un tag tipo "p" o "span" donde se asignará el valor de la opción
	 * En php la clase css se llena mediante la función "getCssDivEsp" en la clase "ControladorBase"
	 * @param {Object} o_cmp
	 */
	function llenarCmpDesc(o_cmp){
		var v_cmp_nom = o_cmp.name;
		var o_sel_opt = o_cmp.options[o_cmp.selectedIndex];
		var v_cmp_desc_nom = v_cmp_nom+"_desc";
		
		if(typeof(o_sel_opt.dataset.desc_val)!=="undefined"){
			v_desc_val = o_sel_opt.dataset.desc_val;
			$("input[name='"+v_cmp_desc_nom+"']").val(v_desc_val);
			if($(".div_"+v_cmp_desc_nom+"_desp").length){
				$(".div_"+v_cmp_desc_nom+"_desp p:nth-last-child(1), .div_"+v_cmp_desc_nom+"_desp span:nth-last-child(1)").html(v_desc_val);
			}
		}
	}
	/**
	 * Para las opciones del select con la bandera "es_esp" (Otro especificar) que permite mostrar u ocultar una sección
	 * Permite desplegar/ocultar varias secciones como opciones con "es_esp" contenga el combo o campo select.
	 * Cada sección se relaciona con la opción del combo a partir de su valor y el dataset "es_esp" y el nombre de la clase de tipo "div_"+[Nombre del camo select]+"_esp"
	 * @param {type} o_cmp
	 */
	function desplegarEsp(o_cmp){
		//Hay tags tipo div con clase tipo "div_"+[Nombre del camo select]+"_esp"...
		if($(".div_"+o_cmp.name+"_esp").length){
			$(".div_"+o_cmp.name+"_esp").removeClass( "d-none" );
			var o_sel_opt = o_cmp.options[o_cmp.selectedIndex];	//Objeto con la opción seleccionada
			//Si el objeto con la opc seleccionada tiene el dataset es_esp...
			if(typeof(o_sel_opt.dataset.es_esp)!=="undefined" && o_sel_opt.dataset.es_esp){
				$(".div_"+o_cmp.name+"_esp").each(function(i, o_div_esp){
					//Si el valor del dataset opc_id del div es igual al valor del objeto con la opción seleccionada...
					if(typeof(o_div_esp.dataset.opc_id)!=="undefined" && o_div_esp.dataset.opc_id===o_sel_opt.value){
						ocultarDOM(false, $(".div_"+o_cmp.name+"_esp").eq(i));
					}else{
						ocultarDOM(true, $(".div_"+o_cmp.name+"_esp").eq(i));
					}
				});
			}else{
				//Al no haber seleccionado ninguna opción es_esp, se ocultan todos los tag tipo div con nombre de clase ".div_"+o_cmp.name+"_esp"
				ocultarDOM(true, $(".div_"+o_cmp.name+"_esp"));
			}
		}
	}
	/**
	 * Oculta los mensajes de validación que aparecen debajo de cada campo al guardar el formulario
	 */
	function quitaAlerta(){
		//$(".form-group").click(function(){
		$("div[class~='has-error']").click(function(){
			$(this).removeClass("has-error");
			$(this).find("span.help-block").html("");
		});
	}
	/**
	 * Muestra ventana informativa, con la información asignada desde el botón
	 */
	function modalInfo(){
		$("#modal_info").on('show.bs.modal', function(event){
			var o_btn = $(event.relatedTarget);	//Objeto del botón que disparó el modal
			var v_txt_tit = o_btn.data('txt_tit');	//dataset con el titulo
			var v_txt_info = o_btn.data('txt_info');	//dataset con el contenido
			var v_id_info = o_btn.data('id_info');	//dataset con el Id del div que contiene la información a mostrar, en caso de no usarse el dataset txt_info
			

			var o_modal = $(this);
			if(v_txt_tit!= undefined){
				//Se llena el titulo a partir del dataset txt_tit
				o_modal.find('.modal-header h4').html(v_txt_tit);
			}
			if(v_txt_info!= undefined){
				//Se llena el contenido a partir del dataset txt_info
				var v_txt_info_p = v_txt_info.replace(/\|/gi, '<br>');	//los saltos de linea se identifican con el caracter pipe (|), entonces aquí se sustituye con un <br>
				o_modal.find('.modal-body p').html(v_txt_info_p);
			}else if(v_id_info!=undefined){
				//Se llena el contenido a partir del contenido del div con el id indicado en el dataset id_info
				var v_div_info = $(v_id_info).html();
				if(v_div_info!=undefined){
					o_modal.find('.modal-body').html(v_div_info);
				}
			}
		});
	}
	
	
	return {
		activaCmpEventos:function(){
			cmpNum();
			cmpFecha();
			cmpSelect();
			quitaAlerta();
			modalInfo();
		},
	}
}();