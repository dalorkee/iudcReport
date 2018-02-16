@extends('layouts.templateDemo')
@section('content')
<?php
$i=1;
$j=2;
//ini_set('max_execution_time', 300); //300 seconds = 5 minutes
$get_provincename_th =\App\Http\Controllers\Controller::get_provincename_th();
$current_year =  (isset($_GET['year']))? $_GET['year']: date('Y');
$disease_code =  (isset($_GET['disease_code']))? $_GET['disease_code']: '01';

//$province,$year,$disease_code
//dd($get_sub_level);
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
		<div class="col-md-6">
			<!-- Default box -->
			<div class="box">
				<div class="box-header with-border">
					<h3 class="box-title">Show By Disease</h3>
				</div>
				<div class="box-body">
					<table id="tree-table" class="table table-hover table-bordered">
						<tbody>
							<th>จังหวัด</th>
							<th>จำนวน</th>
							@foreach ($datas_province as $value_province)
							<tr data-id="{{ $value_province->PROVINCE }}" data-parent="0" data-level="1">
								<td data-column="name">{{ $get_provincename_th[$value_province->PROVINCE] }}</td>
								<td>{{ number_format($value_province->total_province) }}</td>
							</tr>
							<?php $get_sub_level = \App\Http\Controllers\PopulationController::ShowByDiseaseSub($value_province->PROVINCE,$current_year,$disease_code); ?>
							@foreach ($get_sub_level as $value_sub_level)
							<tr data-id="{{ $i }}" data-parent="{{ $value_province->PROVINCE }}" data-level="2">
					      <td data-column="name">{{ $value_sub_level->urbanname }}</td>
					      <td>{{ number_format($value_sub_level->total_amphur) }}</td>
    					</tr>
							@endforeach
							<?php $i++; ?>
							@endforeach
							</tbody>
					</table>
					<div class="text-right">
							<ul class="pagination pull-left">
									แสดงข้อมูล  {!! (($datas_province->currentPage()-1)*$datas_province->perPage())+1 !!}  ถึง  {!! (($datas_province->currentPage()-1)*$datas_province->perPage())+$datas_province->count() !!} จากทั้งหมด  {!! $datas_province->total() !!}
							</ul>
							<ul class="pagination">
								{!! $datas_province->appends(Request::all())->render() !!}
							</ul>
					</div>
			  </div>
		</div>
	</div>
</section>
<!-- /.content -->
<?php
unset($get_provincename_th);
unset($get_sub_level);
 ?>
@stop
