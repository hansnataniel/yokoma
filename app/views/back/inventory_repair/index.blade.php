@extends('back.template.master')

@section('title')
	Inventory Item Repair Management
@stop

@section('head_additional')
	<script type="text/javascript">
		$(document).ready(function(){
			$('.select-branch').live('change', function(){
				var branchId = $(this).val();
				window.location.replace("{{URL::to(Crypt::decrypt($setting->admin_url) . '/inventory-repair/index')}}/" + branchId);
			});
		});
	</script>

	<style type="text/css">
		.icon-sub {
		    width: 120px;
		}
	</style>
@stop

@section('page_title')
	Inventory Item Repair Management
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
		<li>Disini anda dapat melihat sekilas data dari Inventory Item Repair.</li>
	</ul>
@stop

@section('content')
	<section id="index-container">
		<header id="index-header">
			<div class='search-input'>
				Branch Name
				{{Form::select('branch_id', $branch_options, $branch->id, array('class'=>'search-text select select-branch'))}}
			</div>
			<span id="index-header-right">
				{{$records_count}} records found
			</span>
		</header>
		<table id="index-table" style="border-spacing: 0px;">
			<tr class="index-tr index-title">
				<th>#</th>
				<th>Name</th>
				<th>Stock</th>
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
					$inventory = Inventoryrepair::where('branch_id', '=', $branch->id)->where('product_id', '=', $product->id)->orderBy('date', 'desc')->orderBy('id', 'desc')->first();
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
					<td>{{$stock}}</td>
				</tr>
			@endforeach
		</table>
		{{$products->appends($criteria)->links()}}
	</section>
@stop