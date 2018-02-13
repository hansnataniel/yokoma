@extends('front.template.master')

@section('title')
	Request Update Sales
@stop

@section('head_additional')
	{{HTML::style('css/jquery.datetimepicker.css')}}
	{{HTML::script('js/jquery.datetimepicker.js')}}

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
	<a href="{{URL::to(Session::get('last_url'))}}">{{HTML::image('img/admin/back.png', '', array('class'=>'image-img'))}}</a> Request Update Sales
@stop

@section('help')
	<ul style="padding-left: 18px;">
		<li></li>
	</ul>
@stop

@section('content')
	{{Form::model($sale, array('url' => URL::current(), 'method' => 'POST', 'files' => true))}}
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
					{{Form::label('customer_id', 'Customer')}}
				</div><!--
				--><div class="edit-right">
					{{Form::text('customer_id', $sale->customer->name, array('class'=>'medium-text readonly', 'readonly'))}}
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
					{{Form::text('date', $sale->date, array('class'=>'medium-text readonly', 'required', 'readonly'))}}
					<span class="required-tx">
						*Readonly
					</span>
				</div>
			</div>

			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('note', 'Reason Update This Sales')}}
				</div><!--
				--><div class="edit-right">
					{{Form::textarea('note', null, array('class'=>'medium-textarea', 'required'))}}
					<span class="required-tx">
						*Required
					</span>
				</div>
			</div>

			<section class="view-data-info">
				<header class="view-data-header">
					Good Item's
				</header>
				<article class="view-data-ctn hasil-ajax-item">
					<table id="index-table" style="border-spacing: 0px;width: 80%;">
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
						?>
						@foreach ($items as $item)
							@if($item->type == "Product")
								<tr class='index-tr'>
									<td>{{$counter++}}</td>
									<td>{{$item->name}}</td>
									<td>Rp. {{digitGroup($item->price)}}</td>
									<td>{{digitGroup($item->quantity)}}</td>
									<td>{{digitGroup($item->discount1)}}%</td>
									<td>{{digitGroup($item->discount2)}}%</td>
									<td style="text-align: right;">Rp. {{digitGroup($item->subtotal)}}</td>
									<td class="icon">
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
							<td>
								<strong>Total:</strong>
							</td>
							<td style="text-align: right;">
								<strong>Rp. {{digitGroup($sale->price_total)}}</strong>
							</td>
							<td></td>
						</tr>
					</table>
				</article>
			</section>

			<section class="view-data-info">
				<header class="view-data-header">
					Recycle Item's
				</header>
				<article class="view-data-ctn hasil-ajax-item">
					<table id="index-table" style="border-spacing: 0px;width: 80%;">
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
							@if($item->type == "Second")
								<tr class='index-tr'>
									<td>{{$counter++}}</td>
									<td>{{$item->name}}</td>
									<td>Rp. {{digitGroup($item->price)}}</td>
									<td>{{digitGroup($item->quantity)}}</td>
									<td style="text-align: right;">Rp. {{digitGroup($item->subtotal)}}</td>
									<td class="icon">
									</td>
								</tr>
							@endif
						@endforeach
						<tr class='index-tr'>
							<td></td>
							<td></td>
							<td></td>
							<td>
								<strong>Total:</strong>
							</td>
							<td style="text-align: right;">
								<strong>Rp. {{digitGroup($sale->recycle_total)}}</strong>
							</td>
							<td></td>
						</tr>
					</table>
				</article>
			</section>

			<section class="view-data-info">
					<header class="view-data-header">
						Total Payment = Rp. {{digitGroup($sale->paid)}}
					</header>
				</section>

			<div class="edit-group">
				<div class="edit-left">
				</div><!--
				--><div class="edit-right">
					{{Form::submit('Request', array('class'=>'edit-submit margin'))}}
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