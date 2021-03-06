@extends('back.template.master')

@section('title')
	Example Edit
@stop

@section('head_additional')
	{{HTML::style('css/jquery.datetimepicker.css')}}
	{{HTML::script('js/jquery.datetimepicker.js')}}
	
	<script>
		$(function(){
		    $('.datetimepicker').datetimepicker({
				timepicker: false,
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

			$('.delete-image').click(function(){
		    	var value = $(this).attr('value');
		    	$('.ajax-delete-image').fadeIn();
		    	$.ajax({
		    		type: 'GET',
		    		url: "{{URL::to(Crypt::decrypt($setting->admin_url) . '/example/delete-image')}}/"+value,
		    		success: function(msg){
		    			$('.message-photo').fadeIn().delay(1000).fadeOut(1000);
				    	$('.ajax-delete-image').fadeOut();
		    			$('.ajax-image').delay(2000).animate({'width': '0px', 'opacity': '0'}, 500, 'easeInExpo').fadeOut();
		    		}
		    	});
		    });
		});
	</script>
@stop

@section('page_title')
	@if(Session::has('last_url'))
		<a href="{{URL::to(Session::get('last_url'))}}">
	@else
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/example')}}">
	@endif
		{{HTML::image('img/admin/back.png', '', array('class'=>'image-img'))}}
	</a> Example Edit
@stop

@section('help')
	<ul style="padding-left: 18px;">
		<li><!-- Help Goes Here --></li>
		<li><!-- Help Goes Here --></li>
	</ul>
@stop

@section('content')
	{{Form::model($example, array('url' => URL::current(), 'method' => 'PUT', 'files' => true))}}
		<section id="edit-container">
			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('name', 'Name')}}
				</div><!--
				--><div class="edit-right">
					{{Form::text('name', null, array('class'=>'medium-text', 'required'))}}
					{{Form::hidden('name_old', $example->name)}}
					<span class="required-tx">
						*Required
					</span>
				</div>
			</div>
			
			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('fields2', 'Fields2')}}
				</div><!--
				--><div class="edit-right">
					{{Form::text('fields2', null, array('class'=>'large-text', 'required'))}}
					<span class="required-tx">
						*Required
					</span>
				</div>
			</div>

			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('fields3', 'Fields3')}}
				</div><!--
				--><div class="edit-right">
					{{Form::select('fields3', array(''=>'', 'suka'=>'Suka', 'tidak suka'=>'Tidak Suka'), null, array('class'=>'large-text select', 'placeholder-data'=>'Select the option first'))}}
				</div>
			</div>

			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('fields4', 'Fields4')}}
				</div><!--
				--><div class="edit-right">
					<div class="text-group">
						<span class="image-prepend">
							<div>
								IDR
							</div>
						</span><!--
						-->{{Form::text('fields4', null, array('class'=>'medium-text-prepend numeric', 'placeholder'=>'ex. 100.000'))}}
					</div>
					<span class="required-tx">
						*Numeric
					</span>
				</div>
			</div>

			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('fields5', 'Fields5')}}
				</div><!--
				--><div class="edit-right">
					{{Form::radio('fields5', 1, true, array('class'=>'quiz-radio', 'id'=>'true'))}} {{Form::label('true', 'Active', array('class'=>'question-label'))}}<br>
					{{Form::radio('fields5', 0, false, array('class'=>'quiz-radio', 'id'=>'false'))}} {{Form::label('false', 'Not Active', array('class'=>'question-label'))}}
				</div>
			</div>

			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('fields6', 'Fields6')}}
				</div><!--
				--><div class="edit-right">
					{{Form::checkbox('fields6', 1, true, array('class'=>'quiz-radio', 'id'=>'suka'))}} {{Form::label('suka', 'Suka', array('class'=>'question-label'))}}<br>
				</div>
			</div>

			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('fields7', 'Fields7')}}
				</div><!--
				--><div class="edit-right">
					{{Form::textarea('fields7', null, array('class'=>'large-text area'))}}
				</div>
			</div>

			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('fields8', 'Fields8')}}
				</div><!--
				--><div class="edit-right">
					{{Form::textarea('fields8', null, array('class'=>'large-text area ckeditor'))}}
				</div>
			</div>

			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('fields9', 'Fields9')}}
				</div><!--
				--><div class="edit-right">
					{{Form::text('fields9', null, array('class'=>'small-text datetimepicker', 'readonly'))}}
				</div>
			</div>

			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('image', 'Image')}}
				</div><!--
				--><div class="edit-right">
					@if (file_exists(public_path() . '/usr/img/example/' . $example->id . '_' . Str::slug($example->name, '_') . '_thumb.jpg'))
						<div class="ajax-image" style="display: inline-block;">
							{{Form::button('Delete', array('class'=>'delete-image', 'value'=>$example->id . '_' . Str::slug($example->name, '_')))}}
							{{HTML::image('usr/img/example/' . $example->id . '_' . Str::slug($example->name, '_') . '_thumb.jpg?lastmod=' . Str::random(5), '', array('class'=>'edit-photo-list'))}}
						</div>
					@endif
					{{Form::file('image', array('style'=>'vertical-align: top;', 'class' => 'medium-text image-field'))}}
					<span class="ajax-delete-image">Loading...</span>
					<div class="message-photo">
						Success
					</div>
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