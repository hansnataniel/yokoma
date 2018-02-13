@extends('back.template.master')

@section('title')
	User Group View
@stop

@section('head_additional')

@stop

@section('page_title')
	<a href="{{URL::to(Session::get('last_url'))}}">{{HTML::image('img/admin/back.png', '', array('class'=>'image-img'))}}</a> User Group View
@stop

@section('help')
	<ul style="padding-left: 18px;">
		<!--
		<li>Disini anda dapat melihat data User Group secara keseluruhan.</li>
		-->
		<li>Gunakan tombol Edit untuk masuk ke halaman Edit User Group.</li>
	</ul>
@stop

@section('content')
	<section id="view-container">
		<header id="view-header">
			{{$admingroup->name}}
		</header>

		<div id="view-general-information">
			<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/admingroup/edit/' . $admingroup->id)}}">{{HTML::image('img/admin/edit_view.png', '', array('id'=>'view-edit-button'))}}</a>
			<div id="view-date">
				<span class="view-status">
					<span class="view-cell">Created at</span><span class="view-cell">:</span> <span class="view-cell text-blue">{{date('l, d F Y G:i:s', strtotime($admingroup->created_at))}}</span>
				</span>
				<span class="view-status">
					<span class="view-cell">Updated at</span><span class="view-cell">:</span> <span class="view-cell text-blue">{{date('l, d F Y G:i:s', strtotime($admingroup->updated_at))}}</span>
				</span>
			</div>
		</div>
		<table class="view-information" style="border-spacing: 0px;">
			<tr>
				<td colspan="2">
					<table id="index-table" style="border-spacing: 0px;">
						<tr class="index-tr index-title">
							<th>Permissions</th>
							<th>Create</th>
							<th>Read</th>
							<th>Update</th>
							<th>Delete</th>
						</tr>
						<tr class='index-tr'>
							<td>User Group</td>
							<td>{{$admingroup->admingroup_c == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"}}</td>
							<td>{{$admingroup->admingroup_r == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"}}</td>
							<td>{{$admingroup->admingroup_u == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"}}</td>
							<td>{{$admingroup->admingroup_d == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"}}</td>
						</tr>
						<tr class='index-tr'>
							<td>Setting</td>
							<td></td>
							<td></td>
							<td>{{$admingroup->setting_u == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"}}</td>
							<td></td>
						</tr>
						<tr class='index-tr'>
							<td>User</td>
							<td>{{$admingroup->user_c == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"}}</td>
							<td>{{$admingroup->user_r == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"}}</td>
							<td>{{$admingroup->user_u == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"}}</td>
							<td>{{$admingroup->user_d == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"}}</td>
						</tr>
						<tr class='index-tr'>
							<td>Customer</td>
							<td>{{$admingroup->customer_c == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"}}</td>
							<td>{{$admingroup->customer_r == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"}}</td>
							<td>{{$admingroup->customer_u == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"}}</td>
							<td>{{$admingroup->customer_d == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"}}</td>
						</tr>
						<tr class='index-tr'>
							<td>Branch</td>
							<td>{{$admingroup->branch_c == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"}}</td>
							<td>{{$admingroup->branch_r == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"}}</td>
							<td>{{$admingroup->branch_u == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"}}</td>
							<td>{{$admingroup->branch_d == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"}}</td>
						</tr>
						<tr class='index-tr'>
							<td>Salesman</td>
							<td>{{$admingroup->salesman_c == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"}}</td>
							<td>{{$admingroup->salesman_r == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"}}</td>
							<td>{{$admingroup->salesman_u == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"}}</td>
							<td>{{$admingroup->salesman_d == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"}}</td>
						</tr>
						<tr class='index-tr'>
							<td>Product</td>
							<td>{{$admingroup->product_c == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"}}</td>
							<td>{{$admingroup->product_r == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"}}</td>
							<td>{{$admingroup->product_u == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"}}</td>
							<td>{{$admingroup->product_d == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"}}</td>
						</tr>
						<tr class='index-tr'>
							<td>Sales</td>
							<td>{{$admingroup->sales_c == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"}}</td>
							<td>{{$admingroup->sales_r == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"}}</td>
							<td>{{$admingroup->sales_u == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"}}</td>
							<td>{{$admingroup->sales_d == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"}}</td>
						</tr>
						<tr class='index-tr'>
							<td>Sales Return</td>
							<td>{{$admingroup->salesreturn_c == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"}}</td>
							<td>{{$admingroup->salesreturn_r == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"}}</td>
							<td>{{$admingroup->salesreturn_u == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"}}</td>
							<td>{{$admingroup->salesreturn_d == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"}}</td>
						</tr>
						<tr class='index-tr'>
							<td>Payment</td>
							<td>{{$admingroup->payment_c == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"}}</td>
							<td>{{$admingroup->payment_r == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"}}</td>
							<td>{{$admingroup->payment_u == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"}}</td>
							<td>{{$admingroup->payment_d == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"}}</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<table class="view-information" style="border-spacing: 0px;">
			<tr class="view-tr">
				<td class="view-td view-td-left">
					Active
				</td><!--
				--><td class="view-td view-td-right">
					{{$admingroup->is_active == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"}}
				</td>
			</tr>
		</table>
	</section>
@stop