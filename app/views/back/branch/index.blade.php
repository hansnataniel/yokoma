@extends('back.template.master')

@section('title')
	Branch Management
@stop

@section('head_additional')
	<style type="text/css">
		.icon-sub {
		    width: 110px;
		}
	</style>
@stop

@section('page_title')
	Branch Management
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
		<li>Disini anda dapat melihat sekilas data dari branch.</li>
		<li>Gunakan tombol New untuk masuk ke halaman New branch.</li>
		<li>Gunakan tombol View di dalam tombol Action untuk masuk ke halaman View branch.</li>
		<li>Gunakan tombol Edit di dalam tombol Action untuk masuk ke halaman Edit branch.</li>
		<li>Gunakan tombol Delete di dalam tombol Action untuk menghapus branch.</li>
	</ul>
@stop

@section('content')
	<section id="index-container">
		<header id="index-header">
			<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/branch/create')}}" class="index-addnew">
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
				<th>Name</th>
				<th>Address</th>
				<th>Phone</th>
				<th>No. Invoice</th>
				<th>Active Status</th>
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
			@foreach ($branchs as $branch)
				<?php 
					$counter++; 
				?>
				<tr class='index-tr'>
					<td>{{$counter}}</td>
					<td>{{$branch->name}}</td>
					<td>{{$branch->address}}</td>
					<td>{{$branch->phone}}</td>
					<td>{{$branch->no_invoice}}</td>
					<td>{{$branch->is_active == true ? "<span class='text-green'>Yes</span>":"<span class='text-red'>No</span>"}}</td>
					<td class="icon">
						<div class="index-icon">
							{{HTML::image('img/admin/index_action.png', '', array('class'=>'icon-master'))}}
							<div class="index-sub-icon">
								<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/branch/view/' . $branch->id)}}"><div class="icon-sub">{{HTML::image('img/admin/view.png')}} <span>View</span></div></a>
								<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/branch/edit/' . $branch->id)}}"><div class="icon-sub">{{HTML::image('img/admin/edit.png')}} <span>Edit</span></div></a>
							</div>
						</div>
					</td>
				</tr>
			@endforeach
		</table>
		{{$branchs->appends($criteria)->links()}}
	</section>
@stop