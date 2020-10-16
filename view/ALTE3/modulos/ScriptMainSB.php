<!-- Main Sidebar -->
		<script src="/<?php echo DIR_LOCAL; ?>/assets/js/MainSidebar.js"></script>
		<script>
			var sb_mnu_opc_activo = '<?php echo $sb_mnu_opc_activo; ?>';	//La variable se declara en modulos/EnMainSidebar.php
			$(document).ready(function(){
				MainSidebar.activar(sb_mnu_opc_activo);
			});
		</script>