@extends('back.template.master')

@section('title')
	Penerimaan Piutang View
@stop

@section('head_additional')

@stop

@section('page_title')
	<a href="{{URL::to(Session::get('last_url'))}}">{{HTML::image('img/admin/back.png', '', array('class'=>'image-img'))}}</a> Penerimaan Piutang View
@stop

@section('help')
	<ul style="padding-left: 18px;">
		<li>Disini anda dapat melihat data Penerimaan Piutang secara keseluruhan.</li>
	</ul>
@stop

@section('content')
	<section id="view-container">
		<div id="view-general-information">
			<div id="view-date">
				<span class="view-status">
					<span class="view-cell">Created at</span><span class="view-cell">:</span> <span class="view-cell text-blue">{{date('l, d F Y G:i:s', strtotime($payment->created_at))}}</span>
				</span>
				<span class="view-status">
					<span class="view-cell">Updated at</span><span class="view-cell">:</span> <span class="view-cell text-blue">{{date('l, d F Y G:i:s', strtotime($payment->updated_at))}}</span>
				</span>
			</div>
			@if (file_exists(public_path() . '/usr/img/payment/' . $payment->id . '_' . Str::slug($payment->name, '_') . '.jpg'))
				{{HTML::image('usr/img/payment/' . $payment->id . '_' . Str::slug($payment->name, '_') . '.jpg', '', array('class'=>'view-photo'))}}
			@endif
		</div>
		<table class="view-information" style="border-spacing: 0px;">
			<tr class="view-tr">
				<td class="view-td view-td-left">
					Form No
				</td><!--
				--><td class="view-td view-td-right">
					{{$payment->no_invoice}}
				</td>
			</tr>
			<tr class="view-tr">
				<td class="view-td view-td-left">
					Date
				</td><!--
				--><td class="view-td view-td-right">
					{{date('d/m/Y', strtotime($payment->date))}}
				</td>
			</tr>
			<tr class="view-tr">
				<td class="view-td view-td-left">
					Costumer
				</td><!--
				--><td class="view-td view-td-right">
					{{$payment->customer->name}}
				</td>
			</tr>
			<tr class="view-tr">
				<td class="view-td view-td-left">
					Metode Pembayaran
				</td><!--
				--><td class="view-td view-td-right">
					{{$payment->metode_pembayaran}}
				</td>
			</tr>
			@if($payment->metode_pembayaran == 'Giro')
				<tr class="view-tr">
					<td class="view-td view-td-left">
						Tgl. Pencairan
					</td><!--
					--><td class="view-td view-td-right">
						{{date('d F Y', strtotime($payment->tgl_pencairan))}}
					</td>
				</tr>
			@endif
			<tr class="view-tr">
				<td class="view-td view-td-left">
					Keterangan
				</td><!--
				--><td class="view-td view-td-right">
					{{$payment->keterangan}}
				</td>
			</tr>
			<tr class="view-tr">
				<td class="view-td view-td-left">
					Paid
				</td><!--
				--><td class="view-td view-td-right">
					Rp. {{digitGroup($payment->payment_total)}}
				</td>
			</tr>
		</table>
	</section>

	<section class="view-data-info">
		<header class="view-data-header">
			Payment Details
		</header>
		<article class="view-data-ctn hasil-ajax-item">
			<table id="index-table" style="border-spacing: 0px;width: 60%;">
				<tr class="index-tr index-title">
					<th>#</th>
					<th>Sales (No. Invoice)</th>
					<th>Paid</th>
					<th></th>
				</tr>
				<?php
					$counter = 1;
				?>
				@foreach ($paymentdetails as $paymentdetail)
					<tr class='index-tr'>
						<td>{{$counter++}}</td>
						<td>{{$paymentdetail->sale->no_invoice}} | <a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales/view/' . $paymentdetail->sale->id)}}" target="_blank">
							<i>View Sales</i></td>
						</a>
						<td>Rp. {{digitGroup($paymentdetail->price_payment)}}</td>
						<td></td>
					</tr>
				@endforeach
				<tr class='index-tr'>
					<td></td>
					<td><strong>Total:</strong></td>
					<td>
						<strong>Rp. {{digitGroup($payment->payment_total)}}</strong>
					</td>
					<td></td>
				</tr>
			</table>
		</article>
	</section>
@stop