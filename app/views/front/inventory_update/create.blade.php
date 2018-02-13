@extends('front.template.master')

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

			$('.add-item').live('click', function(){
				var branchId = $('.branch-id option:selected').val();
				if(branchId == '')
	            {
	                branchId = 0;
	            }

				$.ajax({
	                type: "GET",
	                url: "{{URL::to('inventory-update/ajax-blur-item')}}/" + branchId,
	                success: function(msg){
	                    $('#blur-ajax-item').html(msg);
						$('#blur-ajax-item').show();
	                }
	            });
			});

			$('#button-cancel').live('click', function(){
				$('#blur-ajax-item').hide();
			});

			$('.ajax-form-item').live('submit', function(e){
				e.preventDefault();
				var submitData = $(this).serialize();
				$.ajax({
					type: "POST",
					data: submitData,
					url: "{{URL::to('inventory-update/add-item')}}",
					success:function(msg) {
						$('.hasil-ajax-item').html(msg);
						$('#blur-ajax-item').hide();
					}
				});
			});

			$('.delete-item').live('click', function(){
				$('.blur-loader').show();
				var itemId = $(this).attr('dataId');
				var price = 0;
				var qty = 0;
				$.ajax({
					type: "GET",
					url: "{{URL::to('inventory-update/ajax-update-item')}}/" + itemId + '/' + price + '/' + qty ,
					success:function(msg) {
						$('.hasil-ajax-item').html(msg);
						$('.blur-loader').hide();
					}
				});
			});

			$('.delete-item').live('click', function(){
				$('.blur-loader').show();
				var itemId = $(this).attr('dataId');
				var price = 0;
				var qty = 0;
				$.ajax({
					type: "GET",
					url: "{{URL::to('inventory-update/ajax-update-item')}}/" + itemId + '/' + price + '/' + qty ,
					success:function(msg) {
						$('.hasil-ajax-item').html(msg);
						$('.blur-loader').hide();
					}
				});
			});


			$('.update-item').live('click', function(){
				var branchId = $('.branch-id option:selected').val();
				if(branchId == '')
	            {
	                branchId = 0;
	            }

				var itemId = $(this).attr('dataId');
				$.ajax({
	                type: "GET",
	                url: "{{URL::to('inventory-update/ajax-blur-update-item')}}/" + itemId + '/' + branchId,
	                success: function(msg){
	                    $('#blur-ajax-item').html(msg);
						$('#blur-ajax-item').show();
	                }
	            });
			});

			$('.ajax-form-update-item').live('submit', function(e){
				e.preventDefault();
				var itemId = $('.update-item-id').val();
				var type = $('.update-type').val();
				var qty = $('.update-qty').val();
				// alert(qty);
				$.ajax({
					type: "GET",
					url: "{{URL::to('inventory-update/ajax-update-item')}}/" + itemId + '/' + type + '/' + qty ,
					success:function(msg) {
						$('.hasil-ajax-item').html(msg);
						$('#blur-ajax-item').hide();
					}
				});
			});

			$('.button-submit').click(function(){
				$('.submit-done').click();
			});
		});
	</script>

	<style type="text/css">
		.add-item {
			background-image: url("{{URL::to('/img/admin/icon_addnew.png')}}");
			background-repeat: no-repeat;
			background-position: 22px 9px;
		    padding-left: 25px;
		}

		.add-item:hover {
			background-image: url("{{URL::to('/img/admin/icon_addnew.png')}}");
			background-repeat: no-repeat;
			background-position: 22px 9px;
		    padding-left: 25px;
		}

		#blur-ajax-item {
		    background: #fff;
		    display: none;
		}

		#blur-ajax-question {
	        border: solid 1px #0C0E3B;
		    padding: 30px;
		}

		.edit-submit {
		    width: 140px;
	        margin-left: 1px;
		}

		.submit-done {
			display: none;
		}
	</style>
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
					{{Form::label('date', 'Date')}}
				</div><!--
				--><div class="edit-right">
					{{Form::text('date', date('Y-m-d'), array('class'=>'medium-text datetimepicker', 'required', 'readonly'))}}
					<span class="required-tx">
						*Required
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
			
			<div>
				{{Form::button('Add Item', array('class'=>'edit-submit add-item'))}}
				<section class="view-data-info">
					<header class="view-data-header">
						Item's
					</header>
					<article class="view-data-ctn hasil-ajax-item">
						<table id="index-table" style="border-spacing: 0px;width: 60%;">
							<tr class="index-tr index-title">
								<th>#</th>
								<th>Product</th>
								<th>Type</th>
								<th>Amount</th>
								<th></th>
							</tr>
							<?php
								$counter = 1;
							?>
							@foreach ($items as $item)
								<tr class='index-tr'>
									<td>{{$counter++}}</td>
									<td>{{$item->name}}</td>
									<td>{{$item->type}}</td>
									<td>{{digitGroup($item->quantity)}}</td>
									<td class="icon">
										<div class="index-icon">
											{{HTML::image('img/admin/index_action.png', '', array('class'=>'icon-master'))}}
											<div class="index-sub-icon">
												<div class="icon-sub update-item" type="goods" dataId="{{$item->id}}">{{HTML::image('img/admin/edit.png')}} <span>Edit</span></div>
												<div class="icon-sub delete-item" dataId="{{$item->id}}">{{HTML::image('img/admin/delete.png')}} <span>Delete</span></div>
											</div>
										</div>
									</td>
								</tr>
							@endforeach
						</table>
					</article>
				</section>
			</div>
		</section>
		{{Form::submit('Save', array('class'=>'edit-submit submit-done'))}}
	{{Form::close()}}

	<section id="blur-ajax-item"></section>
	
	<section id="edit-container">
		<div class="edit-group">
			<div class="edit-left">
			</div><!--
			--><div class="edit-right">
				{{Form::button('Save', array('class'=>'edit-submit margin button-submit'))}}
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


@stop