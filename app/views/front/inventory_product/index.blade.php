@extends('front.template.master')

@section('title')
	Posisi Stock Akhir Management
@stop

@section('head_additional')

@stop

@section('page_title')
	Posisi Stock Akhir Management
@stop

@section('search')
	{{Form::open(array('URL' => URL::current(), 'method' => 'GET'))}}
		<div class="search-group">
			<div class="search-title">Search By</div>
			<div class='search-input'>
				{{Form::text('src_name', '', array('class'=>'search-text', 'placeholder'=>'Name'))}}
			</div>
			<div class='search-input'>
				{{Form::select('src_is_active', array(''=>'-- Active Status --', '0'=>'Not Active', '1'=>'Active'), null, array('class'=>'search-text select'))}}
			</div>
			<div class="separator"></div>
		</div>
		<div class="search-group">
			<div class="search-title">Sort By</div>
			<div class='search-input'>
				{{Form::select('order_by', array('id'=>'Additional Time', 'name'=>'Name', 'email'=>'Email', 'is_active'=>'Active Status'), null, array('class'=>'search-text select'))}}
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
		<li>Disini anda dapat melihat sekilas data dari inventory Posisi Stock Akhir.</li>
		<li>Gunakan tombol New untuk masuk ke halaman New inventory Posisi Stock Akhir.</li>
		<li>Gunakan tombol View di dalam tombol Action untuk masuk ke halaman View inventory Posisi Stock Akhir.</li>
		<li>Gunakan tombol Edit di dalam tombol Action untuk masuk ke halaman Edit inventory Posisi Stock Akhir.</li>
		<li>Gunakan tombol Delete di dalam tombol Action untuk menghapus inventory Posisi Stock Akhir.</li>
	</ul>
@stop

@section('content')
	<section id="index-container">
		<header id="index-header">
			<span id="index-header-right">
				{{$records_count}} records found
			</span>
		</header>
		<table id="index-table" style="border-spacing: 0px;">
			<tr class="index-tr index-title">
				<th>#</th>
				<th>Name</th>
				<th>Price</th>
				<th>Stock</th>
				<th>Active Status</th>
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
			@foreach ($products as $product)
				<?php 
					$counter++; 
					$inventory = Inventorygood::where('branch_id', '=', Auth::user()->get()->branch_id)->where('product_id', '=', $product->id)->orderBy('date', 'desc')->orderBy('id', 'desc')->first();
					if($inventory != null)
					{
						$stock = $inventory->final_stock;
					}
					else
					{
						$stock = 0;
					}
				?>
				<tr class='index-tr'>
					<td>{{$counter}}</td>
					<td>{{$product->name}}</td>
					<td>Rp. {{digitGroup($product->price)}}</td>
					<td>{{$stock}}</td>
					<td>{{$product->is_active == true ? "<span class='text-green'>Yes</span>":"<span class='text-red'>No</span>"}}</td>
				</tr>
			@endforeach
		</table>
		{{$products->appends($criteria)->links()}}
	</section>
@stop