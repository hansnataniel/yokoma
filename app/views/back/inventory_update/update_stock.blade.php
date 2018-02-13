@extends('back.template.master')

@section('title')
	Add Penyesuaian Stock
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
			
			$('.numeric').inputmask('currency', {
				digitsOptional: true,
				groupSize: 3,
				autoGroup: true,
				prefix: '',
				allowMinus: true,
				placeholder: ''
			});
		});
	</script>
@stop

@section('page_title')
	<a href="{{URL::to(Session::get('last_url'))}}">{{HTML::image('img/admin/back.png', '', array('class'=>'image-img'))}}</a> Add Penyesuaian Stock
@stop

@section('help')
	<ul style="padding-left: 18px;">
		
	</ul>
@stop

@section('content')
	{{Form::model($stockgood, array('url' => URL::current(), 'method'=>'POST', 'files' => true))}}
		<section id="edit-container">
			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('branch_id', 'Branch Name')}}
				</div><!--
				--><div class="edit-right">
					{{Form::select('branch_id', $branch_options, $branch->id, array('class'=>'medium-text select', 'required'))}}
					<span class="required-tx">
						*Required
					</span>
				</div>
			</div>
			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('product_id', 'Product Name')}}
				</div><!--
				--><div class="edit-right">
					{{Form::select('product_id', $product_options, null, array('class'=>'medium-text select', 'required'))}}
					<span class="required-tx">
						*Required
					</span>
				</div>
			</div>
			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('date', 'Date')}}
				</div><!--
				--><div class="edit-right">
					{{Form::text('date', null, array('class'=>'medium-text datetimepicker', 'required', 'readonly'))}}
					<span class="required-tx">
						*Required
					</span>
				</div>
			</div>
			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('type', 'Type')}}
				</div><!--
				--><div class="edit-right">
					{{Form::select('type', array(1 => 'Stock In', 0 => 'Stock Out'), 1, array('class'=>'medium-text'))}}
				</div>
			</div>
			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('amount', 'Amount')}}
				</div><!--
				--><div class="edit-right">
					{{Form::text('amount', null, array('class'=>'small-text numeric'))}}
					<span class="required-tx">
						*Required,  Numeric
					</span>
				</div>
			</div>
			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('note', 'Note')}}
				</div><!--
				--><div class="edit-right">
					{{Form::textarea('note', null, array('class'=>'large-text area'))}}
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