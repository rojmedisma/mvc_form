<!-- Forma cero -->
		<form action="" id="frm_cero" name="frm_cero"  method="post">
			<input type="hidden" name="mml_ficha_tecnica_id" id="mml_ficha_tecnica_id" value="<?php echo $controlador_obj->getMMLFichaTecnicaId(); ?>">
			<input type="hidden" name="mml_variable_def_id" id="mml_variable_def_id" value="<?php echo $controlador_obj->getMMLVariableDefId(); ?>">
			<input type="hidden" name="pestania_vista_act" id="pestania_vista_act" value="<?php echo $controlador_obj->getPestaniaVistaAct(); ?>">
			<input type="hidden" name="pestania_frm_act" id="pestania_frm_act" value="<?php echo $controlador_obj->getPestaniaFrmAct(); ?>">
		</form>
