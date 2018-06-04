<!-- sidebar: style can be found in sidebar.less -->
<section class="sidebar">
	<!-- sidebar menu: : style can be found in sidebar.less -->
	<ul class="sidebar-menu" data-widget="tree">
		<li class="header">เมนูหลัก</li>
		<li class="treeview">
			<a href="#">
				<i class="fa fa-calendar-check-o"></i> <span>รายการข้อมูล</span>
				<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
			</a>
			<ul class="treeview-menu" style="display: block;">
				<?php
					$current_year =  (isset($_GET['year']))? $_GET['year']: date('Y');
					/* Current Year */
					$already_selected_value = (int)date('Y');
					/* Start Year */
					$earliest_year = 2012;
					foreach (range($already_selected_value, $earliest_year) as $x) {
						 $year_th = ($x+543);
						 echo '<li '.($x === (int)$current_year ? 'class="active"' : '').'><a href="'.route('dashboard').'?year='.$x.'"><i class="fa fa-circle-o"></i>'.$year_th.'</a></li>';
					}
				?>
			</ul>
		</li>
		<li class="treeview">
			<a href="#">
				<i class="fa fa-cloud-download"></i> <span>ส่งออกข้อมูล</span>
				<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
			</a>
			<ul class="treeview-menu">
				<li><a href="{{ route('export.form') }}?year=<?php echo $current_year; ?>"><i class="fa fa-circle-o"></i> Export File</a></li>
			</ul>
		</li>
		<li>
			<a href="{{ route('report') }}">
				<i class="fa fa-area-chart"></i> <span>รายงาน</span>
			</a>
		</li>
	</ul>
</section>
<!-- /.sidebar -->
