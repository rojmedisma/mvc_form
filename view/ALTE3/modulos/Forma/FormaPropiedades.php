<form role="form" method="post" action="<?php echo define_controlador('formulario','guardar') ?>">
	<?php echo $controlador_obj->getHTMLCamposOcultosBase();?>
	<?php echo $controlador_obj->frm_al3->cmpOculto('cuest_prop_id', $controlador_obj->getCampoValor('cuest_prop_id'));?>
	<div class="row">
		<div class="col-12">
			<h5 class="text-info">Desplegar opción seleccionada en campo <strong>Select</strong> en diferentes tags tipo <strong>div</strong></h5>
			<?php echo $controlador_obj->frm_al3->cmpSelectDeSubCat('p1', 'm2p10r2', '1. Seleccionar') ?>
			<div class="row pl-3">
				<div class="col-4">
					<div class="div_p1_desc_desp">
						<span>Seleccionó la opción:</span>
						<p class="text-info"><?php echo $controlador_obj->getCampoValor('p1_desc');?></p>
					</div>
				</div>
				<div class="col-4">
					<div class="div_p1_desc_desp">
						<span>Seleccionó la opción:</span>
						<span class="text-danger"><?php echo $controlador_obj->getCampoValor('p1_desc');?></span>
					</div>
				</div>
				<div class="col-4">
					<div class="div_p1_desc_desp">
						<span></span>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12">
			<h5 class="text-info">Funcionalidad tipo <strong>Otro (especificar)</strong> con campo <strong>Select</strong></h5>
			<?php echo $controlador_obj->frm_al3->cmpSelectDeSubCat('p2', 'multi_esp', '2. Seleccionar entre opciones Otro y normales') ?>
			<div class="row pl-3">
				<div class="col-4">
					<div class="<?php echo $controlador_obj->getCssDivEsp('p2', '3'); ?>" data-opc_id="3">
						<?php echo $controlador_obj->frm_al3->cmpTexto('p2esp1', '15-1. Campo de texto') ?>
					</div>
				</div>
				<div class="col-4">
					<div class="<?php echo $controlador_obj->getCssDivEsp('p2', '6'); ?>" data-opc_id="6">
						<?php echo $controlador_obj->frm_al3->cmpNum('p2esp2', 0, '15-2. Campo entero') ?>
					</div>
				</div>
				<div class="col-4">
					<div class="<?php echo $controlador_obj->getCssDivEsp('p2', '9'); ?>" data-opc_id="9">
						<?php echo $controlador_obj->frm_al3->cmpNum('p2esp3', 2, '15-3. Campo con dos decimales') ?>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12">
			<h5 class="text-info">Bloquear campos</h5>
			<?php echo $controlador_obj->frm_al3->cmpSelectDeSubCat('p3', 'secciones', '3. Seleccione la sección a bloquear') ?>
			<div class="row pl-3">
				<div class="col-6">
					<p>Sección 1</p>
					<div id="div_p3_sec1">
						<div><p>Contenido sin campos</p></div>
						<?php echo $controlador_obj->frm_al3->cmpTexto('p3s1r1', '3.1.1 Campo de texto') ?>
						<div class="pl-3">
							<?php echo $controlador_obj->frm_al3->cmpTexto('p3s1r2', '3.1.1.1 Campo de texto') ?>
							<?php echo $controlador_obj->frm_al3->cmpCorreo('p3s1r3', '3.1.1.2 Campo de correo') ?>
							<?php echo $controlador_obj->frm_al3->cmpSelectDeSubCat('p3s1r4', 'm2p10r2', '3.1.1.3 Seleccionar') ?>
						</div>
						<?php echo $controlador_obj->frm_al3->cmpSelectDeSubCat('p3s1r5', 'm2p10r2', '3.1.2 Seleccionar') ?>
					</div>
				</div>
				<div class="col-6">
					<p>Sección 2</p>
					<!-- campo oculto -->
					<div id="div_p3_sec2">
						<?php echo $controlador_obj->frm_al3->cmpOculto('p3s2r1', 'Algún valor siempre'); ?>
						<?php echo $controlador_obj->frm_al3->cmpTextArea('p3s2r2', '3.2.1 Campo tipo textarea') ?>
						<div class="pl-3">
							<div class="form-group">
								<label>3.2.1.1. Campos checkbox (Verticales)</label>
								<?php
									echo $controlador_obj->frm_al3->cmpCheckbox('p3s2r3c1', 'Valor 1');
									echo $controlador_obj->frm_al3->cmpCheckbox('p3s2r3c2', 'Valor 2');
								?>
							</div>
							<div class="form-group">
								<label>11. Campos radio (Verticales)</label>
								<?php
									echo $controlador_obj->frm_al3->cmpRadioOpt('p3s2r4', '1', 'Valor 1', $controlador_obj->getArrAtributoCmp('p11'));
									echo $controlador_obj->frm_al3->cmpRadioOpt('p3s2r4', '2', 'Valor 2', $controlador_obj->getArrAtributoCmp('p11'));
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12">
			<h5 class="text-info">Bloquear y ocultar campos</h5>
			<?php echo $controlador_obj->frm_al3->cmpSelectDeSubCat('p4', 'secciones', '3. Seleccione la sección a mostrar') ?>
			<div class="row pl-3">
				<div class="col-12">
					<div id="div_p4_s1">
						<p>Sección 1</p>
						<?php echo $controlador_obj->frm_al3->cmpTexto('p4s1r1', '4.1.1 Campo de texto') ?>
						<?php echo $controlador_obj->frm_al3->cmpTexto('p4s1r2', '4.1.2 Campo de texto') ?>
						<?php echo $controlador_obj->frm_al3->cmpTexto('p4s1r3', '4.1.3 Campo de texto') ?>
					</div>
					<div id="div_p4_s2">
						<p>Sección 2</p>
						<div class="form-group">
							<label>4.2.1. Campos checkbox (Verticales)</label>
							<?php
								echo $controlador_obj->frm_al3->cmpCheckbox('p4s2r1c1', 'Valor 1');
								echo $controlador_obj->frm_al3->cmpCheckbox('p4s2r1c2', 'Valor 2');
							?>
						</div>
					</div>
					<div id="div_p4_s3">
						<p>Sección 3</p>
						<?php echo $controlador_obj->frm_al3->cmpTextArea('p4s3r1', '4.3.1 Campo tipo textarea') ?>
					</div>
				</div>
			</div>
			
				
			
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<button type="submit" class="btn btn-info float-right">Guardar</button>
		</div>
	</div>
</form>
