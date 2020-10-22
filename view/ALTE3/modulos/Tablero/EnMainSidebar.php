<!-- Brand Logo -->
<a href="<?php echo define_controlador(); ?>" class="brand-link" title="<?php echo TIT_CORTO_P1." ".TIT_CORTO; ?>">
	<span class="brand-text font-weight-light pl-3"><?php echo TIT_CORTO_P1 ?> <strong><?php echo TIT_CORTO_P2 ?></strong></span>
</a>
<!-- Sidebar -->
<div class="sidebar">
	<!-- Sidebar Menu -->
	<nav class="mt-2">
		<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
			<?php echo $controlador_obj->getHTMLTag('li_nav_item_sb'); ?>
			
			<li class="nav-header">MULTI LEVEL EXAMPLE</li>
			<li class="nav-item">
				<a href="#" class="nav-link">
					<i class="fas fa-circle nav-icon"></i>
					<p>Level 1</p>
				</a>
			</li>
			<li class="nav-item has-treeview">
				<a href="#" class="nav-link">
					<i class="nav-icon fas fa-circle"></i>
					<p>
						Level 1
						<i class="right fas fa-angle-left"></i>
					</p>
				</a>
				<ul class="nav nav-treeview">
					<li class="nav-item">
						<a href="#" class="nav-link">
							<i class="far fa-circle nav-icon"></i>
							<p>Level 2</p>
						</a>
					</li>
					<li class="nav-item has-treeview">
						<a href="#" class="nav-link">
							<i class="far fa-circle nav-icon"></i>
							<p>
								Level 2
								<i class="right fas fa-angle-left"></i>
							</p>
						</a>
						<ul class="nav nav-treeview">
							<li class="nav-item">
								<a href="#" class="nav-link">
									<i class="far fa-dot-circle nav-icon"></i>
									<p>Level 3</p>
								</a>
							</li>
							<li class="nav-item">
								<a href="#" class="nav-link">
									<i class="far fa-dot-circle nav-icon"></i>
									<p>Level 3</p>
								</a>
							</li>
							<li class="nav-item">
								<a href="#" class="nav-link">
									<i class="far fa-dot-circle nav-icon"></i>
									<p>Level 3</p>
								</a>
							</li>
						</ul>
					</li>
					<li class="nav-item">
						<a href="#" class="nav-link">
							<i class="far fa-circle nav-icon"></i>
							<p>Level 2</p>
						</a>
					</li>
				</ul>
			</li>
			<li class="nav-item">
				<a href="#" class="nav-link">
					<i class="fas fa-circle nav-icon"></i>
					<p>Level 1</p>
				</a>
			</li>
			<li class="nav-header">LABELS</li>
			<li class="nav-item">
				<a href="#" class="nav-link">
					<i class="nav-icon far fa-circle text-danger"></i>
					<p class="text">Important</p>
				</a>
			</li>
			<li class="nav-item">
				<a href="#" class="nav-link">
					<i class="nav-icon far fa-circle text-warning"></i>
					<p>Warning</p>
				</a>
			</li>
			<li class="nav-item">
				<a href="#" class="nav-link">
					<i class="nav-icon far fa-circle text-info"></i>
					<p>Informational</p>
				</a>
			</li>
		</ul>
	</nav>
	<!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->

