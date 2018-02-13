@extends('back.template.master')

@section('title')
	Branch View
@stop

@section('head_additional')

@stop

@section('page_title')
	<a href="{{URL::to(Session::get('last_url'))}}">{{HTML::image('img/admin/back.png', '', array('class'=>'image-img'))}}</a> Branch View
@stop

@section('help')
	<ul style="padding-left: 18px;">
		<li>Disini anda dapat melihat data Branch secara keseluruhan.</li>
		<li>Gunakan tombol Edit untuk masuk ke halaman Edit Promo.</li>
	</ul>
@stop

@section('content')
	<section id="view-container">
		<div id="view-general-information">
			<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/branch/edit/' . $branch->id)}}">{{HTML::image('img/admin/edit_view.png', '', array('id'=>'view-edit-button'))}}</a>
			<div id="view-date">
				<span class="view-status">
					<span class="view-cell">Created at</span><span class="view-cell">:</span> <span class="view-cell text-blue">{{date('l, d F Y G:i:s', strtotime($branch->created_at))}}</span>
				</span>
				<span class="view-status">
					<span class="view-cell">Updated at</span><span class="view-cell">:</span> <span class="view-cell text-blue">{{date('l, d F Y G:i:s', strtotime($branch->updated_at))}}</span>
				</span>
			</div>
			@if (file_exists(public_path() . '/usr/img/branch/' . $branch->id . '_' . Str::slug($branch->name, '_') . '.jpg'))
				{{HTML::image('usr/img/branch/' . $branch->id . '_' . Str::slug($branch->name, '_') . '.jpg', '', array('class'=>'view-photo'))}}
			@endif
		</div>
		<table class="view-information" style="border-spacing: 0px;">
			<tr class="view-tr">
				<td class="view-td view-td-left">
					Name
				</td><!--
				--><td class="view-td view-td-right">
					{{$branch->name}}
				</td>
			</tr>
			<tr class="view-tr">
				<td class="view-td view-td-left">
					Address
				</td><!--
				--><td class="view-td view-td-right">
					{{$branch->address}}
				</td>
			</tr>
			<tr class="view-tr">
				<td class="view-td view-td-left">
					Phone
				</td><!--
				--><td class="view-td view-td-right">
					{{$branch->phone}}
				</td>
			</tr>
			<tr class="view-tr">
				<td class="view-td view-td-left">
					Email
				</td><!--
				--><td class="view-td view-td-right">
					{{$branch->email}}
				</td>
			</tr>
			<tr class="view-tr">
				<td class="view-td view-td-left">
					Active
				</td><!--
				--><td class="view-td view-td-right">
					{{$branch->is_active == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"}}
				</td>
			</tr>
		</table>
	</section>
@stop