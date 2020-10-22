<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include_once 'modulos/EnHead.php'; ?>
	</head>
	<body class="hold-transition layout-top-nav text-sm accent-<?php echo COLOR_ACENTUAR; ?>">
		<form action="" id="frm_cero" name="frm_cero"  method="post">
			
		</form>
		<div class="wrapper">
			
			<!-- /.navbar -->
			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper" style="min-height: 492.85px;">
				<!-- Content Header (Page header) -->
				<!-- Main content -->
				<div class="content">
					<div class="container">
						<!-- Formularios -->
						<div class="row pt-3">
							<div class="col-md-8">
								<div class="card">
									<div class="card-body">
										<h4><?php echo TIT_LARGO; ?></h4>
										<h5 class="pt-3">Objetivo del proyecto</h5>
										<p>Elaborar propuesta de mecanismo capaz de recabar datos relevantes, confiables y representativos para el cálculo del inventario de gases de efecto invernadero en el sector agropecuario, acuícola y pesquero y adecuar la plataforma del SIAP para su almacenamiento y manejo.</p>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="card">
									<div class="card-header">
										<h5 class="card-title m-0">Iniciar Sesión</h5>
									</div>
									<div class="card-body">
										<form action="<?php echo define_controlador('autentificar','autentificar', '', false) ?>" method="post">
											<div class="input-group mb-3">
												<input name="usuario" id="usuario" type="text" class="form-control" placeholder="Usuario">
												<div class="input-group-append">
													<div class="input-group-text">
														<span class="fas fa-user"></span>
													</div>
												</div>
											</div>
											<div class="input-group mb-3">
												<input name="clave" id="clave" type="password" class="form-control" placeholder="Contraseña">
												<div class="input-group-append">
													<div class="input-group-text">
														<span class="fas fa-lock"></span>
													</div>
												</div>
											</div>
											<?php if($controlador_obj->getEsInfoIncorrecta()){ ?>
											<div class="callout callout-danger">
						                    	<h5><i class="icon fas fa-ban"></i> Acceso denegado!</h5>
						                    	<p>Nombre de usuario o contraseña incorrectos</p>
						                    </div>
						                    <?php } ?>
											<button type="submit" class="btn btn-primary btn-block">Ingresar</button>
										</form>
									</div>
								</div>
							</div>
						</div><!-- Formularios -->
						
					</div><!-- /.container-fluid -->
				</div><!-- /.content -->
			</div>
			<!-- /.content-wrapper -->
			<!-- Main Footer -->
			<footer class="main-footer">
				<?php include_once 'modulos/EnFooter.php'; ?>
			</footer>
		</div>
		<!-- ./wrapper -->
		<?php include_once 'modulos/Scripts.php'; ?>
	</body>
</html>