<!-- Forma AdminLTE3 -->
<form role="form" method="post" action="<?php echo define_controlador('formulario','guardar') ?>">
	<?php echo $controlador_obj->frm_al3->cmpOculto('cuestionario_id', $controlador_obj->getCampoValor('cuestionario_id'));?>
	<div class="row">
		<div class="col-md-6">
			<?php echo $controlador_obj->frm_al3->cmpTexto('p1', '1. Campo de texto', $controlador_obj->getArrAtributoCmp('p1')) ?>
		</div>
		<div class="col-md-6">
			<?php echo $controlador_obj->frm_al3->cmpCorreo('p2', '2. Campo de correo', $controlador_obj->getArrAtributoCmp('p2')) ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<?php echo $controlador_obj->frm_al3->cmpContrasenia('p3', '3. Campo de contraseña', $controlador_obj->getArrAtributoCmp('p3')) ?>
		</div>
		<div class="col-md-6">
			<?php echo $controlador_obj->frm_al3->cmpTextArea('p4', '4. Campo tipo textarea', $controlador_obj->getArrAtributoCmp('p4')) ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<?php echo $controlador_obj->frm_al3->cmpSelectArreglo('p5', '5. Campo select (fuente: arreglo)', $controlador_obj->arr_tmp_options, $controlador_obj->getArrAtributoCmp('p5')) ?>
		</div>
		<div class="col-md-6">
			<?php echo $controlador_obj->frm_al3->cmpSelectDeTbl('p6', 'cat_estado', 'cat_estado_id', 'descripcion', '', '6. Campo select (fuente: tabla)', $controlador_obj->getArrAtributoCmp('p6')) ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<?php echo $controlador_obj->frm_al3->cmpSelectDeSubCat('p7', 'm2p10r2', '7. Campo select (fuente: tabla de subcatálogos)', $controlador_obj->getArrAtributoCmp('p7')) ?>
		</div>
		<div class="col-md-6">
			<?php echo $controlador_obj->frm_al3->cmpSelectDeSubCat('p8', 'm2p10r1', '8. Campo select2', $controlador_obj->getArrAtributoCmp('p8')) ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label>9. Campos checkbox (Verticales)</label>
				<?php
					echo $controlador_obj->frm_al3->cmpCheckbox('p9r1', 'Valor 1', $controlador_obj->getArrAtributoCmp('p9r1'));
					echo $controlador_obj->frm_al3->cmpCheckbox('p9r2', 'Valor 2', $controlador_obj->getArrAtributoCmp('p9r2'));
					echo $controlador_obj->frm_al3->cmpCheckbox('p9r3', 'Valor 3', $controlador_obj->getArrAtributoCmp('p9r3'));
				?>
			</div>
		</div>
		<div class="col-md-6">
			<label>10. Campos checkbox (Horizontales)</label>
			<div class="form-group">
				<?php
					echo $controlador_obj->frm_al3->cmpCheckbox('p10r1', 'Valor 1', $controlador_obj->getArrAtributoCmp('p10r1'));
					echo $controlador_obj->frm_al3->cmpCheckbox('p10r2', 'Valor 2', $controlador_obj->getArrAtributoCmp('p10r2'));
				?>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label>11. Campos radio (Verticales)</label>
				<?php
					echo $controlador_obj->frm_al3->cmpRadioOpt('p11', '1', 'Valor 1', $controlador_obj->getArrAtributoCmp('p11'));
					echo $controlador_obj->frm_al3->cmpRadioOpt('p11', '2', 'Valor 2', $controlador_obj->getArrAtributoCmp('p11'));
					echo $controlador_obj->frm_al3->cmpRadioOpt('p11', '3', 'Valor 3', $controlador_obj->getArrAtributoCmp('p11'));
				?>
			</div>
		</div>
		<div class="col-md-6">
			<label>12. Campos radio (Horizontales)</label>
			<div class="form-group">
				<?php
					echo $controlador_obj->frm_al3->cmpRadioOpt('p12', '1', 'Valor 1', $controlador_obj->getArrAtributoCmp('p12'));
					echo $controlador_obj->frm_al3->cmpRadioOpt('p12', '2', 'Valor 2', $controlador_obj->getArrAtributoCmp('p12'));
					echo $controlador_obj->frm_al3->cmpRadioOpt('p12', '3', 'Valor 3', $controlador_obj->getArrAtributoCmp('p12'));
				?>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<?php echo $controlador_obj->frm_al3->cmpNum('p13', 2, '13. Campo numérico', $controlador_obj->getArrAtributoCmp('p13')) ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<button type="submit" class="btn btn-info float-right">Guardar</button>
		</div>
	</div>
</form>

			
			
			
			
			
			