@extends('layouts.template')
@section('content')
@section('style')
<style>
tr.group,
tr.group:hover {
    background-color: #ddd !important;
}
</style>
@endsection
<?php
use \App\Http\Controllers\Controller as Controller;
use \App\Http\Controllers\ExportPatientController as ExportPatientController;
use \App\Http\Controllers\PopulationController as PopulationController;

$arr_month = array('Jan','Feb','Mar','Apr','May','June','July','Aug','Sept','Oct','Nov','Dec','Total');
$get_all_province_th = Controller::get_provincename_th();
$get_all_disease = Controller::list_disease();
$get_all_disease_array = Controller::list_disease()->toArray();


//add array
function array_push_assoc($array, $key, $value){
$array[$key] = $value;
return $array;
}
array_push_assoc($get_all_disease,'26-27-66',"DHF Total");

$select_year = (isset($_GET['select_year']))? $_GET['select_year'] : date('Y')-1;
$disease_code = (isset($_GET['disease_code']))? $_GET['disease_code'] : "01";
?>
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>รายงานข้อมูลอัตราป่วย/อัตราตาย/อัตราป่วย-ตาย<small>รายจังหวัด</small></h1>
<ol class="breadcrumb">
	<li><a href="{{ route('dashboard') }}"><i class="fa fa-home"></i> หนัาหลัก</a></li>
	<li><a href="#" class="active">รายงาน</a></li>
  <li><a href="{{ route('export-patient-data.main') }}" class="active">รายงานข้อมูลผู้ป่วย</a></li>
	<li><a href="{{ route('export-patient.sick-death-month') }}" class="active">ส่งออกข้อมูลอัตราป่วย/อัตราตาย/อัตราป่วย-ตาย จำแนกรายจังหวัด</a></li>
</ol>
</section>
<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-6">
			<!-- Default box -->
			<div class="box box-info">
				<div class="box-header with-border">
					<h3 class="box-title">อัตราป่วย/อัตราตาย/อัตราป่วย-ตาย จำแนกรายจังหวัด</h3>
				</div>
				<div class="box-body">
				<form action='{{ route('export-patient.sick-death-ratio') }}' class="form-horizontal" method="get">
					<div class="box-body">
						<div class="form-group">
							<label for="input_monthchoose" class="col-sm-2 control-label">โรค</label>
							<div class="col-sm-4">
								<select class="form-control" name="disease_code" id="disease_code">
								@foreach ($get_all_disease as $disease_key => $disease_value)
									<option value="{{ $disease_key }}" <?php if($disease_key == $disease_code){ echo 'selected="selected"'; }?>>{{ $disease_key }} - {{ $disease_value }}</option>
								@endforeach
								</select>
							</div>
						</div>
						<div class="form-group">
						<label for="input_yearchoose" class="col-sm-2 control-label">ปี</label>
							<div class="col-sm-4">
								<?php
											//$current_year =  (isset($_GET['year']))? $_GET['year']: date('Y');
											//Current Year
											$already_selected_value = (int)date('Y');
											//Start Year
											$earliest_year = 2012;

											echo '<select name="select_year" class="form-control" id="select_year">';
												foreach (range($already_selected_value, $earliest_year) as $x) {
														 $year_th = $x+543;
														 print '<option value="'.$x.'"'.($x ==$select_year ? ' selected="selected"' : '').'>'.$year_th.'</option>';
												}
											echo '</select>';
								?>
							</div>
						</div>
					</div>
					<!-- /.box-body -->
					<div class="box-footer">
						<button type="submit" class="btn btn-info pull-right">แสดงข้อมูล</button>
					</div>
					<!-- /.box-footer -->
				</form>
			</div>
		</div>
	</div>
 </div>

 <div class="row">
	 	<div class="col-md-12">
			<div class="box box-success">
				<div class="box-header with-border">
					<h3 class="box-title"><span class="ds-box-title">ตารางข้อมูลอัตราป่วย/อัตราตาย/อัตราป่วย-ตาย จำแนกรายจังหวัด โรค <?php if($disease_code=='26-27-66'){ echo 'DHF Total'; } else{ echo $disease_code.' - '.$get_all_disease_array[$disease_code];}?> ปี <?php echo $select_year+543;?></span></h3>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-bordered table-responsive" id="example" style="width:100%">
									<thead>
											<tr>
												  <th>DPC_NAME</th>
													<th>Reporting Area</th>
													<th class="text-center">จำนวนผู้ป่วย</th>
                          <th class="text-center">อัตราป่วย(ต่อประชากรแสนคน)</th>
                          <th class="text-center">จำนวนผู้เสียชีวิต</th>
                          <th class="text-center">อัตราป่วยตาย(%)</th>
                          <th class="text-center">อัตราตาย(ต่อประชากรแสนคน)</th>
                          <th class="text-center">จำนวนประชากร</th>
											</tr>
									</thead>
									<tbody>
											<?php $get_data = ExportPatientController::get_patient_sick_death_ratio($select_year,$disease_code); ?>
                    	@foreach ($get_data as $data)
                      <?php $total_pop_in_province = PopulationController::all_population_by_province($select_year);
                            if(isset($total_pop_in_province[$data['PROVINCE_CODE']]['poptotal_in_province'])){
                              $total_pop = number_format($total_pop_in_province[$data['PROVINCE_CODE']]['poptotal_in_province']);
                              $cal_ratio_cases = Controller::cal_ratio_cases($total_pop_in_province[$data['PROVINCE_CODE']]['poptotal_in_province'],$data['case_total']);
                              $cal_ratio_deaths = Controller::cal_ratio_cases_deaths($total_pop_in_province[$data['PROVINCE_CODE']]['poptotal_in_province'],$data['death_total']);
                              $cal_ratio_cases_deaths = Controller::cal_ratio_cases_deaths($data['case_total'],$data['death_total']);
                            }else{
                              $total_pop = 0;
                              $cal_ratio_cases = 0;
                              $cal_ratio_deaths = 0;
                            }
                            ?>
											<tr>
                        <td>{{ $data['DPC'] }}</td>
												<td>{{ $data['PROVINCE'] }}</td>
												<td class="text-center">{{ number_format($data['case_total']) }}</td>
												<td class="text-center">{{ $cal_ratio_cases }}</td>
												<td class="text-center">{{ number_format($data['death_total']) }}</td>
												<td class="text-center">{{ $cal_ratio_cases_deaths }}</td>
												<td class="text-center">{{ $cal_ratio_deaths }}</td>
												<td class="text-center">{{ $total_pop }}</td>
											</tr>
											@endforeach
									</tbody>
									<tfoot>
                    <th>DPC_NAME</th>
                    <th>Reporting Area</th>
                    <th class="text-center">จำนวนผู้ป่วย</th>
                    <th class="text-center">อัตราป่วย(ต่อประชากรแสนคน)</th>
                    <th class="text-center">จำนวนผู้เสียชีวิต</th>
                    <th class="text-center">อัตราป่วยตาย(%)</th>
                    <th class="text-center">อัตราตาย(ต่อประชากรแสนคน)</th>
                    <th class="text-center">จำนวนประชากร</th>
									</tfoot>
							 </table>
						 </div>
						</div>

					</div>
					<!-- /.row -->
				</div>
				<!-- /.box-body -->
				<div class="box-footer">
            <a href="{{ route('xls_patient_sick_death_ratio') }}?disease_code={{ $disease_code }}&select_year={{ $select_year }}" class="btn btn-sm btn-success pull-right"><i class="fa fa-download"> </i> ส่งออกข้อมูลเป็น XLS</a>
				</div>
				<!-- /.footer -->
			</div>
		</div>
 </div>

</section>
<!-- /.content -->
@stop
@section('CustomJSScript')

@stop
@section('script')
<script>
$(document).ready(function() {
    var groupColumn = 0;
    var table = $('#example').DataTable({
        "columnDefs": [
            { "visible": false, "targets": groupColumn},
						{ "orderable": false, targets: '_all' }
        ],
        //"order": [[ groupColumn, 'asc' ]],

        //"displayLength": 25,
				"bPaginate": false,
				"searching": false,
				"bInfo": false,
        "drawCallback": function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last=null;

            api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
                if ( last !== group ) {
                    $(rows).eq( i ).before(
                        '<tr class="group"><td colspan="27">'+group+'</td></tr>'
                    );
                    last = group;
                }
            } );
        }
    } );

    //Order by the grouping
    // $('#example tbody').on( 'click', 'tr.group', function () {
    //     var currentOrder = table.order()[0];
    //     if ( currentOrder[0] === groupColumn && currentOrder[1] === 'asc' ) {
    //         table.order( [ groupColumn, 'desc' ] ).draw();
    //     }
    //     else {
    //         table.order( [ groupColumn, 'asc' ] ).draw();
    //     }
    // } );
} );
</script>
@endsection
