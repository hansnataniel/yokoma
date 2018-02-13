@extends('front.template.master')

@section('title')
	Accu Mati Purchase Management
@stop

@section('head_additional')
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
	Accu Mati Purchase Management
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
		<li>Disini anda dapat melihat sekilas data dari Accu Mati Purchase.</li>
		<li>Gunakan tombol New untuk masuk ke halaman New Accu Mati Purchase.</li>
		<li>Gunakan tombol View di dalam tombol Action untuk masuk ke halaman View Accu Mati Purchase.</li>
		<li>Gunakan tombol Edit di dalam tombol Action untuk masuk ke halaman Edit Accu Mati Purchase.</li>
		<li>Gunakan tombol Delete di dalam tombol Action untuk menghapus Accu Mati Purchase.</li>
	</ul>
@stop

@section('content')
	<section id="index-container">
		<header id="index-header">
			<a href="{{URL::to('import-second-product/create')}}" class="index-addnew">
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
				<th>Date</th>
				<th>Customer</th>
				<th>Paid</th>
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
			@foreach ($importsecondproducts as $importsecondproduct)
				<?php 
					$counter++; 
				?>
				<tr class='index-tr'>
					<td>{{$counter}}</td>
					<td>{{$importsecondproduct->no_invoice}}</td>
					<td>{{date('d/m/Y', strtotime($importsecondproduct->date))}}</td>
					<td>{{$importsecondproduct->customer->name}}</td>
					<td>Rp. {{digitGroup($importsecondproduct->price_total)}}</td>
					<td class="icon">
						<div class="index-icon">
							{{HTML::image('img/admin/index_action.png', '', array('class'=>'icon-master'))}}
							<div class="index-sub-icon">
								<a href="{{URL::to('import-second-product/view/' . $importsecondproduct->id)}}"><div class="icon-sub">{{HTML::image('img/admin/view.png')}} <span>View</span></div></a>
								<a href="{{URL::to('import-second-product/edit/' . $importsecondproduct->id)}}"><div class="icon-sub">{{HTML::image('img/admin/edit.png')}} <span>Edit</span></div></a>
								<div class="icon-sub delete">{{HTML::image('img/admin/delete.png')}} <span>Delete</span></div>
								<section class="blur">
									<div class="blur-question">
										<span class="blur-text">
											Do you really want to delete this importsecondproduct?
										</span>
										<table>
											<tr>
												<td>
													Form No
												</td>
												<td>
													<span>
														:
													</span>
													{{$importsecondproduct->no_invoice}}
												</td>
											</tr>
										</table>
										<a href="{{URL::to('import-second-product/delete/' . $importsecondproduct->id . '?_token=' . Session::token())}}">
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
		{{$importsecondproducts->appends($criteria)->links()}}
	</section>
@stop