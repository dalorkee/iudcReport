@extends('layouts.template')
@section('content')
<?php
use \App\Http\Controllers\Controller as Controller;
$array_sector_th_name = Controller::get_pop_sector_th_name();

?>
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>จำนวนประชากรจำแนกตามเพศ <small>รายภาค</small></h1>
<ol class="breadcrumb">
	<li><a href="{{ route('dashboard') }}"><i class="fa fa-home"></i> หนัาหลัก</a></li>
	<li><a href="#">ส่งออกข้อมูล</a></li>
	<li><a href="{{ route('export-population.main') }}" class="active">ประชากร</a></li>
	<li><a href="{{ route('export-population.sector') }}" class="active">จำนวนประชากรจำแนกตามเพศ รายภาค</a></li>
</ol>
</section>
<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-8">
			<!-- Default box -->
			<div class="box box-info">
				<div class="box-header with-border">
					<h3 class="box-title">ส่งออกข้อมูล(XLS) - จำนวนประชากรจำแนกตามเพศ <small>รายภาค</small></h3>
				</div>
				<div class="box-body">
				<form action='{{ route('post_population_sector') }}' class="form-horizontal" method="post">
					{{ csrf_field() }}
					<div class="box-body">
						<div class="form-group">
							<label for="input_monthchoose" class="col-sm-2 control-label">ภาค</label>
							<div class="col-sm-6">
								<select class="form-control" name="sector" id="sector">
								@foreach ($array_sector_th_name as $sector_key => $sector_value)
									<option value="{{ $sector_key }}">{{ $sector_value }}</option>
								@endforeach
								</select>
							</div>
						</div>
						<div class="form-group">
						<label for="input_yearchoose" class="col-sm-2 control-label">ปี</label>
							<div class="col-sm-6">
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
