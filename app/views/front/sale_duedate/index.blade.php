@extends('front.template.master')

@section('title')
	Sales Due Date
@stop

@section('head_additional')
	<style type="text/css">
		a.index-addnew {
	        padding-top: 5px;
		    padding-bottom: 5px;
		    top: 0;
		    height: 17px;
		    margin: auto;
		}

		.icon-sub {
		    background: #f7961f;
		    width: 100px;
		}
	</style>
@stop

@section('page_title')
	Sales Due Date
@stop

@section('search')
	{{Form::open(array('URL' => URL::current(), 'method' => 'GET'))}}
		<div class="search-group">
			<div class="search-title">Search By</div>
			<div class='search-input'>
				{{Form::text('src_name', '', array('class'=>'search-text', 'placeholder'=>'Name'))}}
			</div>
			<div class="separator"></div>
		</div>
		<div class="search-group">
			<div class="search-title">Sort By</div>
			<div class='search-input'>
				{{Form::select('order_by', array('id'=>'Additional Time'), null, array('class'=>'search-text select'))}}
			</div>
			<div class='search-input'>
				<div class="search-radio-group">
					{{Form::radio('order_method', 'asc', true, array('class'=>'search-radio'))}}
					{{HTML::image('img/admin/sort1.png', '', array('class'=>'search-radio-image'))}}
				</div>
				<div class="search-radio-group">
					{{Form::radio('order_method', 'desc', false, array('class'=>'search-radio'))}}
					{{HTML::image('img/admin/sort2.png', '', array('class'=>'search-radio-image'))}}
				</div>
			</div>
		</div>
		<div class='search-input'>
			{{Form::submit('Search', array('class'=>'search-button'))}}
		</div>
	{{Form::close()}}
@stop

@section('help')
	<ul style="padding-left: 18px;">
		<li>Disini anda dapat melihat data dari Account Receivable.</li>
	</ul>
@stop

@section('content')
	<section id="index-container">
		<header id="index-header">
			<span id="index-header-right">
			</span>
		</header>
		<table id="index-table" style="border-spacing: 0px;">
			<tr class="index-tr index-title">
				<th>#</th>
				<th>Name Customer</th>
				<th>Address</th>
				<th style="text-align: right;">Amont to Pay</th>
				<th style="text-align: right;">Total Paid</th>
				<th style="text-align: right;">Total Owed</th>
				<th></th>
			</tr>
			<?php
				if (Input::has('page'))
				{
					$counter = (Input::get('page')-1) * $per_page;
				}
				else
				{
					$counter = 0;
				}
			?>
			@foreach ($sales as $sale)
				<?php 
					$counter++; 
					$receivable = $sale->paid;
					$paymentdetails = Paymentdetail::where('sale_id', '=', $sale->id)->get();
					foreach($paymentdetails as $paymentdetail) 
					{
						$receivable = $receivable - $paymentdetail->price_payment;
					}
				?>
				@if(count($sales) != 0)
					<tr class='index-tr'>
						<td>{{$counter}}</td>
						<td>{{$sale->customer->name}}</td>
						<td>{{$sale->customer->address}}</td>
						<td style="text-align: right;">Rp. {{digitGroup($sale->paid)}}</td>
						<td style="text-align: right;">Rp. {{digitGroup($sale->paid - $receivable)}}</td>
						<td style="text-align: right;">Rp. {{digitGroup($receivable)}}</td>
						<td class="icon">
							<div class="index-icon">
								{{HTML::image('img/admin/index_action.png', '', array('class'=>'icon-master'))}}
								<div class="index-sub-icon">
									<a href="{{URL::to('sales/view/' . $sale->id)}}" target="_blank"><div class="icon-sub">{{HTML::image('img/admin/view.png')}} <span>View Sales</span></div></a>
									<a href="{{URL::to('payment/create')}}" target="_blank"><div class="icon-sub">{{HTML::image('img/admin/check.png')}} <span>Payment</span></div></a>
								</div>
							</div>
						</td>
					</tr>
				@endif
			@endforeach
		</table>
		{{$sales->appends($criteria)->links()}}
	</section>
@stop