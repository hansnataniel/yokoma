@extends('back.template.master')

@section('title')
	New Image for {{$example->name}}
@stop

@section('head_additional')

@stop

@section('page_title')
	@if(Session::has('last_url'))
		<a href="{{URL::to(Session::get('last_url'))}}">
	@else
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/example/image/' . $example->id)}}">
	@endif
		{{HTML::image('img/admin/back.png', '', array('class'=>'image-img'))}}
	</a> 
	New Image for {{$example->name}}
@stop

@section('help')
	<ul style="padding-left: 18px;">
		<li><!-- Help Goes Here --></li>
		<li><!-- Help Goes Here --></li>
	</ul>
@stop

@section('content')
	{{Form::model($exampleimage, array('url' => URL::current(), 'files' => true))}}
		<section id="edit-container">
			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('name', 'Name')}}
				</div><!--
				--><div class="edit-right">
					{{Form::text('name', null, array('class'=>'large-text', 'required'))}}
					<span class="required-tx">
						*Required
					</span>
				</div>
			</div>

			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('image', 'Image')}}
				</div><!--
				--><div class="edit-right">
					{{Form::file('image', array('class'=>'medium-text image-field'))}}
					<span class="required-tx">
						*Required
					</span>
				</div>
			</div>

			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('is_active', 'Active')}}
				</div><!--
				--><div class="edit-right">
					{{Form::radio('is_active', 1, 1, array('class'=>'quiz-radio', 'id'=>'true'))}} {{Form::label('true', 'Active', array('class'=>'question-label'))}}<br>
					{{Form::radio('is_active', 0, 0, array('class'=>'quiz-radio', 'id'=>'false'))}} {{Form::label('false', 'Not Active', array('class'=>'question-label'))}}
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