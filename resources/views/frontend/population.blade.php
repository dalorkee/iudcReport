@extends('layouts.template')
@section('content')
<?php
  use \App\Http\Controllers\Controller as Controller;
	use \App\Http\Controllers\PopulationController as PopulationController;
	$select_year = (isset($_GET['year']))? $_GET['year']: date('Y');

	// get group disease name
	$get_group_of_disease =\App\Http\Controllers\Controller::get_group_of_disease($select_year);
	$get_group_of_disease_th =\App\Http\Controllers\Controller::get_group_of_disease_th($select_year);
  $get_disease_more_code = \App\Http\Controllers\Controller::list_merge_disease();

	//count group_of_disease
	$total_group_of_disease = count($get_group_of_disease);
	$current_year =  (isset($_GET['year']))? $_GET['year']: date('Y');
	$current_year_th = $current_year+543;

	$total_all_pop = PopulationController::all_population($current_year);
  //echo $total_all_pop;
?>
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>รายงาน.....ประจำปี {{ $current_year_th }}</h1>
	<ol class="breadcrumb">
		<li><a href="{{ route('population') }}"><i class="fa fa-home"></i> หนัาหลัก</a></li>
		<li class="active">รายงาน....ประจำปี {{ $current_year_th }}</li>
	</ol>
</section>
<!-- Main content -->
<section class="content">
	<?php
	$max_columns = 2; //columns will arrange to any number (as long as it is evenly divisible by 12)
	$column = 12/$max_columns; //column number
	$total_items = $total_group_of_disease ;
	$remainder = $total_group_of_disease %$max_columns; //how many items are in the last row
	$first_row_item = ($total_items - $remainder); //first item in the last row
?>
<?php $i=0; // counter ?>
<?php foreach ($get_group_of_disease as $group_name_of_disease): ?>
<?php $data[$group_name_of_disease] = PopulationController::get_total_population($select_year);
	//convert json data
	$json = json_decode($data[$group_name_of_disease], true);
?>
<?php if ($i%$max_columns==0) { // if counter is multiple of 3 ?>
	<div class="row">
	<?php } ?>

<?php if ($i >= $first_row_item) { //if in last row ?>
	<div class="col-md-<?php echo 12/$remainder; ?>">
<?php } else { ?>
	<div class="col-md-<?php echo $column; ?>">
<?php } ?>
		<div class="box box-primary direct-chat direct-chat-primary">
			<div class="box-header with-border">
				<h3 class="box-title"><?php echo $get_group_of_disease_th[$group_name_of_disease];?></h3>
			</div>
			<div class="box-body">
				<div class="table-responsive">
					<table class="iudc-tbl" id="example<?php echo $i;?>">
						<thead>
							<tr>
								<th rowspan="2">โรค (Code)</th>
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
						<tfoot></tfoot>
						<tbody>
						<?php $j=1;  foreach ($json[$group_name_of_disease] as $total_by_disease) : ?>
							<tr>
								<td><a href="showbydisease?disease_code={{ $total_by_disease['DISEASE'] }}&year={{ $current_year }}"><?php echo $total_by_disease['DISNAME'];?></a></td>
								<td><?php echo number_format($total_by_disease['total']);?></td>
								<td><?php echo Controller::cal_ratio_cases($total_all_pop,$total_by_disease['total']);?></td>
								<td><?php echo number_format($total_by_disease['totald']);?></td>
								<td><?php echo Controller::cal_ratio_deaths($total_all_pop,$total_by_disease['totald']);?></td>
								<td><?php if($total_by_disease['totald']>0) { echo Controller::cal_ratio_cases_deaths($total_by_disease['total'],$total_by_disease['totald']); }else{ echo "0.00";}?></td>
							</tr>
							<?php $j++; endforeach; ?>
						</tbody>
					</table>
				</div>
				<!-- <?php if($group_name_of_disease=='vbd') :?>
					<div class="box-footer text-center">
						<div class="no-margin">
							 <a href="showbydisease?disease_code=26-27-66&year={{ $current_year }}">Total D.H.F. = <?php echo number_format($json['vbd']['0']['total']+$json['vbd']['1']['total']+$json['vbd']['3']['total']); ?></a>
						</div>
					</div>
				<?php endif; ?> -->
			</div><!-- /.box-body -->
		</div>
	</div>
	<?php $i++; ?>
	<?php if($i%$max_columns==0) { // if counter is multiple of 3 ?>
		</div>
	<?php } ?>
<?php endforeach; ?>
<?php if($i%$max_columns!=0) { // put closing div if loop is not exactly a multiple of 3 ?>
	</div>
<?php } ?>
<div class="row">
    <div class="col-md-8">
      <div class="box box-primary direct-chat direct-chat-primary">
        <div class="box-header with-border">
          <h3 class="box-title">โรคหลายรหัส</h3>
        </div>
        <div class="box-body">
          <div class="table-responsive">
            <table class="iudc-tbl" id="example10">
              <thead>
                <tr>
                  <th rowspan="2">โรค (Code)</th>
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
              <tfoot></tfoot>
              <tbody>
                @foreach ($get_disease_more_code as $key_disease_more_code => $val_disease_more_code)
                <?php $list_disease = PopulationController::Show_disease_more_code($select_year,$key_disease_more_code); ?>
                <tr>
                  <td><a href="showbydisease?disease_code={{ $key_disease_more_code }}&year={{ $current_year }}">{{ $val_disease_more_code }}</a></td>
                  <td><?php echo number_format($list_disease['total_case']);?></td>
                  <td><?php echo Controller::cal_ratio_cases($total_all_pop,$list_disease['total_case']);?></td>
                  <td><?php echo number_format($list_disease['total_death']);?></td>
                  <td><?php echo Controller::cal_ratio_deaths($total_all_pop,$list_disease['total_death']);?></td>
                  <td><?php if($list_disease['total_death']>0) { echo Controller::cal_ratio_cases_deaths($list_disease['total_case'],$list_disease['total_death']); }else{ echo "0.00";}?></td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
</div>
</section>
<!-- /.content -->
@stop
@section('script')
<script>
$(document).ready(function () {
	$('#example0,#example1,#example2,#example3,#example4,#example5,#example6,#example7,#example8,#example9,#example10').DataTable({
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
