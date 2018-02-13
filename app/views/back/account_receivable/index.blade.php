@extends('back.template.master')

@section('title')
	Laporan Piutang Management
@stop

@section('head_additional')
	<style type="text/css">
		a.index-addnew {
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
		}

		.summary {
		    margin-top: 25px;
		    margin-bottom: 20px;
		    width: 330px;
		    border: solid 1px #000;
		    padding: 10px 15px;
		    padding-top: 0;
		    font-size: 16px;
		    line-height: 24px;
		    position: relative;
		    font-weight: bold;
		}

		.summary strong {
		    background: #f5f5f5;
		    left: 0;
		    font-size: 20px;
		    margin-bottom: 7px;
		    position: relative;
		    top: -14px;
		}

		.summary div span:first-child {
		    width: 115px;
		    display: inline-block;
		}
	</style>

	<script type="text/javascript">
		$(document).ready(function(){
			$('.select-branch').live('change', function(){
				var branchId = $(this).val();
				window.location.replace("{{URL::to(Crypt::decrypt($setting->admin_url) . '/account-receivable/index')}}/" + branchId);
			});
		});
	</script>
@stop

@section('page_title')
	Laporan Piutang Management
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
		<li>Disini anda dapat melihat data dari Laporan Piutang.</li>
	</ul>
@stop

@section('content')
	<section id="index-container">
		<header id="index-header">
			<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/account-receivable/print-report/' . $branch->id)}}" target="_blank" class="index-addnew">
				{{HTML::image('img/admin/printer.png', '', array('class'=>'image-header'))}}
				<span>Print Report</span>
			</a>
			<h2>OVERDUE</h2>
			<div class='search-input'>
				Branch Name
				{{Form::select('branch_id', $branch_options, $branch->id, array('class'=>'search-text select select-branch'))}}
			</div>
		</header>
		<table id="index-table" style="border-spacing: 0px; margin-bottom: 50px;">
			<tr class="index-tr index-title">
				<th>Name</th>
				<th>Branch</th>
				<th>Address</th>
				<th>Phone</th>
				<th>CP Name</th>
				<th>CP No. HP</th>
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

				$total_piutang = 0;
			?>
			@foreach ($customers as $customer)
				<?php 
					$counter++; 

					$sales = Sale::where('customer_id', '=', $customer->id)->where('due_date', '<=', date('Y-m-d'))->where('status', '=', 'Waiting for Payment')->get();
				?>
				@if(count($sales) != 0)
					<tr class='index-tr'>
						<td>{{$customer->name}}</td>
						@if($customer->branch_id != 0)
							<td>{{$customer->branch->name}}</td>
						@else
							<td>-</td>
						@endif
						<td>{{$customer->address}}</td>
						<td>{{$customer->no_telp}}</td>
						<td>{{$customer->cp_name}}</td>
						<td>{{$customer->cp_no_hp}}</td>
					</tr>
					<tr class="index-tr index-title" style="background: none; height: 26px;">
						<td></td>
						<th style="background: rgb(32, 36, 113);">No. Nota</th>
						<th style="background: rgb(32, 36, 113);">Invoice Date</th>
						<th style="background: rgb(32, 36, 113);">Due Date</th>
						<th style="background: rgb(32, 36, 113);">Paid</th>
						<th style="background: rgb(32, 36, 113);">Owed</th>
					</tr>
					@foreach($sales as $sale)
						@if(date('Y-m-d') >= $sale->due_date)
							<tr class='index-tr' style="height: 30px;">
								<td></td>
								<td>{{$sale->no_invoice}}</td>
								<td>{{date('d/m/Y', strtotime($sale->date))}}</td>
								<td style="color: red;">
									{{date('d/m/Y', strtotime($sale->due_date))}}
								</td>
								<td>Rp. {{digitGroup($sale->owed)}}</td>
								<td>Rp. {{digitGroup($sale->paid - $sale->owed)}}</td>
							</tr>
							<?php 
								$total_piutang = $total_piutang + ($sale->paid - $sale->owed);
							?>
						@endif
					@endforeach
					<tr class='index-tr' style="height: 30px;">
						<td colspan="6"></td>
					</tr>
				@endif
			@endforeach
		</table>

		<header id="index-header">
			<h2>NOT OVERDUE</h2>
		</header>
		<table id="index-table" style="border-spacing: 0px;">
			<tr class="index-tr index-title">
				<th>Name</th>
				<th>Branch</th>
				<th>Address</th>
				<th>Phone</th>
				<th>CP Name</th>
				<th>CP No. HP</th>
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
			@foreach ($customers as $customer)
				<?php 
					$counter++; 

					$sales = Sale::where('customer_id', '=', $customer->id)->where('due_date', '>', date('Y-m-d'))->where('status', '=', 'Waiting for Payment')->get();
				?>
				@if(count($sales) != 0)
					<tr class='index-tr'>
						<td>{{$customer->name}}</td>
						@if($customer->branch_id != 0)
							<td>{{$customer->branch->name}}</td>
						@else
							<td>-</td>
						@endif
						<td>{{$customer->address}}</td>
						<td>{{$customer->no_telp}}</td>
						<td>{{$customer->cp_name}}</td>
						<td>{{$customer->cp_no_hp}}</td>
					</tr>
					<tr class="index-tr index-title" style="background: none; height: 26px;">
						<td></td>
						<th style="background: rgb(32, 36, 113);">Sale (No. Invoice)</th>
						<th style="background: rgb(32, 36, 113);">Invoice Date</th>
						<th style="background: rgb(32, 36, 113);">Due Date</th>
						<th style="background: rgb(32, 36, 113);">Paid</th>
						<th style="background: rgb(32, 36, 113);">Owed</th>
					</tr>
					@foreach($sales as $sale)
						@if(date('Y-m-d') < $sale->due_date)
							<tr class='index-tr' style="height: 30px;">
								<td></td>
								<td>{{$sale->no_invoice}}</td>
								<td>{{date('d/m/Y', strtotime($sale->date))}}</td>
								<td>
									{{date('d/m/Y', strtotime($sale->due_date))}}
								</td>
								<td>Rp. {{digitGroup($sale->owed)}}</td>
								<td>Rp. {{digitGroup($sale->paid - $sale->owed)}}</td>
								<?php 
									$total_piutang = $total_piutang + ($sale->paid - $sale->owed);
								?>
							</tr>
						@endif
					@endforeach
					<tr class='index-tr' style="height: 30px;">
						<td colspan="6"></td>
					</tr>
				@endif
			@endforeach
		</table>
		<div class="summary">
			<strong>Summary</strong>
			<div>
				<span>Total Piutang</span>
				<span>: Rp. {{digitGroup($total_piutang)}}</span>
			</div>
		</div>
	</section>
@stop