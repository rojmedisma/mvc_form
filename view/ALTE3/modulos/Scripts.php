<!-- REQUIRED SCRIPTS -->
        <!-- jQuery -->
		<script src="/library/AdminLTE_3/plugins/jquery/jquery.min.js"></script>
		<!-- Bootstrap 4 -->
		<script src="/library/AdminLTE_3/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
		<!-- DataTables -->
		<script src="/library/AdminLTE_3/plugins/datatables/jquery.dataTables.js"></script>
		<script src="/library/AdminLTE_3/plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
		<!-- bs-custom-file-input -->
		<script src="/library/AdminLTE_3/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
		<?php if($controlador_obj->getConMenuLateralFijo()){?>
		<!-- overlayScrollbars -->
		<script src="/library/AdminLTE_3/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
		<?php }?>
		<?php if($controlador_obj->getUsarLibToastr()){?>
		<!-- Toastr: Para las alertas -->
		<script src="/library/AdminLTE_3/plugins/toastr/toastr.min.js"></script>
		<?php }?>
		<!-- AdminLTE App -->
		<script src="/library/AdminLTE_3/dist/js/adminlte.min.js"></script>
		<!-- Principal -->
		<script src="/<?php echo DIR_LOCAL; ?>/assets/js/Principal.js"></script>

