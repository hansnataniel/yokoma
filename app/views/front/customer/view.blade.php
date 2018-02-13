@extends('front.template.master')

@section('title')
	Customer View
@stop

@section('head_additional')

@stop

@section('page_title')
	<a href="{{URL::to(Session::get('last_url'))}}">{{HTML::image('img/admin/back.png', '', array('class'=>'image-img'))}}</a> Customer View
@stop

@section('help')
	<ul style="padding-left: 18px;">
		<li>Disini anda dapat melihat data Customer secara keseluruhan.</li>
		<li>Gunakan tombol Edit untuk masuk ke halaman Edit Promo.</li>
	</ul>
@stop

@section('content')
	<section id="view-container">
		<div id="view-general-information">
			<a href="{{URL::to('customer/edit/' . $customer->id)}}">{{HTML::image('img/admin/edit_view.png', '', array('id'=>'view-edit-button'))}}</a>
			<div id="view-date">
				<span class="view-status">
					<span class="view-cell">Created at</span><span class="view-cell">:</span> <span class="view-cell text-blue">{{date('l, d F Y G:i:s', strtotime($customer->created_at))}}</span>
				</span>
				<span class="view-status">
					<span class="view-cell">Updated at</span><span class="view-cell">:</span> <span class="view-cell text-blue">{{date('l, d F Y G:i:s', strtotime($customer->updated_at))}}</span>
				</span>
			</div>
			@if (file_exists(public_path() . '/usr/img/customer/' . $customer->id . '_' . Str::slug($customer->name, '_') . '.jpg'))
				{{HTML::image('usr/img/customer/' . $customer->id . '_' . Str::slug($customer->name, '_') . '.jpg', '', array('class'=>'view-photo'))}}
			@endif
		</div>
		<table class="view-information" style="border-spacing: 0px;">
			<tr class="view-tr">
				<td class="view-td view-td-left">
					Branch
				</td><!--
				--><td class="view-td view-td-right">
					{{$customer->branch->name}}
				</td>
			</tr>
			<tr class="view-tr">
				<td class="view-td view-td-left">
					Name
				</td><!--
				--><td class="view-td view-td-right">
					{{$customer->name}}
				</td>
			</tr>
			<tr class="view-tr">
				<td class="view-td view-td-left">
					Address
				</td><!--
				--><td class="view-td view-td-right">
					{{$customer->address}}
				</td>
			</tr>
			<tr class="view-tr">
				<td class="view-td view-td-left">
					No. Telephone
				</td><!--
				--><td class="view-td view-td-right">
					{{$customer->no_telp}}
				</td>
			</tr>
			<tr class="view-tr">
				<td class="view-td view-td-left">
					CP Name
				</td><!--
				--><td class="view-td view-td-right">
					{{$customer->cp_name}}
				</td>
			</tr>
			<tr class="view-tr">
				<td class="view-td view-td-left">
					CP No. HP
				</td><!--
				--><td class="view-td view-td-right">
					{{$customer->cp_no_hp}}
				</td>
			</tr>
			<?php
				$salesman1 = Salesman::find($customer->salesman_id1);
				$salesman2 = Salesman::find($customer->salesman_id2);
			?>
			@if($salesman1 != null)
				<tr class="view-tr">
					<td class="view-td view-td-left">
						Salesman1 / commission
					</td><!--
					--><td class="view-td view-td-right">
						{{$salesman1->name}} / {{$customer->commission1}}%
					</td>
				</tr>
			@endif
			@if($salesman2 != null)
				<tr class="view-tr">
					<td class="view-td view-td-left">
						Salesman2 / commission
					</td><!--
					--><td class="view-td view-td-right">
						{{$salesman2->name}} / 
						@if($customer->from_net == true)
							{{$customer->commission1}}+{{$customer->commission2}}
						@else
							{{$customer->commission2}}
						@endif
						%
					</td>
				</tr>
			@endif
			<tr class="view-tr">
				<td class="view-td view-td-left">
					Debt Maturity
				</td><!--
				--><td class="view-td view-td-right">
					{{$customer->due_date}} days
				</td>
			</tr>
			<tr class="view-tr">
				<td class="view-td view-td-left">
					Active
				</td><!--
				--><td class="view-td view-td-right">
					{{$customer->is_active == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"}}
				</td>
			</tr>
		</table>
	</section>
@stop