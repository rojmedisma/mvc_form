<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Bootstrap Example</title>
		<meta charset="utf-8">
		<meta name="Keywords" content="Ismael Rojas, desarrollador, videoconferencias, mesa trabajo"/>
		<meta name="author" content="Ismael Rojas Medina">
		<meta name="copyright" content="Ismael Rojas Medina">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	</head>
	<body>
		<nav class="navbar">
			<ul class="nav">
				<li class="nav-item">
					<a class="nav-link" href="<?php echo define_controlador(); ?>">Inicio</a>
				</li>
			</ul>
		</nav>
		<div class="container">
			
			<div class="row">
				<div class="col-12">
					<h2>Generar tabla de campos </h2>
					<p>Pantalla para convertir el arreglo json <em>arr_cmp_atrib</em> generado por la clase modelo <em>FormularioALTE3</em> en una tabla</p>
					<form action="<?php echo define_controlador("tblcampos", "crear") ?>" method="post">
						<div class="form-group">
							<label for="comment">Coloca aquí el arreglo <em>arr_cmp_atrib</em>:</label>
							<textarea class="form-control" rows="5" id="cmp_json" name="cmp_json"><?php echo $controlador_obj->getCmpJson(); ?></textarea>
						</div>
						<button type="submit" class="btn btn-primary">Crear tabla</button>
					</form>
				</div>
				<div class="col-12">
					<h2>Tabla</h2>
					<table class="table">
						<thead>
							<tr>
								<th scope="col">cmp_id_nom</th>
								<th scope="col">cmp_tipo</th>
								<th scope="col">xls_tipo_id</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($controlador_obj->getArrCmpTbl() as $arr_det_tbl){ ?>
							<tr>
								<td><?php echo $arr_det_tbl->cmp_id_nom; ?></td>
								<td><?php echo $arr_det_tbl->cmp_tipo; ?></td>
								<td><?php echo $arr_det_tbl->xls_tipo_id; ?></td>
							</tr>
							<?php }?>
						</tbody>
					</table>
				</div>
			</div>
			
		</div>
	</body>
</html>