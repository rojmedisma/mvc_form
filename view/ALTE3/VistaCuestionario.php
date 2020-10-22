<!DOCTYPE html>
<html>
	<head>
		<?php include_once 'modulos/EnHead.php'; ?>
	</head>
	<body class="hold-transition sidebar-mini layout-fixed accent-<?php echo COLOR_ACENTUAR; ?>">
		<!-- Site wrapper -->
		<div class="wrapper">
			<!-- Navbar -->
			<nav class="main-header navbar navbar-expand navbar-white navbar-light">
				<?php include_once 'modulos/Tablero/EnMainHeader.php'; ?>
			</nav>
			<!-- /.navbar -->
			<!-- Main Sidebar Container -->
			<aside class="main-sidebar sidebar-dark-<?php echo COLOR_ACENTUAR; ?> elevation-4">
				<?php include_once 'modulos/Tablero/EnMainSidebar.php'; ?>
			</aside>
			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<?php include_once 'modulos/EnContentHeader.php'; ?>
				</section>
				<!-- Main content -->
				<section class="content">
					<div class="container-fluid">
						<div class="row">
							<div class="col-12">
								<!-- Default box -->
								<div class="card">
									<div class="card-header">
										<h3 class="card-title">Title</h3>
									</div>
									<div class="card-body">
										Vista cuestionario
									</div>
									<!-- /.card-footer-->
								</div>
								<!-- /.card -->
							</div>
						</div>
					</div>
				</section>
				<!-- /.content -->
			</div>
			<!-- /.content-wrapper -->
			<footer class="main-footer">
				<?php include_once 'modulos/EnFooter.php'; ?>
			</footer>
			<!-- /.control-sidebar -->
		</div>
		<!-- ./wrapper -->
		<?php include_once 'modulos/Scripts.php'; ?>
	</body>
</html>