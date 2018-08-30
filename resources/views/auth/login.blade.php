<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<title>DUCD::Login</title>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">
	{{ Html::style(('public/components/Login/vendor/bootstrap/css/bootstrap.min.css')) }}
	{{ Html::style(('public/components/Login/fonts/font-awesome-4.7.0/css/font-awesome.min.css')) }}
	{{ Html::style(('public/components/Login/fonts/iconic/css/material-design-iconic-font.min.css')) }}
	{{ Html::style(('public/components/Login/vendor/animate/animate.css')) }}
	{{ Html::style(('public/components/Login/vendor/css-hamburgers/hamburgers.min.css')) }}
	{{ Html::style(('public/components/Login/vendor/animsition/css/animsition.min.css')) }}
	{{ Html::style(('public/components/Login/vendor/select2/select2.min.css')) }}
	{{ Html::style(('public/components/Login/vendor/daterangepicker/daterangepicker.css')) }}
	{{ Html::style(('public/components/Login/css/util.css')) }}
	{{ Html::style(('public/components/Login/css/main.css')) }}
</head>
<body>
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100 p-t-85 p-b-20">
				<form method="POST" action="{{ route('login') }}" class="login100-form validate-form">
					{{ csrf_field() }}
					<span class="login100-form-avatar">
						{{ Html::image('public/images/urban-logo.png', 'alt=AVATAR') }}
					</span>
					<span class="login100-form-title p-b-70">Database of urban diseases</span>
					<div class="wrap-input100 validate-input m-t-85 m-b-35" data-validate = "Enter username">
						<input type="text" name="username" id="username" class="input100">
						<span class="focus-input100" data-placeholder="Username"></span>
					</div>
					<div class="wrap-input100 validate-input m-b-50" data-validate="Enter password">
						<input type="password" name="password" id="password" class="input100">
						<span class="focus-input100" data-placeholder="Password"></span>
					</div>
					<div class="container-login100-form-btn">
						<button type="submit" class="login100-form-btn">Login</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div id="dropDownSelect1"></div>
	{{ Html::script(('public/components/Login/vendor/jquery/jquery-3.2.1.min.js')) }}
	{{ Html::script(('public/components/Login/vendor/animsition/js/animsition.min.js')) }}
	{{ Html::script(('public/components/Login/vendor/bootstrap/js/popper.js')) }}
	{{ Html::script(('public/components/Login/vendor/bootstrap/js/bootstrap.min.js')) }}
	{{ Html::script(('public/components/Login/vendor/select2/select2.min.js')) }}
	{{ Html::script(('public/components/Login/vendor/daterangepicker/moment.min.js')) }}
	{{ Html::script(('public/components/Login/vendor/daterangepicker/daterangepicker.js')) }}
	{{ Html::script(('public/components/Login/vendor/countdowntime/countdowntime.js')) }}
	{{ Html::script(('public/components/Login/js/main.js')) }}
</body>
</html>
