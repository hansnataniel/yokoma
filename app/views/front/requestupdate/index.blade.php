@extends('front.template.master')

@section('title')
	Status Perubahan Nota
@stop

@section('head_additional')
	<style type="text/css">
		.icon-sub {
		    width: 110px;
		}
	</style>
@stop

@section('page_title')
	Status Perubahan Nota
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
		<li>Disini anda dapat melihat sekilas data dari Status Perubahan Nota.</li>
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
				<th>Date</th>
				<th>No. Invoice (Nota)</th>
				<th>Note</th>
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
			@foreach ($requestupdates as $requestupdate)
				<?php 
					$counter++; 
				?>
				<tr class='index-tr'>
					<td>{{$counter}}</td>
					<td>{{date('d/m/Y', strtotime($requestupdate->created_at))}}</td>
					<td>{{$requestupdate->sale->no_invoice . ' | ' . $requestupdate->sale->customer->name}}</td>
					<td>{{$requestupdate->note}}</td>
					<td>
						@if($requestupdate->status == 'Waiting Confirmation for Admin')
							<span style="color: orange;">Request Perubahan Nota</span>
						@elseif($requestupdate->status == 'Waiting Cancelation')
							<span style="color: orange;">Request Pembatalan Nota</span>
						@elseif($requestupdate->status == 'Approve Updates')
							<span style="color: blue;">Perubahan Nota di Ijinkan</span>
						@elseif($requestupdate->status == 'Finish Updated')
							<span style="color: green;">Perubahan Nota Selesai</span>
						@elseif($requestupdate->status == 'Finish Canceled')
							<span style="color: green;">Nota Dibatalkan</span>
						@elseif($requestupdate->status == 'Declined Canceled')
							<span style="color: red;">Pembatalan Nota Ditolak</span>
						@else
							<span style="color: red;">Request Tidak di Ijinkan</span>
						@endif
					</td>
					<td class="icon">
						@if($requestupdate->status == 'Approve Updates')
							<div class="index-icon">
								{{HTML::image('img/admin/index_action.png', '', array('class'=>'icon-master'))}}
								<div class="index-sub-icon">
									<a href="{{URL::to('request-update-sales/edit/' . $requestupdate->id)}}"><div class="icon-sub">{{HTML::image('img/admin/edit.png')}} <span>Update Nota</span></div></a>
								</div>
							</div>
						@endif
						@if($requestupdate->status == 'Waiting Cancelation')
							<a href="{{URL::to('request-update-sales/decline/' . $requestupdate->id)}}"><div class="icon-sub">{{HTML::image('img/admin/closed.png')}} <span>Decline</span></div></a>
						@endif
					</td>
				</tr>
			@endforeach
		</table>
		{{$requestupdates->appends($criteria)->links()}}
	</section>
@stop