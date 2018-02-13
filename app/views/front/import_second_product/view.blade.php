@extends('front.template.master')

@section('title')
	Accu Mati Purchase View
@stop

@section('head_additional')

@stop

@section('page_title')
	<a href="{{URL::to(Session::get('last_url'))}}">{{HTML::image('img/admin/back.png', '', array('class'=>'image-img'))}}</a> 
	Accu Mati Purchase View
@stop

@section('help')
	<ul style="padding-left: 18px;">
		<li>Disini anda dapat melihat data Accu Mati Purchase secara keseluruhan.</li>
		<li>Gunakan tombol Edit untuk masuk ke halaman Edit Promo.</li>
	</ul>
@stop

@section('content')
	<section id="view-container">
		<div id="view-general-information">
			<a href="{{URL::to('import-second-product/edit/' . $importsecondproduct->id)}}">{{HTML::image('img/admin/edit_view.png', '', array('id'=>'view-edit-button'))}}</a>
			<div id="view-date">
				<span class="view-status">
					<span class="view-cell">Created at</span><span class="view-cell">:</span> <span class="view-cell text-blue">{{date('l, d F Y G:i:s', strtotime($importsecondproduct->created_at))}}</span>
				</span>
				<span class="view-status">
					<span class="view-cell">Updated at</span><span class="view-cell">:</span> <span class="view-cell text-blue">{{date('l, d F Y G:i:s', strtotime($importsecondproduct->updated_at))}}</span>
				</span>
			</div>
		</div>
		<table class="view-information" style="border-spacing: 0px;">
			<tr class="view-tr">
				<td class="view-td view-td-left">
					No. Invoice
				</td><!--
				--><td class="view-td view-td-right">
					{{$importsecondproduct->no_invoice}}
				</td>
			</tr>
			<tr class="view-tr">
				<td class="view-td view-td-left">
					Date
				</td><!--
				--><td class="view-td view-td-right">
					{{date('d/m/Y', strtotime($importsecondproduct->date))}}
				</td>
			</tr>
			<tr class="view-tr">
				<td class="view-td view-td-left">
					Costumer
				</td><!--
				--><td class="view-td view-td-right">
					{{$importsecondproduct->customer->name}}
				</td>
			</tr>
			<tr class="view-tr">
				<td class="view-td view-td-left">
					Paid
				</td><!--
				--><td class="view-td view-td-right">
					Rp. {{digitGroup($importsecondproduct->price_total)}}
				</td>
			</tr>
		</table>
	</section>

	<section class="view-data-info">
		<header class="view-data-header">
			Accu Mati Purchase Details
		</header>
		<article class="view-data-ctn hasil-ajax-item">
			<table id="index-table" style="border-spacing: 0px;width: 60%;">
				<tr class="index-tr index-title">
					<th>#</th>
					<th>Item</th>
					<th>Price</th>
					<th>Quantity</th>
					<th style="text-align: right;">Subtotal</th>
					<th></th>
				</tr>
				<?php
					$counter = 1;
				?>
				@foreach ($importsecondproductdetails as $importsecondproductdetail)
					<tr class='index-tr'>
						<td>{{$counter++}}</td>
						<td>{{$importsecondproductdetail->product->name}}</td>
						<td>Rp. {{digitGroup($importsecondproductdetail->price)}}</td>
						<td>{{digitGroup($importsecondproductdetail->qty)}}</td>
						<td style="text-align: right;">Rp. {{digitGroup($importsecondproductdetail->price * $importsecondproductdetail->qty)}}</td>
						<td></td>
					</tr>
				@endforeach
				<tr class='index-tr'>
					<td></td>
					<td></td>
					<td></td>
					<td><strong>Total:</strong></td>
					<td style="text-align: right;">
						<strong>Rp. {{digitGroup($importsecondproduct->price_total)}}</strong>
					</td>
					<td></td>
				</tr>
			</table>
		</article>
	</section>
@stop