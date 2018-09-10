@extends('layouts.template')
@section('content')
<?php
use \App\Http\Controllers\Controller as Controller;

$get_all_disease_merge = Controller::list_merge_disease();
$get_all_disease = Controller::list_disease();
//add array
function array_push_assoc($array, $key, $value){
$array[$key] = $value;
return $array;
}

foreach ($get_all_disease_merge as $key_merge => $val_merge){

  array_push_assoc($get_all_disease,$key_merge,$val_merge);
}
?>
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>การส่งออกข้อมูลรายโรค<small></small></h1>
<ol class="breadcrumb">
	<li><a href="{{ route('dashboard') }}"><i class="fa fa-home"></i> หนัาหลัก</a></li>
	<li><a href="#">ส่งออกข้อมูล</a></li>
	<li><a href="{{ route('export.form') }}" class="active">รายโรค</a></li>
</ol>
</section>
<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-8">
			<!-- Default box -->
			<div class="box box-danger">
				<div class="box-header with-border">
					<h3 class="box-title">การส่งออกข้อมูลรายโรค</h3>
				</div>
				<div class="box-body">
				<form action='{{ url('exportbydisease') }}' class="form-horizontal" method="post">
					{{ csrf_field() }}
					<div class="box-body">
						<div class="form-group">
							<label for="input_monthchoose" class="col-sm-3 control-label">โรค</label>
							<div class="col-sm-4">
								<select class="form-control" name="disease_code" id="disease_code">
								@foreach ($get_all_disease as $disease_key => $disease_value)
									<option value="{{ $disease_key }}">{{ $disease_key }} - {{ $disease_value }}</option>
								@endforeach
								</select>
							</div>
						</div>
						<div class="form-group">
						<label for="input_yearchoose" class="col-sm-3 control-label">ปี</label>
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
</section>
<!-- /.content -->
@stop
<?php unset($get_all_disease); ?>
