@extends('front.template.master')

@section('title')
	Customer Edit
@stop

@section('head_additional')
	
@stop

@section('page_title')
	<a href="{{URL::to(Session::get('last_url'))}}">{{HTML::image('img/admin/back.png', '', array('class'=>'image-img'))}}</a> Customer Edit
@stop

@section('help')
	<ul style="padding-left: 18px;">
		
	</ul>
@stop

@section('content')
	{{Form::model($customer, array('url' => URL::current(), 'method' => 'PUT', 'files' => true))}}
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
					{{Form::label('no_telp', 'No. Telphone')}}
				</div><!--
				--><div class="edit-right">
					{{Form::text('no_telp', null, array('class'=>'medium-text'))}}
				</div>
			</div>
			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('cp_name', 'CP Name ')}}
				</div><!--
				--><div class="edit-right">
					{{Form::text('cp_name', null, array('class'=>'medium-text'))}}
				</div>
			</div>
			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('cp_no_hp', 'CP No. HP ')}}
				</div><!--
				--><div class="edit-right">
					{{Form::text('cp_no_hp', null, array('class'=>'medium-text'))}}
				</div>
			</div>
			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('salesman1', 'Salesman 1 ')}}
				</div><!--
				--><div class="edit-right">
					<div style="display: inline-block; vertical-align: top;">
						{{Form::select('salesman1', $salesman1_options, $customer->salesman_id1, array('class'=>'medium-text select'))}}
					</div>
					{{Form::text('commission1', null, array('class'=>'small-text-prepend', 'placeholder'=>'Commision'))}}<!--
				 --><span class="image-prepend">
						<div>
							%
						</div>
					</span>
				</div>
			</div>
			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('salesman2', 'Salesman 2')}}
				</div><!--
				--><div class="edit-right">
					<div style="display: inline-block; vertical-align: top;">
						{{Form::select('salesman2', $salesman2_options, $customer->salesman_id2, array('class'=>'medium-text select'))}}
					</div>
					{{Form::input('number', 'commission2', null, array('class'=>'small-text-prepend', 'placeholder'=>'Commision'))}}<!--
				 --><span class="image-prepend" style="vertical-align: middle;">
						<div>
							%
						</div>
					</span>
					@if($customer->from_net == 1)
						{{Form::checkbox('from_net', 'true', true, array('id'=>'from_net', "style" => "vertical-align: middle;display: inline-block;"))}}
					@else
						{{Form::checkbox('from_net', 'true', false, array('id'=>'from_net', "style" => "vertical-align: middle;display: inline-block;"))}}
					@endif
					{{Form::label('from_net', 'From Net', array('id'=>'from_net'))}}
				</div>
			</div>
			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('due_date', 'Debt Maturity')}}
				</div><!--
				--><div class="edit-right">
					{{Form::input('number', 'due_date', null, array('class'=>'medium-text-prepend', 'required', 'min'=>1))}}<!--
				 --><span class="image-prepend">
						<div>
							Days
						</div>
					</span>
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