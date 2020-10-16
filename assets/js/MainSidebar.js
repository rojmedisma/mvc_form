var MainSidebar = function(){
	/**
	 * Función para agregar la clase active a la opción del menu seleccionada e identificada a partir del id asignado en la variable sb_mnu_opc_activo
	 */
	function sb_mnu_opc_activar(sb_mnu_opc_activo){
		try{
			if(sb_mnu_opc_activo!=""){
				$('#'+sb_mnu_opc_activo).addClass('active');
			}
		}catch(e){
			alert("Error interno: ["+e.message+"]");
			console.log(e);
		}
	}
	return{
		activar:function(sb_mnu_opc_activo){
			sb_mnu_opc_activar(sb_mnu_opc_activo);
		}
	}
}();