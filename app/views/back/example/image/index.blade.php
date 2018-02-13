@extends('back.template.master')

@section('title')
	Photo(s) of Example Management
@stop

@section('head_additional')
	<style>
		.image-content {
			position: relative;
			padding-top: 20px;
			font-size: 0px;
		}

		.image-group {
			position: relative;
			display: inline-block;
			vertical-align: top;
			border: 1px solid #d2d2d2;
			padding: 10px;
			width: 300px;
			margin: 0px 20px 20px 0px;
		}

		.image-group img {
			width: 300px;
		}

		.text-group {
			position: relative;
			display: block;
			padding: 10px 0px;
			border-bottom: 0px;
		}

		.text-group td {
			position: relative;
			padding: 5px 10px;
			color: #535353;
			font-size: 12px;
			background: rgba(0, 0, 0, 0);
		}

		.image-b-group {
			position: relative;
			font-size: 0px;
			border-top: 1px solid #d2d2d2;
		}

		.image-b-g {
			font-size: 14px;
			cursor: pointer;
			position: relative;
			display: inline-block;
			vertical-align: top;
			font-size: 14px;
			padding: 8px 0px;
			text-align: center;
			background: #0d0f3b;
			color: #fff;
			width: 50%;
			-webkit-transition: background 0.4s, color 0.4s;
			-moz-transition: background 0.4s, color 0.4s;
			-ms-transition: background 0.4s, color 0.4s;
			transition: background 0.4s, color 0.4s;
		}

		.image-b-g:hover {
			background: #f7961e;
			color: #fff;
			-webkit-transition: background 0.4s, color 0.4s;
			-moz-transition: background 0.4s, color 0.4s;
			-ms-transition: background 0.4s, color 0.4s;
			transition: background 0.4s, color 0.4s;
		}

		.delete:hover {
			background: #ff0000;
			color: #fff;
			-webkit-transition: background 0.4s, color 0.4s;
			-moz-transition: background 0.4s, color 0.4s;
			-ms-transition: background 0.4s, color 0.4s;
			transition: background 0.4s, color 0.4s;
		}
	</style>
@stop

@section('page_title')
	<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/example')}}">
		{{HTML::image('img/admin/back.png', '', array('class'=>'image-img'))}}
	</a> 
	Photo(s) of Example Management
@stop

@section('search')
	
@stop

@section('help')
	<ul style="padding-left: 18px;">
		<li><!-- Help Goes Here --></li>
		<li><!-- Help Goes Here --></li>
	</ul>
@stop

@section('content')
	<section id="index-container">
		<header id="index-header">
			<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/example-image/create/' . $example->id)}}" class="index-addnew" style="display: inline-block; position: relative;">
				{{HTML::image('img/admin/icon_addnew.png', '', array('class'=>'image-header'))}}
				<span>Add New</span>
			</a><!--
			--><span id="index-header-right" style="position: relative; display: inline-block; border-top: 1px solid #ccc; padding: 5px 10px 0px; vertical-align: top; top: 0px; right: 0px;">
				{{$records_count}} record(s) found
			</span>
		</header>
		<div class="image-content">
			<?php
				if (Input::has('photo'))
				{
					$counter = (Input::get('photo')-1) * $per_photo;
				}
				else
				{
					$counter = 0;
				}
			?>
			@foreach ($exampleimages as $exampleimage)
				<?php $counter++; ?>
				<div class="image-group">
					<div class="image-g-img">
						{{HTML::image('usr/img/example-image/' . $exampleimage->id . '_' . Str::slug($exampleimage->name, '_') . '_thumb.jpg?lastmod=' . Str::random(5), '', array('class'=>'image-of-index'))}}
					</div>
					<div class="text-group">
						<table>
							<tr>
								<td>
									Name
								</td>
								<td>
									: {{$exampleimage->name}}
								</td>
							</tr>
							<tr>
								<td>
									Active Status
								</td>
								<td>
									: {{$exampleimage->is_active == true ? "<span class='text-green'>Active</span>":"<span class='text-red'>Not Active</span>"}}
								</td>
							</tr>
							<tr>
								<td>
									Order
								</td>
								<td>
									: {{Form::open(array('url' => URL::to(Crypt::decrypt($setting->admin_url) . '/example-image/moveto/' . $example->id), 'class'=>'form-moveto'))}}

										{{Form::hidden('id', $exampleimage->id)}}
										{{Form::text('moveto', $exampleimage->order, array('class'=>'order-image-value'))}}
										{{Form::submit('Save', array('class'=>'submit-moveto'))}}

									{{Form::close()}}

									@if ($counter == 1)
										{{HTML::link(URL::to(Crypt::decrypt($setting->admin_url) . '/example-image/movedown/' . $exampleimage->id), '', array('class'=>'index-link-down'))}}
									@endif
									
									@if (($counter != 1) AND ($counter != $records_count))
										{{HTML::link(URL::to(Crypt::decrypt($setting->admin_url) . '/example-image/moveup/' . $exampleimage->id), '', array('class'=>'index-link-up'))}} {{HTML::link(URL::to(Crypt::decrypt($setting->admin_url) . '/example-image/movedown/' . $exampleimage->id), '', array('class'=>'index-link-down'))}}
									@endif
									
									@if ($counter == $records_count)
										{{HTML::link(URL::to(Crypt::decrypt($setting->admin_url) . '/example-image/moveup/' . $exampleimage->id), '', array('class'=>'index-link-up'))}}
									@endif
								</td>
							</tr>
						</table>
					</div>
					<div class="image-b-group">
						<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/example-image/edit/' . $exampleimage->id)}}"><div class="image-b-g">
							Edit
						</div></a>
						<div class="image-b-g delete">
							Delete
						</div></a>
						<section class="blur">
							<div class="blur-question">
								<span class="blur-text">
									Do you really want to delete this photo?
								</span>
								{{HTML::image('usr/img/example-image/' . $exampleimage->id . '_' . Str::slug($exampleimage->name, '_') . '.jpg')}}
								<table>
									<tr>
										<td>
											Name
										</td>
										<td>
											<span>
												:
											</span>
											{{$exampleimage->name}}
										</td>
									</tr>
								</table>
								<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/example-image/delete/' . $exampleimage->id . '?_token=' . Session::token())}}">
									{{Form::button('Yes', array('class'=>'blur-submit blur-left'))}}
								</a>
								{{Form::button('Cancel', array('class'=>'blur-submit cancel'))}}
							</div>
						</section>
					</div>
				</div>
			@endforeach
		</div>
		{{$exampleimages->appends($criteria)->links()}}
	</section>
@stop