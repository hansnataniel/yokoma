@extends('back.template.master')

@section('title')
	Laporan Kartu Stock
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
	Laporan Kartu Stock
@stop

@section('help')
	<ul style="padding-left: 18px;">
		<li></li>
	</ul>
@stop

@section('content')
	{{Form::open(array('url' => URL::to(Crypt::decrypt($setting->admin_url) . '/stock-cart/report'), 'method'=>'GET', 'files' => true, 'target'=>'_blank'))}}
		<section id="edit-container">
			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('branch', 'Branch Name')}}
				</div><!--
				--><div class="edit-right">
					{{Form::select('branch', $branch_options, null, array('class'=>'medium-text select', 'required'))}}
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
					{{Form::label('product', 'Product')}}
				</div><!--
				--><div class="edit-right">
					<div style="margin-bottom: 7px;">
						{{Form::radio('type', 'all', true, ['class'=>'quiz-radio', 'id'=>'all'])}}
						{{Form::label('all', 'All')}}
					</div>
					<div style="margin-bottom: 7px;">
						{{Form::radio('type', 'specific', false, ['class'=>'quiz-radio', 'id'=>'specific'])}}
						{{Form::label('specific', 'Specific Product', ["style"=>"width: 120px;display: inline-block;"])}}
						{{Form::select('product', $product_options, null, array('class'=>'medium-text select'))}}
					</div>
					<div style="margin-bottom: 7px;">
						{{Form::radio('type', 'search', false, ['class'=>'quiz-radio', 'id'=>'search'])}}
						{{Form::label('search', 'Search by Name', ["style"=>"width: 120px;display: inline-block;"])}}
						{{Form::text('product_name', null, array('class'=>'medium-text'))}}
					</div>
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