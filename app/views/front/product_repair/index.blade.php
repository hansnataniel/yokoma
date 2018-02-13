@extends('front.template.master')

@section('title')
	Klaim Item Management
@stop

@section('head_additional')
	<style type="text/css">
		.icon-sub {
		    width: 110px;
		}
	</style>

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
		});
	</script>
@stop

@section('page_title')
	Klaim Item Management
@stop

@section('search')
	{{Form::open(array('URL' => URL::current(), 'method' => 'GET'))}}
		<div class="search-group">
			<div class="search-title">Search By</div>
			<div class='search-input'>
				{{Form::text('src_no_invoice', '', array('class'=>'search-text', 'placeholder'=>'No. Invoice'))}}
			</div>
			<div class='search-input'>
				{{Form::text('src_date', '', array('class'=>'search-text datetimepicker', 'placeholder'=>'Date'))}}
			</div>
			<div class='search-input'>
				{{Form::select('src_customer_id', $customer_options, '', array('class'=>'search-text select', 'placeholder'=>'Customer'))}}
			</div>
			<div class="separator"></div>
		</div>
		<div class="search-group">
			<div class="search-title">Sort By</div>
			<div class='search-input'>
				{{Form::select('order_by', array('id'=>'Additional Time', 'name'=>'Name'), null, array('class'=>'search-text select'))}}
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
		<li>Disini anda dapat melihat sekilas data dari Klaim Item.</li>
		<li>Gunakan tombol New untuk masuk ke halaman New Klaim Item.</li>
		<li>Gunakan tombol View di dalam tombol Action untuk masuk ke halaman View Klaim Item.</li>
		<li>Gunakan tombol Edit di dalam tombol Action untuk masuk ke halaman Edit Klaim Item.</li>
		<li>Gunakan tombol Delete di dalam tombol Action untuk menghapus Klaim Item.</li>
	</ul>
@stop

@section('content')
	<section id="index-container">
		<header id="index-header">
			<a href="{{URL::to('product-repair/create')}}" class="index-addnew">
				{{HTML::image('img/admin/icon_addnew.png', '', array('class'=>'image-header'))}}
				<span>Add New</span>
			</a>
			<span id="index-header-right">
				{{$records_count}} records found
			</span>
		</header>
		<table id="index-table" style="border-spacing: 0px;">
			<tr class="index-tr index-title">
				<th>#</th>
				<th>Form No</th>
				<th>Date</th>
				<th>Customer</th>
				<th>Keterangan</th>
				<th>Status</th>
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
			@foreach ($productrepairs as $productrepair)
				<?php 
					$counter++; 
				?>
				<tr class='index-tr'>
					<td>{{$counter}}</td>
					<td>{{$productrepair->no_invoice}}</td>
					<td>{{date('d/m/Y', strtotime($productrepair->date))}}</td>
					<td>{{$productrepair->customer->name}}</td>
					<td>{{$productrepair->keterangan != null ? $productrepair->keterangan : '-'}}</td>
					<td>
						@if($productrepair->status == 'Repair')
							<span style="color: red;">{{$productrepair->status}}</span>
						@else
							<span style="color: green;">{{$productrepair->status}}</span>
						@endif
					</td>
					<td class="icon">
						<div class="index-icon">
							{{HTML::image('img/admin/index_action.png', '', array('class'=>'icon-master'))}}
							<div class="index-sub-icon">
								<a href="{{URL::to('product-repair/view/' . $productrepair->id)}}"><div class="icon-sub">{{HTML::image('img/admin/view.png')}} <span>View</span></div></a>
								@if($productrepair->status != 'Finish Repair')
									<a href="{{URL::to('product-repair/finish-repair/' . $productrepair->id)}}"><div class="icon-sub">{{HTML::image('img/admin/check.png')}} <span>Finish Repair</span></div></a>
								@endif
							</div>
						</div>
					</td>
				</tr>
			@endforeach
		</table>
		{{$productrepairs->appends($criteria)->links()}}
	</section>
@stop