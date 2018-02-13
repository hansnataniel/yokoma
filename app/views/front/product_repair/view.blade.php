@extends('front.template.master')

@section('title')
	Kalim Item View
@stop

@section('head_additional')

@stop

@section('page_title')
	<a href="{{URL::to(Session::get('last_url'))}}">{{HTML::image('img/admin/back.png', '', array('class'=>'image-img'))}}</a> Import Kalim Item View
@stop

@section('help')
	<ul style="padding-left: 18px;">
		<li>Disini anda dapat melihat data Kalim Item secara keseluruhan.</li>
		<li>Gunakan tombol Edit untuk masuk ke halaman Edit Promo.</li>
	</ul>
@stop

@section('content')
	<section id="view-container">
		<div id="view-general-information">
			<div id="view-date">
				<span class="view-status">
					<span class="view-cell">Created at</span><span class="view-cell">:</span> <span class="view-cell text-blue">{{date('l, d F Y G:i:s', strtotime($productrepair->created_at))}}</span>
				</span>
				<span class="view-status">
					<span class="view-cell">Updated at</span><span class="view-cell">:</span> <span class="view-cell text-blue">{{date('l, d F Y G:i:s', strtotime($productrepair->updated_at))}}</span>
				</span>
			</div>
		</div>
		<table class="view-information" style="border-spacing: 0px;">
			<tr class="view-tr">
				<td class="view-td view-td-left">
					No. Invoice
				</td><!--
				--><td class="view-td view-td-right">
					{{$productrepair->no_invoice}}
				</td>
			</tr>
			<tr class="view-tr">
				<td class="view-td view-td-left">
					Date
				</td><!--
				--><td class="view-td view-td-right">
					{{date('d/m/Y', strtotime($productrepair->date))}}
				</td>
			</tr>
			<tr class="view-tr">
				<td class="view-td view-td-left">
					Costumer
				</td><!--
				--><td class="view-td view-td-right">
					{{$productrepair->customer->name}}
				</td>
			</tr>
			<tr class="view-tr">
				<td class="view-td view-td-left">
					Keterangan
				</td><!--
				--><td class="view-td view-td-right">
					{{$productrepair->keterangan != null ? $productrepair->keterangan : '-'}}
				</td>
			</tr>
		</table>
	</section>

	<section class="view-data-info">
		<header class="view-data-header">
			Kalim Item Details
		</header>
		<article class="view-data-ctn hasil-ajax-item">
			<table id="index-table" style="border-spacing: 0px;width: 60%;">
				<tr class="index-tr index-title">
					<th>#</th>
					<th>Product</th>
					<th>Quantity</th>
					<th></th>
				</tr>
				<?php
					$counter = 1;
					$total = 0;
				?>
				@foreach ($productrepairdetails as $productrepairdetail)
					<tr class='index-tr'>
						<td>{{$counter++}}</td>
						<td>{{$productrepairdetail->product->name}}</td>
						<td>{{digitGroup($productrepairdetail->qty)}}</td>
						<?php $total = $total + $productrepairdetail->qty ?>
						<td></td>
					</tr>
				@endforeach
				<tr class='index-tr'>
					<td></td>
					<td><strong>Total:</strong></td>
					<td>
						<strong>{{digitGroup($total)}}</strong>
					</td>
					<td></td>
				</tr>
			</table>
		</article>
	</section>
@stop