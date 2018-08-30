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
<h1>รายงานข้อมูลผู้ป่วย <small>รายสัปดาห์</small></h1>
<ol class="breadcrumb">
	<li><a href="{{ route('dashboard') }}"><i class="fa fa-home"></i> หนัาหลัก</a></li>
	<li><a href="#" class="active">รายงาน</a></li>
  <li><a href="{{ route('export-patient-data.main') }}" class="active">รายงานข้อมูลผู้ป่วย</a></li>
	<li><a href="{{ route('export-patient.sick-death-month') }}" class="active">ส่งออกข้อมูลผู้ป่วยจำนวนป่วย รายเดือน</a></li>
</ol>
</section>
<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-6">
			<!-- Default box -->
			<div class="box box-info">
				<div class="box-header with-border">
					<h3 class="box-title">ข้อมูลจำนวนป่วย รายสัปดาห์ จำแนกรายจังหวัด</h3>
				</div>
				<div class="box-body">
				<form action='{{ route('export-patient.sick-weekly') }}' class="form-horizontal" method="get">
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
					<h3 class="box-title"><span class="ds-box-title">ตารางข้อมูลจำนวนป่วย รายสัปดาห์ จำแนกรายจังหวัด โรค <?php echo $get_all_disease[$disease_code];?> ปี <?php echo $select_year+543;?></span></h3>
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
													<?php for($wk=1;$wk<=53;$wk++) : ?>
													<th class="text-center">{{ $wk }}</th>
													<?php endfor; ?>
											</tr>
									</thead>
									<tbody>
											<?php $get_data = ExportPatientController::get_patient_sick_weekly($select_year,$disease_code); ?>
											@foreach ($get_data as $data)
											<tr>
												<td>{{ $data['prov_dpc'] }}</td>
												<td>{{ $data['PROVINCE'] }}</td>
												<td>{{ number_format($data['wk01']) }}</td>
												<td>{{ number_format($data['wk02']) }}</td>
												<td>{{ number_format($data['wk03']) }}</td>
												<td>{{ number_format($data['wk04']) }}</td>
												<td>{{ number_format($data['wk05']) }}</td>
												<td>{{ number_format($data['wk06']) }}</td>
												<td>{{ number_format($data['wk07']) }}</td>
												<td>{{ number_format($data['wk08']) }}</td>
												<td>{{ number_format($data['wk09']) }}</td>
												<td>{{ number_format($data['wk10']) }}</td>
												<td>{{ number_format($data['wk11']) }}</td>
												<td>{{ number_format($data['wk12']) }}</td>
												<td>{{ number_format($data['wk13']) }}</td>
												<td>{{ number_format($data['wk14']) }}</td>
												<td>{{ number_format($data['wk15']) }}</td>
												<td>{{ number_format($data['wk16']) }}</td>
												<td>{{ number_format($data['wk17']) }}</td>
												<td>{{ number_format($data['wk18']) }}</td>
												<td>{{ number_format($data['wk19']) }}</td>
												<td>{{ number_format($data['wk20']) }}</td>
												<td>{{ number_format($data['wk21']) }}</td>
												<td>{{ number_format($data['wk22']) }}</td>
												<td>{{ number_format($data['wk23']) }}</td>
												<td>{{ number_format($data['wk24']) }}</td>
												<td>{{ number_format($data['wk25']) }}</td>
												<td>{{ number_format($data['wk26']) }}</td>
                        <td>{{ number_format($data['wk27']) }}</td>
                        <td>{{ number_format($data['wk28']) }}</td>
                        <td>{{ number_format($data['wk29']) }}</td>
                        <td>{{ number_format($data['wk30']) }}</td>
                        <td>{{ number_format($data['wk31']) }}</td>
                        <td>{{ number_format($data['wk32']) }}</td>
                        <td>{{ number_format($data['wk33']) }}</td>
                        <td>{{ number_format($data['wk34']) }}</td>
                        <td>{{ number_format($data['wk35']) }}</td>
                        <td>{{ number_format($data['wk36']) }}</td>
                        <td>{{ number_format($data['wk37']) }}</td>
                        <td>{{ number_format($data['wk38']) }}</td>
                        <td>{{ number_format($data['wk39']) }}</td>
                        <td>{{ number_format($data['wk40']) }}</td>
                        <td>{{ number_format($data['wk41']) }}</td>
                        <td>{{ number_format($data['wk42']) }}</td>
                        <td>{{ number_format($data['wk43']) }}</td>
                        <td>{{ number_format($data['wk44']) }}</td>
                        <td>{{ number_format($data['wk45']) }}</td>
                        <td>{{ number_format($data['wk46']) }}</td>
                        <td>{{ number_format($data['wk47']) }}</td>
                        <td>{{ number_format($data['wk48']) }}</td>
                        <td>{{ number_format($data['wk49']) }}</td>
                        <td>{{ number_format($data['wk50']) }}</td>
                        <td>{{ number_format($data['wk51']) }}</td>
                        <td>{{ number_format($data['wk52']) }}</td>
                        <td>{{ number_format($data['wk53']) }}</td>
											</tr>
											@endforeach
									</tbody>
									<tfoot>
                    <tr>
                        <th>DPC_NAME</th>
                        <th>Reporting Area</th>
                        <?php for($wk=1;$wk<=53;$wk++) : ?>
                        <th class="text-center">{{ $wk }}</th>
                        <?php endfor; ?>
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
            <a href="{{ route('xls_patient_sick_weekly') }}?disease_code={{ $disease_code }}&select_year={{ $select_year }}" class="btn btn-sm btn-success pull-right"><i class="fa fa-download"> </i> ส่งออกข้อมูลเป็น CSV</a>
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
                        '<tr class="group"><td colspan="54">'+group+'</td></tr>'
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
