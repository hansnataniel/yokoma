<html>
	<head>
		<meta name = "description" content = "@yield('metadescription')">

        <link rel="shortcut icon" href="{{URL::to('img/admin/favicon.jpg')}}" />

		<title>{{$setting->name}} | @yield('title')</title>
		{{HTML::style('css/general.css')}}
		
		{{HTML::style('css/front-style.css')}}
		
		{{HTML::style('css/select2.css')}}
		
		{{HTML::script('js/jquery-1.8.3.min.js')}}

		{{HTML::script('js/jquery.easing.1.3.js')}}

		{{HTML::script('js/ckeditor/ckeditor.js')}}

		{{HTML::script('js/select2.js')}}
		
		{{HTML::script('js/inputmask.js')}}

		{{HTML::script('js/inputmask.numeric.extensions.js')}}
		
		{{HTML::script('js/jquery.inputmask.js')}}

		{{HTML::script('js/jquery.validate.js')}}
		

		<script>
			$(document).ready(function(){
				$('.nav').click(function(){
					$('.nav-cover').css({'left': 0});
					$('.menu-show').removeClass('blue-active');
					$(this).addClass('blue-active');
					$('.nav').stop().animate({'left': -50}, 400, "easeInOutCubic");
					$('.search').stop().delay(50).animate({'left': -50}, 400, "easeInOutCubic");
					$('.help').stop().delay(100).animate({'left': -50}, 400, "easeInOutCubic");
					$('#nav-container').stop().animate({'right': 0}, 450, "easeInOutCubic");
					$('#navigation').delay(500).fadeIn();
					$('#searching').animate({'padding-left': 10, 'opacity': 0}, 200).delay(50).fadeOut();
					$('#helper').animate({'padding-left': 10, 'opacity': 0}, 200).delay(50).fadeOut();
					$('.nav-title').stop().delay(350).animate({'padding-left': 30, 'opacity': 1});
					$('.nav-sub').stop().delay(500).animate({'padding-left': 30, 'opacity': 1});
					$('.nav-link').stop().delay(500).animate({'padding-left': 30, 'opacity': 1});
				});
				$('.search').click(function(){
					$('.nav-cover').css({'left': 0});
					$('.menu-show').removeClass('blue-active');
					$(this).addClass('blue-active');
					$('.nav').stop().delay(50).animate({'left': -50}, 400, "easeInOutCubic");
					$('.search').stop().animate({'left': -50}, 400, "easeInOutCubic");
					$('.help').stop().delay(100).animate({'left': -50}, 400, "easeInOutCubic");
					$('#nav-container').stop().animate({'right': 0}, 450, "easeInOutCubic");
					$('.nav-title').stop().animate({'padding-left': 10, 'opacity': 0}, 200);
					$('.nav-sub').stop().delay(150).animate({'padding-left': 10, 'opacity': 0}, 200);
					$('.nav-link').stop().delay(150).animate({'padding-left': 10, 'opacity': 0}, 200);
					$('#navigation').delay(50).fadeOut();
					$('#searching').delay(100).fadeIn().animate({'padding-left': 30, 'opacity': 1});
					$('#helper').animate({'padding-left': 10, 'opacity': 0}, 300).delay(50).fadeOut();
					$('.search-input > .chosen-container').css({'width': '240px'});
				});
				$('.help').click(function(){
					$('.nav-cover').css({'left': 0});
					$('.menu-show').removeClass('blue-active');
					$(this).addClass('blue-active');
					$('.nav').stop().delay(100).animate({'left': -50}, 400, "easeInOutCubic");
					$('.search').stop().delay(50).animate({'left': -50}, 400, "easeInOutCubic");
					$('.help').stop().animate({'left': -50}, 400, "easeInOutCubic");
					$('#nav-container').stop().animate({'right': 0}, 450, "easeInOutCubic");
					$('.nav-title').stop().animate({'padding-left': 10, 'opacity': 0}, 200);
					$('.nav-sub').stop().delay(200).animate({'padding-left': 10, 'opacity': 0}, 200);
					$('.nav-link').stop().delay(200).animate({'padding-left': 10, 'opacity': 0}, 200);
					$('#navigation').delay(50).fadeOut();
					$('#searching').animate({'padding-left': 10, 'opacity': 0}, 300).delay(50).fadeOut();
					$('#helper').delay(100).fadeIn().animate({'padding-left': 30, 'opacity': 1});
				});
				$('#nav-image').click(function(){
					$('.nav-title').stop().animate({'padding-left': 10, 'opacity': 0});
					$('.nav-sub').stop().delay(150).animate({'padding-left': 10, 'opacity': 0});
					$('.nav-link').stop().delay(150).animate({'padding-left': 10, 'opacity': 0});
					$('#navigation').delay(100).fadeOut();
					$('#searching').animate({'padding-left': 10, 'opacity': 0}).delay(300).fadeOut();
					$('#helper').animate({'padding-left': 10, 'opacity': 0}).delay(300).fadeOut();
					$('#nav-container').stop().animate({'right': -250}, 450, "easeInOutCubic");
					$('.nav').stop().delay(100).animate({'left': 0}, 400, "easeInOutCubic");
					$('.search').stop().delay(150).animate({'left': 0}, 400, "easeInOutCubic");
					$('.help').stop().delay(200).animate({'left': 0}, 400, "easeInOutCubic");
					$('.nav-cover').delay(200).animate({'left': 50});
					$('.menu-show').removeClass('blue-active');
				});

				$('.nav-cover').css({'height': $('#nav-container').height() - 100});
				$('#content-container').css({'width': $(document).width() - 110});
				$('.index-icon').live('click', function(){
					$('.icon-master').removeClass('actived');
					$(this).find('.index-sub-icon').stop().slideDown('100', "easeInOutCubic");
					$(this).find('.icon-master').addClass('actived');
				});
				$('.index-icon').live('mouseleave', function(){
					$('.icon-master').removeClass('actived');
					$(this).find('.index-sub-icon').stop().slideUp('100', "easeInOutCubic");
				});

				$('#reset').click(function(){
					$('#blur').fadeIn();
					$('#blur-question').stop().delay(500).animate({'opacity': 1, 'top': ($('#blur').height() - $('#blur-question').height()) / 2});
				});

				$('.delete').click(function(){
					$(this).parent().find('.blur').fadeIn();
					$(this).parent().find('.blur-question').stop().delay(500).animate({'opacity': 1, 'top': (($('.height-document').height() - $(this).parent().find('.blur-question').height()) / 2) - 40});
				});
				$('.blur-submit').click(function(){
					$('#blur-question').stop().animate({'opacity': 0, 'top': 0});
					$('#blur').delay(500).fadeOut();

					$('.blur-question').stop().animate({'opacity': 0, 'top': 0});
					$('.blur').delay(500).fadeOut();
				});
				$('.right').css({'width': $('.product-tr').width() - $('.left').width()});
				$('.error-close-class').click(function(){
					$(this).parent().fadeOut();
				});

				$('#message-fixed').delay(3000).fadeOut(700);

				$('.select').each(function(){
					var data = $(this).attr('placeholder-data');

					$(this).select2({
						placeholder: data
					});
				});

				$(window).resize(function(){
					$('#blur-question').css({'top': ($('#blur').height() - $('#blur-question').height()) / 2});
					$('.nav-cover').css({'height': $('#nav-container').height() - 100});
					$('#content-container').css({'width': $(document).width() - 110});
				});
			});
		</script>

		@if (isset($scripts))
	        @foreach ($scripts as $key => $script)
	            {{HTML::script($script)}}
	        @endforeach
	    @endif
	    
	    @if (isset($styles))
	        @foreach ($styles as $key => $style)
	            {{HTML::style($style)}}
	        @endforeach
	    @endif

		@yield('head_additional')
	</head>
	<body>
		<?php
			$setting = Setting::first();
		?>
		<div class="height-document" style="position: fixed; height: 100%; width: 0px; opacity: 0; top: 0px; left: 0px;"></div>
		<section id="wrapper">
			<nav id="nav-container">
				<nav id="nav-content">
					@include('front.template.nav')
				</nav>
			</nav>
			<section id="content-container">
				<header id="page-header">
					<?php $branch = Branch::find(Auth::user()->get()->branch_id); ?>
					<div id="greetings">Hello <a href="{{URL::to('user/edit-profile')}}"><strong>{{Auth::user()->get()->name}}</strong></a> Welcome to {{$setting->name}} - {{$branch->name}} system  |  <span id="sign-out">{{HTML::link(URL::to('logout'), 'Sign Out')}}</span></div>
					<div id="page-title">@yield('page_title')</div>
				</header>
				<article id="page-content">
					<div id="message-fixed">
						@if (Session::has('success-message'))
							<div class='success-message'>
								{{Session::get('success-message')}}
							</div>
						@endif
						@if (Session::has('warning-message'))
							<div class='warning-message'>
								{{Session::get('warning-message')}}
							</div>
						@endif
						@if (Session::has('error-message'))
							<div class='error-message'>
								{{Session::get('error-message')}}
							</div>
						@endif
					</div>
					<div id="message">
						@foreach ($errors->all("<div class='validation-message'>:message<div class='error-close-class'></div></div>") as $error)
							{{$error}}
						@endforeach
					</div>
					@yield('content')
					<div class="blur-loader">
						<div class="blur-loader-content">
							Loading...
						</div>
					</div>
				</article>
				<footer id="page-footer">
					Design and development by {{HTML::link('http://www.creids.net', 'CREIDS', array('id'=>'footer-link', 'target'=>'_blank'))}}
				</footer>
			</section>
		</section>
	</body>
</html>