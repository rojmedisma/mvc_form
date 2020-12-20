var VistaPermiso = function(){
	function borrar(event){
		if(!confirm("¿Desea borrar el registro?")){
			event.preventDefault();
		}
	}
	return{
		activar:function(){
			$('#tbl_permisos').DataTable({
				"order": [[ 0, "desc" ]],
				"searching": false,
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