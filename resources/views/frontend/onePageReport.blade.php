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
	<h1>รายงานรายสัปดาห์</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> หน้าหลัก</a></li>
		<li class="active">รายงานรายสัปดาห์</li>
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
		<!-- row#1 -->
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title"><span class="ds-box-title">One page report</span></h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
							</button>
							<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-md-12">
								<article class="onepage">
									<div style="text-indent: 50px;">
										ข้อมูลเฝ้าระวังโรคในเขตเมือง ตั้งแต่วันที่ {{ $patientOnYear['minDate'] }} - {{ $patientOnYear['maxDate'] }}
										พบผู้ป่วย {{ $patientOnYear['patientThisYear'] }} ราย
										จาก {{ $patientPerProv['cntProv'] }} จังหวัด
										เสียชีวิต {{ number_format((int)$caseDead['caseDead']) }} ราย
										อัตราป่วย {{ number_format(((int)$patientOnYear['patientThisYear']*100)/100000, 2) }} ต่อประชากรแสนคน
										อัตราตาย {{ number_format(((int)$caseDead['caseDead']*100)/100000, 2) }} ต่อประชากรแสนคน
										อัตราส่วนเพศชายต่อเพศหญิง {{ $patientBySex['ratio'] }}
										กลุ่มอายุที่พบมากที่สุด เรียงตามลำดับ คือ
										@foreach ($patientByAgeGroup as $key=>$val)
											{{ $key." ปี (".$val."%)" }}
										@endforeach
										@foreach ($patientByNation as $key => $val)
											@if ($key == 'ไทย')
												{{ "สัญชาติเป็น".$key." ร้อยละ ".$val." " }}
											@else
												{{ $key." ร้อยละ ".$val." " }}
											@endif
										@endforeach
										อาชีพส่วนใหญ่คือ
										@foreach ($patientByOccupation  as $key=>$val)
											{{ $key. " ร้อยละ ".$val." " }}
										@endforeach
									</div>
									<div style="text-indent: 50px;">
										จังหวัดที่มีอัตราป่วยต่อแสนประชากรสูงสุด 5 อันดับแรก คือ
										@foreach ($top5PtByYear as $key=>$val)
											{{ $key." (".$val." ต่อแสนประชากร) " }}
										@endforeach
									</div>
									<div style="text-indent: 50px;">
										ภาคที่มีอัตราป่วยสูงสุด คือ
										@foreach ($patientByProvRegion as $key=>$val)
											{{ $key." (".$val." ต่อแสนประชากร) " }}
										@endforeach
									</div>
									<div style="text-indent: 50px;">
										ในสัปดาห์นี้ ตั้งแต่วันที่
										{{ $patientOnLastWeek['date_start']." - ".$patientOnLastWeek['date_end'] }}
										พบผู้ป่วย {{ $patientOnLastWeek['patient'] }} ราย
									</div>
								</article>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- /. row -->
		<!-- row#2 -->
		<div class="row">
			<div class="col-md-12">
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title"><span class="ds-box-title">ผู้ป่วยจำแนกรายสัปดาห์</span></h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
							</button>
							<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-md-12">
								<div class="chart-responsive charts-box">
									<div class="charts">
										<canvas id="line-canvas" width="300" height="300"></canvas>
									</div>
								</div>
								<!-- ./chart-responsive -->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- /. row2 -->
		<!-- row#3 -->
		<div class="row">
			<div class="col-md-12">
				<div class="box box-danger">
					<div class="box-header with-border">
						<h3 class="box-title"><span class="ds-box-title">แผนที่</span></h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
							</button>
							<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-md-12">
								<div class="map-box">
									<div id="map"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
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
/* Line chart for sick per week */
function createLineChart(id, type, options) {
	var data = {
		labels: [
			@for ($i=1; $i<=53; $i++)
				{{ $i.',' }}
			@endfor
		],
		datasets: [
			{
				label: 'ผู้ป่วย',
				fill: false,
				borderColor: '#FF7900',
				backgroundColor: '#FFFFFF',
				data: [
					@foreach ($patintPerWeek['ptCWeek'] as $val)
						{{ (int)$val.", " }}
					@endforeach
				]
			},
			{
				label: 'ผู้ป่วย',
				fill: false,
				borderColor: '#FF00FF',
				backgroundColor: '#FFFFFF',
				data: [
					@foreach ($patintPerWeek['ptNWeek'] as $val)
						{{ (int)$val.", " }}
					@endforeach
				]
			},
			{
				label: 'ผู้ป่วย',
				fill: false,
				borderColor: '#4486F8',
				backgroundColor: '#FFFFFF',
				data: [
					@foreach ($patintPerWeek['ptNeWeek'] as $val)
						{{ (int)$val.", " }}
					@endforeach
				]
			},
			{
				label: 'ผู้ป่วย',
				fill: false,
				borderColor: '#026802',
				backgroundColor: '#FFFFFF',
				data: [
					@foreach ($patintPerWeek['ptSWeek'] as $val)
						{{ (int)$val.", " }}
					@endforeach
				]
			},
			{
				label: 'ผู้ป่วย',
				fill: false,
				borderColor: '#E51400',
				backgroundColor: '#FFFFFF',
				data: [
					@foreach ($patintPerWeek['ptTotal'] as $val)
						{{ (int)$val.", " }}
					@endforeach
				]
			},
		]
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
	/* Line chart for sick per week */
	createLineChart('line-canvas', 'line', {
		responsive: true,
		maintainAspectRatio: false,
		legend: {
			display: false,
			position: 'right',
		},
		scales: {
			xAxes: [{
				display: true,
				scaleLabel: {
					display: true,
					labelString: 'Week'
				}
			}],
			yAxes: [{
				display: true,
				scaleLabel: {
					display: true,
					labelString: 'Patient'
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
	$color = ['#489E48', '#D1202E', '#FBBC05'];
	$htm = "";
	$i = 1;
	foreach ($lstPatientPerProv as $val) {
		$ranColor = $color[mt_rand(0, count($color) - 1)];
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
					'fill-color': '".$ranColor."',
					'fill-opacity': 0.8
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
// Add zoom and rotation controls to the map.
map.addControl(new mapboxgl.NavigationControl());
{!! $htm !!}
</script>
<!-- bootstrap datepicker -->
{{ Html::script(('public/AdminLTE-2.4.2/bower_components/moment/min/moment.min.js')) }}
{{ Html::script(('public/AdminLTE-2.4.2/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')) }}
<!-- Select2 -->
{{ Html::script(('public/AdminLTE-2.4.2/bower_components/select2/dist/js/select2.full.min.js')) }}
@endsection
