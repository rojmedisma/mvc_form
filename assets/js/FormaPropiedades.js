var FormaPropiedades = function(){
	function para_p3(o_cmp){
		limpiarCamposDentroDe(parseInt(o_cmp.val())===1, ["#div_p3_sec1"]);
		limpiarCamposDentroDe(parseInt(o_cmp.val())===2, ["#div_p3_sec2"]);
	}
	function para_p4(o_cmp){
		var v_cmp = parseInt(o_cmp.val());
		ocultarSeccionesEn(v_cmp!==1, ["#div_p4_s1"]);
		ocultarSeccionesEn(v_cmp!==2, ["#div_p4_s2"]);
		ocultarSeccionesEn(v_cmp!==3, ["#div_p4_s3"]);
	}
	return{
		activar:function(){
			para_p3($("select[name='p3']"));
			$("select[name='p3']").on("change", function(){
				para_p3($(this));
			});
			para_p4($("select[name='p4']"));
			$("select[name='p4']").on("change", function(){
				para_p4($(this));
			});
		}
	}
}();

