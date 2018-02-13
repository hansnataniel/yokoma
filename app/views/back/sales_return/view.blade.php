@extends('back.template.master')

@section('title')
	Sales Return View
@stop

@section('head_additional')

@stop

@section('page_title')
	<a href="{{URL::to(Session::get('last_url'))}}">{{HTML::image('img/admin/back.png', '', array('class'=>'image-img'))}}</a> Sales Return View
@stop

@section('help')
	<ul style="padding-left: 18px;">
		<li>Disini anda dapat melihat data Salesreturn secara keseluruhan.</li>
		<li>Gunakan tombol Edit untuk masuk ke halaman Edit Promo.</li>
	</ul>
@stop

@section('content')
	<section id="view-container">
		<div id="view-general-information">
			<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales-return/edit/' . $salesreturn->id)}}">{{HTML::image('img/admin/edit_view.png', '', array('id'=>'view-edit-button'))}}</a>
			<div id="view-date">
				<span class="view-status">
					<span class="view-cell">Created at</span><span class="view-cell">:</span> <span class="view-cell text-blue">{{date('l, d F Y G:i:s', strtotime($salesreturn->created_at))}}</span>
				</span>
				<span class="view-status">
					<span class="view-cell">Updated at</span><span class="view-cell">:</span> <span class="view-cell text-blue">{{date('l, d F Y G:i:s', strtotime($salesreturn->updated_at))}}</span>
				</span>
			</div>
			@if (file_exists(public_path() . '/usr/img/salesreturn/' . $salesreturn->id . '_' . Str::slug($salesreturn->name, '_') . '.jpg'))
				{{HTML::image('usr/img/salesreturn/' . $salesreturn->id . '_' . Str::slug($salesreturn->name, '_') . '.jpg', '', array('class'=>'view-photo'))}}
			@endif
		</div>
		<table class="view-information" style="border-spacing: 0px;">
			<tr class="view-tr">
				<td class="view-td view-td-left">
					No. Invoice
				</td><!--
				--><td class="view-td view-td-right">
					{{$salesreturn->no_invoice}}
				</td>
			</tr>
			<tr class="view-tr">
				<td class="view-td view-td-left">
					Date
				</td><!--
				--><td class="view-td view-td-right">
					{{date('d/m/Y', strtotime($salesreturn->date))}}
				</td>
			</tr>
			<tr class="view-tr">
				<td class="view-td view-td-left">
					Costumer
				</td><!--
				--><td class="view-td view-td-right">
					{{$salesreturn->sale->customer->name}}
				</td>
			</tr>
			<tr class="view-tr">
				<td class="view-td view-td-left">
					Price Total
				</td><!--
				--><td class="view-td view-td-right">
					Rp. {{digitGroup($salesreturn->price_total)}}
				</td>
			</tr>
		</table>
	</section>

	<section class="view-data-info">
		<header class="view-data-header">
			Sales Return Details
		</header>
		<article class="view-data-ctn hasil-ajax-item">
			<table id="index-table" style="border-spacing: 0px;width: 60%;">
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
				@foreach ($salesreturndetails as $salesreturndetail)
					<tr class='index-tr'>
						<td>{{$counter++}}</td>
						<td>{{$salesreturndetail->product->name}}</td>
						<td>Rp. {{digitGroup($salesreturndetail->price)}}</td>
						<td>{{digitGroup($salesreturndetail->qty)}}</td>
						<td style="text-align: right;">Rp. {{digitGroup($salesreturndetail->subtotal)}}</td>
						<td></td>
					</tr>
				@endforeach
				<tr class='index-tr'>
					<td></td>
					<td></td>
					<td></td>
					<td><strong>Total:</strong></td>
					<td style="text-align: right;">
						<strong>Rp. {{digitGroup($salesreturn->price_total)}}</strong>
					</td>
					<td></td>
				</tr>
			</table>
		</article>
	</section>
@stop