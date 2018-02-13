@extends('back.template.master')

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

		$('document').ready(function(){
			$('.ajax-branch').live('change', function(){
				$('.blur-loader').show();
				var selected = $('.ajax-branch option:selected').val();
	            if(selected != '')
	            {
		            $.ajax({
		                type: "GET",
		                url: "{{URL::to(Crypt::decrypt($setting->admin_url) . '/commission-report/ajax-branch')}}/"+selected,
		                success: function(msg){
		                    $('.hasil-ajax-branch').html(msg);
							$('.blur-loader').hide();
		                }
		            });
	            }
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
	{{Form::open(array('url' => URL::to(Crypt::decrypt($setting->admin_url) . '/commission-report/report'), 'method'=>'GET', 'files' => true, 'target'=>'_blank'))}}
		<section id="edit-container">
			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('branch', 'Branch Name')}}
				</div><!--
				--><div class="edit-right">
					{{Form::select('branch', $branch_options, null, array('class'=>'medium-text select ajax-branch', 'required'))}}
					<span class="required-tx">
						*Required
					</span>
				</div>
			</div>

			<div class="edit-group hasil-ajax-branch">
				
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