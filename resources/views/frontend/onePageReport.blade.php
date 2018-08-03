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
		.onepage>div {
			line-height:2em;
			text-indent: 50px;
			font-size: 1.075em;
		}
		#map { position:absolute; top:0; bottom:0; width:100%; }
		.mapboxgl-popup {
			max-width: 400px;
			font: 12px/20px 'Helvetica Neue', Arial, Helvetica, sans-serif;
		}
		.map-popup {
			margin: 0;
			padding: 5px;
			list-style: none;
		}
		.map-popup li span {
			display: inline-block;
			width: 46px;
			font-weight: bold;
		}
		.map-overlay {
			position: absolute;
			bottom: 0;
			right: 0;
			background: rgba(255, 255, 255, 0.8);
			margin-right: 20px;
			font-family: Arial, sans-serif;
			overflow: auto;
			border-radius: 3px;
		}
		#legend {
			padding: 10px;
			box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
			line-height: 18px;
			height: 120px;
			margin-bottom: 40px;
			min-width: 110px;
		}
		.legend-key {
			display: inline-block;
			border-radius: 20%;
			width: 10px;
			height: 10px;
			margin-right: 5px;
		}
	</style>
@endsection
@section('incHeaderScript')
	{{ Html::script(('public/components/Chart.js/dist/2.7.2/Chart.bundle.js')) }}
	{{ Html::script(('public/components/Chart.js/src/chart.js')) }}
	{{ Html::script(('https://api.tiles.mapbox.com/mapbox-gl-js/v0.46.0/mapbox-gl.js')) }}
@endsection
@section('content')
<section class="content-header">
	<h1>One page report</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> หน้าหลัก</a></li>
		<li class="active">One page report</li>
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
			<form method="get" action='{{ route('onePage') }}'>
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
									<div>
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
										จังหวัดที่มีอัตราป่วยสูงสุด 5 อันดับแรก คือ
										@foreach ($top5PtByYear as $key=>$val)
											{{ $key." (".$val." ต่อประชากรแสนคน) " }}
										@endforeach
									</div>
									<div style="text-indent: 50px;">
										ภาคที่มีอัตราป่วยสูงสุด คือ
										@foreach ($patientByProvRegion as $key=>$val)
											{{ $key." (".$val." ต่อประชากรแสนคน) " }}
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
						<h3 class="box-title"><span class="ds-box-title">แผนที่ผู้ป่วย {{ $patientMap['disease']['ds_name'] }}</span></h3>
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
									<div class='map-overlay' id='legend'></div>
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
				label: 'ภาคกลาง',
				fill: false,
				borderColor: '#FF9D3C',
				backgroundColor: '#FF9D3C',
				data: [
					@foreach ($patintPerWeek['ptCWeek'] as $val)
						{{ (int)$val.", " }}
					@endforeach
				]
			},
			{
				label: 'ภาคเหนือ',
				fill: false,
				borderColor: '#9060EF',
				backgroundColor: '#9060EF',
				data: [
					@foreach ($patintPerWeek['ptNWeek'] as $val)
						{{ (int)$val.", " }}
					@endforeach
				]
			},
			{
				label: 'ภาคตะวันออกเฉียงเหนือ',
				fill: false,
				borderColor: '#36A2EB',
				backgroundColor: '#36A2EB',
				data: [
					@foreach ($patintPerWeek['ptNeWeek'] as $val)
						{{ (int)$val.", " }}
					@endforeach
				]
			},
			{
				label: 'ภาคใต้',
				fill: false,
				borderColor: '#4BC0C0',
				backgroundColor: '#4BC0C0',
				data: [
					@foreach ($patintPerWeek['ptSWeek'] as $val)
						{{ (int)$val.", " }}
					@endforeach
				]
			},
			{
				label: 'รวม',
				fill: false,
				borderColor: '#FF6384',
				backgroundColor: '#FF6384',
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
			display: true,
			position: 'top',
		},
		tooltips: {
			mode: 'index',
			intersect: false,
		},
		hover: {
			mode: 'nearest',
			intersect: true
		},
		scales: {
			xAxes: [{
				display: true,
				scaleLabel: {
					display: true,
					labelString: 'สัปดาห์'
				}
			}],
			yAxes: [{
				display: true,
				scaleLabel: {
					display: true,
					labelString: 'จำนวน'
				},
				ticks: {
					beginAtZero: true
				},
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
		$htm .= "
		map.on('load', function () {
			map.addLayer({
				'id': 'mhs".$i."',
				'type': 'fill',
				'source': {
					'type': 'geojson',
					'data': 'public/gis/".$val['prov_name_en'].".geojson'
				},
				'layout': {

				},
				'paint': {
					'fill-color': '".$val['color']."',
					'fill-opacity': 0.8
				}
			});
		});\n";
		$htm .= "
		map.on('click', 'mhs".$i."', function (e) {
			new mapboxgl.Popup()
				.setLngLat(e.lngLat)
				.setHTML(e.features.map(function(feature) {
					return '<ul class=\"map-popup\"><li><span>จังหวัด</span>' + feature.properties.PROV_NAMT + '</li><li><span>ผู้ป่วย</span>' + '".number_format($val['amount'])."</li></ul>';
				}).join(', '))
				.addTo(map);
		});";
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
var layers = [
	<?php
		$str = null;
		foreach ($patientMap['range'] as $val) {
			if (is_null($str)) {
				$str = "";
			} else {
				$str = $str.", ";
			}
			$str = $str."'".$val."'";
		}
		echo $str;
	?>
];
var colors = [
	<?php
		$str = null;
		foreach ($patientMap['colors'] as $val) {
			if (is_null($str)) {
				$str = "";
			} else {
				$str = $str.", ";
			}
			$str = $str."'".$val."'";
		}
		echo $str;
	?>
];
for (i = 0; i < layers.length; i++) {
	var layer = layers[i];
	var color = colors[i];
	var item = document.createElement('div');
	var key = document.createElement('span');
	key.className = 'legend-key';
	key.style.backgroundColor = color;

	var value = document.createElement('span');
	value.innerHTML = layer;
	item.appendChild(key);
	item.appendChild(value);
	legend.appendChild(item);
}
map.addControl(new mapboxgl.NavigationControl());
map.addControl(new mapboxgl.GeolocateControl({
	positionOptions: {
		enableHighAccuracy: true
	},
	trackUserLocation: true
}));
map.getCanvas().style.cursor = 'default';
{!! $htm !!}
</script>
<!-- bootstrap datepicker -->
{{ Html::script(('public/AdminLTE-2.4.2/bower_components/moment/min/moment.min.js')) }}
{{ Html::script(('public/AdminLTE-2.4.2/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')) }}
<!-- Select2 -->
{{ Html::script(('public/AdminLTE-2.4.2/bower_components/select2/dist/js/select2.full.min.js')) }}
@endsection
