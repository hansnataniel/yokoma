@extends('back.template.master')

@section('title')
	New Pembulatan
@stop

@section('head_additional')
	<script>
		$(function() {
			$('.numeric').inputmask('currency', {
				digitsOptional: true,
				groupSize: 3,
				autoGroup: true,
				prefix: '',
				allowMinus: true,
				placeholder: ''
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


		$(document).ready(function(){
			$('.ajax-nota').live('change', function(){
				var selected = $('.ajax-nota option:selected').val();
				$.ajax({
	                type: "GET",
	                url: "{{URL::to(Crypt::decrypt($setting->admin_url) . '/pembulatan-nota/ajax-nota')}}/"+selected,
	                success: function(msg){
	                    $('.hasil-ajax-nota').html(msg);
	                }
	            });
			});
		});

	</script>
@stop

@section('page_title')
	<a href="{{URL::to(Session::get('last_url'))}}">{{HTML::image('img/admin/back.png', '', array('class'=>'image-img'))}}</a> New Pembulatan
@stop

@section('help')
	<ul style="padding-left: 18px;">
		<li></li>
	</ul>
@stop

@section('content')
	{{Form::model($pembulatan, array('url' => URL::current(), 'files' => true))}}
		<section id="edit-container">
			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('sale_id', 'No. Nota')}}
				</div><!--
				--><div class="edit-right">
					{{Form::select('sale_id', $sale_options, null, array('class'=>'medium-text select ajax-nota', 'required'))}}
					<span class="required-tx">
						*Required
					</span>
				</div>
			</div>
			<div class="hasil-ajax-nota">
				
			</div>
			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('price', 'Pembulatan Harga')}}
				</div><!--
				--><div class="edit-right">
					<span class="image-prepend">
						<div>
							Rp.
						</div>
					</span><!--
					-->{{Form::text('price', null, array('class'=>'medium-text-prepend numeric', 'required'))}}
					<span class="required-tx">
						*Required, Numeric
					</span>
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