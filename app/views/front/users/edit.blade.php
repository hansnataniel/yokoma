@extends('back.template.master')

@section('title')
	User Edit
@stop

@section('head_additional')
	
@stop

@section('page_title')
	<a href="{{URL::to(Session::get('last_url'))}}">{{HTML::image('img/admin/back.png', '', array('class'=>'image-img'))}}</a> User Edit
@stop

@section('help')
	<ul style="padding-left: 18px;">
		<li>Anda dapat merubah detail user pada halaman ini.</li>
		<li>Field dengan tanda <i>*required</i> itu menandakan kalau field tersebut wajib di isi</li>
		<li>Field dengan tanda <i>*must be numeric</i> itu menandakan kalau field tersebut wajib di isi dengan angka</li>
		<li>Field dengan tanda <i>*unique</i> itu menandakan kalau field tersebut tidak boleh berisi data yang sama dengan data sebelumnya</li>
		<li>Field dengan tanda <i>*email format</i> itu menandakan kalau field tersebut wajib di isi dengan format email</li>
		<li>Field dengan tanda <i>*must be exactly the same with new password field</i> itu menandakan kalau field tersebut wajib di isi sama persis dengan field new password</li>
		<li>Field dengan tanda <i>*min 6 character</i> itu menandakan kalau field tersebut wajib di isi lebih dari 6 karakter.</li>
		<li>Field dengan tanda <i>*Stay null if you not have change the password</i> itu menandakan kalau field tersebut boleh dikosongkan jika Anda tidak ingin merubah password yang lama.</li>
		<li>Field dengan tanda <i>*Required if field new password not null</i> itu menandakan kalau field tersebut wajib di isi jika field New Password tidak kosong.</li>
		<li>Pilih Active pada Active Status jika user yang anda buat mempunyai status aktif, dan sebaliknya.</li>
	</ul>
@stop

@section('content')
	{{Form::model($user, array('url' => URL::current(), 'method' => 'PUT', 'files' => true))}}
		<section id="edit-container">
			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('branch', 'Branch Name')}}
				</div><!--
				--><div class="edit-right">
					{{Form::select('branch', $branch_options, $user->branch_id, array('class'=>'medium-text select', 'required'))}}
					<span class="required-tx">
						*Required
					</span>
				</div>
			</div>
			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('name', 'Name')}}
				</div><!--
				--><div class="edit-right">
					{{Form::text('name', null, array('class'=>'medium-text', 'required'))}}
					<span class="required-tx">
						*Required
					</span>
				</div>
			</div>
			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('email', 'Email')}}
				</div><!--
				--><div class="edit-right">
					{{Form::email('email', null, array('class'=>'medium-text', 'required'))}}
					<span class="required-tx">
						*Required, Unique, and Email Format
					</span>
				</div>
			</div>
			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('new_password', 'New Password')}}
				</div><!--
				--><div class="edit-right">
					{{Form::password('new_password', array('class'=>'medium-text'))}}
					<span class="required-tx">
						*Stay null if you not have change the password and min 6 character
					</span>
				</div>
			</div>
			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('new_password_confirmation', 'Password Confirmation')}}
				</div><!--
				--><div class="edit-right">
					{{Form::password('new_password_confirmation', array('class'=>'medium-text'))}}
					<span class="required-tx">
						*Required if field new password not null and must be exactly the same with new password field
					</span>
				</div>
			</div>
			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('is_active', 'Active')}}
				</div><!--
				--><div class="edit-right">
					{{Form::radio('is_active', 1, true, array('class'=>'quiz-radio', 'id'=>'true'))}} {{Form::label('true', 'Active', array('class'=>'question-label'))}}<br>
					{{Form::radio('is_active', 0, false, array('class'=>'quiz-radio', 'id'=>'false'))}} {{Form::label('false', 'Not Active', array('class'=>'question-label'))}}
				</div>
			</div>
			<div class="edit-group">
				<div class="edit-left">
				</div><!--
				--><div class="edit-right">
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