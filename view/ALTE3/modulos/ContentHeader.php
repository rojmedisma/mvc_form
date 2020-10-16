<!-- Content Header (Page header) -->
				<div class="content-header">
					<div class="container-fluid">
						<div class="row mb-2">
							<div class="col-sm-6">
								<h1 class="m-0 text-dark"><?php echo $controlador_obj->getTituloPagina(); ?></h1>
							</div>
							<!-- /.col -->
							<div class="col-sm-6">
								<ol class="breadcrumb float-sm-right">
								<?php if($controlador_obj->getPaginaAnterior(1, 'titulo_pagina')!=""){ ?>
									<li class="breadcrumb-item"><a href="<?php echo url_controlador($controlador_obj->getPaginaAnterior(1, 'controlador'), $controlador_obj->getPaginaAnterior(1, 'accion'), "", true);?>"><?php echo $controlador_obj->getPaginaAnterior(1, 'titulo_pagina'); ?></a></li>
								<?php }?>
								<?php if($controlador_obj->getPaginaAnterior(0, 'titulo_pagina')!=""){ ?>
									<li class="breadcrumb-item"><a href="<?php echo url_controlador($controlador_obj->getPaginaAnterior(0, 'controlador'), $controlador_obj->getPaginaAnterior(0, 'accion'), "", true);?>"><?php echo $controlador_obj->getPaginaAnterior(0, 'titulo_pagina'); ?></a></li>
								<?php }?>
									<li class="breadcrumb-item active"><?php echo $controlador_obj->getTituloPagina(); ?></li>
								</ol>
							</div>
							<!-- /.col -->
						</div>
						<!-- /.row -->
					</div>
					<!-- /.container-fluid -->
				</div>
				<!-- /.content-header -->
