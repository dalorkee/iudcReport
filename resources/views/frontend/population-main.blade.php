@extends('layouts.template')
@section('content')
<?php
ini_set('max_execution_time', 300); //300 seconds = 5 minutes
$get_all_disease =\App\Http\Controllers\Controller::list_disease();
//dd($get_all_disease);
?>
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>การส่งออกข้อมูลจำนวนประชากร<small></small></h1>
<ol class="breadcrumb">
	<li><a href="{{ route('dashboard') }}"><i class="fa fa-home"></i> หนัาหลัก</a></li>
	<li><a href="#">ส่งออกข้อมูล</a></li>
	<li><a href="{{ route('export-population.main') }}" class="active">ประชากร</a></li>
</ol>
</section>
<!-- Main content -->
<section class="content">
	<div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
							<h4>ส่งออกข้อมูล</h4>
              <p>จำนวนประชากรจำแนกตามเพศ รายภาค</p>
            </div>
            <div class="icon">
              <i class="fa fa-pie-chart"></i>
            </div>
            <a href="{{ route('export-population.sector') }}" class="small-box-footer">
              ดูรายงานข้อมูล <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h4>ส่งออกข้อมูล</h4>

              <p>จำนวนประชากรจำแนกตามเพศ รายเขต</p>
            </div>
            <div class="icon">
              <i class="fa fa-pie-chart"></i>
            </div>
            <a href="{{ route('export-population.area') }}" class="small-box-footer">
              ดูรายงานข้อมูล <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h4>ส่งออกข้อมูล</h4>

              <p>จำนวนประชากรจำแนกตามเพศ รายจังหวัด</p>
            </div>
            <div class="icon">
              <i class="fa fa-pie-chart"></i>
            </div>
            <a href="{{ route('export-population.province') }}" class="small-box-footer">
              ดูรายงานข้อมูล <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h4>ส่งออกข้อมูล</h4>

              <p>จำนวนประชากรจำแนกตามเพศ รายพื้นที่ (เทศบาล)</p>
            </div>
            <div class="icon">
              <i class="fa fa-pie-chart"></i>
            </div>
            <a href="{{ route('export-population.municipality') }}" class="small-box-footer">
              ดูรายงานข้อมูล <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
      </div>
			<div class="row">
		        <div class="col-lg-3 col-xs-6">
		          <!-- small box -->
		          <div class="small-box bg-gray">
		            <div class="inner">
									<h4>ส่งออกข้อมูล</h4>
		              <p>จำนวนประชากรจำแนกตามอายุและเพศ รายจังหวัด</p>
		            </div>
		            <div class="icon">
		              <i class="ion ion-pie-graph"></i>
		            </div>
		            <a href="{{ route('export-population.sex-age-province') }}" class="small-box-footer">
		              ดูรายงานข้อมูล <i class="fa fa-arrow-circle-right"></i>
		            </a>
		          </div>
		        </div>
		        <!-- ./col -->
		        <div class="col-lg-3 col-xs-6">
		          <!-- small box -->
		          <div class="small-box bg-maroon">
		            <div class="inner">
		              <h4>ส่งออกข้อมูล</h4>

		              <p>จำนวนประชากรจำแนกตามอายุและเพศ รายพื้นที่ (เทศบาล)</p>
		            </div>
		            <div class="icon">
		              <i class="ion ion-pie-graph"></i>
		            </div>
		            <a href="{{ route('export-population.sex-age-municipality') }}" class="small-box-footer">
		              ดูรายงานข้อมูล <i class="fa fa-arrow-circle-right"></i>
		            </a>
		          </div>
		        </div>

		      </div>
</section>
<!-- /.content -->
@stop
