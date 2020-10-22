<!DOCTYPE html>
<!--
	This is a starter template page. Use this page to start your new project from
	scratch. This page gets rid of all links and provides the needed markup only.
	-->
<html lang="en">
	<head>
		<?php include_once 'modulos/EnHead.php'; ?>
	</head>
	<body class="hold-transition layout-top-nav text-sm  accent-<?php echo COLOR_ACENTUAR; ?>">
		<form action="" id="frm_cero" name="frm_cero"  method="post">
			
		</form>
		<div class="wrapper">
			<!-- Navbar -->
			<nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
				<?php include_once 'modulos/EnMainHeader.php';?>
			</nav>
			<!-- /.navbar -->
			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper" style="min-height: 492.85px;">
				<!-- Content Header (Page header) -->
				<?php include_once 'modulos/ContentHeader.php'; ?>
				<!-- /.content-header -->
				<!-- Main content -->
				<div class="content">
					<div class="container-fluid">
						<!-- Formularios -->
						<div class="row">
							<div class="col-md-12">
								<div class="card card-primary card-outline">
									<div class="card-header">
										<h3 class="card-title">
											Formulario AdminLTE 3
										</h3>
									</div>
									<div class="card-body">
										<?php include_once 'modulos/Forma/FormAdminLTE3.php'; ?>
									</div><!-- /.card-body -->
								</div><!-- /.card -->
							</div>
						</div><!-- Formularios -->
						<!-- Log -->
						<div class="row">
							<div class="col-md-12">
								<div class="card card-primary card-outline">
									<div class="card-header">
										<h3 class="card-title">
											Arreglo de formulario AdminLTE 3
										</h3>
									</div>
									<div class="card-body">
										<div class="form-group">
											<textarea class="form-control" rows="3" style="color: white;background-color: darkslategrey;"><?php echo $controlador_obj->frm_al3->imprimeArrCmpAtrib(); ?></textarea>
										</div>
									</div>
								</div>
							</div>
						</div>
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
		<?php include_once 'modulos/ScriptCuestForma.php'; ?>
	</body>
</html>