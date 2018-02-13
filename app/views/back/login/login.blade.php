<html>
<head>
	<title>CREIDS | Login Page</title>
	<link rel="shortcut icon" href="{{URL::to('img/admin/favicon.jpg')}}" />

	{{HTML::style('css/back_style.css')}}
	{{HTML::script('js/jquery-1.8.3.min.js')}}

	<script>
		$(document).ready(function(){
			$('#login-footer').css({'margin-top': $('#login-content').height() / 5});
		});
	</script>
</head>
<body>
	<section id="login-container">
		<section id="login-content">
			<header id="login-header">
				{{HTML::image('img/admin/creids_logo.png')}}
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
					{{Form::open(array('url' => URL::current()))}}
					{{Form::email('email', null, array('class' => 'login-text', 'placeholder' => 'Email', 'required', 'autofocus'))}}
					{{Form::password('password', array('class' => 'login-text', 'placeholder' => 'Password', 'required'))}}
					{{Form::submit('Login', array('id' => 'login-submit'))}}
					{{Form::close()}}
					<?php
						$setting = Setting::first();
					?>
				</div>
			</div>
			<footer id="login-footer">
				 © 2016 Backend system version 2.2.1 . Developed by {{HTML::link('http://www.creids.net', 'CREIDS', array('id'=>'login-footer-link'), "target = '_blank'")}}
			</footer>
		</section>
	</section>
</body>
</html>