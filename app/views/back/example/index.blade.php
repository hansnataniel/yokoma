@extends('back.template.master')

@section('title')
	Example Management
@stop

@section('head_additional')

@stop

@section('page_title')
	Example Management
@stop

@section('search')
	{{Form::open(array('URL' => URL::current(), 'method' => 'GET'))}}
		<div class="search-group">
			<div class="search-title">Search By</div>
			<div class='search-input'>
				{{Form::text('src_name', '', array('class'=>'search-text', 'placeholder'=>'Name'))}}
			</div>
			<div class='search-input'>
				{{Form::select('src_fields3', array(''=>'', 'suka'=>'Suka', 'tidak suka'=>'Tidak Suka'), null, array('class'=>'search-text select', 'placeholder-data'=>'Select the option first'))}}
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
		<li>Gunakan tombol New untuk menambahkan data baru.</li>
		<li>Gunakan tombol View di dalam tombol Action untuk melihat detail dari Example.</li>
		<li>Gunakan tombol Edit di dalam tombol Action untuk meng-edit Example.</li>
		<li>Gunakan tombol Delete di dalam tombol Action untuk menghapus Example.</li>
	</ul>
@stop

@section('content')
	<section id="index-container">
		<header id="index-header">
			<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/example/create')}}" class="index-addnew">
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
				<th>Image</th>
				<th>Name</th>
				<th>Fields 2</th>
				<th>Fields 3</th>
				<th>Fields 4</th>
				<th>Fields 5</th>
				<th>Fields 6</th>
				<th>Order</th>
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
			@foreach ($examples as $example)
				<?php $counter++; ?>
				<tr class='index-tr'>
					<td>{{$counter}}</td>
					<td>
						{{HTML::image('usr/img/example/' . $example->id . '_' . Str::slug($example->name, '_') . '_thumb.jpg?lastmod=' . Str::random(5), '', array('style'=>'width: 100px; margin: 10px 0px;'))}}
					</td>
					<td>{{$example->name}}</td>
					<td>{{$example->fields2}}</td>
					<td>{{$example->fields3}}</td>
					<td>IDR {{digitGroup($example->fields4)}}</td>
					<td>{{$example->fields5 == true ? "<span class='text-green'>Active</span>":"<span class='text-red'>Not Active</span>"}}</td>
					<td>{{$example->fields6 == true ? "<span class='text-green'>Suka</span>":"<span class='text-red'>Tidak Suka</span>"}}</td>
					<td>
						{{Form::open(array('url' => URL::to(Crypt::decrypt($setting->admin_url) . '/example/moveto'), 'class'=>'form-moveto'))}}
						{{Form::hidden('id', $example->id)}}
						{{Form::text('moveto', $example->order, array('class'=>'index-moveto'))}}
						{{Form::submit('Save', array('class'=>'submit-moveto'))}}
						{{Form::close()}}
						@if ($records_count > 1)
							@if ($counter == 1)
								{{HTML::link(URL::to(Crypt::decrypt($setting->admin_url) . '/example/movedown/' . $example->id), '', array('class'=>'index-link-down'))}}
							@endif
							@if (($counter != 1) AND ($counter != $records_count))
								{{HTML::link(URL::to(Crypt::decrypt($setting->admin_url) . '/example/moveup/' . $example->id), '', array('class'=>'index-link-up'))}} {{HTML::link(URL::to(Crypt::decrypt($setting->admin_url) . '/example/movedown/' . $example->id), '', array('class'=>'index-link-down'))}}
							@endif
							@if ($counter == $records_count)
								{{HTML::link(URL::to(Crypt::decrypt($setting->admin_url) . '/example/moveup/' . $example->id), '', array('class'=>'index-link-up'))}}
							@endif
						@endif
					</td>
					<td class="icon">
						<div class="index-icon">
							{{HTML::image('img/admin/index_action.png', '', array('class'=>'icon-master'))}}
							<div class="index-sub-icon">
								<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/example-image/list/' . $example->id)}}">
									<div class="icon-sub index-action-separator">
										{{HTML::image('img/admin/color.png')}} <span>Image</span>
									</div>
								</a>
								<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/example/view/' . $example->id)}}">
									<div class="icon-sub">
										{{HTML::image('img/admin/view.png')}} <span>View</span>
									</div>
								</a>
								<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/example/edit/' . $example->id)}}">
									<div class="icon-sub">
										{{HTML::image('img/admin/edit.png')}} <span>Edit</span>
									</div>
								</a>
								<div class="icon-sub delete">
									{{HTML::image('img/admin/delete.png')}} <span>Delete</span>
								</div>
								<section class="blur">
									<div class="blur-question">
										<span class="blur-text">
											Do you really want to delete this example?
										</span>
										{{HTML::image('usr/img/example/' . $example->id . '_' . Str::slug($example->name, '_') . '_thumb.jpg')}}
										<table>
											<tr>
												<td>
													Name
												</td>
												<td>
													<span>
														:
													</span>
													{{$example->name}}
												</td>
											</tr>
										</table>
										<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/example/delete/' . $example->id . '?_token=' . Session::token())}}">
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
		{{$examples->appends($criteria)->links()}}
	</section>
@stop