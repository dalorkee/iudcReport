@extends('layouts.template')
@section('content')
<?php
ini_set('max_execution_time', 300); //300 seconds = 5 minutes
$get_all_disease =\App\Http\Controllers\Controller::list_disease();
//dd($get_all_disease);
?>
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>การส่งออกข้อมูลผู้ป่วย จำแนกรายเดือน<small></small></h1>
<ol class="breadcrumb">
	<li><a href="{{ route('dashboard') }}"><i class="fa fa-home"></i> หนัาหลัก</a></li>
	<li><a href="#">รายงาน</a></li>
	<li><a href="{{ route('export-patient-data.main') }}" class="active">รายงานข้อมูลผู้ป่วย</a></li>
</ol>
</section>
<!-- Main content -->
<section class="content">
	<div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
							<h4>ส่งออกข้อมูลผู้ป่วย</h4>
              <p>จำนวนป่วย/ตาย รายเดือน</p>
            </div>
            <div class="icon">
              <i class="fa fa-pie-chart"></i>
            </div>
            <a href="{{ route('export-patient.sick-death-month') }}" class="small-box-footer">
              ดูรายงานข้อมูล <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h4>ส่งออกข้อมูลผู้ป่วย</h4>
              <p>อัตราป่วย อัตราตาย อัตราป่วยตาย</p>
            </div>
            <div class="icon">
              <i class="fa fa-pie-chart"></i>
            </div>
            <a href="{{ route('export-patient.sick-death-ratio') }}" class="small-box-footer">
              ดูรายงานข้อมูล <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h4>ส่งออกข้อมูลผู้ป่วย</h4>
              <p>จำนวนป่วย รายสัปดาห์</p>
            </div>
            <div class="icon">
              <i class="fa fa-pie-chart"></i>
            </div>
            <a href="{{ route('export-patient.sick-weekly') }}" class="small-box-footer">
              ดูรายงานข้อมูล <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h4>ส่งออกข้อมูลผู้ป่วย</h4>
              <p>จำนวนป่วย ตามอายุ</p>
            </div>
            <div class="icon">
              <i class="fa fa-pie-chart"></i>
            </div>
            <a href="{{ route('export-patient.sick-by-age') }}" class="small-box-footer">
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
									<h4>ส่งออกข้อมูลผู้ป่วย</h4>
		              <p>จำนวนตาย ตามอายุ</p>
		            </div>
		            <div class="icon">
		              <i class="fa fa-pie-chart"></i>
		            </div>
		            <a href="{{ route('export-patient.death-by-age') }}" class="small-box-footer">
		              ดูรายงานข้อมูล <i class="fa fa-arrow-circle-right"></i>
		            </a>
		          </div>
		        </div>
		        <!-- ./col -->
		        <div class="col-lg-3 col-xs-6">
		          <!-- small box -->
		          <div class="small-box bg-maroon">
		            <div class="inner">
		              <h4>ส่งออกข้อมูลผู้ป่วย</h4>
		              <p>จำนวนป่วย/ตาย จำแนกตามสัญชาติ</p>
		            </div>
		            <div class="icon">
		              <i class="fa fa-pie-chart"></i>
		            </div>
		            <a href="{{ route('export-patient.sick-death-by-nation') }}" class="small-box-footer">
		              ดูรายงานข้อมูล <i class="fa fa-arrow-circle-right"></i>
		            </a>
		          </div>
		        </div>

						<div class="col-lg-3 col-xs-6">
		          <!-- small box -->
		          <div class="small-box bg-purple">
		            <div class="inner">
		              <h4>ส่งออกข้อมูลผู้ป่วย</h4>
		              <p>จำนวนป่วย ตามกลุ่มอาชีพ</p>
		            </div>
		            <div class="icon">
		              <i class="fa fa-pie-chart"></i>
		            </div>
		            <a href="{{ route('export-patient.sick-by-occupation') }}" class="small-box-footer">
		              ดูรายงานข้อมูล <i class="fa fa-arrow-circle-right"></i>
		            </a>
		          </div>
		        </div>

						<div class="col-lg-3 col-xs-6">
		          <!-- small box -->
		          <div class="small-box bg-teal">
		            <div class="inner">
		              <h4>ส่งออกข้อมูลผู้ป่วย</h4>
		              <p>จำนวนป่วย ตามเพศ</p>
		            </div>
		            <div class="icon">
		              <i class="fa fa-pie-chart"></i>
		            </div>
		            <a href="{{ route('export-patient.sick-by-sex') }}" class="small-box-footer">
		              ดูรายงานข้อมูล <i class="fa fa-arrow-circle-right"></i>
		            </a>
		          </div>
		        </div>

		      </div>
</section>
<!-- /.content -->
@stop
