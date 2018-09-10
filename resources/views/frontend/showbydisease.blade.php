@extends('layouts.template')
@section('content')
<?php
$i=1;
$j=2;
//ini_set('max_execution_time', 300); //300 seconds = 5 minutes
use \App\Http\Controllers\Controller as Controller;
use \App\Http\Controllers\PopulationController as PopulationController;
$get_provincename_th =\App\Http\Controllers\Controller::get_provincename_th();
//$get_list_disease =\App\Http\Controllers\Controller::list_disease();
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
$current_year =  (isset($_GET['year']))? $_GET['year']: date('Y');
$disease_code =  (isset($_GET['disease_code']))? $_GET['disease_code']: '01';
$current_year_th = $current_year+543;
//$total_all_pop = PopulationController::all_population($current_year);
$total_pop_in_province = PopulationController::all_population_by_province($current_year);
$total_pop_in_urban = PopulationController::all_population_by_urban($current_year);


//dd($total_pop_in_urban['เทศบาลนครเชียงใหม่']);

// if($disease_code=="26-27-66"){
//  $disease_name = "Total D.H.F.";
// }else{
//  $disease_name = $get_list_disease[$disease_code];
// }
?>
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>รายงานปี <?php echo $current_year_th; ?> โรค <?php echo $get_all_disease[$disease_code];?></h1>
<ol class="breadcrumb">
	<li><a href="{{ route('dashboard') }}"><i class="fa fa-home"></i> หน้าหลัก</a></li>
	<li><a href="{{ route('dashboard') }}?year=<?php echo $current_year; ?>">ข้อมูลปี <?php echo $current_year_th; ?></a></li>
	<li class="active"><a href="showbydisease?disease_code={{ $disease_code }}&year={{ $current_year }}"> โรค <?php echo $get_all_disease[$disease_code];?></a></li>
</ol>
</section>
<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-8">
			<!-- Default box -->
			<div class="box">
				<div class="box-header with-border">
					<h3 class="box-title">แสดงข้อมูลปี <?php echo $current_year_th; ?> โรค <?php echo $get_all_disease[$disease_code];?></h3>
				</div>
				<div class="box-body">
					<table id="tree-table" class="table table-hover table-bordered">
						<thead>
							<tr>
								<th rowspan="2">จังหวัด</th>
								<th colspan="2">ป่วย</th>
								<th colspan="2">ตาย</th>
								<th rowspan="2">อัตรา ป/ต</th>
							</tr>
							<tr>
								<th>จำนวน</th>
								<th>อัตรา</th>
								<th>ตาย</th>
								<th>อัตรา</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($datas_province as $value_province)
              <?php if(isset($total_pop_in_province[$value_province->PROVINCE]['poptotal_in_province'])){
                      $total_pop_province = number_format($total_pop_in_province[$value_province->PROVINCE]['poptotal_in_province']);
                      $cal_ratio_cases_province = Controller::cal_ratio_cases($total_pop_in_province[$value_province->PROVINCE]['poptotal_in_province'],$value_province->total_cases);
                      $cal_ratio_deaths_province = Controller::cal_ratio_cases_deaths($total_pop_in_province[$value_province->PROVINCE]['poptotal_in_province'],$value_province->total_deaths);
                      $cal_ratio_cases_deaths_province = Controller::cal_ratio_cases_deaths($value_province->total_cases,$value_province->total_deaths);
                    }else{
                      $total_pop_province = 0;
                      $cal_ratio_cases_province = 0;
                      $cal_ratio_deaths_province = 0;
                      $cal_ratio_cases_deaths_province = 0;
                    }
              ?>
							<tr data-id="{{ $value_province->PROVINCE }}" data-parent="0" data-level="1">
								<td data-column="name">{{ $get_provincename_th[$value_province->PROVINCE] }}</td>
								<td>{{ number_format($value_province->total_cases) }}</td>
								<td>{{ $cal_ratio_cases_province }}</td>
								<td>{{ number_format($value_province->total_deaths) }}</td>
								<td>{{ $cal_ratio_deaths_province }}</td>
								<td>{{ $cal_ratio_cases_deaths_province }}</td>
							</tr>
							<?php $get_sub_level = \App\Http\Controllers\PopulationController::ShowByDiseaseSub($value_province->PROVINCE,$current_year,$disease_code); ?>
              @foreach ($get_sub_level as $value_sub_level)
              <?php if(isset($total_pop_in_urban[$value_sub_level->urbanname]['poptotal_in_urban'])){
                      $total_pop_urban = number_format($total_pop_in_urban[$value_sub_level->urbanname]['poptotal_in_urban']);
                      $cal_ratio_cases_urban = Controller::cal_ratio_cases($total_pop_in_urban[$value_sub_level->urbanname]['poptotal_in_urban'],$value_sub_level->total_cases);
                      $cal_ratio_deaths_urban = Controller::cal_ratio_cases_deaths($total_pop_in_urban[$value_sub_level->urbanname]['poptotal_in_urban'],$value_sub_level->total_deaths);
                      $cal_ratio_cases_deaths_urban = Controller::cal_ratio_cases_deaths($value_sub_level->total_cases,$value_sub_level->total_deaths);
              }else{
                $total_pop_urban = 0;
                $cal_ratio_cases_urban = 0;
                $cal_ratio_deaths_urban = 0;
                $cal_ratio_cases_deaths_urban = 0;
              }
              ?>
							<tr data-id="{{ $i }}" data-parent="{{ $value_province->PROVINCE }}" data-level="2">
								<td data-column="name">{{ $value_sub_level->urbanname }}</td>
								<td>{{ number_format($value_sub_level->total_cases) }}</td>
								<td>{{ $cal_ratio_cases_urban }}</td>
								<td>{{ number_format($value_sub_level->total_deaths) }}</td>
								<td>{{ $cal_ratio_deaths_urban }}</td>
								<td>{{ $cal_ratio_cases_deaths_urban }}</td>
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
					<div class="text-right">
						<a type="button" href="{{ route('export_total_disease') }}?disease_code={{$disease_code}}&year={{$current_year}}" class="btn btn-success pull-right">ส่งออกข้อมูล</a>
					</div>
			  </div>
		</div>
	</div>
</section>
<!-- /.content -->
<?php
unset($get_provincename_th);
unset($get_sub_level);
unset($get_list_disease);
 ?>
@stop
@section('script')
<script>
$(document).ready(function () {
	$('.sidebar-menu').tree();
	$('#example10').DataTable({
		'paging'      : false,
		'lengthChange': false,
		'searching'   : false,
		'ordering'    : true,
		'info'        : false,
		'autoWidth'   : false,
		"aaSorting": [[ 1, "desc" ]]
	});
});
</script>
@stop
