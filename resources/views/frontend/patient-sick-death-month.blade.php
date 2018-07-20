@extends('layouts.template')
@section('content')
@section('style')
{{ Html::style(('https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css')) }}

@endsection
@section('incHeaderScript')

@endsection
<?php
$get_all_disease =\App\Http\Controllers\Controller::list_disease();
//add array
function array_push_assoc($array, $key, $value){
$array[$key] = $value;
return $array;
}
array_push_assoc($get_all_disease,'26-27-66',"DHF Total");

$arr_month = array('Jan','Feb','Mar','Apr','May','June','July','Aug','Sept','Nov','Dec','Total');
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
				<form action='{{ route('post_population_area') }}' class="form-horizontal" method="post">
					{{ csrf_field() }}
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
					<h3 class="box-title"><span class="ds-box-title">ตารางข้อมูลผู้ป่วยจำนวนป่วย/ตาย</span></h3>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-bordered table-responsive" id="users-table">
									<thead>
											<tr>
													<th rowspan="2">Reporting Area</th>
													@foreach ($arr_month as $month)
													<th colspan="2">{{ $month }}</th>
													@endforeach
											</tr>
											<tr>
												@foreach ($arr_month as $month)
												<th>Cases</th>
												<th>Deaths</th>
												@endforeach
											</tr>
									</thead>
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
@section('script')
<script>

$(document).ready(function () {
				var disease_code = $('#disease_code').val();
				var select_year = $('#select_year').val();
				//console.log('disease_code='+disease_code+'<><><> select_year='+select_year);
        $('#users-table').DataTable({
            "processing": true,
            "serverSide": true,
						"searching": false,
						"bPaginate": false,
						"bInfo": false,
						dom: 'Bfrtip',
		        buttons: [
		            'copy', 'csv', 'excel', 'pdf', 'print'
		        ],
            "ajax":{
                     "url": "{{ route('get_patient_sick_death_by_month') }}",
                     "dataType": "json",
                     "type": "GET",
                     "data":{ disease_code : disease_code,select_year : select_year }
                   },
            "columns": [
												{"data" : "id"},
												{"data" : "name"},
												{"data" : "email1"},
												{"data" : "email2"},
												{"data" : "email3"},
												{"data" : "email4"},
												{"data" : "email5"},
												{"data" : "email6"},
												{"data" : "email7"},
												{"data" : "email8"},
												{"data" : "email9"},
												{"data" : "email10"},
												{"data" : "email11"},
												{"data" : "email12"},
												{"data" : "email13"},
												{"data" : "email14"},
												{"data" : "email15"},
												{"data" : "email16"},
												{"data" : "email17"},
												{"data" : "email18"},
												{"data" : "email19"},
												{"data" : "email20"},
												{"data" : "email21"},
												{"data" : "email22"},
												{"data" : "email23"},
            ]

        });
    });
</script>
@endsection
