<!-- sidebar: style can be found in sidebar.less -->
<section class="sidebar">
	<!-- sidebar menu: : style can be found in sidebar.less -->
	<ul class="sidebar-menu" data-widget="tree">
		<li class="header">MAIN NAVIGATION</li>
		<li class="treeview">
			<a href="#">
				<i class="fa fa-dashboard"></i> <span>Demo</span>
				<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
			</a>
			<ul class="treeview-menu">
			    <li><a href="{{ route('dashboard') }}"><i class="fa fa-circle-o"></i> Population</a></li>
				<li><a href="{{ route('export.form') }}"><i class="fa fa-circle-o"></i> Export</a></li>
			</ul>
		</li>
	</ul>
</section>
<!-- /.sidebar -->