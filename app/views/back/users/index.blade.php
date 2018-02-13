@extends('back.template.master')

@section('title')
	User Management
@stop

@section('head_additional')

@stop

@section('page_title')
	User Management
@stop

@section('search')
	{{Form::open(array('URL' => URL::current(), 'method' => 'GET'))}}
		<div class="search-group">
			<div class="search-title">Search By</div>
			<div class='search-input'>
				{{Form::text('src_name', '', array('class'=>'search-text', 'placeholder'=>'Name'))}}
			</div>
			<div class='search-input'>
				{{Form::text('src_email', '', array('class'=>'search-text', 'placeholder'=>'Email'))}}
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
		<li>Disini anda dapat melihat sekilas data dari user.</li>
		<li>Gunakan tombol New untuk masuk ke halaman New user.</li>
		<li>Gunakan tombol View di dalam tombol Action untuk masuk ke halaman View user.</li>
		<li>Gunakan tombol Edit di dalam tombol Action untuk masuk ke halaman Edit user.</li>
		<li>Gunakan tombol Delete di dalam tombol Action untuk menghapus user.</li>
	</ul>
@stop

@section('content')
	<section id="index-container">
		<header id="index-header">
			<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/user/create')}}" class="index-addnew">
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
				<th>Email</th>
				<th>Branch</th>
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
			@foreach ($users as $user)
				<?php 
					$counter++; 
					$userid = $user->user_id;
					$userget = User::find($userid); 
				?>
				<tr class='index-tr'>
					<td>{{$counter}}</td>
					<td>{{$user->name}}</td>
					<td>{{$user->email}}</td>
					<td>{{$user->branch->name}}</td>
					<td>{{$user->is_active == true ? "<span class='text-green'>Yes</span>":"<span class='text-red'>No</span>"}}</td>
					<td class="icon">
						<div class="index-icon">
							{{HTML::image('img/admin/index_action.png', '', array('class'=>'icon-master'))}}
							<div class="index-sub-icon">
								<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/user/view/' . $user->id)}}"><div class="icon-sub">{{HTML::image('img/admin/view.png')}} <span>View</span></div></a>
								<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/user/edit/' . $user->id)}}"><div class="icon-sub">{{HTML::image('img/admin/edit.png')}} <span>Edit</span></div></a>
								<div class="icon-sub delete">{{HTML::image('img/admin/delete.png')}} <span>Delete</span></div>
								<section class="blur">
									<div class="blur-question">
										<span class="blur-text">
											Do you really want to delete this user?
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
													{{$user->name}}
												</td>
											</tr>
										</table>
										<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/user/delete/' . $user->id . '?_token=' . Session::token())}}">
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
		{{$users->appends($criteria)->links()}}
	</section>
@stop