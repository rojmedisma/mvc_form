/**
 * Funciones generales
 */

/**
 * Redirecciona al control especificado, se llega a llamar por la función php define_controlador
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

/**
 * Limpia todos los campos contenidos dentro del selector o selectores definidos el argumento tipo arreglo a_selector
 * @param {bool} v_aplicar	Condición si se va a aplicar la limpieza o no. En caso contrario, si la bandera v_limpiar_y_bloquear es verdadera, se desbloquean los campos únicamente
 * @param {array} a_selector	Arreglo con los textos para identificar la sección o div a limpiar, ejemplo:  con el id (#[nombre]) o la clase (.[nombre])
 * @param {bool} v_limpiar_y_bloquear	Bandera que indica si además de limpiar se va a hacer bloqueo de campos. Default es true.
 */
function limpiarCamposDentroDe(v_aplicar, a_selector, v_limpiar_y_bloquear=true){
	if(a_selector.length){
		a_selector.forEach(function(v_selector){
			limpiarCamposEnDOM(v_aplicar, $(v_selector), v_limpiar_y_bloquear);
		});
	}
}
/**
 * Limpia todos los campos contenidos dentro del objeto tipo DOM o_DOM
 * @param {bool} v_aplicar	Condición si se va a aplicar la limpieza o no. En caso contrario, si la bandera v_limpiar_y_bloquear es verdadera, se desbloquean los campos únicamente
 * @param {Object} o_DOM	Objeto tipo DOM
 * @param {bool} v_limpiar_y_bloquear	Bandera que indica si además de limpiar se va a hacer bloqueo de campos. Default es true. 
 */
function limpiarCamposEnDOM(v_aplicar, o_DOM, v_limpiar_y_bloquear=true){
	if(o_DOM.length){
		o_DOM.each(function(){
			//var o_input = $(this).find("input");
			var o_input = $(this).find("input");	//Todos los campos input
			var o_select = $(this).find("select");	//Todos los campos select
			var o_textarea = $(this).find("textarea");	//Todos los campos textarea
			//Para campos input
			if(o_input.length){
				o_input.each(function(){
					if($(this).attr('type') === 'checkbox' || $(this).attr('type') === 'radio'){
						if(v_aplicar){
							$(this).prop( "checked", false );
						}
					}else{
						if(v_aplicar){
							$(this).val("");
						}
					}
				});

				//Si además de aplicar limpieza se apliva bloqueo...
				if(v_limpiar_y_bloquear){
					o_input.attr("disabled", v_aplicar);
				}
			}
			//Para campos textarea
			if(o_textarea.length){
				if(v_aplicar){
					o_textarea.val("");
				}
				//Si además de aplicar limpieza se apliva bloqueo...
				if(v_limpiar_y_bloquear){
					o_textarea.attr("disabled", v_aplicar);
				}
			}
			//Para campos select
			if(o_select.length){
				var o_select2 = $(this).find("select[class~='select2']");

				if(o_select2.length){
					if(v_aplicar){
						//El widget Select2 no permite limpiar los campos select, debido a eso, primero se debe destruir antes de limpiar el campo
						o_select2.select2('destroy');
					}else{
						//Se vuelve a revertir habilitando de nuevo el widget Select2
						o_select2.select2();
					}
				}
				if(v_aplicar){
					o_select.val("");
				}
				//Si además de aplicar limpieza se apliva bloqueo...
				if(v_limpiar_y_bloquear){
					o_select.attr("disabled", v_aplicar);
				}else{
					//Si no se va a bloquear, se vuelve a habilitar el widget Select2
					if(o_select2.length){
						o_select2.select2();
					}
				}
			}
		});
	}
}
/**
 * Limpia y oculta todos los campos contenidos dentro del selector o selectores definidos el argumento tipo arreglo a_selector
 * @param {bool} v_aplicar	Condición si se va a aplicar la limpieza o no. En caso contrario, si la bandera v_limpiar_y_bloquear es verdadera, se desbloquean los campos únicamente
 * @param {array} a_selector	Arreglo con los textos para identificar la sección o div a limpiar, ejemplo:  con el id (#[nombre]) o la clase (.[nombre])
 * @param {bool} v_limpiar_campos	Bandera que indica si además de limpiar se va a hacer bloqueo de campos. Default es true. 
 */
function ocultarSeccionesEn(v_aplicar, a_selector, v_limpiar_campos = true){
	if(a_selector.length){
		a_selector.forEach(function(v_selector){
			ocultarDOM(v_aplicar, $(v_selector), v_limpiar_campos);
		});
	}
}
/**
 * Limpia y oculta todos los campos contenidos dentro del objeto DOM definido en el argumento o_DOM
 * @param {bool} v_aplicar
 * @param {Object} o_DOM
 * @param {bool} v_limpiar_y_bloquear
 */
function ocultarDOM(v_aplicar, o_DOM, v_limpiar_y_bloquear = true){
	if(o_DOM.length){
		limpiarCamposEnDOM(v_aplicar, o_DOM, v_limpiar_y_bloquear);
		o_DOM.each(function(){
			if(v_aplicar){
				$(this).hide(400);
			}else{
				$(this).show(400);
			}
		});
		
	}
}