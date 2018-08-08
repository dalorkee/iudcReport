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
	<h1>10 อันดับโรคที่มีอัตราป่วยสูงสุด</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> หน้าหลัก</a></li>
		<li class="active">10 อันดับโรคที่มีอัตราป่วยสูงสุด</li>
	</ol>
</section>
<!-- Main content -->
<section class="content">
	<div class="main-box" style="border:none;background:none;box-shadow:none;">
		<div class="row">
			<!-- Left col#2 -->
			<div class="col-md-12">
				<div class="box box-danger">
					<div class="box-header with-border">
						<h3 class="box-title"><span class="ds-box-title">โรคที่มีอัตราป่วยสูงสุด ปี {{ ((int)$listWeek['year']+543) }}</span></h3>
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
					<div class="box-footer no-padding">
						<ul class="nav nav-pills nav-stacked">
							<li>
								<a href="#">อัตราป่วย<span class="hidden-xs hidden-sm">ด้วยโรคที่เฝ้าระวัง</span> 10 อันดับ <span class="hidden-xs hidden-sm">ในพื้นที่เขตเมือง ปี {{ ((int)$listWeek['year']+543) }}</span> <span class="pull-right text-red"> {{ $listWeek['firstWeek']->DATESICK.' - '.$listWeek['lastWeek']->DATESICK }}</span></a>
							</li>
						</ul>
					</div>
					<!-- /.footer -->
				</div>
				<!-- /.box -->
			</div>

			<!-- Right col#2 -->
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title"><span class="ds-box-title">โรคที่มีอัตราป่วยสูงสุด สัปดาห์ที่ {{$listWeek['lastWeek']->week_no }} ปี {{ ((int)$listWeek['year']+543) }}</span></h3>
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
										<canvas id="bar-chart2" width="300" height="300"></canvas>
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
								<a href="#">อัตราป่วย<span class="hidden-xs hidden-sm">ด้วยโรคที่เฝ้าระวัง</span> 10 อันดับ
									<span class="hidden-xs hidden-sm">
										<?php
											echo "ในพื้นที่เขตเมือง ปี ".((int)$listWeek['year']+543)."</span>";
											echo "<span class=\"pull-right text-red\">";
											echo "สัปดาห์ที่ ".$listWeek['lastWeek']->week_no.", ";
											echo $listWeek['lastWeekAllDate'][0]->DATESICK." - ";
											$cntDate = count($listWeek['lastWeekAllDate']);
											echo $listWeek['lastWeekAllDate'][((int)$cntDate-1)]->DATESICK;
										?>
									</span>
								</a>
							</li>
						</ul>
					</div>
					<!-- /.footer -->
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
function createBarChart1(id, type, options) {
	var data = {
		labels: [
			@foreach ($top10DsPtYear as $key=>$val)
				{!! "'".$key."'," !!}
			@endforeach
		],
		datasets: [{
			label: 'อัตรา',
			data: [
				@foreach ($top10DsPtYear as $key=>$val)
					{!! "'".number_format($val, 2, '.', '' )."'," !!}
				@endforeach
			],
			backgroundColor: [
				@for($i=1; $i<=10; $i++)
					{!! '"#FE819D"'.',' !!}
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
/*  bar chart2 */
function createBarChart2(id, type, options) {
	var data = {
		labels: [
			@foreach ($top10DsPtWeek as $key=>$val)
				{!! "'".$dsName[$key]."'," !!}
			@endforeach
		],
		datasets: [{
			label: 'อัตรา',
			data: [
				@foreach ($top10DsPtWeek as $key=>$val)
					{!! "'".number_format($val, 2, '.', '' )."'," !!}
				@endforeach
			],
			backgroundColor: [
				@for($i=1; $i<=10; $i++)
					{!! '"#36A2EB"'.',' !!}
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
	/* barchart1 */
	createBarChart1('bar-chart1', 'horizontalBar', {
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
	/* barchart2 */
	createBarChart2('bar-chart2', 'horizontalBar', {
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
