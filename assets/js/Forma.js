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
			despliega_esp(this);
		});
	}
	/**
	 * Despliega el campo especifique para los combos al seleccionar la opción "otros"
	 * @param o_cmp	Objeto del campo selects
	 */
	function despliega_esp(o_cmp){
		v_cmp_id_nom = o_cmp.id;
		v_cmp_desc_nom = v_cmp_id_nom+"_desc";
		v_cmp_esp_nom = v_cmp_id_nom+"_esp";
		v_desc_val = $("#"+v_cmp_id_nom+"  option:selected").data("desc_val");
		if(typeof(v_desc_val) != "undefined"){
			
			//Si tiene campo especifique (Falta probar esta parte para este nuevo tipo de campos)
			o_cmp_esp = document.getElementById(v_cmp_esp_nom);
			if(typeof(o_cmp_esp) != "undefined" && o_cmp_esp != null){
				v_div_esp_nom = 'div_'+v_cmp_id_nom+'_esp';
				o_div_cmp_esp = document.getElementById(v_div_esp_nom);
				if(typeof(o_div_cmp_esp) != "undefined"){
					v_es_esp = $("#"+v_cmp_id_nom+"  option:selected").data("es_esp");
					if(typeof(v_es_esp) != "undefined" && parseInt(v_es_esp)){
						//o_div_cmp_esp.style.display = '';
						$("#"+v_div_esp_nom).slideDown("slow");
					}else{
						//o_div_cmp_esp.style.display = 'none';
						o_cmp_esp.value = '';
						$("#"+v_div_esp_nom).slideUp("slow");
						
					}
				}
				
			}
			
			$("#"+v_cmp_desc_nom).val(v_desc_val);
			$('#div_'+v_cmp_desc_nom+'_desp p').html( v_desc_val );
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