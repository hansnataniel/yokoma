@extends('front.template.master')

@section('title')
	Laporan Komisi Sales
@stop

@section('head_additional')
	{{HTML::style('css/jquery.datetimepicker.css')}}
	{{HTML::script('js/jquery.datetimepicker.js')}}

	<script>
		$(function(){
			$('.datetimepicker').datetimepicker({
				scrollMonth: false,
				timepicker: false,
				maxDate: 'now',
				format: 'Y-m-d'
			});
		});
	</script>
@stop

@section('page_title')
	Laporan Komisi Sales
@stop

@section('help')
	<ul style="padding-left: 18px;">
		<li></li>
	</ul>
@stop

@section('content')
	{{Form::open(array('url' => URL::to('commission-report/report'), 'method'=>'GET', 'files' => true, 'target'=>'_blank', 'id'=>'form-validation'))}}
		<section id="edit-container">
			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('salesman', 'Salesman Name')}}
				</div><!--
				--><div class="edit-right">
					{{Form::select('salesman', $salesman_options, null, array('class'=>'medium-text select'))}}
					<span class="required-tx">
						*Required
					</span>
				</div>
			</div>

			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('start_date', 'Start Date')}}
				</div><!--
				--><div class="edit-right">
					{{Form::text('start_date', null, array('class'=>'medium-text datetimepicker', 'required', 'readonly'))}}
					<span class="required-tx">
						*Required
					</span>
				</div>
			</div>

			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('end_date', 'End Date')}}
				</div><!--
				--><div class="edit-right">
					{{Form::text('end_date', null, array('class'=>'medium-text datetimepicker', 'required', 'readonly'))}}
					<span class="required-tx">
						*Required
					</span>
				</div>
			</div>

			<div class="edit-group">
				<div class="edit-left">
				</div><!--
				--><div class="edit-right">
					{{Form::submit('Create Report', array('class'=>'edit-submit margin'))}}
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