<!-- sidebar: style can be found in sidebar.less -->
<section class="sidebar">
	<!-- sidebar menu: : style can be found in sidebar.less -->
	<ul class="sidebar-menu" data-widget="tree">
		<li class="header" style="font-size:1em;color:#fff;">เมนูหลัก</li>
		<li>
			<a href="{{ route('dbd') }}">
				<i class="fa fa-tachometer" aria-hidden="true"></i> <span>Dashboard</span>
			</a>
		</li>
		<li class="treeview">
			<a href="#">
				<i class="fa fa-list-ol" aria-hidden="true"></i> <span>รายการข้อมูล</span>
				<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
			</a>
			<ul class="treeview-menu">
				<?php
					$current_year =  (isset($_GET['year']))? $_GET['year']: date('Y');
					/* Current Year */
					$already_selected_value = (int)date('Y');
					/* Start Year */
					$earliest_year = 2012;
					foreach (range($already_selected_value, $earliest_year) as $x) {
						 $year_th = ($x+543);
						 echo '<li '.($x === (int)$current_year ? 'class="active"' : '').'><a href="'.route('population').'?year='.$x.'"><i class="fa fa-circle-o"></i>'.$year_th.'</a></li>';
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
				<li><a href="{{ route('export.form') }}?year=<?php echo $current_year; ?>"><i class="fa fa-circle-o text-red"></i> รายโรค</a></li>
				<li><a href="{{ route('export-population.main') }}"><i class="fa fa-circle-o text-aqua"></i> ประชากร</a></li>
			</ul>
		</li>
		<li class="treeview">
			<a href="#">
				<i class="fa fa-pie-chart"></i>
				<span>รายงาน</span>
				<span class="pull-right-container">
					<i class="fa fa-angle-left pull-right"></i>
				</span>
			</a>
			<ul class="treeview-menu nav-level2">
				<li>
					<a href="{{ route('top10DsPt') }}"><i class="fa fa-circle-o text-red"></i> 10 อันดับโรคที่มีอัตราป่วยสูงสุด</a>
				</li>
				<li>
					<a href="{{ route('onePage') }}"><i class="fa fa-circle-o text-yellow"></i> One page report</a>
				</li>
				<li>
					<a href="{{ route('export-patient-data.main') }}"><i class="fa fa-circle-o text-aqua"></i> รายงานข้อมูลผู้ป่วย </a>
				</li>
			</ul>
		</li>
	</ul>
</section>
<!-- /.sidebar -->
