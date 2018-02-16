@extends('layouts.templateDemo')
@section('content')
<?php
ini_set('max_execution_time', 300); //300 seconds = 5 minutes
$get_all_disease =\App\Http\Controllers\Controller::list_disease();
//dd($get_all_disease);
?>
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>Export<small></small></h1>
<ol class="breadcrumb">
	<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
	<li><a href="#">Examples</a></li>
	<li class="active">Blank page</li>
</ol>
</section>
<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-8">
			<!-- Default box -->
			<div class="box">
				<div class="box-header with-border">
					<h3 class="box-title">Export</h3>
				</div>
				<div class="box-body">
				<form action='{{ url('exportbydisease') }}' class="form-horizontal" method="post">
					{{ csrf_field() }}
					<div class="box-body">
						<div class="form-group">
							<label for="input_monthchoose" class="col-sm-3 control-label">โรค</label>
							<div class="col-sm-4">
								<select class="form-control" name="disease_code" id="disease_code">
								@foreach ($get_all_disease as $disease_value)
									<option value="{{ $disease_value->DISEASE }}">{{ $disease_value->DISEASE }} - {{ $disease_value->DISNAME }}</option>
								@endforeach
								</select>
							</div>
						</div>
						<div class="form-group">
						<label for="input_yearchoose" class="col-sm-3 control-label">ปี</label>
						<div class="col-sm-4">
							  <?php $year = array('2017' => '2560','2018' => '2561'); ?>
							  <select class="form-control" name="select_year" id="select_year">
							  @foreach ($year as $key_year => $val_year)
							  <option value="{{ $key_year }}">{{ $val_year }}</option>
							  @endforeach
							  </select>
						</div>
						</div>
					</div>
					<!-- /.box-body -->
					<div class="box-footer">
						<button type="submit" class="btn btn-info pull-right">Export CSV Data</button>
					</div>
					<!-- /.box-footer -->
				</form>
			</div>
		</div>
	</div>
</section>
<!-- /.content -->
@stop