@extends('layouts.templateDemo')
@section('content')
<?php
	use \App\Http\Controllers\PopulationController as PopulationController;
	$select_year = (isset($_GET['year']))? $_GET['year']: date('Y');

	// get group disease name
	$get_group_of_disease =\App\Http\Controllers\Controller::get_group_of_disease($select_year);
	$get_group_of_disease_th =\App\Http\Controllers\Controller::get_group_of_disease_th($select_year);

	// dd($get_group_of_disease_th);
	//count group_of_disease
	$total_group_of_disease = count($get_group_of_disease);
	$current_year =  (isset($_GET['year']))? $_GET['year']: date('Y');
	$current_year_th = $current_year+543;
	//dd($current_year);
?>
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>รายงานประชากรประจำปี {{ $current_year_th }}</h1>
	<ol class="breadcrumb">
		<li><a href="{{ route('dashboard') }}"><i class="fa fa-home"></i> หนัาหลัก</a></li>
		<li class="active">รายงานประชากรประจำปี {{ $current_year_th }}</li>
	</ol>
</section>
<!-- Main content -->
<section class="content">
	<?php
	$max_columns = 3; //columns will arrange to any number (as long as it is evenly divisible by 12)
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

	//dd($json);
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
					<table class="table table-bordered table-hover" id="example<?php echo $i;?>">
						<thead>
							<tr>
								<th>โรค (Code)</th>
								<th>ป่วย</th>
								<th>ตาย</th>
							</tr>
						</thead>
						<tbody>
						<?php $j=1;  foreach ($json[$group_name_of_disease] as $total_by_disease) : ?>
							<tr>
								<td><a href="showbydisease?disease_code={{ $total_by_disease['DISEASE'] }}&year={{ $current_year }}"><?php echo $total_by_disease['DISNAME'];?></a></td>
								<td><?php echo number_format($total_by_disease['total']);?></td>
								<td><?php echo number_format($total_by_disease['totald']);?></td>
							</tr>
							<?php $j++; endforeach; ?>
						</tbody>
					</table>
				<?php if($group_name_of_disease=='vbd') :?>
					<div class="box-footer text-center">
						<div class="no-margin">
							Total D.H.F. = <?php echo number_format($json['vbd']['0']['total']+$json['vbd']['1']['total']+$json['vbd']['3']['total']); ?>
						</div>
					</div>
				<?php endif; ?>
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
</section>
<!-- /.content -->
@stop
