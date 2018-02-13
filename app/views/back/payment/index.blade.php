@extends('back.template.master')

@section('title')
	Penerimaan Piutang Management
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

		$(document).ready(function(){
			$('.select-branch').live('change', function(){
				var branchId = $(this).val();
				window.location.replace("{{URL::to(Crypt::decrypt($setting->admin_url) . '/payment/index')}}/" + branchId);
			});
		});
	</script>
@stop

@section('page_title')
	Penerimaan Piutang Management
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
		<li>Disini anda dapat melihat sekilas data dari Penerimaan Piutang.</li>
		<li>Gunakan tombol New untuk masuk ke halaman New Penerimaan Piutang.</li>
		<li>Gunakan tombol View di dalam tombol Action untuk masuk ke halaman View Penerimaan Piutang.</li>
	</ul>
@stop

@section('content')
	<section id="index-container">
		<header id="index-header">
			<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/payment/create')}}" class="index-addnew">
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
				<th>Form No</th>
				<th>Date</th>
				<th>Customer</th>
				<th>Nota | Paid | Owed</th>
				<th>Metode Pembayaran</th>
				<th>Amount to Pay</th>
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
			@foreach ($payments as $payment)
				<?php 
					$counter++; 
				?>
				<tr class='index-tr'>
					<td>{{$counter}}</td>
					<td>{{$payment->no_invoice}}</td>
					<td>{{date('d/m/Y', strtotime($payment->date))}}</td>
					<td>{{$payment->customer->name}}</td>
					<td>
						<?php $paymentdetails = Paymentdetail::where('payment_id', '=', $payment->id)->get(); ?>
						@foreach($paymentdetails as $paymentdetail)
							- {{$paymentdetail->sale->no_invoice}} | {{digitGroup($paymentdetail->price_payment)}} | {{digitGroup($paymentdetail->sale->paid - $paymentdetail->sale->owed)}} <br>
						@endforeach
					</td>
					<td>
						{{$payment->metode_pembayaran}}
						{{$payment->metode_pembayaran == 'Giro' ? ' | ' . date('d-m-Y', strtotime($payment->tgl_pencairan)) : ' '}}
					</td>
					<td>Rp. {{digitGroup($payment->payment_total)}}</td>
					<td class="icon">
						<div class="index-icon">
							{{HTML::image('img/admin/index_action.png', '', array('class'=>'icon-master'))}}
							<div class="index-sub-icon">
								<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/payment/view/' . $payment->id)}}"><div class="icon-sub">{{HTML::image('img/admin/view.png')}} <span>View</span></div></a>
								<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/payment/print-invoice/' . $payment->id)}}" target="_blank"><div class="icon-sub">{{HTML::image('img/admin/printer.png')}} <span>Print Invoice</span></div></a>
								<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/payment/edit/' . $payment->id)}}"><div class="icon-sub">{{HTML::image('img/admin/edit.png')}} <span>Edit</span></div></a>
								<div class="icon-sub delete">{{HTML::image('img/admin/delete.png')}} <span>Delete</span></div>
								<section class="blur">
									<div class="blur-question">
										<span class="blur-text">
											Do you really want to Delete this Penerimaan Piutang?
										</span>
										<table>
											<tr>
												<td>
													Form No.
												</td>
												<td>
													<span>
														:
													</span>
													{{$payment->no_invoice}}
												</td>
											</tr>
										</table>
										<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/payment/delete/' . $payment->id)}}">
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
		{{$payments->appends($criteria)->links()}}
	</section>
@stop