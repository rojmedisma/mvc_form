<!-- Left navbar links -->
<ul class="navbar-nav">
	<li class="nav-item">
		<a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
	</li>
</ul>

<!-- Right navbar links -->
<ul class="navbar-nav ml-auto">
	<!-- Notifications Dropdown Menu -->
	<li class="nav-item dropdown">
		<a class="nav-link" data-toggle="dropdown" href="#">
			<i class="far fa-user"></i> <?php echo $controlador_obj->usuario_dato("usuario") ?>
		</a>
		<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
			<span class="dropdown-item dropdown-header"><?php echo concatena_nombre($controlador_obj->usuario_dato("nombre"),$controlador_obj->usuario_dato("ap_paterno"),$controlador_obj->usuario_dato("ap_materno")) ?></span>
			<div class="dropdown-divider"></div>
			<a href="<?php echo define_controlador('desautentificar','inicio'); ?>" class="dropdown-item"><i class="fas fa-sign-out-alt mr2"></i> Cerrar Sesión</a>
		</div>
	</li>
</ul>
