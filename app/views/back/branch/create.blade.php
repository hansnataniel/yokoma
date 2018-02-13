@extends('back.template.master')

@section('title')
	New Branch
@stop

@section('head_additional')
	
@stop

@section('page_title')
	<a href="{{URL::to(Session::get('last_url'))}}">{{HTML::image('img/admin/back.png', '', array('class'=>'image-img'))}}</a> New Branch
@stop

@section('help')
	<ul style="padding-left: 18px;">
		
	</ul>
@stop

@section('content')
	{{Form::model($branch, array('url' => URL::current(), 'files' => true))}}
		<section id="edit-container">
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
					{{Form::label('address', 'Address')}}
				</div><!--
				--><div class="edit-right">
					{{Form::text('address', null, array('class'=>'medium-text', 'required'))}}
					<span class="required-tx">
						*Required
					</span>
				</div>
			</div>
			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('phone', 'Phone')}}
				</div><!--
				--><div class="edit-right">
					{{Form::text('phone', null, array('class'=>'medium-text'))}}
				</div>
			</div>
			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('email', 'Email')}}
				</div><!--
				--><div class="edit-right">
					{{Form::email('email', null, array('class'=>'medium-text'))}}
					<span class="required-tx">
						*Email Format
					</span>
				</div>
			</div>
			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('no_invoice', 'No Invoice')}}
				</div><!--
				--><div class="edit-right">
					{{Form::text('no_invoice', null, array('class'=>'medium-text', 'required'))}}
					<span class="required-tx">
						*Required
					</span>
				</div>
			</div>
			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('is_active', 'Is Active')}}
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