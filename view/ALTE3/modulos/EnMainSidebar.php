<?php
$sb_mnu_opc_activo = "";	//Sidebar menu - opcion activada o seleccionada
$controlador_act = $controlador_obj->getControlador();
$accion_act = $controlador_obj->getAccion();
$tag_forma = "";
$son_acc_de_mml_forma = ($accion_act=="ficha" || $accion_act=="var_def");	//Si la acción pertenece a una de las formas dentro de MML Indicador
if($controlador_act == CONTROLADOR_DEFECTO && $accion_act == ACCION_DEFECTO){
	$sb_mnu_opc_activo="a_mnu_opc_inicio";
}elseif($controlador_act=="mml_indicador" && $accion_act=="vista_ind" || $accion_act=="vista_var"){
	$sb_mnu_opc_activo="a_mnu_opc_vista";
}elseif($controlador_act=="mml_indicador" && $son_acc_de_mml_forma){
	$sb_mnu_opc_activo= "a_mnu_opc_forma";
}
$accion_act_sel_frm = "";
$accion_act_sel_vista = "";
if($controlador_act=="mml_indicador" && $controlador_obj->getIndFolio() ){
	$txt_mnu = ($controlador_obj->getIndFolio()!="")? "Folio: ".$controlador_obj->getIndFolio() : "Forma";
	$accion_act_sel_frm = ($controlador_obj->getPestaniaFrmAct()!="")? $controlador_obj->getPestaniaFrmAct() : "ficha";
	$accion_act_sel_vista = ($controlador_obj->getPestaniaVistaAct()!="")? $controlador_obj->getPestaniaVistaAct() : "vista_ind";
	$arr_tag_forma = array();
	$arr_tag_forma[] = '<li class="nav-item">';
	$arr_tag_forma[] = '	<a id="'.$sb_mnu_opc_activo.'" href="'.url_controlador("mml_indicador",$accion_act_sel_frm,"",true).'" class="nav-link">';
	$arr_tag_forma[] = '		<i class="nav-icon fas fa-clipboard-list"></i><p>'.$txt_mnu.'</p>';
	$arr_tag_forma[] = '	</a>';
	$arr_tag_forma[] = '</li>';
	$tag_forma =   tag_string($arr_tag_forma);
}
?>
<!-- Brand Logo -->
				<a href="/<?php echo DIR_LOCAL; ?>/index.php" class="brand-link">
					<img src="/<?php echo DIR_LOCAL; ?>/assets/img/logo.png" alt="<?php echo TIT_LARGO ?>" class="brand-image img-circle elevation-3" style="opacity: .8">
					<span class="brand-text font-weight-light" title="<?php echo TIT_LARGO ?>"><?php echo TIT_CORTO_P1; ?> <?php echo TIT_CORTO_P2; ?></span>
				</a>
				<!-- Sidebar -->
				<div class="sidebar">
					<!-- Sidebar user panel (optional) -->
					<div class="user-panel mt-3 pb-3 mb-3 d-flex">
						<div class="info">
							<a href="#" class="d-block"><i class="fas fa-user mr-1"></i>Ismael Rojas</a>
						</div>
					</div>
					<!-- Sidebar Menu -->
					<nav class="mt-2">
						<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
							<li class="nav-item">
								<a id="a_mnu_opc_inicio" href="<?php echo url_controlador()?>" class="nav-link">
									<i class="nav-icon fas fa-home"></i><p>Inicio</p>
								</a>
							</li>
							<li class="nav-header">INDICADORES MML</li>
							<li class="nav-item">
								<a id="a_mnu_opc_vista" href="<?php echo url_controlador("mml_indicador",$accion_act_sel_vista,"",true)?>" class="nav-link">
									<i class="nav-icon fas fa-th-list"></i><p>Consulta</p>
								</a>
							</li>
							<?php echo $tag_forma; ?>
						</ul>
					</nav>
					<!-- /.sidebar-menu -->
				</div>
				<!-- /.sidebar -->
				<script>
					
				</script>
