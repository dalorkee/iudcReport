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
		labels: [
			@foreach ($top10DsPt as $key=>$val)
				{!! "'".$key."'," !!}
			@endforeach
		],
		datasets: [{
			label: 'อัตรา',
			data: [
				@foreach ($top10DsPt as $key=>$val)
					{!! "'".number_format($val, 2, '.', '' )."'," !!}
				@endforeach
			],
			backgroundColor: [
				@for($i=1; $i<=10; $i++)
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
				scaleLabel: {
					display: true,
					labelString: 'อัตราป่วย (ต่อแสน)'
				},
				stacked: true
			}],
			xAxes: [{
				ticks: {
					beginAtZero: true
				},
				stacked: true,
				barPercentage: .8
			}]
		}
	});
});
</script>
@endsection
