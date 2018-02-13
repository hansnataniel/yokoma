@extends('back.template.master')

@section('title')
	Pembelian Management
@stop

@section('head_additional')
	<style type="text/css">
		.icon-sub {
		    width: 120px;
		}

		.index-cancel td {
		    text-decoration: line-through;
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
	Pembelian Management
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
				{{Form::select('src_branch_id', $branch_options, '', array('class'=>'search-text select', 'placeholder'=>'Branch'))}}
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
		<li>Disini anda dapat melihat sekilas data dari purchases.</li>
		<li>Gunakan tombol New untuk masuk ke halaman New purchases.</li>
		<li>Gunakan tombol View di dalam tombol Action untuk masuk ke halaman View purchases.</li>
		<li>Gunakan tombol Edit di dalam tombol Action untuk masuk ke halaman Edit purchases.</li>
		<li>Gunakan tombol Delete di dalam tombol Action untuk menghapus purchases.</li>
	</ul>
@stop

@section('content')
	<section id="index-container">
		<header id="index-header">
			{{-- <a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/pembelian/create')}}" class="index-addnew">
				{{HTML::image('img/admin/icon_addnew.png', '', array('class'=>'image-header'))}}
				<span>Add Pembelian</span>
			</a> --}}
			<span id="index-header-right">
				{{$records_count}} records found
			</span>
		</header>
		<table id="index-table" style="border-spacing: 0px;">
			<tr class="index-tr index-title">
				<th>#</th>
				<th>Date</th>
				<th>Branch</th>
				<th>Form No.</th>
				<th>Price Total</th>
				{{-- <th>Status</th> --}}
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
			@foreach ($purchases as $purchase)
				<?php 
					$counter++; 
				?>
				@if($purchase->status != 'Canceled')
					<tr class='index-tr'>
						<td>{{$counter}}</td>
						<td>{{date('d/m/Y', strtotime($purchase->date))}}</td>
						<td>{{$purchase->branch->name}}</td>
						<td>{{$purchase->no_invoice}}</td>
						<td>Rp. {{digitGroup($purchase->price_total)}}</td>
						{{-- <td>
							@if($purchase->status == 'Waiting for Payment')
								<span style="color: orange;">{{$purchase->status}}</span>
							@elseif($purchase->status == 'Canceled')
								<span style="color: red;">{{$purchase->status}}</span>
							@elseif($purchase->status == 'Paid')
								<span style="color: green;">{{$purchase->status}}</span>
							@else
								<span style="color: blue;">{{$purchase->status}}</span>
							@endif
						</td> --}}
						<td class="icon">
							<div class="index-icon">
								{{HTML::image('img/admin/index_action.png', '', array('class'=>'icon-master'))}}
								<div class="index-sub-icon">
									<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/pembelian/view/' . $purchase->id)}}"><div class="icon-sub">{{HTML::image('img/admin/view.png')}} <span>View</span></div></a>
									{{-- <a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/pembelian/edit/' . $purchase->id)}}"><div class="icon-sub">{{HTML::image('img/admin/edit.png')}} <span>Edit</span></div></a> --}}
									{{-- <a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/pembelian/print-invoice/' . $purchase->id)}}" target="_blank"><div class="icon-sub">{{HTML::image('img/admin/printer.png')}} <span>Print Invoice</span></div></a> --}}
								</div>
							</div>
						</td>
					</tr>
				@else
					<tr class='index-tr index-cancel'>
						<td>{{$counter}}</td>
						<td>{{$purchase->no_invoice}}</td>
						<td>{{date('d/m/Y', strtotime($purchase->date))}}</td>
						<td>{{$purchase->customer->name}}</td>
						<td>Rp. {{digitGroup($purchase->paid)}}</td>
						<td>Rp. {{digitGroup($purchase->owed)}}</td>
						<td>Rp. {{digitGroup($purchase->paid - $purchase->owed)}}</td>
						<td>
							<span style="color: red;">Canceled</span>
						</td>
						<td class="icon">
							<div class="index-icon">
								{{HTML::image('img/admin/index_action.png', '', array('class'=>'icon-master'))}}
								<div class="index-sub-icon">
									<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/pembelian/view/' . $purchase->id)}}"><div class="icon-sub">{{HTML::image('img/admin/view.png')}} <span>View</span></div></a>
								</div>
							</div>
						</td>
					</tr>
				@endif
			@endforeach
		</table>
		{{$purchases->appends($criteria)->links()}}
	</section>
@stop