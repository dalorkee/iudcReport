@extends('layouts.template')
@section('content')
<?php
use \App\Http\Controllers\Controller as Controller;
$array_dpc_th_name = Controller::get_pop_dpc_nameth();


$array_dpc_code_group = Controller::get_pop_dpc_group();
 //dd($array_dpc_code_group);
?>
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>จำนวนประชากรจำแนกตามเพศ <small>รายเขต</small></h1>
<ol class="breadcrumb">
	<li><a href="{{ route('dashboard') }}"><i class="fa fa-home"></i> หนัาหลัก</a></li>
	<li><a href="#">ส่งออกข้อมูล</a></li>
	<li><a href="{{ route('export-population.main') }}" class="active">ประชากร</a></li>
	<li><a href="{{ route('export-population.area') }}" class="active">จำนวนประชากรจำแนกตามเพศ รายเขต</a></li>
</ol>
</section>
<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-6">
			<!-- Default box -->
			<div class="box box-info">
				<div class="box-header with-border">
					<h3 class="box-title">ส่งออกข้อมูล(XLS) - จำนวนประชากรจำแนกตามเพศ <small>รายเขต</small></h3>
				</div>
				<div class="box-body">
				<form action='{{ route('post_population_area') }}' class="form-horizontal" method="post">
					{{ csrf_field() }}
					<div class="box-body">
						<div class="form-group">
							<label for="input_monthchoose" class="col-sm-2 control-label">สคร.</label>
							<div class="col-sm-4">
								<select class="form-control" name="dpc_code" id="dpc_code">
								@foreach ($array_dpc_th_name as $dpc_key => $dpc_value)
									<option value="{{ $dpc_key }}">{{ $dpc_value }}</option>
								@endforeach
								</select>
							</div>
						</div>
						<div class="form-group">
						<label for="input_yearchoose" class="col-sm-2 control-label">ปี</label>
							<div class="col-sm-4">
								<?php
											$current_year =  (isset($_GET['year']))? $_GET['year']: date('Y');
											//Current Year
											$already_selected_value = (int)date('Y');
											//Start Year
											$earliest_year = 2012;

											echo '<select name="select_year" class="form-control" id="select_year">';
												foreach (range($already_selected_value, $earliest_year) as $x) {
														 $year_th = $x+543;
														 print '<option value="'.$x.'"'.($x === (int)$current_year ? ' selected="selected"' : '').'>'.$year_th.'</option>';
												}
											echo '</select>';
								?>
							</div>
						</div>
					</div>
					<!-- /.box-body -->
					<div class="box-footer">
						<button type="submit" class="btn btn-info pull-right">ส่งออกข้อมูล</button>
					</div>
					<!-- /.box-footer -->
				</form>
			</div>
		</div>
	</div>
 </div>
</section>
<!-- /.content -->
@stop
