<?php
$arr_usuario = (object) $controlador_obj->getArrUsuario();
?>
<!-- User Account Menu -->
<li class="dropdown user user-menu">
	<?php if(!empty($controlador_obj->getArrUsuario())){?>
		<!-- Menu Toggle Button -->
		<a href="#" class="dropdown-toggle" data-toggle="dropdown">
			<!-- The user image in the navbar-->
			<span class="glyphicon glyphicon-user"></span>
			<!-- hidden-xs hides the username on small devices so only the image appears. -->
			<span class="hidden-xs"><?php echo $arr_usuario->usuario; ?></span>
		</a>
		<ul class="dropdown-menu">
			<!-- The user image in the menu -->
			<li class="user-header">
				<p><?php echo $arr_usuario->nombre_completo; ?>
				</p>
			</li>
			<!-- Menu Body -->
			<li class="user-body">
				<!-- 
				<div class="row">
					<div class="col-xs-4 text-center">
						<a href="#">Followers</a>
					</div>
					<div class="col-xs-4 text-center">
						<a href="#">Sales</a>
					</div>
					<div class="col-xs-4 text-center">
						<a href="#">Friends</a>
					</div>
				</div>
				 -->
			</li>
			<!-- Menu Footer-->
			<li class="user-footer">
				<div class="pull-left">
					<a href="<?php echo url_controlador('cat_usuario','abrir',array("cat_usuario_id"=>$arr_usuario->cat_usuario_id)); ?>" class="btn btn-default btn-flat">Ir a perfil</a>
				</div>
				<div class="pull-right">
					<a href="<?php echo url_controlador('desautentificar','inicio'); ?>" class="btn btn-default btn-flat">Cerrar sesión</a>
				</div>
			</li>
		</ul>
	<?php }?>
</li>