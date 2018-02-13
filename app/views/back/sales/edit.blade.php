@extends('back.template.master')

@section('title')
	Nota Edit
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

			var selected = $('.customer-id option:selected').val();
            if(selected == '')
            {
                selected = 0;
            }

            var commission1 = $('.commission1').val();
			if(commission1 == '')
            {
                commission1 = 0;
            }
            
			var commission2 = $('.commission2').val();
			if(commission2 == '')
            {
                commission2 = 0;
            }
            
			var fromNet = $('#from_net').is(":checked");

            $.ajax({
                type: "GET",
                url: "{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales/ajax-customer')}}/" + selected + '/' + commission1 + '/' + commission2 + '/' + fromNet,
                success: function(msg){
                    $('.hasil-ajax-item').html(msg);
                }
            });

			$('.customer-id').live('change', function(){
				$('.blur-loader').show();

				var selected = $('.customer-id option:selected').val();
	            if(selected == '')
	            {
	                selected = 0;
	            }

	            $.ajax({
	                type: "GET",
	                url: "{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales/ajax-salesman')}}/"+selected,
	                success: function(msg){
	                    $('.hasil-ajax-salesman').html(msg);
	                    
			            var commission1 = $('.commission1').val();
						if(commission1 == '')
			            {
			                commission1 = 0;
			            }
			            
						var commission2 = $('.commission2').val();
						if(commission2 == '')
			            {
			                commission2 = 0;
			            }
			            
						var fromNet = $('#from_net').is(":checked");

			            $.ajax({
			                type: "GET",
			                url: "{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales/ajax-customer')}}/" + selected + '/' + commission1 + '/' + commission2 + '/' + fromNet,
			                success: function(msg){
			                    $('.hasil-ajax-item').html(msg);
			                }
			            });
	                }
	            });
				$('.blur-loader').hide();
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
	                url: "{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales/ajax-product')}}/" + branchId + '/' + productId,
	                success: function(msg){
	                    $('.hasil-ajax-product').html(msg);
	                }
	            });
			});

			$('.add-item-good').live('click', function(){
	            var customerId = $('.customer-id option:selected').val();
	            if(customerId == '')
	            {
	                customerId = 0;
	            }

				$.ajax({
	                type: "GET",
	                url: "{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales/ajax-blur-item')}}/" + customer_id,
	                success: function(msg){
	                    $('#blur-ajax-item-good').html(msg);
						$('#blur-ajax-item-good').show();
	                }
	            });
			});

			$('.add-item-recycle').live('click', function(){
				var customerId = $('.customer-id option:selected').val();
	            if(customerId == '')
	            {
	                customerId = 0;
	            }

				$.ajax({
	                type: "GET",
	                url: "{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales/ajax-blur-item-recycle')}}/" + customerId,
	                success: function(msg){
	                    $('#blur-ajax-item-recycle').html(msg);
						$('#blur-ajax-item-recycle').show();
	                }
	            });
			});

			$('#button-cancel').live('click', function(){
				$('#blur-ajax-item-good').hide();
				$('#blur-ajax-item-recycle').hide();
			});

			$('.ajax-form-item').live('submit', function(e){
				e.preventDefault();
				var customerId = $('.customer-id option:selected').val();
	            if(customerId == '')
	            {
	                customerId = 0;
	            }

	            var commission1 = $('.commission1').val();
				if(commission1 == '')
	            {
	                commission1 = 0;
	            }
	            
				var commission2 = $('.commission2').val();
				if(commission2 == '')
	            {
	                commission2 = 0;
	            }
	            
				var fromNet = $('#from_net').is(":checked");

				var submitData = $(this).serialize();
				$.ajax({
					type: "POST",
					data: submitData,
					url: "{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales/add-item')}}/" + customerId + '/' + commission1 + '/' + commission2 + '/' + fromNet,
					success:function(msg) {
						$('.hasil-ajax-item').html(msg);
						$('#blur-ajax-item').hide();
					}
				});
			});

			$('.delete-item').live('click', function(){
				$('.blur-loader').show();
				var customerId = $('.customer-id option:selected').val();
	            if(customerId == '')
	            {
	                customerId = 0;
	            }

	            var commission1 = $('.commission1').val();
				if(commission1 == '')
	            {
	                commission1 = 0;
	            }
	            
				var commission2 = $('.commission2').val();
				if(commission2 == '')
	            {
	                commission2 = 0;
	            }
	            
				var fromNet = $('#from_net').is(":checked");

				var itemId = $(this).attr('dataId');
				$.ajax({
					type: "GET",
					url: "{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales/ajax-delete-item')}}/" + itemId + '/' + customerId + '/' + commission1 + '/' + commission2 + '/' + fromNet,
					success:function(msg) {
						$('.hasil-ajax-item').html(msg);
						$('.blur-loader').hide();
					}
				});
			});


			$('.update-item').live('click', function(){
				var customerId = $('.customer-id option:selected').val();
	            if(customerId == '')
	            {
	                customerId = 0;
	            }

				var itemId = $(this).attr('dataId');
				var type = $(this).attr('type');
				$.ajax({
	                type: "GET",
	                url: "{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales/ajax-blur-update-item')}}/" + itemId + '/' + customerId,
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
				var customerId = $('.customer-id option:selected').val();
	            if(customerId == '')
	            {
	                customerId = 0;
	            }

	            var commission1 = $('.commission1').val();
				if(commission1 == '')
	            {
	                commission1 = 0;
	            }
	            
				var commission2 = $('.commission2').val();
				if(commission2 == '')
	            {
	                commission2 = 0;
	            }
	            
				var fromNet = $('#from_net').is(":checked");
				var submitData = $(this).serialize();
				$.ajax({
					type: "POST",
					data: submitData,
					url: "{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales/ajax-update-item')}}/" + customerId + '/' + commission1 + '/' + commission2 + '/' + fromNet,
					success:function(msg) {
						$('.hasil-ajax-item').html(msg);
						$('#blur-ajax-item').hide();
					}
				});
			});

			$('.commission1, .commission2').live('keyup', function(){
				var customerId = $('.customer-id option:selected').val();
	            if(customerId == '')
	            {
	                customerId = 0;
	            }

				var commission1 = $('.commission1').val();
				if(commission1 == '')
	            {
	                commission1 = 0;
	            }
	            
				var commission2 = $('.commission2').val();
				if(commission2 == '')
	            {
	                commission2 = 0;
	            }

				var fromNet = $('#from_net').is(":checked");

				$.ajax({
					type: "GET",
					url: "{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales/ajax-commission')}}/" + customerId + '/' + commission1 + '/' + commission2 + '/' + fromNet,
					success:function(msg) {
						$('.hasil-ajax-item').html(msg);
					}
				});
			});

			$('#from_net').live('click', function(){
				var customerId = $('.customer-id option:selected').val();
	            if(customerId == '')
	            {
	                customerId = 0;
	            }

				var commission1 = $('.commission1').val();
				if(commission1 == '')
	            {
	                commission1 = 0;
	            }
	            
				var commission2 = $('.commission2').val();
				if(commission2 == '')
	            {
	                commission2 = 0;
	            }

				var fromNet = $('#from_net').is(":checked");

				$.ajax({
					type: "GET",
					url: "{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales/ajax-commission')}}/" + customerId + '/' + commission1 + '/' + commission2 + '/' + fromNet,
					success:function(msg) {
						$('.hasil-ajax-item').html(msg);
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
		    background: #fff;
		    display: none;
		}

		#blur-ajax-question {
	        border: solid 1px #0C0E3B;
		    padding: 30px;
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
	<a href="{{URL::to(Session::get('last_url'))}}">{{HTML::image('img/admin/back.png', '', array('class'=>'image-img'))}}</a> Nota Edit
@stop

@section('help')
	<ul style="padding-left: 18px;">
		<li></li>
	</ul>
@stop

@section('content')
	{{Form::model($sale, array('url' => URL::current(), 'method' => 'PUT', 'files' => true))}}
		<section id="edit-container">
			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('no_invoice', 'No. Invoice')}}
				</div><!--
				--><div class="edit-right">
					{{Form::text('no_invoice', $sale->no_invoice, array('class'=>'medium-text readonly', 'readonly'))}}
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
					{{Form::text('branch_name', $sale->branch->name, array('class'=>'medium-text readonly', 'readonly'))}}
					{{Form::hidden('branch_id', $sale->branch_id, array('class'=>'medium-text readonly branch-id', 'readonly'))}}
					<span class="required-tx">
						*Readonly
					</span>
				</div>
			</div>
			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('customer_id', 'Customer')}}
				</div><!--
				--><div class="edit-right">
					{{Form::select('customer_id', $customer_options, null, array('class'=>'medium-text select customer-id', 'required'))}}
					<span class="required-tx">
						*Required
					</span>
				</div>
			</div>
			<div class="hasil-ajax-salesman">
				@if($salesman1 != null)
					<div class="edit-group">
						<div class="edit-left">
							{{Form::label('salesman1', 'Commission 1 ')}}
						</div><!--
						--><div class="edit-right">
							<div style="display: inline-block; vertical-align: top;">
								{{Form::text('salesman1', $salesman1->name, array('class'=>'medium-text readonly', 'readonly'))}}
							</div>
							{{Form::input('number', 'commission1', $sale->commission1, array('class'=>'small-text-prepend commission1', 'placeholder'=>'Commision', 'min'=>0, 'max'=>100))}}<!--
						 --><span class="image-prepend">
								<div>
									%
								</div>
							</span>
						</div>
					</div>
				@else
					{{Form::hidden('commission1', 0, array('class'=>'small-text commission1'))}}
				@endif
				@if($salesman2 != null)
					<div class="edit-group">
						<div class="edit-left">
							{{Form::label('salesman2', 'Commission 2')}}
						</div><!--
						--><div class="edit-right">
							<div style="display: inline-block; vertical-align: top;">
								{{Form::text('salesman2', $salesman2->name, array('class'=>'medium-text readonly', 'readonly'))}}
							</div>
							{{Form::input('number', 'commission2', $sale->commission2, array('class'=>'small-text-prepend commission2', 'placeholder'=>'Commision', 'min'=>0, 'max'=>100))}}<!--
						 --><span class="image-prepend" style="vertical-align: middle;">
								<div>
									%
								</div>
							</span>
							@if($customer->from_net == 1)
								{{Form::checkbox('from_net', 'true', true, array('id'=>'from_net', "style" => "vertical-align: middle;display: inline-block;"))}}
							@else
								{{Form::checkbox('from_net', 'true', false, array('id'=>'from_net', "style" => "vertical-align: middle;display: inline-block;"))}}
							@endif
							{{Form::label('from_net', 'From Net')}}
						</div>
					</div>
				@else
					{{Form::hidden('commission2', 0, array('class'=>'small-text commission2'))}}
					{{Form::checkbox('from_net', 'true', false, array('id'=>'from_net', "style" => "display: none;"))}}
				@endif
			</div>
			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('date', 'Date')}}
				</div><!--
				--><div class="edit-right">
					{{Form::text('date', $sale->date, array('class'=>'medium-text datetimepicker', 'required', 'readonly'))}}
					<span class="required-tx">
						*Required
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

			{{Form::submit('Save', array('class'=>'edit-submit submit-done'))}}
		</section>
	{{Form::close()}}
	<section id="edit-container">
		<div class="hasil-ajax-item">
			<section class="view-data-info">
				<header class="view-data-header">
					Good Item's
				</header>
				<article class="view-data-ctn">
					{{Form::button('Add Item', array('class'=>'edit-submit add-item add-item-good'))}}
					<table id="index-table" style="border-spacing: 0px;width: 80%; min-width: 900px;">
						<tr class="index-tr index-title">
							<th>#</th>
							<th>Product</th>
							<th>Price</th>
							<th>Disc. 1</th>
							<th>Disc. 2</th>
							<th>Quantity</th>
							<th style="text-align: right;">Subtotal</th>
							<th></th>
						</tr>
						<?php
							$counter = 1;
							$total = 0;
						?>
						@foreach ($items as $item)
							@if($item->type == 'Product')
								<?php 
									$subtotal = $item->price * $item->quantity;
									if($item->discount1 != 0)
									{
										$subtotal = $subtotal - ($subtotal * $item->discount1 / 100);							
									}

									if($item->discount2 != 0)
									{
										$subtotal = $subtotal - ($subtotal * $item->discount2 / 100);							
									}

									$total = $total + $subtotal; 
								?>
								<tr class='index-tr'>
									<td>{{$counter++}}</td>
									<td>{{$item->name}}</td>
									<td>Rp. {{digitGroup($item->price)}}</td>
									<td>{{digitGroup($item->discount1)}}%</td>
									<td>{{digitGroup($item->discount2)}}%</td>
									<td>{{digitGroup($item->quantity)}}</td>
									<td style="text-align: right;">Rp. {{digitGroup($subtotal)}}</td>
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
							@endif
						@endforeach
						<tr class='index-tr'>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td><strong>Total:</strong></td>
							<td style="text-align: right;">
								<strong>Rp. {{digitGroup($total)}}</strong>
								{{Form::hidden('price_total', $total)}}
							</td>
							<td></td>
						</tr>
					</table>
				</article>
			</section>

			<section id="blur-ajax-item-good"></section>

			<section class="view-data-info">
				<header class="view-data-header">
					Recycle Item's
				</header>
				<article class="view-data-ctn">
					{{Form::button('Add Recycle', array('class'=>'edit-submit add-item add-item-recycle'))}}
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
							$total_recycle = 0;
						?>
						@foreach ($items as $item)
							@if($item->type == 'Second')
								<?php
									$total_recycle = $total_recycle + ($item->price * $item->quantity);
								?>
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
												<div class="icon-sub update-item" type="recycle" dataId="{{$item->id}}">{{HTML::image('img/admin/edit.png')}} <span>Edit</span></div>
												<div class="icon-sub delete-item" dataId="{{$item->id}}">{{HTML::image('img/admin/delete.png')}} <span>Delete</span></div>
											</div>
										</div>
									</td>
								</tr>
							@endif
						@endforeach
						<tr class='index-tr'>
							<td></td>
							<td></td>
							<td></td>
							<td><strong>Total:</strong></td>
							<td style="text-align: right;">
								<strong>Rp. {{digitGroup($total_recycle)}}</strong>
								{{Form::hidden('recycle_total', $total_recycle)}}
							</td>
							<td></td>
						</tr>
					</table>
				</article>
			</section>

			<section id="blur-ajax-item-recycle"></section>

			<section class="view-data-info">
				<header class="view-data-header">
					Sales Total = Rp. {{digitGroup($total)}}
				</header>
				@if($customer != null)
					<?php
						$salesman1 = Salesman::find($customer->salesman_id1);
						$salesman2 = Salesman::find($customer->salesman_id2);

						if($customer->salesman_id1 != null)
						{
							if(isset($new_commission1))
							{
								$commission1 = $total * $new_commission1 / 100;
							}
							else
							{
								$commission1 = $total * $customer->commission1 / 100;
							}
						}

						if(isset($new_from_net))
						{
							$from_net = $new_from_net;
						}
						else
						{
							$from_net = $customer->from_net;
						}

						if($customer->salesman_id2 != null)
						{
							if($from_net == 'false')
							{
								if(isset($new_commission2))
								{
									$commission2 = $total * $new_commission2 / 100;
								}
								else
								{
									$commission2 = $total * $customer->commission2 / 100;
								}
							}
							else
							{
								if(isset($new_commission2))
								{
									$commission2 = ($total - $commission1) * ($new_commission2 / 100);
								}
								else
								{
									$commission2 = ($total - $commission1) * ($customer->commission2 / 100);
								}
							}
						}
					?>
					<header class="view-data-header">
						Commission Total 
					</header>
					<section id="edit-container">
						@if($customer->salesman_id1 != null)
							<div class="edit-group" style="padding-top: 10px; padding-left: 23px;">
								<div class="edit-left">
									{{Form::label('commission1', 'Commission 1')}}
								</div><!--
								--><div class="edit-right">
									{{$salesman1->name}}, 
									@if(isset($new_commission1))
										{{$new_commission1}}%<br>
									@else
										{{$customer->commission1}}%<br>
									@endif
									Rp. {{digitGroup($commission1)}}
								</div>
							</div>
						@endif
						@if($customer->salesman_id2 != null)
							<div class="edit-group" style="padding-top: 10px; padding-left: 23px;">
								<div class="edit-left">
									{{Form::label('commission2', 'Commission 2')}}
								</div><!--
								--><div class="edit-right">
									{{$salesman2->name}}, 
									@if($from_net == 'true')
										@if(isset($new_commission1))
											{{$new_commission1}}
										@else
											{{$customer->commission1}}
										@endif
										+
										@if(isset($new_commission2))
											{{$new_commission2}}%
										@else
											{{$customer->commission2}}%
										@endif
									@else
										@if(isset($new_commission2))
											{{$new_commission2}}%
										@else
											{{$customer->commission2}}%
										@endif
									@endif
									<br>
									Rp. {{digitGroup($commission2)}}
								</div>
							</div>
						@endif
					</section>
				@else
					<header class="view-data-header">
						Commission Total
					</header>
				@endif
				<header class="view-data-header">
					Total Payment = Rp. {{digitGroup($total - $total_recycle)}}
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