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

$arr_month = array('Jan','Feb','Mar','Apr','May','June','July','Aug','Sept','Oct','Nov','Dec','Total');
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

$select_year = (isset($_GET['select_year']))? $_GET['select_year'] : date('Y')-1;
$disease_code = (isset($_GET['disease_code']))? $_GET['disease_code'] : "01";

?>
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>รายงานข้อมูลผู้ป่วย</h1>
<ol class="breadcrumb">
	<li><a href="{{ route('dashboard') }}"><i class="fa fa-home"></i> หนัาหลัก</a></li>
	<li><a href="#" class="active">รายงาน</a></li>
  <li><a href="{{ route('export-patient-data.main') }}" class="active">รายงานข้อมูลผู้ป่วย</a></li>
	<li><a href="{{ route('export-patient.sick-death-month') }}" class="active">ส่งออกข้อมูลจำนวนป่วย/ตาย รายเดือน</a></li>
</ol>
</section>
<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-6">
			<!-- Default box -->
			<div class="box box-info">
				<div class="box-header with-border">
					<h3 class="box-title"><i class="fa fa-search"></i> ค้นหาข้อมูลจำนวนป่วย/ตาย</h3>
				</div>
				<div class="box-body">
				<form action='{{ route('export-patient.sick-death-month') }}' class="form-horizontal" method="get">
					<div class="box-body">
						<div class="form-group">
							<label for="input_monthchoose" class="col-sm-2 control-label">โรค</label>
							<div class="col-sm-4">
								<select class="form-control" name="disease_code" id="disease_code">
								@foreach ($get_all_disease as  $disease_key => $disease_value)
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
					<h3 class="box-title"><span class="ds-box-title">ตารางข้อมูลจำนวนป่วย/ตาย โรค <?php echo $get_all_disease[$disease_code];?> ปี <?php echo $select_year+543;?></span></h3>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-bordered table-responsive" id="example" style="width:100%">
									<thead>
											<tr>
												  <th rowspan="2">DPC_NAME</th>
													<th rowspan="2" class="text-top">Reporting Area</th>
													@foreach ($arr_month as $month)
													<th colspan="2" class="text-center">{{ $month }}</th>
													@endforeach
											</tr>
											<tr>
												@foreach ($arr_month as $month)
												<th class="text-center">Cases</th>
												<th class="text-center">Deaths</th>
												@endforeach
											</tr>
									</thead>
									<tbody>
											<?php $get_data = ExportPatientController::get_patient_sick_death_by_month($select_year,$disease_code); ?>
											@foreach ($get_data as $data)
											<tr>
												<td>{{ $data['prov_dpc'] }}</td>
												<td>{{ $data['PROVINCE'] }}</td>
												<td>{{ number_format($data['case_jan']) }}</td>
												<td>{{ number_format($data['death_jan']) }}</td>
												<td>{{ number_format($data['case_feb']) }}</td>
												<td>{{ number_format($data['death_feb']) }}</td>
												<td>{{ number_format($data['case_mar']) }}</td>
												<td>{{ number_format($data['death_mar']) }}</td>
												<td>{{ number_format($data['case_apr']) }}</td>
												<td>{{ number_format($data['death_apr']) }}</td>
												<td>{{ number_format($data['case_may']) }}</td>
												<td>{{ number_format($data['death_may']) }}</td>
												<td>{{ number_format($data['case_jun']) }}</td>
												<td>{{ number_format($data['death_jun']) }}</td>
												<td>{{ number_format($data['case_jul']) }}</td>
												<td>{{ number_format($data['death_jul']) }}</td>
												<td>{{ number_format($data['case_aug']) }}</td>
												<td>{{ number_format($data['death_aug']) }}</td>
												<td>{{ number_format($data['case_sep']) }}</td>
												<td>{{ number_format($data['death_sep']) }}</td>
												<td>{{ number_format($data['case_oct']) }}</td>
												<td>{{ number_format($data['death_oct']) }}</td>
												<td>{{ number_format($data['case_nov']) }}</td>
												<td>{{ number_format($data['death_nov']) }}</td>
												<td>{{ number_format($data['case_dec']) }}</td>
												<td>{{ number_format($data['death_dec']) }}</td>
												<td>{{ number_format($data['total_case']) }}</td>
												<td>{{ number_format($data['total_death']) }}</td>
											</tr>
											@endforeach
									</tbody>
									<tfoot>
											<tr>
													<th rowspan="2">DPC_NAME</th>
													<th rowspan="2">Reporting Area</th>
													@foreach ($arr_month as $month)
													<th colspan="2" class="text-center">{{ $month }}</th>
													@endforeach
											</tr>
											<tr>
												@foreach ($arr_month as $month)
												<th class="text-center">Cases</th>
												<th class="text-center">Deaths</th>
												@endforeach
											</tr>
									</tfoot>
							 </table>
						 </div>
						</div>

					</div>
					<!-- /.row -->
				</div>
				<!-- /.box-body -->
				<div class="box-footer">
            <a href="{{ route('xls_patient_sick_death_by_month') }}?disease_code={{ $disease_code }}&select_year={{ $select_year }}" class="btn btn-sm btn-success pull-right"><i class="fa fa-download"> </i> ส่งออกข้อมูลเป็น CSV</a>
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
