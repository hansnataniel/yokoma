@extends('back.template.master')

@section('title')
	Penyesuaian Stock
@stop

@section('head_additional')
	{{HTML::style('css/jquery.datetimepicker.css')}}
	{{HTML::script('js/jquery.datetimepicker.js')}}

	<script type="text/javascript">
		$(function(){
			$('.datetimepicker').datetimepicker({
				scrollMonth: false,
				timepicker: false,
				maxDate: 'now',
				format: 'Y-m-d'
			});
		});
		$(document).ready(function(){
			$('.select-branch').live('change', function(){
				var branchId = $(this).val();
				window.location.replace("{{URL::to(Crypt::decrypt($setting->admin_url) . '/inventory-update/index')}}/" + branchId);
			});
		});
	</script>

	<style type="text/css">
		.icon-sub {
		    width: 110px;
		}
	</style>
@stop

@section('page_title')
	Penyesuaian Stock
@stop

@section('search')
	{{Form::open(array('URL' => URL::current(), 'method' => 'GET'))}}
		<div class="search-group">
			<div class="search-title">Search By</div>
			<div class='search-input'>
				{{Form::text('src_date', '', array('class'=>'search-text', 'placeholder'=>'Date'))}}
			</div>
			<div class='search-input'>
				{{Form::select('src_product_id', array(''=>'-- Active Status --', '0'=>'Not Active', '1'=>'Active'), null, array('class'=>'search-text select'))}}
			</div>
			<div class="separator"></div>
		</div>
		<div class="search-group">
			<div class="search-title">Sort By</div>
			<div class='search-input'>
				{{Form::select('order_by', array('id'=>'Created At', 'date'=>'Date'), null, array('class'=>'search-text select'))}}
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
		<li>Disini Anda dapat melihat sekilas data dari Update stok dari yang terbaru.</li>
		<li>Pilih Branch untuk melihat ke data cabang lain</li>
	</ul>
@stop

@section('content')
	<section id="index-container">
		<header id="index-header">
			<div class='search-input' style="margin-left: 205px;">
				Branch Name
				{{Form::select('branch_id', $branch_options, $branch->id, array('class'=>'search-text select select-branch'))}}
			</div>
			<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/inventory-update/create/' . $branch->id)}}" class="index-addnew">
				{{HTML::image('img/admin/icon_addnew.png', '', array('class'=>'image-header'))}}
				<span>Add Penyesuaian Stock</span>
			</a>
			<span id="index-header-right">
				{{$records_count}} records found
			</span>
		</header>
		<table id="index-table" style="border-spacing: 0px;">
			<tr class="index-tr index-title">
				<th>#</th>
				<th>Form No.</th>
				<th>Date</th>
				<th>Note</th>
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
			@foreach ($stockgoods as $stockgood)
				<?php 
					$counter++; 
				?>
				<tr class='index-tr'>
					<td>{{$counter}}</td>
					<td>{{$stockgood->form_no}}</td>
					<td>{{date('d/m/Y', strtotime($stockgood->date))}}</td>
					<td>
						@if($stockgood->note != null)
							{{$stockgood->note}}
						@else
							-
						@endif
					</td>
					<td class="icon">
						<div class="index-icon">
							{{HTML::image('img/admin/index_action.png', '', array('class'=>'icon-master'))}}
							<div class="index-sub-icon">
								<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/inventory-update/view/' . $stockgood->id)}}"><div class="icon-sub">{{HTML::image('img/admin/view.png')}} <span>View</span></div></a>
							</div>
						</div>
					</td>
				</tr>
			@endforeach
		</table>
		{{$stockgoods->appends($criteria)->links()}}
	</section>
@stop