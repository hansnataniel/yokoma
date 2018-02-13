@extends('back.template.master')

@section('title')
	Example View
@stop

@section('head_additional')

@stop

@section('page_title')
	@if(Session::has('last_url'))
		<a href="{{URL::to(Session::get('last_url'))}}">
	@else
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/example')}}">
	@endif
		{{HTML::image('img/admin/back.png', '', array('class'=>'image-img'))}}
	</a> Example View
@stop

@section('help')
	<ul style="padding-left: 18px;">
		<li><!-- Help Goes Here --></li>
		<li><!-- Help Goes Here --></li>
	</ul>
@stop

@section('content')
	<section id="view-container">
		<header id="view-header">
			{{$example->name}}
		</header>

		<div id="view-general-information">
			<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/example/edit/' . $example->id)}}">{{HTML::image('img/admin/edit_view.png', '', array('id'=>'view-edit-button'))}}</a>
			<div id="view-date">
				<span class="view-status">
					<span class="view-cell">Created at</span><span class="view-cell">:</span> <span class="view-cell text-blue">{{date('l, d F Y G:i:s', strtotime($example->created_at))}}</span>
				</span>
				<span class="view-status">
					<span class="view-cell">Updated at</span><span class="view-cell">:</span> <span class="view-cell text-blue">{{date('l, d F Y G:i:s', strtotime($example->updated_at))}}</span>
				</span>
			</div>
			@if (file_exists(public_path() . '/usr/img/example/' . $example->id . '_' . Str::slug($example->name, '_') . '_thumb.jpg'))
				{{HTML::image('usr/img/example/' . $example->id . '_' . Str::slug($example->name, '_') . '_thumb.jpg?lastmod=' . Str::random(5), '', array('class'=>'view-photo'))}}
			@endif
		</div>
		<table class="view-information" style="border-spacing: 0px;">
			<tr class="view-tr">
				<td class="view-td view-td-left">
					Fields 2
				</td><!--
				--><td class="view-td view-td-right">
					{{$example->fields2}}
				</td>
			</tr>
			<tr class="view-tr">
				<td class="view-td view-td-left">
					Fields 3
				</td><!--
				--><td class="view-td view-td-right">
					{{$example->fields3}}
				</td>
			</tr>
			<tr class="view-tr">
				<td class="view-td view-td-left">
					Fields 4
				</td><!--
				--><td class="view-td view-td-right">
					IDR {{digitGroup($example->fields4)}}
				</td>
			</tr>
			<tr class="view-tr">
				<td class="view-td view-td-left">
					Fields 5
				</td><!--
				--><td class="view-td view-td-right">
					{{$example->fields5 == 1 ? "<span class='text-green'>Active</span>" : "<span class='text-red'>Not Active</span>"}}
				</td>
			</tr>
			<tr class="view-tr">
				<td class="view-td view-td-left">
					Fields 6
				</td><!--
				--><td class="view-td view-td-right">
					{{$example->fields6 == 1 ? "<span class='text-green'>Suka</span>" : "<span class='text-red'>Tidak Suka</span>"}}
				</td>
			</tr>
			<tr class="view-tr">
				<td class="view-td view-td-left">
					Fields 9
				</td><!--
				--><td class="view-td view-td-right">
					{{$example->fields9}}
				</td>
			</tr>
		</table>
		<section class="view-data-info">
			<header class="view-data-header">
				Fields 7
			</header>
			<article class="view-data-ctn">
				{{$example->fields7}}
			</article>
		</section>
		<section class="view-data-info">
			<header class="view-data-header">
				Fields 8
			</header>
			<article class="view-data-ctn">
				{{$example->fields8}}
			</article>
		</section>
	</section>
@stop