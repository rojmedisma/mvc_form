var Vista = function(){
	function borrar(event){
		if(!confirm("¿Desea borrar el registro?")){
			event.preventDefault();
		}
	}
	return{
		activar:function(v_vista_nombre){
			$('#'+v_vista_nombre).DataTable({
				"order": [[ 1, "desc" ]],
				"language": {
					"lengthMenu": "Mostrar _MENU_ registros por página",
					"zeroRecords": "Ningún registro encontrado",
					"info": "Mostrando página _PAGE_ de _PAGES_",
					"infoFiltered": "(filtrado de _MAX_ total de registros)",
					"search": "Buscar",
					"paginate": {
						"first": "Primera",
						"last": "Última",
						"next": "Siguiente",
						"previous": "Previo"
					},
				}
			});
			$(".frm_borrar").submit(function(event){
				borrar(event);
			});
		}
	}
}();