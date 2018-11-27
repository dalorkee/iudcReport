@php $pj = 'PJ'; @endphp
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>DUCD</title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	@include('layouts.incStylesheet')
	@yield('style')
	@yield('incHeaderScript')
</head>
<body class="hold-transition skin-purple sidebar-mini">
<!-- Site wrapper -->
<div class="iudc-header hidden-xs hidden-sm">
	<figure>
		{{ Html::image('public/images/urban-logo86.png', 'alt=logo', array('class'=>'moph-logo')) }}
	</figure>
	<div class="iudc-title">
		<h2>ฐานข้อมูลโรคติดต่อเขตเมือง</h2>
		<p>Database of Urban Communicable Diseases</p>
	</div>
</div>

<div class="wrapper">
	@include('layouts.incHeader')
	<!-- =============================================== -->
	<!-- Left side column. contains the sidebar -->
	<aside class="main-sidebar">
		@include('layouts.incSidebar')
	</aside>
	<!-- =============================================== -->
	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		@include('flash::message')
		<!-- animation loader -->
		<div class="loader"></div>
		@yield('content')
	</div>
	<!-- /.content-wrapper -->
	@include('layouts.incFooter')
	<!-- Control Sidebar -->
	<!--<aside class="control-sidebar control-sidebar-dark">
		 include('layouts.incControlSidebar')
	</aside>-->
	<!-- /.control-sidebar -->
	<!-- Add the sidebar's background. This div must be placed
		immediately after the control sidebar -->
	<div class="control-sidebar-bg"></div>
	<a href="" id="scroll-to-top"></a>
</div>
<!-- ./wrapper -->
@include('layouts.incFooterScript')
@yield('CustomJSScript')
@yield('script')
</body>
</html>
