<!DOCTYPE html>
<html>
	<head>
		<?php include_once 'modulos/EnHead.php'; ?>
		<style type="text/css">
        .tlargo {
        	font-size: 1.5rem;
        	font-weight: 300;
        	margin-bottom: .9rem;
        	text-align: center;
        }
		</style>
	</head>
	<body class="hold-transition login-page">
		<div class="login-box">
			<div class="login-logo tlargo">
				<a href="/<?php echo DIR_LOCAL; ?>/index.php"><b><?php echo TIT_LARGO; ?></b></a>
			</div>
			<!-- /.login-logo -->
			<div class="card">
				<div class="card-body login-card-body">
					<?php if($controlador_obj->getEsInfoIncorrecta()){ ?>
					<div class="callout callout-danger">
                    	<h5><i class="icon fas fa-ban"></i> Acceso denegado!</h5>
                    	<p>Nombre de usuario o contraseña incorrectos</p>
                    </div>
                    <?php } ?>
					<p class="login-box-msg">Iniciar sesión</p>
					<form action="<?php echo url_controlador('autentificar','autentificar', '', false) ?>" method="post">
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
						<div class="row">
							<div class="col-8">
								<div class="icheck-primary">
									<input type="checkbox" id="remember">
									<label for="remember">
									Recordar
									</label>
								</div>
							</div>
							<!-- /.col -->
							<div class="col-4">
								<button type="submit" class="btn btn-primary btn-block">Ingresar</button>
							</div>
							<!-- /.col -->
						</div>
					</form>
					
					
				</div>

				<!-- /.login-card-body -->
			</div>
		</div>
						
		<!-- /.login-box -->
		<?php include_once 'modulos/Scripts.php'; ?>
	</body>
</html>