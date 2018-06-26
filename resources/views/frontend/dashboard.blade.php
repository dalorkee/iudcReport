@extends('layouts.template')
@section('style')
	<!-- daterange picker -->
	{{ Html::style(('public/AdminLTE-2.4.2/bower_components/bootstrap-daterangepicker/daterangepicker.css')) }}
	  <!-- Select2 -->
	{{ Html::style(('public/AdminLTE-2.4.2/bower_components/select2/dist/css/select2.min.css')) }}
	<style>
		.select2 * {
			moz-border-radius: 0 !important;
			webkit-border-radius: 0 !important;
			border-radius: 0 !important;
		}
		.ds-box-title {
			font-size: .80em;
		}
		.charts-box {
			min-height: 350px;
			margin: 20px 0;
		}
	</style>
@endsection
@section('incHeaderScript')
	{{ Html::script(('public/components/Chart.PieceLabel.js/src/Chart.bundle.min.js')) }}
	{{ Html::script(('public/components/Chart.PieceLabel.js/src/Chart.PieceLabel.js')) }}
@endsection
@section('content')
<section class="content-header">
	<h1>Dashboard</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> หน้าหลัก</a></li>
		<li class="active">Dashboard</li>
	</ol>
</section>
<!-- Main content -->
<section class="content">
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">Filter</h3>
			<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
				<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
			</div>
		</div>
		<!-- /.box-header -->
		<div class="box-body">
			<div class="row">
				<div class="col-md-3">
					<div class="form-group">
						<label>เลือกโรค:</label>
						<select class="form-control select2" style="width: 100%;">
						<?php
							$i = 1;

							foreach ($dsgroups as $dsgroup) {
								if ($i == 1) {
									echo "<option value=\"".$dsgroup->DISEASE."\" selected=\"selected\">".$dsgroup->DISNAME."</option>\n";
								} else {
									echo "<option value=\"".$dsgroup->DISEASE."\">".$dsgroup->DISNAME."</option>\n";
								}
								$i++;
							}
						?>
						</select>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>วันที่:</label>
						<div class="input-group">
							<div class="input-group-addon">
								<i class="fa fa-calendar"></i>
							</div>
							<input type="text" class="form-control pull-right" id="reservation">
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						{{ Html::link('ds', 'Find', array(
							'class'=>'btn btn-primary'
						)) }}
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="box" style="border:none;background:none;box-shadow:none;">
		<!-- /.row -->
		<div class="row">
			<!-- Left col#1 -->
			<div class="col-md-6">
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title"><span class="ds-box-title">ร้อยละผู้ป่วยจำแนกตามเพศ</span></h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
							</button>
							<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<div class="row">
							<div class="col-md-8">
								<div class="chart-responsive charts-box">
									<div class="charts">
										<canvas id="doughnut-canvas1"></canvas>
									</div>
								</div>
								<!-- ./chart-responsive -->
							</div>
						</div>
						<!-- /.row -->
					</div>
					<!-- /.box-body -->
					<div class="box-footer no-padding">
						<ul class="nav nav-pills nav-stacked">
							<li>
								<a href="#">ชาย <span class="pull-right text-red"><i class="fa fa-male"></i> {{ number_format($cpBySex['patient']['male']) }}</span></a>
							</li>
							<li>
								<a href="#">หญิง <span class="pull-right text-blue"><i class="fa fa-female"></i> {{ number_format($cpBySex['patient']['female']) }}</span></a>
							</li>
						</ul>
					</div>
					<!-- /.footer -->
				</div>
				<!-- /.box -->
			</div>

			<!-- right col#1 -->
			<div class="col-md-6">
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title"><span class="ds-box-title">อัตราป่วยจำแนกตามกลุ่มอายุ</span></h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
							</button>
							<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<div class="row">
							<div class="col-md-8">
								<div class="chart-responsive charts-box">
									<div class="charts">
										<canvas id="bar-canvas1" width="300" height="300"></canvas>
									</div>
								</div>
								<!-- ./chart-responsive -->
							</div>
						</div>
						<!-- /.row -->
					</div>
					<!-- /.box-body -->
					<div class="box-footer no-padding">
						<ul class="nav nav-pills nav-stacked">
							<li>
								<a href="#">สูงสุด <span class="pull-right text-red"><i class="fa fa-angle-up"></i> {{ number_format(0) }}</span></a>
							</li>
							<li>
								<a href="#">ต่ำสุด <span class="pull-right text-green"><i class="fa fa-angle-down"></i> {{ number_format(0) }}</span></a>
							</li>
						</ul>
					</div>
					<!-- /.footer -->
				</div>
				<!-- /.box -->
			</div>

			<!-- Left col#1 -->
			<div class="col-md-6">
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title"><span class="ds-box-title">จำนวนผู้ป่วยรายเดือน</span></h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
							</button>
							<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<div class="row">
							<div class="col-md-8">
								<div class="chart-responsive charts-box">
									<div class="charts">
										<canvas id="line-canvas1" width="300" height="300"></canvas>
									</div>
								</div>
								<!-- ./chart-responsive -->
							</div>
						</div>
						<!-- /.row -->
					</div>
					<!-- /.box-body -->
					<div class="box-footer no-padding">
						<ul class="nav nav-pills nav-stacked">
							<li>
								<a href="#">สูงสุด <span class="pull-right text-red"><i class="fa fa-angle-up"></i> {{ number_format(0) }}</span></a>
							</li>
							<li>
								<a href="#">ต่ำสุด <span class="pull-right text-green"><i class="fa fa-angle-down"></i> {{ number_format(0) }}</span></a>
							</li>
						</ul>
					</div>
					<!-- /.footer -->
				</div>
				<!-- /.box -->
			</div>

			<!-- Right col#1 -->
			<div class="col-md-6">
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title"><span class="ds-box-title">จำนวนผู้เสียชีวิตรายเดือน</span></h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
							</button>
							<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<div class="row">
							<div class="col-md-8">
								<div class="chart-responsive charts-box">
									<div class="charts">
										<canvas id="line-canvas2" width="300" height="300"></canvas>
									</div>
								</div>
								<!-- ./chart-responsive -->
							</div>
						</div>
						<!-- /.row -->
					</div>
					<!-- /.box-body -->
					<div class="box-footer no-padding">
						<ul class="nav nav-pills nav-stacked">
							<li>
								<a href="#">สูงสุด <span class="pull-right text-red"><i class="fa fa-angle-up"></i> {{ number_format(0) }}</span></a>
							</li>
							<li>
								<a href="#">ต่ำสุด <span class="pull-right text-green"><i class="fa fa-angle-down"></i> {{ number_format(0) }}</span></a>
							</li>
						</ul>
					</div>
					<!-- /.footer -->
				</div>
				<!-- /.box -->
			</div>


		</div>
	<!-- /.row -->
	</div>
	<!-- /. box -->
</section>


<!-- /.content -->
@stop
@section('script')
<script>
	$(function () {
		/* Initialize Select2 Elements */
		$('.select2').select2()
		//Date range picker
$('#reservation').daterangepicker()
//Date range picker with time picker
$('#reservationtime').daterangepicker({ timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A' })
//Date range as a button
$('#daterange-btn').daterangepicker(
  {
	ranges   : {
	  'Today'       : [moment(), moment()],
	  'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
	  'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
	  'Last 30 Days': [moment().subtract(29, 'days'), moment()],
	  'This Month'  : [moment().startOf('month'), moment().endOf('month')],
	  'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
	},
	startDate: moment().subtract(29, 'days'),
	endDate  : moment()
  },
  function (start, end) {
	$('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
  }
)
	});
</script>
<script>
/* sex doughnut chart */
function createDoughnutChart(id, type, options) {
	var data = {
		labels: ['ชาย', 'หญิง'],
		datasets: [{
			label: 'My dataset',
			data: [{{ $cpBySex['patient']['male'] }}, {{ $cpBySex['patient']['female'] }}],
			backgroundColor: [
				'#FF6384',
				'#36A2EB'
			]
		}]
	};
	new Chart(document.getElementById(id), {
		type: type,
		data: data,
		options: options
	});
}
/* agegurout bar chart */
function createBarChart(id, type, options) {
	var data = {
		labels: ['<5', '5-9', '10-14', '15-24', '25-34', '35-44', '45-54', '55-64', '65>'],
		datasets: [{
			label: 'จำนวน',
			data: [
				{{ $cpByAge['g1'] }},
				{{ $cpByAge['g2'] }},
				{{ $cpByAge['g3'] }},
				{{ $cpByAge['g4'] }},
				{{ $cpByAge['g5'] }},
				{{ $cpByAge['g6'] }},
				{{ $cpByAge['g7'] }},
				{{ $cpByAge['g8'] }},
				{{ $cpByAge['g9'] }},
			],
			backgroundColor: [
				'#FF6384',
				'#FF6384',
				'#FF6384',
				'#FF6384',
				'#FF6384',
				'#FF6384',
				'#FF6384',
				'#FF6384',
				'#FF6384'
			]
		}]
	};
	new Chart(document.getElementById(id), {
		type: type,
		data: data,
		options: options
	});
}
/*  Line chart for patient per month */
function createLineChart1(id, type, options) {
	var data = {
		labels: ['มค.', 'กพ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'],
		datasets: [{
			label: 'จำนวน',
			fill: false,
			borderColor: '#36A2EB',
			backgroundColor: '#36A2EB',
			data: [
				<?php
					foreach ($cpPerMonth as $key => $val) {
						echo $val.",";
					}
				?>
			]
		}]
	};
	new Chart(document.getElementById(id), {
		type: type,
		data: data,
		options: options
	});
}
/*  Line chart for case dead per month */
function createLineChart2(id, type, options) {
	var data = {
		labels: ['มค.', 'กพ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'],
		datasets: [{
			label: 'จำนวน',
			fill: false,
			borderColor: '#FF6384',
			backgroundColor: '#FF6384',
			data: [
				<?php
					/* foreach ($cpPerMonth as $key => $val) {
						echo $val.",";
					} */
				?>
			]
		}]
	};
	new Chart(document.getElementById(id), {
		type: type,
		data: data,
		options: options
	});
}
</script>
<script>
$('document').ready(function () {
	/* Sex doughnut chart */
	createDoughnutChart('doughnut-canvas1', 'doughnut', {
		responsive: true,
		maintainAspectRatio: false,
		legend: {
			position: 'top',
		},
		pieceLabel: {
			render: 'percentage',
			fontColor: ['white', 'white'],
			precision: 2
		}
	});
	/* Age bar chart */
	createBarChart('bar-canvas1', 'bar', {
		responsive: true,
		maintainAspectRatio: false,
		legend: {
			display: true,
			position: 'right',
		},
		pieceLabel: {
			render: 'value',
			precision: 2,
			overlap: true,
		},
		scales: {
			yAxes: [{
				ticks: {
					beginAtZero: true
				},
				scaleLabel: {
					display: true,
					labelString: 'อัตราป่วย (ต่อแสน)'
				}
			}],
			xAxes: [{
				barPercentage: 1
			}]
		}
	});

	/* Line chart for patient per month */
	createLineChart1('line-canvas1', 'line', {
		responsive: true,
		maintainAspectRatio: false,
		legend: {
			display: true,
			position: 'right',
		},
		scales: {
			yAxes: [{
				stacked: true,
				ticks: {
					beginAtZero: true
				},
				scaleLabel: {
					display: true,
					labelString: 'จำนวนผู้ป่วย (ราย)'
				}
			}]
		},
		elements: {
			line: {
				tension: 0,
			}
		},
	});

	/* Line chart for case dead per month */
	createLineChart2('line-canvas2', 'line', {
		responsive: true,
		maintainAspectRatio: false,
		legend: {
			display: true,
			position: 'right',
		},
		scales: {
			yAxes: [{
				stacked: true,
				ticks: {
					beginAtZero: true
				},
				scaleLabel: {
					display: true,
					labelString: 'จำนวนผู้เสียชีวิต (ราย)'
				}
			}]
		},
		elements: {
			line: {
				tension: 0,
			}
		},
	});
});
</script>

<!-- date-range-picker -->
{{ Html::script(('public/AdminLTE-2.4.2/bower_components/moment/min/moment.min.js')) }}
{{ Html::script(('public/AdminLTE-2.4.2/bower_components/bootstrap-daterangepicker/daterangepicker.js')) }}
<!-- Select2 -->
{{ Html::script(('public/AdminLTE-2.4.2/bower_components/select2/dist/js/select2.full.min.js')) }}

@endsection
