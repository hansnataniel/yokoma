@extends('front.template.master')

@section('title')
	Pembulatan Nota Management
@stop

@section('head_additional')

@stop

@section('page_title')
	Pembulatan Nota Management
@stop

@section('search')
	{{Form::open(array('URL' => URL::current(), 'method' => 'GET'))}}
		<div class="search-group">
			<div class="search-title">Search By</div>
			<div class='search-input'>
				{{Form::select('src_sale_id', $sale_options, null, array('class'=>'search-text select'))}}
			</div>
			<div class='search-input'>
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
		<li>Disini anda dapat melihat sekilas data dari pembulatan-nota.</li>
		<li>Gunakan tombol New untuk masuk ke halaman New pembulatan-nota.</li>
		<li>Gunakan tombol View di dalam tombol Action untuk masuk ke halaman View pembulatan-nota.</li>
		<li>Gunakan tombol Edit di dalam tombol Action untuk masuk ke halaman Edit pembulatan-nota.</li>
		<li>Gunakan tombol Delete di dalam tombol Action untuk menghapus pembulatan-nota.</li>
	</ul>
@stop

@section('content')
	<section id="index-container">
		<header id="index-header">
			<a href="{{URL::to('pembulatan-nota/create')}}" class="index-addnew">
				{{HTML::image('img/admin/icon_addnew.png', '', array('class'=>'image-header'))}}
				<span>Add New</span>
			</a>
			<span id="index-header-right">
				{{-- {{$records_count}} records found --}}
			</span>
		</header>
		<table id="index-table" style="border-spacing: 0px;">
			<tr class="index-tr index-title">
				<th>#</th>
				<th>Date</th>
				<th>No. Nota</th>
				<th>Pembulatan</th>
				<th>Hasil Pembulatan</th>
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
			@foreach ($pembulatans as $pembulatan)
				@if($pembulatan->sale->branch_id == Auth::user()->get()->branch_id)
					<?php 
						$counter++; 
					?>
					<tr class='index-tr'>
						<td>{{$counter}}</td>
						<td>{{date('d/m/Y', strtotime($pembulatan->created_at))}}</td>
						<td>{{$pembulatan->sale->no_invoice}}</td>
						<td>Rp. {{digitGroup($pembulatan->price)}}</td>
						<td>Rp. {{digitGroup($pembulatan->sale->paid)}}</td>
					</tr>
				@endif
			@endforeach
		</table>
		{{-- {{$pembulatans->appends($criteria)->links()}} --}}
	</section>
@stop