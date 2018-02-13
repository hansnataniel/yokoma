@extends('back.template.master')

@section('title')
	Penyesuaian Stock View
@stop

@section('head_additional')

@stop

@section('page_title')
	<a href="{{URL::to(Session::get('last_url'))}}">{{HTML::image('img/admin/back.png', '', array('class'=>'image-img'))}}</a> Penyesuaian Stock View
@stop

@section('help')
	<ul style="padding-left: 18px;">
		{{-- <li>Disini anda dapat melihat data Stockgood secara keseluruhan.</li> --}}
		{{-- <li>Gunakan tombol Edit untuk masuk ke halaman Edit Promo.</li> --}}
	</ul>
@stop

@section('content')
	<section id="view-container">
		<div id="view-general-information">
			<div id="view-date">
				<span class="view-status">
					<span class="view-cell">Created at</span><span class="view-cell">:</span> <span class="view-cell text-blue">{{date('l, d F Y G:i:s', strtotime($stockgood->created_at))}}</span>
				</span>
				<span class="view-status">
					<span class="view-cell">Updated at</span><span class="view-cell">:</span> <span class="view-cell text-blue">{{date('l, d F Y G:i:s', strtotime($stockgood->updated_at))}}</span>
				</span>
			</div>
			@if (file_exists(public_path() . '/usr/img/stockgood/' . $stockgood->id . '_' . Str::slug($stockgood->name, '_') . '.jpg'))
				{{HTML::image('usr/img/stockgood/' . $stockgood->id . '_' . Str::slug($stockgood->name, '_') . '.jpg', '', array('class'=>'view-photo'))}}
			@endif
		</div>
		<table class="view-information" style="border-spacing: 0px;">
			<tr class="view-tr">
				<td class="view-td view-td-left">
					Form No.
				</td><!--
				--><td class="view-td view-td-right">
					{{$stockgood->form_no}}
				</td>
			</tr>
			<tr class="view-tr">
				<td class="view-td view-td-left">
					Branch Name
				</td><!--
				--><td class="view-td view-td-right">
					{{$stockgood->branch->name}}
				</td>
			</tr>
			<tr class="view-tr">
				<td class="view-td view-td-left">
					Date
				</td><!--
				--><td class="view-td view-td-right">
					{{date('d/m/Y', strtotime($stockgood->date))}}
				</td>
			</tr>
			<tr class="view-tr">
				<td class="view-td view-td-left">
					Note
				</td><!--
				--><td class="view-td view-td-right">
					@if($stockgood->note == null)
						-
					@else
						{{$stockgood->note}}
					@endif
				</td>
			</tr>
		</table>
	</section>

	<?php $items = Stockgooddetail::where('stockgood_id', '=', $stockgood->id)->get(); ?>

	<section class="view-data-info">
		<header class="view-data-header">
			Item Details
		</header>
		<article class="view-data-ctn hasil-ajax-item">
			<table id="index-table" style="border-spacing: 0px;width: 60%;">
				<tr class="index-tr index-title">
					<th>#</th>
					<th>Product</th>
					<th>Type</th>
					<th>Amount</th>
				</tr>
				<?php
					$counter = 1;
				?>
				@foreach ($items as $item)
					<tr class='index-tr'>
						<td>{{$counter++}}</td>
						<td>{{$item->product->name}}</td>
						<td>{{$item->type == 1 ? "Stock In":"Stock Out"}}</td>
						<td>{{digitGroup($item->amount)}}</td>
					</tr>
				@endforeach
			</table>
		</article>
	</section>
@stop