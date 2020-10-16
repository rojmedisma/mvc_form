<?php
$arr_usuario = (object) $controlador_obj->getArrUsuario();
?>
<!-- Control sidebar -->
				<div class="p-3">
					<h5>Usuario</h5>
					<?php if(!empty($controlador_obj->getArrUsuario())){?>
					<i class="fas fa-user mr-1"></i>
					<span class="hidden-xs"><?php echo $arr_usuario->usuario; ?></span>
					<?php }?>
					<a href="<?php echo url_controlador('desautentificar','inicio'); ?>" type="button" class="btn btn-block btn-outline-secondary">Cerrar sesión</a>
					
				</div>
