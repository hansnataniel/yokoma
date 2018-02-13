@extends('front.template.master')

@section('title')
	Salesman View
@stop

@section('head_additional')

@stop

@section('page_title')
	<a href="{{URL::to(Session::get('last_url'))}}">{{HTML::image('img/admin/back.png', '', array('class'=>'image-img'))}}</a> Salesman View
@stop

@section('help')
	<ul style="padding-left: 18px;">
		<li>Disini anda dapat melihat data Salesman secara keseluruhan.</li>
		<li>Gunakan tombol Edit untuk masuk ke halaman Edit Promo.</li>
	</ul>
@stop

@section('content')
	<section id="view-container">
		<div id="view-general-information">
			<a href="{{URL::to('salesman/edit/' . $salesman->id)}}">{{HTML::image('img/admin/edit_view.png', '', array('id'=>'view-edit-button'))}}</a>
			<div id="view-date">
				<span class="view-status">
					<span class="view-cell">Created at</span><span class="view-cell">:</span> <span class="view-cell text-blue">{{date('l, d F Y G:i:s', strtotime($salesman->created_at))}}</span>
				</span>
				<span class="view-status">
					<span class="view-cell">Updated at</span><span class="view-cell">:</span> <span class="view-cell text-blue">{{date('l, d F Y G:i:s', strtotime($salesman->updated_at))}}</span>
				</span>
			</div>
			@if (file_exists(public_path() . '/usr/img/salesman/' . $salesman->id . '_' . Str::slug($salesman->name, '_') . '.jpg'))
				{{HTML::image('usr/img/salesman/' . $salesman->id . '_' . Str::slug($salesman->name, '_') . '.jpg', '', array('class'=>'view-photo'))}}
			@endif
		</div>
		<table class="view-information" style="border-spacing: 0px;">
			<tr class="view-tr">
				<td class="view-td view-td-left">
					Name
				</td><!--
				--><td class="view-td view-td-right">
					{{$salesman->name}}
				</td>
			</tr>
			<tr class="view-tr">
				<td class="view-td view-td-left">
					Address
				</td><!--
				--><td class="view-td view-td-right">
					{{$salesman->address}}
				</td>
			</tr>
			<tr class="view-tr">
				<td class="view-td view-td-left">
					Phone
				</td><!--
				--><td class="view-td view-td-right">
					{{$salesman->no_hp}}
				</td>
			</tr>
			<tr class="view-tr">
				<td class="view-td view-td-left">
					Active
				</td><!--
				--><td class="view-td view-td-right">
					{{$salesman->is_active == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"}}
				</td>
			</tr>
		</table>
	</section>
@stop