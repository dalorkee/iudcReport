@extends('layouts.template')
@section('style')
	<!-- bootstrap datepicker -->
	{{ Html::style(('public/AdminLTE-2.4.2/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')) }}
	<!-- Select2 -->
	{{ Html::style(('public/AdminLTE-2.4.2/bower_components/select2/dist/css/select2.min.css')) }}
	{{ Html::style(('https://api.tiles.mapbox.com/mapbox-gl-js/v0.46.0/mapbox-gl.css')) }}
	<style>
		.select2 * {
			moz-border-radius: 0 !important;
			webkit-border-radius: 0 !important;
			border-radius: 0 !important;
			height: 32px !important;
		}
		.ds-box-title {
			font-size: .80em;
		}
		.charts-box {
			min-height: 350px;
			margin: 20px 0;
		}
		.map-box {
			width: 100%;
			min-height: 580px;
			position: relative;
		}
		#map { position:absolute; top:0; bottom:0; width:100%; }
	</style>
@endsection
@section('incHeaderScript')
	{{ Html::script(('public/components/Chart.PieceLabel.js/src/Chart.bundle.min.js')) }}
	{{ Html::script(('public/components/Chart.PieceLabel.js/src/Chart.PieceLabel.js')) }}
	{{ Html::script(('https://api.tiles.mapbox.com/mapbox-gl-js/v0.46.0/mapbox-gl.js')) }}
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
			<h3 class="box-title text-primary" style="font-size:1.10em">ค้นหา</h3>
			<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
				<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
			</div>
		</div>
		<!-- /.box-header -->
		<div class="box-body">
			<form method="get" action='{{ route('dashboard') }}'>
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label for="selectDisease" class="sr-only">เลือกโรค:</label>
							<select name="disease" class="form-control select2" style="width:100%;">
								<optgroup label="โรคที่มี 1 รหัส">
								<?php
									if ($selectDs['selected'] == true) {
										$selected1 = "selected=\"selected\"";
										$selected2 = null;
										echo "<option value=\"".$selectDs['disease']."\"".$selected1.">".$dsgroups[$selectDs['disease']]['ds_name']."</option>";
									} else {
										$selected2 = "selected=\"selected\"";
									}
									$i = 1;
									foreach ($dsgroups as $dsgroup) {
										if ($i == 1) {
											echo "<option value=\"".$dsgroup['ds_id']."\" ".$selected2.">".$dsgroup['ds_name']."</option>\n";
										} else {
											echo "<option value=\"".$dsgroup['ds_id']."\">".$dsgroup['ds_name']."</option>\n";
										}
										$i++;
									}
								?>
								</optgroup>
								<optgroup label="โรคที่มีหลายรหัส">
									<option value="-1">DHF+DSS+DF</option>
									<option value="-2">Dysentery</option>
									<option value="-3">Encephalitis</option>
									<option value="-4">Hepatitis</option>
									<option value="-5">Measles</option>
									<option value="-6">S.T.I</option>
									<option value="-7">Tetanus inc.Neo</option>
									<option value="-8">Tuberculosis</option>
								</optgroup>
							</select>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group ds-filter">
							<label for="selectYear" class="sr-only">ปี:</label>
							<div class="input-group date">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input type="text" name="year" class="form-control pull-right" id="select-year">
							</div>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							{{ Form::submit('ค้นหา', ['class'=>'btn btn-primary']) }}
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
	<div class="main-box" style="border:none;background:none;box-shadow:none;">
		<div class="row">
			<!-- Left col#1 -->
			<div class="col-md-6">
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title"><span class="ds-box-title">ร้อยละผู้ป่วยจำแนกตามเพศ</span></h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<div class="row">
							<div class="col-md-12">
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
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<div class="row">
							<div class="col-md-12">
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
								<a href="#">อัตราสูงสุดช่วงอายุ <span class="pull-right text-red"> {{ $ageRange[array_search(max($cpByAge), $cpByAge)] }}</span></a>
							</li>
							<li>
								<a href="#">อัตรา <span class="pull-right text-green"> {{ number_format(max(array_unique($cpByAge)), 2) }}</span></a>
							</li>
						</ul>
					</div>
					<!-- /.footer -->
				</div>
				<!-- /.box -->
			</div>
		</div>
		<!-- /. row -->
		<div class="row">
			<!-- Left col#2 -->
			<div class="col-md-6">
				<div class="box box-info">
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
							<div class="col-md-12">
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
				</div>
				<!-- /.box -->
			</div>

			<!-- Right col#2 -->
			<div class="col-md-6">
				<div class="box box-info">
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
		<div class="row">
			<!-- Left col#3 -->
			<div class="col-md-12">
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title"><span class="ds-box-title">จำนวนผู้ป่วยรายสัปดาห์</span></h3>
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
										<canvas id="line-canvas3" width="300" height="300"></canvas>
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
		<div class="row">
			<!-- Left col#4 -->
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title"><span class="ds-box-title">แผนที่</span></h3>
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
								<div class="map-box">
									<div id="map"></div>
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
	<!-- /.row -->
	</div>
	<!-- /. box -->
</section>
<!-- /.content -->
@stop
@section('script')
<script>
$(document).ready(function () {
	/* Initialize Select2 Elements */
	$('.select2').select2();
	/* Date picker */
	$('#select-year').datepicker({
		format: "yyyy",
		viewMode: "years",
		minViewMode: "years",
		autoclose: true
	});
	<?php
		if ($selectDs['selected'] == true) {
			echo "$('#select-year').datepicker('setDate', '".$selectDs['selectYear']."');";
		} else {
			echo "$('#select-year').datepicker('setDate', 'toYear');";
		}
	?>
});
</script>
<script>
/* Sex doughnut chart */
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
			label: 'อัตรา',
			data: [
				@foreach ($cpByAge as $val)
					{!! $val.',' !!}
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
/*  Line chart for patient per month */
function createLineChart1(id, type, options) {
	var data = {
		labels: [
			@for ($i=1; $i<=count($monthLabel); $i++)
				@if ($i != count($monthLabel))
					{!! "'".$monthLabel[$i]."'," !!}
				@else
					{!! "'".$monthLabel[$i]."'" !!}
				@endif
			@endfor
		],
		datasets: [{
			label: 'จำนวน',
			fill: false,
			borderColor: '#36A2EB',
			backgroundColor: '#FFFFFF',
			data: [
				@foreach ($cpPerMonth as $val)
					{{ $val."," }}
				@endforeach
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
		labels: [
			@for ($i=1; $i<=count($monthLabel); $i++)
				@if ($i != count($monthLabel))
					{!! "'".$monthLabel[$i]."'," !!}
				@else
					{!! "'".$monthLabel[$i]."'" !!}
				@endif
			@endfor
		],
		datasets: [{
			label: 'จำนวน',
			fill: false,
			borderColor: '#D1202E',
			backgroundColor: '#FFFFFF',
			data: [
				@foreach ($cDeadPerMonth as $val)
					{{ $val.", " }}
				@endforeach
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
function createLineChart3(id, type, options) {
	var data = {
		labels: [
			@for ($i=1; $i<=53; $i++)
				{{ $i.',' }}
			@endfor
		],
		datasets: [{
			label: 'จำนวน',
			fill: false,
			borderColor: '#FF7900',
			backgroundColor: '#FFFFFF',
			data: [
				@foreach ($cpPerWeek as $val)
					{{ $val.", " }}
				@endforeach
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

	/* Line chart for patient per month */
	createLineChart1('line-canvas1', 'line', {
		responsive: true,
		maintainAspectRatio: false,
		legend: {
			display: false,
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
			display: false,
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

	/* Line chart for case dead per month */
	createLineChart3('line-canvas3', 'line', {
		responsive: true,
		maintainAspectRatio: false,
		legend: {
			display: false,
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
					labelString: 'จำนวนผู้ป่วยรายสัปดาห์ (ราย)'
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
<?php
	$htm = "";
	$i = 1;
	foreach ($patientMap['patient'] as $val) {
		$htm .= "map.on('load', function () {
			map.addLayer({
				'id': 'mhs".$i."',
				'type': 'fill',
				'source': {
					'type': 'geojson',
					'data': 'public/gis/".$val->prov_name_en.".geojson'
				},
				'layout': {
				},
				'paint': {
					'fill-color': '".$val->mapColor."',
					'fill-opacity': 0.8,
				}
			});
		});\n";
		$i++;
	}
?>
<script>
mapboxgl.accessToken = 'pk.eyJ1IjoiZGFsb3JrZWUiLCJhIjoiY2pnbmJrajh4MDZ6aTM0cXZkNDQ0MzI5cCJ9.C2REqhILLm2HKIQSn9Wc0A';
var map = new mapboxgl.Map({
	container: 'map',
	style: 'mapbox://styles/mapbox/streets-v9',
	center: [100.277405, 13.530735],
	zoom: 4.5
});
map.setPaintProperty(1, '1', 1);
{!! $htm !!}
</script>
<!-- bootstrap datepicker -->
{{ Html::script(('public/AdminLTE-2.4.2/bower_components/moment/min/moment.min.js')) }}
{{ Html::script(('public/AdminLTE-2.4.2/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')) }}
<!-- Select2 -->
{{ Html::script(('public/AdminLTE-2.4.2/bower_components/select2/dist/js/select2.full.min.js')) }}
@endsection
