@extends('back.template.master')

@section('title')
	Nota Management
@stop

@section('head_additional')
	<style type="text/css">
		.icon-sub {
		    background: #f7961f;
		    width: 130px;
		}

		.index-cancel td {
		    text-decoration: line-through;
		}

		/*a.index-addnew {
		    left: auto;
		    right: 0;
		}

		h2 {
		    display: inline-block;
		    vertical-align: top;
		    margin: 0;
		}

		.search-input {
		    display: inline-block;
		    vertical-align: top;
		    margin-left: 30px;
		}*/
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

		$(document).ready(function(){
			$('.select-branch').live('change', function(){
				var branchId = $(this).val();
				window.location.replace("{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales/index')}}/" + branchId);
			});
		});
	</script>
@stop

@section('page_title')
	Nota Management
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
		<li>Disini anda dapat melihat sekilas data dari sales.</li>
		<li>Gunakan tombol New untuk masuk ke halaman New sales.</li>
		<li>Gunakan tombol View di dalam tombol Action untuk masuk ke halaman View sales.</li>
		<li>Gunakan tombol Edit di dalam tombol Action untuk masuk ke halaman Edit sales.</li>
		<li>Gunakan tombol Delete di dalam tombol Action untuk menghapus sales.</li>
	</ul>
@stop

@section('content')
	<section id="index-container">
		<header id="index-header">
			<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales/create')}}" class="index-addnew">
				{{HTML::image('img/admin/icon_addnew.png', '', array('class'=>'image-header'))}}
				<span>Add New</span>
			</a>
			<div class='search-input' style="left: 115px;display: block;width: 340px;">
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
				<th>No. Invoice</th>
				<th>Date</th>
				<th>Customer</th>
				<th>Price Total</th>
				<th>Paid</th>
				<th>Owed</th>
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
			@foreach ($sales as $sale)
				<?php 
					$counter++; 
				?>
				@if($sale->status != 'Canceled')
					<tr class='index-tr'>
						<td>{{$counter}}</td>
						<td>{{$sale->no_invoice}}</td>
						<td>{{date('d/m/Y', strtotime($sale->date))}}</td>
						<td>{{$sale->customer->name}}</td>
						<td>Rp. {{digitGroup($sale->paid)}}</td>
						<td>Rp. {{digitGroup($sale->owed)}}</td>
						<td>Rp. {{digitGroup($sale->paid - $sale->owed)}}</td>
						<td>
							@if($sale->status == 'Waiting for payment')
								<span style="color: orange;">{{$sale->status}}</span>
							@elseif($sale->status == 'Canceled')
								<span style="color: red;">{{$sale->status}}</span>
							@elseif($sale->status == 'Paid')
								<span style="color: green;">{{$sale->status}}</span>
							@else
								<span style="color: blue;">{{$sale->status}}</span>
							@endif
						</td>
						<td class="icon">
							<div class="index-icon">
								{{HTML::image('img/admin/index_action.png', '', array('class'=>'icon-master'))}}
								<div class="index-sub-icon">
									<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales/view/' . $sale->id)}}"><div class="icon-sub">{{HTML::image('img/admin/view.png')}} <span>View</span></div></a>
									@if($sale->is_editable == 1)
										@if($sale->status != 'Paid')
											<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales/transaction-cash/' . $sale->id)}}"><div class="icon-sub">{{HTML::image('img/admin/edit.png')}} <span>Transaction Cash</span></div></a>
										@endif
									@endif
									<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales/print-invoice/' . $sale->id)}}" target="_blank"><div class="icon-sub">{{HTML::image('img/admin/printer.png')}} <span>Print Invoice</span></div></a>
									@if($sale->is_editable == 1)
										<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales/edit/' . $sale->id)}}"><div class="icon-sub">{{HTML::image('img/admin/edit.png')}} <span>Edit</span></div></a>

										<div class="icon-sub delete">{{HTML::image('img/admin/closed.png')}} <span>Cancel</span></div>
										<section class="blur">
											<div class="blur-question">
												<span class="blur-text">
													Do you really want to cancel this nota?
												</span>
												<table>
													<tr>
														<td>
															No. Invoice
														</td>
														<td>
															<span>
																:
															</span>
															{{$sale->no_invoice}}
														</td>
													</tr>
												</table>
												<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales/cancel/' . $sale->id)}}">
													{{Form::button('Yes', array('class'=>'blur-submit blur-left'))}}
												</a>
												{{Form::button('Cancel', array('class'=>'blur-submit cancel'))}}
											</div>
										</section>
									@endif
								</div>
							</div>
						</td>
					</tr>
				@else
					<tr class='index-tr index-cancel'>
						<td>{{$counter}}</td>
						<td>{{$sale->no_invoice}}</td>
						<td>{{date('d/m/Y', strtotime($sale->date))}}</td>
						<td>{{$sale->customer->name}}</td>
						<td>Rp. {{digitGroup($sale->paid)}}</td>
						<td>Rp. {{digitGroup($sale->owed)}}</td>
						<td>Rp. {{digitGroup($sale->paid - $sale->owed)}}</td>
						<td>
							<span style="color: red;">Canceled</span>
						</td>
						<td class="icon">
							<div class="index-icon">
								{{HTML::image('img/admin/index_action.png', '', array('class'=>'icon-master'))}}
								<div class="index-sub-icon">
									<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales/view/' . $sale->id)}}"><div class="icon-sub">{{HTML::image('img/admin/view.png')}} <span>View</span></div></a>
									<div class="icon-sub delete">{{HTML::image('img/admin/delete.png')}} <span>Delete</span></div>
									<section class="blur">
										<div class="blur-question">
											<span class="blur-text">
												Do you really want to Delete this nota?
											</span>
											<table>
												<tr>
													<td style="text-decoration: none;">
														No. Invoice
													</td>
													<td style="text-decoration: none;">
														<span>
															:
														</span>
														{{$sale->no_invoice}}
													</td>
												</tr>
											</table>
											<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales/delete/' . $sale->id)}}">
												{{Form::button('Yes', array('class'=>'blur-submit blur-left'))}}
											</a>
											{{Form::button('Cancel', array('class'=>'blur-submit cancel'))}}
										</div>
									</section>
								</div>
							</div>
						</td>
					</tr>
				@endif
			@endforeach
		</table>
		{{$sales->appends($criteria)->links()}}
	</section>
@stop