@extends('layouts.template')
@section('style')
	<!-- bootstrap datepicker -->
	{{ Html::style(('public/AdminLTE-2.4.2/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')) }}
	<!-- Select2 -->
	{{ Html::style(('public/AdminLTE-2.4.2/bower_components/select2/dist/css/select2.min.css')) }}
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
	</style>
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
		<div class="row">
			<!-- Left col#1 -->
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
					<!-- /.box-header -->
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
								</article>

							</div>
						</div>
					</div>
					<!-- /.box-body -->
					<div class="box-footer no-padding">
						<ul class="nav nav-pills nav-stacked">
							<li>
								<a href="#">box footer</a>
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
<!-- bootstrap datepicker -->
{{ Html::script(('public/AdminLTE-2.4.2/bower_components/moment/min/moment.min.js')) }}
{{ Html::script(('public/AdminLTE-2.4.2/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')) }}
<!-- Select2 -->
{{ Html::script(('public/AdminLTE-2.4.2/bower_components/select2/dist/js/select2.full.min.js')) }}
@endsection
