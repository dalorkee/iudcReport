@extends('layouts.template')
@section('content')
@section('style')
<style>
tr.group,
tr.group:hover {
    background-color: #ddd !important;
}
</style>
{{ Html::style(('https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css')) }}
@endsection
<?php
$get_all_disease =\App\Http\Controllers\Controller::list_disease();
//add array
function array_push_assoc($array, $key, $value){
$array[$key] = $value;
return $array;
}
array_push_assoc($get_all_disease,'26-27-66',"DHF Total");

$arr_month = array('Jan','Feb','Mar','Apr','May','June','July','Aug','Sept','Oct','Nov','Dec','Total');
$get_all_province_th =\App\Http\Controllers\Controller::get_provincename_th();

$select_year = (isset($_GET['select_year']))? $_GET['select_year'] : date('Y')-1;
$disease_code = (isset($_GET['disease_code']))? $_GET['disease_code'] : "01";

?>
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>รายงานข้อมูลผู้ป่วย <small>รายเดือน</small></h1>
<ol class="breadcrumb">
	<li><a href="{{ route('dashboard') }}"><i class="fa fa-home"></i> หนัาหลัก</a></li>
	<li><a href="#" class="active">รายงาน</a></li>
	<li><a href="{{ route('export-patient.sick-death-month') }}" class="active">ส่งออกข้อมูลผู้ป่วยจำนวนป่วย/ตาย รายเดือน</a></li>
</ol>
</section>
<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-6">
			<!-- Default box -->
			<div class="box box-info">
				<div class="box-header with-border">
					<h3 class="box-title">ข้อมูลผู้ป่วยจำนวนป่วย/ตาย</h3>
				</div>
				<div class="box-body">
				<form action='{{ route('post_patient_sick_death_by_month') }}' class="form-horizontal" method="GET">
					<div class="box-body">
						<div class="form-group">
							<label for="input_monthchoose" class="col-sm-2 control-label">โรค</label>
							<div class="col-sm-4">
								<select class="form-control" name="disease_code" id="disease_code">
								@foreach ($get_all_disease as $disease_key => $disease_value)
									<option value="{{ $disease_key }}">{{ $disease_key }} - {{ $disease_value }}</option>
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
					<h3 class="box-title"><span class="ds-box-title">ตารางข้อมูลผู้ป่วยจำนวนป่วย/ตาย โรค <?php echo $disease_code;?></span></h3>
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
											<?php $get_data = \App\Http\Controllers\ExportPatientController::post_patient_sick_death_by_month($select_year,$disease_code); ?>
											@foreach ($get_data as $data)
											<tr>
												<td>{{ $data['DPC'] }}</td>
												<td>{{ $data['PROVINCE'] }}</td>
												<td>{{ $data['case_jan'] }}</td>
												<td>{{ $data['death_jan'] }}</td>
												<td>{{ $data['case_feb'] }}</td>
												<td>{{ $data['death_feb'] }}</td>
												<td>{{ $data['case_mar'] }}</td>
												<td>{{ $data['death_mar'] }}</td>
												<td>{{ $data['case_apr'] }}</td>
												<td>{{ $data['death_apr'] }}</td>
												<td>{{ $data['case_may'] }}</td>
												<td>{{ $data['death_may'] }}</td>
												<td>{{ $data['case_jun'] }}</td>
												<td>{{ $data['death_jun'] }}</td>
												<td>{{ $data['case_jul'] }}</td>
												<td>{{ $data['death_jul'] }}</td>
												<td>{{ $data['case_aug'] }}</td>
												<td>{{ $data['death_aug'] }}</td>
												<td>{{ $data['case_sep'] }}</td>
												<td>{{ $data['death_sep'] }}</td>
												<td>{{ $data['case_oct'] }}</td>
												<td>{{ $data['death_oct'] }}</td>
												<td>{{ $data['case_nov'] }}</td>
												<td>{{ $data['death_nov'] }}</td>
												<td>{{ $data['case_dec'] }}</td>
												<td>{{ $data['death_dec'] }}</td>
												<td>{{ $data['total_case'] }}</td>
												<td>{{ $data['total_death'] }}</td>
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
						<button type="submit" class="btn btn-success pull-right">ส่งออกข้อมูลเป็น XLS</button>
				</div>
				<!-- /.footer -->
			</div>
		</div>
 </div>

 <!-- <div class="row">
 <div class="col-md-12">
							<table class="table table-bordered" id="posts">
									 <thead>
													<th>Id</th>
													<th>Title</th>
													<th>Body</th>
													<th>Created At</th>
													<th>Options</th>
									 </thead>
							</table>
			 </div>
</div> -->


</section>
<!-- /.content -->
@stop
@section('CustomJSScript')

@stop
@section('script')
<script>
// $(document).ready(function () {
// 				//var provincename_th = {!! json_encode($get_all_province_th) !!};
// 				//var disease_code = $('#disease_code').val();
// 				//var select_year = $('#select_year').val();
// 				//console.log('disease_code='+disease_code+'<><><> select_year='+select_year);
//         $('#example').DataTable({
//             //"processing": true,
//             //"serverSide": true,
// 						"searching": false,
// 						"bPaginate": false,
// 						"bInfo": false,
// 						"order": []
// 						//"pageLength": 10
//         });
//     });
$(document).ready(function() {
    var groupColumn = 0;
    var table = $('#example').DataTable({
        "columnDefs": [
            { "visible": false, "targets": groupColumn},
						{ "orderable": false, targets: -27 }
        ],
        "order": [[ groupColumn, 'asc' ]],

        //"displayLength": 25,
				"bPaginate": false,
				"searching": false,
				//"bInfo": false,
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
    $('#example tbody').on( 'click', 'tr.group', function () {
        var currentOrder = table.order()[0];
        if ( currentOrder[0] === groupColumn && currentOrder[1] === 'asc' ) {
            table.order( [ groupColumn, 'desc' ] ).draw();
        }
        else {
            table.order( [ groupColumn, 'asc' ] ).draw();
        }
    } );
} );
</script>
@endsection
