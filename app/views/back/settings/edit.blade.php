@extends('back.template.master')

@section('title')
	Setting Edit
@stop

@section('head_additional')
	
@stop

@section('page_title')
	<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/dashboard')}}">{{HTML::image('img/admin/back.png', '', array('class'=>'image-img'))}}</a> Setting Edit
@stop

@section('help')
	<ul style="padding-left: 18px;">
		<li>Session Lifetime adalah waktu yang digunakan untuk Sign Out secara otomatis jika halaman back end yang Anda buka tidak ada kegiatan sama sekali dalam waktu yang sudah ditentukan.</li>
		<li>Admin URL adalah URL yang digunakan untuk masuk ke halaman Back End.</li>
		<li>Pastikan anda tidak lupa dengan Admin URL Anda.</li>
		<li>Contact Email akan ditampilkan di halaman Contact Us front end</li>
		<li>Receiver Email akan digunakan untuk menerima email dari user lewat sistem di front end</li>
		<li>Sender Email akan digunakan sebagai alamat email pengirim yang di kirim melalui sistem</li>
		<!--
		<li>Anda dapat merubah detail Setting pada halaman ini.</li>
		<li>Maintenance digunakan ketika anda ingin menon-aktifkan halaman front end untuk sementara waktu.</li>
		<li>Field dengan tanda <i>*required</i> itu menandakan kalau field tersebut wajib di isi</li>
		<li>Field dengan tanda <i>*must be number</i> itu menandakan kalau field tersebut wajib di isi dengan angka</li>
		<li>Field dengan tanda <i>*email format</i> itu menandakan kalau field tersebut wajib di isi dengan format email</li>
		-->
	</ul>
@stop

@section('content')
	{{Form::model($setting, array('url' => URL::current(), 'method' => 'PUT', 'files' => true))}}
		<section id="edit-container">
			<div class="setting-group" style="border: 1px solid #d2d2d2; margin-bottom: 30px;">
				<span class="setting-title" style="display: block; margin-bottom: 10px; font-size: 20px; padding: 20px; border-bottom: 1px solid #d2d2d2;">
					Session
				</span>
				<div class="setting-group" style="padding: 20px;">
					<div class="edit-group">
						<div class="edit-left">
							{{Form::label('session_lifetime', 'Session Lifetime')}}
						</div><!--
						--><div class="edit-right">
							{{Form::text('session_lifetime', null, array('class'=>'large-text', 'required'))}}
							<span class="required-tx">
								*Required and Must be Number
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="setting-group" style="border: 1px solid #d2d2d2; margin-bottom: 30px;">
				<span class="setting-title" style="display: block; margin-bottom: 10px; font-size: 20px; padding: 20px; border-bottom: 1px solid #d2d2d2;">
					Admin Management
				</span>
				<div class="setting-group" style="padding: 20px;">
					<div class="edit-group">
						<div class="edit-left">
							{{Form::label('admin_url', 'Admin URL')}}
						</div><!--
						--><div class="edit-right">
							{{Form::text('admin_url', Crypt::decrypt($setting->admin_url), array('class'=>'large-text', 'required'))}}
							<span class="required-tx">
								*Required
							</span>
						</div>
					</div>
					<div class="edit-group">
						<div class="edit-left">
							{{Form::label('maintenance', 'Maintenance')}}
						</div><!--
						--><div class="edit-right">
							{{Form::radio('maintenance', 0, 0, array('class'=>'quiz-radio', 'id'=>'false'))}} {{Form::label('false', 'Not Maintenance', array('class'=>'question-label'))}}
							{{Form::radio('maintenance', 1, 1, array('class'=>'quiz-radio', 'id'=>'true'))}} {{Form::label('true', 'Maintenance Mode', array('class'=>'question-label'))}}<br>
						</div>
					</div>
				</div>
			</div>
			<div class="setting-group" style="border: 1px solid #d2d2d2; margin-bottom: 30px;">
				<span class="setting-title" style="display: block; margin-bottom: 10px; font-size: 20px; padding: 20px; border-bottom: 1px solid #d2d2d2;">
					Name
				</span>
				<div class="setting-group" style="padding: 20px;">
					
					<div class="edit-group">
						<div class="edit-left">
							{{Form::label('name', 'Name Company')}}
						</div><!--
						--><div class="edit-right">
							{{Form::text('name', null, array('class'=>'large-text'))}}
						</div>
					</div>
				</div>
			</div>
			<div class="edit-group">
				<div class="edit-left">
				</div><!--
				--><div class="edit-right" style="margin-left: 25px;">
					{{Form::submit('Save', array('class'=>'edit-submit margin'))}}
					{{Form::button('Reset', array('class'=>'edit-submit', 'id'=>'reset'))}}
					<section id="blur">
						<div id="blur-question">
							<span id="blur-text">Do you really want to reset this form?</span>
							{{Form::reset('Yes', array('class'=>'blur-submit blur-left'))}}
							{{Form::button('Cancel', array('class'=>'blur-submit cancel'))}}
						</div>
					</section>
				</div>
			</div>	
		</section>
	{{Form::close()}}
@stop