@extends('back.template.master')

@section('title')
	Pembelian View
@stop

@section('head_additional')

@stop

@section('page_title')
	<a href="{{URL::to(Session::get('last_url'))}}">{{HTML::image('img/admin/back.png', '', array('class'=>'image-img'))}}</a> Pembelian View
@stop

@section('help')
	<ul style="padding-left: 18px;">
		<li>Disini anda dapat melihat data Pembelian secara keseluruhan.</li>
		<li>Gunakan tombol Edit untuk masuk ke halaman Edit Promo.</li>
	</ul>
@stop

@section('content')
	<section id="view-container">
		<div id="view-general-information">
			{{-- <a href="{{URL::to('pembelian/edit/' . $purchase->id)}}"><div class="icon-sub">{{HTML::image('img/admin/edit.png')}} <span>Edit</span></div></a> --}}
			<div id="view-date">
				<span class="view-status">
					<span class="view-cell">Created at</span><span class="view-cell">:</span> <span class="view-cell text-blue">{{date('l, d F Y G:i:s', strtotime($purchase->created_at))}}</span>
				</span>
				<span class="view-status">
					<span class="view-cell">Updated at</span><span class="view-cell">:</span> <span class="view-cell text-blue">{{date('l, d F Y G:i:s', strtotime($purchase->updated_at))}}</span>
				</span>
			</div>
			@if (file_exists(public_path() . '/usr/img/purchase/' . $purchase->id . '_' . Str::slug($purchase->name, '_') . '.jpg'))
				{{HTML::image('usr/img/purchase/' . $purchase->id . '_' . Str::slug($purchase->name, '_') . '.jpg', '', array('class'=>'view-photo'))}}
			@endif
		</div>
		<table class="view-information" style="border-spacing: 0px;">
			<tr class="view-tr">
				<td class="view-td view-td-left">
					Form No.
				</td><!--
				--><td class="view-td view-td-right">
					{{$purchase->no_invoice}}
				</td>
			</tr>
			<tr class="view-tr">
				<td class="view-td view-td-left">
					Date
				</td><!--
				--><td class="view-td view-td-right">
					{{date('d/m/Y', strtotime($purchase->date))}}
				</td>
			</tr>
			<tr class="view-tr">
				<td class="view-td view-td-left">
					Price Total
				</td><!--
				--><td class="view-td view-td-right">
					Rp. {{digitGroup($purchase->price_total)}}
				</td>
			</tr>
			{{-- <tr class="view-tr">
				<td class="view-td view-td-left">
					Status
				</td><!--
				--><td class="view-td view-td-right">
					@if($purchase->status == 'Waiting for payment')
						<span style="color: orange;">{{$purchase->status}}</span>
					@elseif($purchase->status == 'Canceled')
						<span style="color: red;">{{$purchase->status}}</span>
					@elseif($purchase->status == 'Paid')
						<span style="color: green;">{{$purchase->status}}</span>
					@else
						<span style="color: blue;">{{$purchase->status}}</span>
					@endif
				</td>
			</tr> --}}
		</table>
	</section>

	<section class="view-data-info">
		<header class="view-data-header">
			Pembelian Details
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
				@foreach ($purchasedetails as $purchasedetail)
					@if($purchasedetail->product->type == 'Product')
						<tr class='index-tr'>
							<td>{{$counter++}}</td>
							<td>{{$purchasedetail->product->name}}</td>
							<td>Rp. {{digitGroup($purchasedetail->price)}}</td>
							<td>{{digitGroup($purchasedetail->qty)}}</td>
							<td style="text-align: right;">Rp. {{digitGroup($purchasedetail->subtotal)}}</td>
							<td></td>
						</tr>
					@endif
				@endforeach
				<tr class='index-tr'>
					<td></td>
					<td></td>
					<td></td>
					<td><strong>Total Pembelian:</strong></td>
					<td style="text-align: right;">
						<strong>Rp. {{digitGroup($purchase->price_total)}}</strong>
					</td>
					<td></td>
				</tr>
			</table>
		</article>
	</section>
@stop