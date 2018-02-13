@extends('back.template.master')

@section('title')
	Sales Returns Return Edit
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
				minDate: "{{date('Y/m/d', strtotime($salesreturn->sale->date))}}",
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
	                url: "{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales-return/ajax-branch')}}/"+selected,
	                success: function(msg){
	                    $('.hasil-ajax-branch').html(msg);
						$('.blur-loader').hide();
	                }
	            });
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
	                url: "{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales-return/ajax-product')}}/" + branchId + '/' + productId,
	                success: function(msg){
	                    $('.hasil-ajax-product').html(msg);
	                }
	            });
			});

			$('.add-item').live('click', function(){
				$('.blur-loader').show();
				var dataId = $(this).attr('dataId');

				$.ajax({
	                type: "GET",
	                url: "{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales-return/ajax-blur-item')}}/" + dataId,
	                success: function(msg){
	                    $('#blur-ajax-item').html(msg);
						$('#blur-ajax-item').show();
						$('.blur-loader').hide();
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
					url: "{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales-return/add-item')}}",
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
					url: "{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales-return/ajax-update-item')}}/" + itemId + '/' + price + '/' + qty ,
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
					url: "{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales-return/ajax-update-item')}}/" + itemId + '/' + price + '/' + qty ,
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
	                url: "{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales-return/ajax-blur-update-item')}}/" + itemId + '/' + branchId,
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
				var qty = $('.update-qty').val();
				// alert(qty);
				$.ajax({
					type: "GET",
					url: "{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales-return/ajax-update-item')}}/" + itemId + '/' + price + '/' + qty ,
					success:function(msg) {
						$('.hasil-ajax-item').html(msg);
						$('#blur-ajax-item').hide();
					}
				});
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

		#blur-ajax-question {
		    width: 500px;
		    height: 195px;
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
	<a href="{{URL::to(Session::get('last_url'))}}">{{HTML::image('img/admin/back.png', '', array('class'=>'image-img'))}}</a> Sales Returns Return Edit
@stop

@section('help')
	<ul style="padding-left: 18px;">
		<li></li>
	</ul>
@stop

@section('content')
	{{Form::model($salesreturn, array('url' => URL::current(), 'method' => 'PUT', 'files' => true))}}
		<section id="edit-container">
			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('no_invoice', 'No. Invoice')}}
				</div><!--
				--><div class="edit-right">
					{{Form::text('no_invoice', $salesreturn->no_invoice, array('class'=>'medium-text readonly', 'readonly'))}}
					<span class="required-tx">
						*Readonly
					</span>
				</div>
			</div>
			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('branch_id', 'Branch')}}
				</div><!--
				--><div class="edit-right">
					{{Form::text('branch_name', $salesreturn->branch->name, array('class'=>'medium-text readonly', 'readonly'))}}
					{{Form::hidden('branch_id', $salesreturn->branch_id, array('class'=>'medium-text readonly branch-id', 'readonly'))}}
					<span class="required-tx">
						*Readonly
					</span>
				</div>
			</div>
			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('sale_id', 'Sales (No. Invoice | Name Customer)')}}
				</div><!--
				--><div class="edit-right">
					{{Form::text('sale_id', $salesreturn->sale->no_invoice . ' | ' . $salesreturn->sale->customer->name , array('class'=>'medium-text readonly', 'readonly'))}}
					<span class="required-tx">
						*readonly
					</span>
				</div>
			</div>
			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('date', 'Date')}}
				</div><!--
				--><div class="edit-right">
					{{Form::text('date', $salesreturn->date, array('class'=>'medium-text datetimepicker', 'required', 'readonly'))}}
					<span class="required-tx">
						*Required
					</span>
				</div>
			</div>

			<section class="view-data-info" style="display: inline-block; width: 49%; vertical-align: top;">
				<header class="view-data-header">
					Sales Items
				</header>
				<article class="view-data-ctn">
					<table id="index-table" style="border-spacing: 0px;width: 100%;">
						<tr class="index-tr index-title">
							<th>#</th>
							<th>Product</th>
							<th>Price</th>
							<th>Qty</th>
							<th style="text-align: right;">Subtotal</th>
							<th></th>
						</tr>
						<?php
							$counter = 1;
						?>
						@foreach ($saledetails as $saledetail)
							<tr class='index-tr'>
								<td>{{$counter++}}</td>
								<td>{{$saledetail->product->name}}</td>
								<td>Rp. {{digitGroup($saledetail->price)}}</td>
								<td>
									<?php
										$salesreturndetails = Salesreturndetail::where('salesdetail_id', '=', $saledetail->id)->get(); 
										$last_stock = 0;
										// foreach ($salesreturndetails as $salesreturndetail) 
										// {
										// 	$last_stock = $last_stock + $salesreturndetail->qty;
										// }
									?>
									{{digitGroup($saledetail->qty - $last_stock)}}
								</td>
								<td style="text-align: right;">Rp. {{digitGroup($saledetail->subtotal)}}</td>
								<td>
									<div class="icon-sub add-item" dataId="{{$saledetail->id}}" style="background-image: none; width: 50px; padding-left: 4px; height: 13px;">
										<span>Return</span>
									</div>
								</td>
							</tr>
						@endforeach
						<tr class='index-tr'>
							<td></td>
							<td></td>
							<td></td>
							<td><strong>Total:</strong></td>
							<td style="text-align: right;">
								<strong>Rp. {{digitGroup($sale->paid)}}</strong>
							</td>
							<td></td>
						</tr>
					</table>
				</article>
			</section>

			<section class="view-data-info" style="display: inline-block; width: 49%; vertical-align: top;">
				<header class="view-data-header">
					Sales Return Items
				</header>
				<article class="view-data-ctn hasil-ajax-item">
					<table id="index-table" style="border-spacing: 0px;width: 100%;">
						<tr class="index-tr index-title">
							<th>#</th>
							<th>Product</th>
							<th>Price</th>
							<th>Qty</th>
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
								<td class="icon">
									<div class="index-icon">
										{{HTML::image('img/admin/index_action.png', '', array('class'=>'icon-master'))}}
										<div class="index-sub-icon">
											<div class="icon-sub update-item" dataId="{{$item->product_id . '-' . $item->price}}">{{HTML::image('img/admin/edit.png')}} <span>Edit</span></div>
											<div class="icon-sub delete-item" dataId="{{$item->product_id . '-' . $item->price}}">{{HTML::image('img/admin/delete.png')}} <span>Delete</span></div>
										</div>
									</div>
								</td>
							</tr>
						@endforeach
						<tr class='index-tr'>
							<td></td>
							<td></td>
							<td></td>
							<td>
								<strong>Total:</strong>
							</td>
							<td style="text-align: right;">
								<strong>Rp. {{digitGroup(Cart::total())}}</strong>
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