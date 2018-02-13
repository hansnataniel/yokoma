@extends('front.template.master')

@section('title')
	Penerimaan Edit
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

			$('.datetimepicker2').datetimepicker({
				scrollMonth: false,
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

			$('.branch-id').live('change', function(){
				$('.blur-loader').show();
				var selected = $('.branch-id option:selected').val();
	            if(selected == '')
	            {
	                selected = 0;
	            }

	            $.ajax({
	                type: "GET",
	                url: "{{URL::to('payment/ajax-branch')}}/"+selected,
	                success: function(msg){
	                    $('.hasil-ajax-branch').html(msg);
						$('.blur-loader').hide();
	                }
	            });
			});

			$('.ajax-customer').live('change', function(){
				$('.blur-loader').show();
				var customerId = $('.ajax-customer option:selected').val();
	            if(customerId == '')
	            {
	                customerId = 0;
	            }

	            $.ajax({
	                type: "GET",
	                url: "{{URL::to('payment/ajax-customer')}}/" + customerId,
	                success: function(msg){
	                    $('.hasil-ajax-customer').html(msg);
						$('.blur-loader').hide();
	                }
	            });
			});


			$('.ajax-sales').live('change', function(){
				var salesId = $('.ajax-sales option:selected').val();
	            if(salesId == '')
	            {
	                salesId = 0;
	            }

	            $.ajax({
	                type: "GET",
	                url: "{{URL::to('payment/ajax-sales')}}/" + salesId,
	                success: function(msg){
	                    $('.hasil-ajax-sales').html(msg);
	                }
	            });
			});

			$('.add-item').live('click', function(){
                customerId = {{$payment->customer->id}};

				$.ajax({
	                type: "GET",
	                url: "{{URL::to('payment/ajax-blur-item')}}/" + customerId,
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
					url: "{{URL::to('payment/add-item')}}",
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
					url: "{{URL::to('payment/ajax-update-item')}}/" + itemId + '/' + price + '/' + qty + '/0',
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
					url: "{{URL::to('payment/ajax-update-item')}}/" + itemId + '/' + price + '/' + qty ,
					success:function(msg) {
						$('.hasil-ajax-item').html(msg);
						$('.blur-loader').hide();
					}
				});
			});


			$('.update-item').live('click', function(){
				var itemId = $(this).attr('dataId');
				$.ajax({
	                type: "GET",
	                url: "{{URL::to('payment/ajax-blur-update-item2')}}/" + itemId,
	                success: function(msg){
	                    $('#blur-ajax-item').html(msg);
						$('#blur-ajax-item').show();
	                }
	            });
			});

			$('.ajax-form-update-item').live('submit', function(e){
				e.preventDefault();
				var itemId = $('.update-item-id').val();
				var price = $('.update-price').val();
				var pembulatan = $('.update-pembulatan').val();
				if(pembulatan == '')
				{
					pembulatan = 0;
				}
				// alert(qty);
				$.ajax({
					type: "GET",
					url: "{{URL::to('payment/ajax-update-item')}}/" + itemId + '/' + price + '/1/' + pembulatan ,
					success:function(msg) {
						$('.hasil-ajax-item').html(msg);
						$('#blur-ajax-item').hide();
					}
				});
			});

			// $('.edit-submit.submit-fix').click(function(){
			// 	alert('jalan');
			// });



			$('.create-submit').live('submit', function(e){
				e.preventDefault();
				var amountToPay = $('.amount-to-pay').val().replace(/,/g, "");
				var priceTotal = $('.price-total').val();

				if(parseInt(amountToPay) == parseInt(priceTotal))
				{
					$(this).removeClass('create-submit');
					$('.form-crate').submit();
				}
				else
				{
					alert('Amount To Pay and Total Paid is not balanced.');
				}
			});

			$('.quiz-radio').change(function(){
				var value = $(this).val();
				if(value == 'Giro')
				{
					$('.tgl-pencairan').show();
				}
				else
				{
					$('.tgl-pencairan').hide();
				}
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
		    width: 100%;
		    height: 100%;
		    position: fixed;
		    z-index: 100;
		    top: 0;
		    left: 0;
		    text-align: center;
		    background: rgba(0, 0, 0, 0.8);
		    display: none;
		}

		@if($payment->metode_pembayaran != 'Giro')
			.tgl-pencairan {
				display: none;
			}
		@endif

		#blur-ajax-question {
		    width: 500px;
		    height: 245px;
		    text-align: left;
		    position: absolute;
		    margin: auto;
		    left: 0;
		    right: 0;
		    top: 0;
		    bottom: 0;
		    background: #fff;
		    padding: 20px;
		}
		
	</style>
@stop

@section('page_title')
	<a href="{{URL::to(Session::get('last_url'))}}">{{HTML::image('img/admin/back.png', '', array('class'=>'image-img'))}}</a> Penerimaan Edit
@stop

@section('help')
	<ul style="padding-left: 18px;">
		<li></li>
	</ul>
@stop

@section('content')
	{{Form::model($payment, array('url' => URL::current(), 'method' => 'PUT', 'files' => true, 'class'=>'form-crate create-submit'))}}
		<section id="edit-container">
			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('form_no', 'Form No')}}
				</div><!--
				--><div class="edit-right">
					{{Form::text('form_no', $payment->no_invoice, array('class'=>'medium-text readonly', 'readonly'))}}
					<span class="required-tx">
						*Readonly
					</span>
				</div>
			</div>
			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('customer_name', 'Customer')}}
				</div><!--
				--><div class="edit-right">
					{{Form::text('customer_name', $payment->customer->name, array('class'=>'medium-text readonly', 'required', 'readonly'))}}
					{{Form::hidden('customer_id', $payment->customer->id, array('class'=>'medium-text ajax-customer'))}}
					<span class="required-tx">
						*Readonly
					</span>
				</div>
			</div>

			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('date', 'Date')}}
				</div><!--
				--><div class="edit-right">
					{{Form::text('date', $payment->date, array('class'=>'medium-text datetimepicker', 'required', 'readonly'))}}
					<span class="required-tx">
						*Required
					</span>
				</div>
			</div>
			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('metode_pembayaran', 'Metode Pembayaran')}}
				</div><!--
				--><div class="edit-right">
					{{Form::radio('metode_pembayaran', 'Cash', true, array('class'=>'quiz-radio', 'id'=>'cash'))}} {{Form::label('cash', 'Cash', array('class'=>'question-label'))}}<br>
					{{Form::radio('metode_pembayaran', 'Transfer', false, array('class'=>'quiz-radio', 'id'=>'transfer'))}} {{Form::label('transfer', 'Transfer', array('class'=>'question-label'))}}<br>
					{{Form::radio('metode_pembayaran', 'Giro', false, array('class'=>'quiz-radio', 'id'=>'giro'))}} {{Form::label('giro', 'Giro', array('class'=>'question-label'))}}
				</div>
			</div>
			<div class="edit-group tgl-pencairan">
				<div class="edit-left">
					{{Form::label('tgl_pencairan', 'Tgl. Pencairan')}}
				</div><!--
				--><div class="edit-right">
					{{Form::text('tgl_pencairan', $payment->tgl_pencairan, array('class'=>'medium-text datetimepicker2', 'required', 'readonly'))}}
					<span class="required-tx">
						*Required jika metode pembayaran "Giro"
					</span>
				</div>
			</div>
			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('keterangan', 'Keterangan')}}
				</div><!--
				--><div class="edit-right">
					{{Form::textarea('keterangan', null, array('class'=>'large-text area'))}}
				</div>
			</div>
			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('amount_to_pay', 'Amount to Pay')}}
				</div><!--
				--><div class="edit-right">
					<span class="image-prepend">
						<div>
							Rp.
						</div>
					</span><!--
					-->{{Form::input('number', 'amount_to_pay', $payment->payment_total, array('class'=>'medium-text-prepend amount-to-pay', 'required'))}}
					<span class="required-tx">
						*Required, Numeric
					</span>
				</div>
			</div>
			{{Form::button('Add Nota', array('class'=>'edit-submit add-item'))}}
			<section class="view-data-info">
				<header class="view-data-header">
					Nota Item's
				</header>
				<article class="view-data-ctn hasil-ajax-item">
					<table id="index-table" style="border-spacing: 0px;width: 60%;">
						<tr class="index-tr index-title">
							<th>#</th>
							<th>No. Nota</th>
							<th>Owed</th>
							<th>Pembulatan</th>
							<th>Price</th>
							<th></th>
						</tr>
						<?php
							$counter = 1;
						?>
						@foreach ($items as $item)
							<tr class='index-tr'>
								<td>{{$counter++}}</td>
								<td>{{$item->name}}</td>
								<td>Rp. {{digitGroup($item->owed)}}</td>
								<td>Rp. {{digitGroup($item->pembulatan)}}</td>
								<td>Rp. {{digitGroup($item->price)}}</td>
								<td class="icon">
									<div class="index-icon">
										{{HTML::image('img/admin/index_action.png', '', array('class'=>'icon-master'))}}
										<div class="index-sub-icon">
											<div class="icon-sub update-item" dataId="{{$item->id}}">{{HTML::image('img/admin/edit.png')}} <span>Edit</span></div>
											<div class="icon-sub delete-item" dataId="{{$item->id}}">{{HTML::image('img/admin/delete.png')}} <span>Delete</span></div>
										</div>
									</div>
								</td>
							</tr>
						@endforeach
						<tr class='index-tr'>
							<td></td>
							<td><strong>Total Paid:</strong></td>
							<td>
								<strong>{{digitGroup(Cart::total())}}</strong>
								{{Form::hidden('price_total', Cart::total(), array('class'=>'price-total'))}}
							</td>
							<td></td>
						</tr>
					</table>
				</article>
			</section>
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
	
	<section id="blur-ajax-item"></section>
@stop