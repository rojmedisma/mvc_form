/**
 * Funciones generales
 */

/**
 * Redirecciona al control especificado, se llega a llamar por la función php url_controlador
 * @param v_forma	Nombre de la forma con al que se hace el submit
 * @param v_controlador	Nombre del controlador
 * @param v_accion	Nombre de la acción
 * @param v_url_arg	Argumentos adicionales
 * @param v_campo_x_arg	Bandera que define si los argumentos adicionales se almacenan dentro de los campos previamente declarados dentro de la forma v_forma
 * @returns
 */
function f_ir_a_controlador(v_forma, v_controlador, v_accion, v_url_arg, v_campo_x_arg){
	v_url_arg = (typeof v_url_arg == "undefined")? "" : v_url_arg;
	
	if(v_campo_x_arg){
		//Se asignan en la forma v_forma los campos definidos en el argumento v_url_arg
		f_asigna_arg_url_en_campos(v_forma, v_url_arg);
		v_url_arg="";	//Se limpia para que ya no se mande por argumento (Esto es solo por la parte visual, ya que en lo funcional no afecta si se deja el valor debido a quien manda es el valor en el campo)
	}
											   
	document.forms.namedItem(v_forma).action = "index.php?controlador="+v_controlador+"&accion="+v_accion+v_url_arg;
	document.forms.namedItem(v_forma).submit();
}

/**
 * 
 * @param v_controlador
 * @param v_accion
 * @param v_url_arg
 * @param v_target
 * @returns
 */
function f_ir_a_otra_ventana(v_ruta, v_target){
	v_target = (v_target=='')? '_new' : v_target;
	window.open(v_ruta, v_target);
}


/**
 * Oculta secciones completas
 * @param slide {boolean} Si se quiere ocultar o mostrar la sección
 * @param patron {string} Puede ser un #id, .class o una expresión regular
 */
function ocultar_secciones(slide, patron){
	if(slide){
		$(patron).slideUp("slow");
	}else{
		$(patron).slideDown("slow");
	}
}
/**
 * Bloquea y limpia una lista de campos
 * @param bloquea {boolean} 
 * @param campos
 */
function bloqueaCampos(bloquea, campos, limpiar){
	if (limpiar === undefined){
		limpiar = true;
	}
	
	$.each(campos, function(index, campo){
		if($("#"+campo).length > 0){
			if(bloquea && limpiar){
				if($("#"+campo).is(":checkbox")) {
					$("#"+campo).attr("checked", false);
				}else{
					$("#"+campo).val("");
				}
			}
			
			$("#"+campo).attr("disabled", bloquea);
			
			
			$("#"+campo).change();
			
			if($("#"+campo).is(":text")) {
				$("#"+campo).keyup();
			}
			
		}else{
			var msg = "Se esta intentado "+
						"bloquear el campo \""+campo+"\" "+
						"que no existe en esta forma.";
			
			alert(msg);
		}
	});
}
/**
 * Regresa el valor de cualquier campo de la forma actual
 * @param id {string} Identificador del campo
 * @param return_num {boolean} Si queremos que el valor sea devuelto como número
 *        detault false
 * @returns {mixed}
 */
function getValueForm(id, return_num){
	var value = "";
	
	if($("#"+id).is(":checkbox")){
		value = ($("#"+id).is(':checked'))?"1":"";
	}else{
		value = $("#"+id).val();
	}

	if(value === null){
		value = "";
	}

	return_num = (return_num === undefined)?false:return_num;

	if(return_num){
		if(value === ""){
			value = 0;
		}else{
			value = parseFloat(value);
		}
	}

	return value;
}
/**
 * Abre en una ventana nueva la redirección al control especificado
 * @param v_controlador	Nombre del controlador
 * @param v_accion	Nombre de la acción
 * @param v_url_arg	Más argumentos dentro del URL, si es necesario
 * @param v_target	Atributo Target o nombre de la ventana
 * @returns
 */
function f_ventana_ir_a_controlador(v_controlador, v_accion, v_url_arg, v_target){
	v_target = (v_target=='')? '_new' : v_target;
	window.open("index.php?controlador="+v_controlador+"&accion="+v_accion+v_url_arg, v_target);
}

/**
 * A partir de un argumento url se obtienen los campos y su valor que se asignan a los campos que se deben encontrar en la forma definida
 * @param v_forma	Nombre de la forma donde se encuentran los campos
 * @param v_url_arg	Argumento URL que contiene el nombre de campo y valor
 * @returns
 */
function f_asigna_arg_url_en_campos(v_forma, v_url_arg) {
    var sURLVariables = v_url_arg.split('&'),sParameterName,i, v_forma, v_cmp_nom, v_cmp_val, o_cmp_asig;
    
    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');
        if(sParameterName[0]!="" && sParameterName[1] !== undefined){
        	try{
        		v_cmp_nom = sParameterName[0];
            	v_cmp_val = decodeURIComponent(sParameterName[1]);
            	o_cmp_asig = document.getElementById(v_forma).elements.namedItem(v_cmp_nom);
            	if(o_cmp_asig === null){
            		alert("Campo ["+v_cmp_nom+"] no definido en la forma ["+v_forma+"]");
            	}else{
            		o_cmp_asig.value = v_cmp_val;
            	}
        	}catch(e){
        		alert("Error interno: ["+e.message+"]");
    			console.log(e);
        	}
        	
        	
        }
                
    }
};