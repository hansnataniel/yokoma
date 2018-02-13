<html>
<head>
	<title>CREIDS | Login Page</title>
	<link rel="shortcut icon" href="{{URL::to('img/admin/favicon.jpg')}}" />

	{{HTML::style('css/front-style.css')}}
	{{HTML::script('js/jquery-1.8.3.min.js')}}

	<style type="text/css">
		#login-header img {
	        width: 140px;
		    margin-top: -50px;
		}
	</style>

	<script>
		$(document).ready(function(){
			$('#login-footer').css({'margin-top': $('#login-content').height() / 5});
		});
	</script>
</head>
<body>
	<?php
		$setting = Setting::first();
	?>
	<section id="login-container">
		<section id="login-content">
			<header id="login-header">
				{{HTML::image('img/budijaya-logo.png')}}
			</header>
			<div id="login-ctn">
				<div id="login-message">
					@foreach ($errors->all("<div class='login-error-message'>:message</div>") as $eror)
						{{$error}}
					@endforeach
					@if (Session::has('message'))
						<div class='login-error-message'>{{Session::get('message')}}</div>
					@endif
					@if (Session::has('success'))
						<div class='login-error-message' style="background: transparent; padding: 0px; font-size: 14px;">{{Session::get('success')}}</div>
					@endif
				</div>
				<div id="login-input">
					{{Form::open(array('url' => URL::to('login'), 'method'=>'POST', 'files'=>true))}}
						{{Form::email('email', null, array('class' => 'login-text', 'placeholder' => 'Email', 'required', 'autofocus'))}}
						{{Form::password('password', array('class' => 'login-text', 'placeholder' => 'Password', 'required'))}}
						{{Form::submit('Login', array('id' => 'login-submit'))}}
					{{Form::close()}}
					
				</div>
			</div>
			<footer id="login-footer">
				 © 2017 Budi Jaya system. Developed by {{HTML::link('http://www.creids.net', 'CREIDS', array('id'=>'login-footer-link'), "target = '_blank'")}}
			</footer>
		</section>
	</section>
</body>
</html>