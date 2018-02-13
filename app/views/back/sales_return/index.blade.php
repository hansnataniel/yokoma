@extends('back.template.master')

@section('title')
	Sales Return Management
@stop

@section('head_additional')
	<style type="text/css">
		.icon-sub {
		    width: 100px;
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
	Sales Return Management
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
				{{Form::select('order_by', array('id'=>'Additional Time', 'date'=>'Date'), null, array('class'=>'search-text select'))}}
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
		<li>Disini anda dapat melihat sekilas data dari Sales Return.</li>
		<li>Gunakan tombol New untuk masuk ke halaman New Sales Return.</li>
		<li>Gunakan tombol View di dalam tombol Action untuk masuk ke halaman View Sales Return.</li>
		<li>Gunakan tombol Edit di dalam tombol Action untuk masuk ke halaman Edit Sales Return.</li>
		<li>Gunakan tombol Delete di dalam tombol Action untuk menghapus Sales Return.</li>
	</ul>
@stop

@section('content')
	<section id="index-container">
		<header id="index-header">
			<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales-return/create')}}" class="index-addnew">
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
				<th>No. Invoice</th>
				<th>Customer</th>
				<th>Date</th>
				<th>Price Total</th>
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
			@foreach ($salesreturns as $salesreturn)
				<?php 
					$counter++; 
				?>
				<tr class='index-tr'>
					<td>{{$counter}}</td>
					<td>{{$salesreturn->no_invoice}}</td>
					<td>{{$salesreturn->sale->customer->name}}</td>
					<td>{{date('d/m/Y', strtotime($salesreturn->date))}}</td>
					<td>Rp. {{digitGroup($salesreturn->price_total)}}</td>
					<td class="icon">
						<div class="index-icon">
							{{HTML::image('img/admin/index_action.png', '', array('class'=>'icon-master'))}}
							<div class="index-sub-icon">
								<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales-return/view/' . $salesreturn->id)}}"><div class="icon-sub">{{HTML::image('img/admin/view.png')}} <span>View</span></div></a>
								<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales-return/print-invoice/' . $salesreturn->id)}}" target="_blank"><div class="icon-sub">{{HTML::image('img/admin/printer.png')}} <span>Print Invoice</span></div></a>
								<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales-return/edit/' . $salesreturn->id)}}"><div class="icon-sub">{{HTML::image('img/admin/edit.png')}} <span>Edit</span></div></a>
								<div class="icon-sub delete">{{HTML::image('img/admin/delete.png')}} <span>Delete</span></div>
								<section class="blur">
									<div class="blur-question">
										<span class="blur-text">
											Do you really want to delete this Sale Return?
										</span>
										<table>
											<tr>
												<td>
													Name
												</td>
												<td>
													<span>
														:
													</span>
													{{$salesreturn->name}}
												</td>
											</tr>
										</table>
										<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales-return/delete/' . $salesreturn->id . '?_token=' . Session::token())}}">
											{{Form::button('Yes', array('class'=>'blur-submit blur-left'))}}
										</a>
										{{Form::button('Cancel', array('class'=>'blur-submit cancel'))}}
									</div>
								</section>
							</div>
						</div>
					</td>
				</tr>
			@endforeach
		</table>
		{{$salesreturns->appends($criteria)->links()}}
	</section>
@stop