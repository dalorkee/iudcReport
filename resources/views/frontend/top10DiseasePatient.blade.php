@extends('layouts.template')
@section('style')
	<!-- bootstrap datepicker -->
	{{ Html::style(('public/AdminLTE-2.4.2/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')) }}
	<!-- Select2 -->
	{{ Html::style(('public/AdminLTE-2.4.2/bower_components/select2/dist/css/select2.min.css')) }}
	<style>
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
	<h1>รายงานรายสัปดาห์</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> หน้าหลัก</a></li>
		<li class="active">รายงานรายสัปดาห์</li>
	</ol>
</section>
<!-- Main content -->
<section class="content">
	<div class="main-box" style="border:none;background:none;box-shadow:none;">
		<div class="row">
			<!-- Left col#2 -->
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title"><span class="ds-box-title">โรคที่มีอัตราป่วยสูงสุด 10 อันดับ</span></h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
							</button>
							<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<div class="row">
							<div class="col-md-12">
								<div class="chart-responsive charts-box">
									<div class="charts">
										<canvas id="bar-chart1" width="300" height="300"></canvas>
									</div>
								</div>
								<!-- ./chart-responsive -->
							</div>
						</div>
						<!-- /.row -->
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->
			</div>

			<!-- Right col#2 -->
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title"><span class="ds-box-title">อัตราป่วยสะสมด้วยโรคที่เฝ้าระวัง 10 อันดับ</span></h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
							</button>
							<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<div class="row">
							<div class="col-md-12">
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
				</div>
				<!-- /.box -->
			</div>
		</div>
		<!-- /. row -->
	</div>
	<!-- /. box -->
</section>

<!-- /.content -->
@stop
@section('script')
<script>
/*  bar chart1 */
function createBarChart(id, type, options) {
	var data = {
		labels: ['x1', 'x2', 'x3', 'x4', 'x5', 'x6', 'x7', 'x8', 'x9', 'x10'],
		datasets: [{
			label: 'จำนวน',
			data: [
				5, 7, 6, 3, 6, 9, 3, 0, 3, 10
			],
			backgroundColor: [
				@for($i=1; $i<=9; $i++)
					{!! '"#00A65A"'.',' !!}
				@endfor
			]
		}]
	};
	new Chart(document.getElementById(id), {
		type: type,
		data: data,
		options: options
	});
}
/*  bar chart1 */
function createBarChart(id, type, options) {
	var data = {
		labels: [
			@foreach ($top10DsPt as $val)
				{!! "'".$val->DISNAME."'," !!}
			@endforeach
		],
		datasets: [{
			label: 'จำนวน',
			data: [
				@foreach ($top10DsPt as $val)
					{!! "'".$val->total."'," !!}
				@endforeach
			],
			backgroundColor: [
				@for($i=1; $i<=9; $i++)
					{!! '"#00A65A"'.',' !!}
				@endfor
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
	createBarChart('bar-chart1', 'horizontalBar', {
		responsive: true,
		maintainAspectRatio: false,
		legend: {
			display: false,
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
				barPercentage: .8
			}]
		}
	});
});
</script>
@endsection
