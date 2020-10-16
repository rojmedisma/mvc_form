<!-- Cabecera para Top Navigation -->
<!-- main-header -->
				<div class="container-fluid">
					<a href="<?php echo url_controlador()?>" class="navbar-brand">
						<span class="brand-text font-weight-light"><?php echo TIT_CORTO_P1." ".TIT_CORTO_P2?></span>
					</a>
					<button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"></span>
					</button>
					<div class="collapse navbar-collapse order-3" id="navbarCollapse">
						<ul class="navbar-nav">
							<?php if($controlador_obj->tienePermiso("cat_usuario")){?>
							<li class="nav-item">
								<a href="<?php echo url_controlador('cat_usuario','vista'); ?>" class="nav-link">Catálogo de usuarios</a>
							</li>
							<?php }?>
						</ul>
					</div>
					<!-- Right navbar links -->
					<ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
						<li class="nav-item dropdown">
							<a class="nav-link" data-toggle="dropdown" href="#">
								<i class="far fa-user"></i> <?php echo $controlador_obj->usuario_dato("usuario") ?>
							</a>
							<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
								<span class="dropdown-header"><?php echo concatena_nombre($controlador_obj->usuario_dato("nombre"),$controlador_obj->usuario_dato("ap_paterno"),$controlador_obj->usuario_dato("ap_materno")) ?></span>
								<div class="dropdown-divider"></div>
								
								<div class="dropdown-divider"></div>
								<a href="<?php echo url_controlador('desautentificar','inicio'); ?>" class="dropdown-item"><i class="fas fa-sign-out-alt mr2"></i> Cerrar Sesión
								</a>
							</div>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="<?php echo url_controlador('desautentificar','inicio'); ?>" title="Cerrar Sesión"><i class="fas fa-sign-out-alt mr2"></i></a>
						</li>
					</ul>
				</div>