@extends('front.template.master')

@section('title')
	New Pembelian
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

			$('.ajax-product').live('change', function(){
				var branchId = $('.branch-id option:selected').val();
				var productId = $('.ajax-product option:selected').val();
	            if(productId == '')
	            {
	                productId = 0;
	            }

	            $.ajax({
	                type: "GET",
	                url: "{{URL::to('pembelian/ajax-product')}}/" + branchId + '/' + productId,
	                success: function(msg){
	                    $('.hasil-ajax-product').html(msg);
	                }
	            });
			});

			$('.add-item-good').live('click', function(){
				$.ajax({
	                type: "GET",
	                url: "{{URL::to('pembelian/ajax-blur-item')}}",
	                success: function(msg){
	                    $('#blur-ajax-item-good').html(msg);
						$('#blur-ajax-item-good').show();
	                }
	            });
			});

			$('#button-cancel').live('click', function(){
				$('#blur-ajax-item-good').hide();
			});

			$('.ajax-form-item').live('submit', function(e){
				e.preventDefault();
				var submitData = $(this).serialize();
				$.ajax({
					type: "POST",
					data: submitData,
					url: "{{URL::to('pembelian/add-item')}}",
					success:function(msg) {
						$('.hasil-ajax-item').html(msg);
						$('#blur-ajax-item').hide();
					}
				});
			});

			$('.delete-item').live('click', function(){
				$('.blur-loader').show();
				var itemId = $(this).attr('dataId');
				$.ajax({
					type: "GET",
					url: "{{URL::to('pembelian/ajax-delete-item')}}/" + itemId,
					success:function(msg) {
						$('.hasil-ajax-item').html(msg);
						$('.blur-loader').hide();
					}
				});
			});


			$('.update-item').live('click', function(){
				var itemId = $(this).attr('dataId');
				var type = $(this).attr('type');
				$.ajax({
	                type: "GET",
	                url: "{{URL::to('pembelian/ajax-blur-update-item')}}/" + itemId,
	                success: function(msg){
						if(type == 'goods')
						{
		                    $('#blur-ajax-item-good').html(msg);
							$('#blur-ajax-item-good').show();
						}
						else
						{
							$('#blur-ajax-item-recycle').html(msg);
							$('#blur-ajax-item-recycle').show();
						}

	                }
	            });
			});

			$('.ajax-form-update-item').live('submit', function(e){
				e.preventDefault();

				var submitData = $(this).serialize();
				$.ajax({
					type: "POST",
					data: submitData,
					url: "{{URL::to('pembelian/ajax-update-item')}}",
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

		#blur-ajax-item-good, #blur-ajax-item-recycle {
		    /*width: 100%;*/
		    /*height: 100%;*/
		    /*position: fixed;*/
		    /*z-index: 100;*/
		    /*top: 0;*/
		    /*left: 0;*/
		    /*text-align: center;*/
		    background: #fff;
		    display: none;
		}

		#blur-ajax-question {
	        border: solid 1px #d71d21;
		    padding: 30px;
		    /*width: 500px;
		    height: 300px;
		    text-align: left;
		    position: absolute;
		    margin: auto;
		    left: 0;
		    right: 0;
		    top: 0;
		    bottom: 0;
		    background: #fff;
		    padding: 20px;*/
		}

		.edit-submit {
		    width: 160px;
	        margin-left: 1px;
		}

		.submit-done {
			display: none;
		}
	</style>
@stop

@section('page_title')
	<a href="{{URL::to(Session::get('last_url'))}}">{{HTML::image('img/admin/back.png', '', array('class'=>'image-img'))}}</a> New Pembelian
@stop

@section('help')
	<ul style="padding-left: 18px;">
		<li></li>
	</ul>
@stop

@section('content')
	{{Form::model($purchase, array('url' => URL::current(), 'files' => true))}}
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
		</section>
		{{Form::submit('Save', array('class'=>'edit-submit submit-done'))}}
	{{Form::close()}}
			
	<section id="edit-container">
		<div class="hasil-ajax-item">
			<section class="view-data-info">
				<header class="view-data-header">
					Item's
				</header>
				<article class="view-data-ctn">
					{{Form::button('Add Item', array('class'=>'edit-submit add-item add-item-good'))}}
					<table id="index-table" style="border-spacing: 0px;width: 80%; min-width: 900px;">
						<tr class="index-tr index-title">
							<th>#</th>
							<th>Product</th>
							<th>Price</th>
							<th>Quantity</th>
							<th style="text-align: right;">Subtotal</th>
							<th></th>
						</tr>
						<?php
							$counter = 1;
						?>
						@foreach ($items as $item)
							<tr class='index-tr'>
								<td>{{$counter++}}</td>
								<td>{{$item->name}}</td>
								<td>Rp. {{digitGroup($item->price)}}</td>
								<td>{{digitGroup($item->quantity)}}</td>
								<td style="text-align: right;">Rp. {{digitGroup($item->price * $item->quantity)}}</td>
								<td></td>
							</tr>
						@endforeach
						<tr class='index-tr'>
							<td></td>
							<td></td>
							<td></td>
							<td><strong>Total:</strong></td>
							<td style="text-align: right;">
								<strong>Rp. {{digitGroup(Cart::total())}}</strong>
							</td>
							<td></td>
						</tr>
					</table>
				</article>
			</section>

			<section id="blur-ajax-item-good"></section>

			<section class="view-data-info">
				<header class="view-data-header">
					Pembelian Total = Rp. {{digitGroup(0)}}
				</header>
			</section>
		</div>
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