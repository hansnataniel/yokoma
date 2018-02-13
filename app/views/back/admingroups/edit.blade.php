@extends('back.template.master')

@section('title')
	User Group Edit
@stop

@section('head_additional')
	<script>
		$(document).ready(function(){
			$('.checkAll').click(function(){
		    	$(this).parent().parent().find('.childAll').attr('checked', true);
		    	$(this).parent().parent().find('.childTriggered').attr('disabled', false);
		   	});

		   	$('.uncheckAll').click(function(){
		    	$(this).parent().parent().find('.childAll').attr('checked', false);
		    	$(this).parent().parent().find('.childTriggered').attr('disabled', true);
		   	});

		   	$('.childTrigger').click(function(){
		    	if (!$(this).is(':checked'))
		    	{
		     		$(this).parent().parent().find('.childTriggered').attr('checked', false).attr('disabled', true);
		    	}
		    	if ($(this).is(':checked'))
		    	{
		     		$(this).parent().parent().find('.childTriggered').attr('disabled', false);
		    	}
		   });
		});
	</script>
@stop

@section('page_title')
	<a href="{{URL::to(Session::get('last_url'))}}">{{HTML::image('img/admin/back.png', '', array('class'=>'image-img'))}}</a> User Group Edit
@stop

@section('help')
	<ul style="padding-left: 18px;">
		<li>admingroup digunakan untuk mengelompokkan user berdasarkan hak akses di back end</li>
		<!--
		<li>Anda dapat merubah detail User Group pada halaman ini.</li>
		<li>Field dengan tanda <i>*required</i> itu menandakan kalau field tersebut wajib di isi</li>
		<li>Field dengan tanda <i>*unique</i> itu menandakan kalau field tersebut tidak boleh berisi data yang sama dengan data sebelumnya</li>
		<li>Pilih Active pada Active Status jika User Group yang anda buat mempunyai status aktif, dan sebaliknya.</li>
		-->
	</ul>
@stop

@section('content')
	{{Form::model($admingroup, array('url' => URL::current(), 'method' => 'PUT', 'files' => true))}}
		<section id="edit-container">
			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('name', 'Name')}}
				</div><!--
				--><div class="edit-right">
					{{Form::text('name', null, array('class'=>'medium-text', 'required'))}}
					<span class="required-tx">
						*Required and Unique
					</span>
				</div>
			</div>
			<br/>
			<table id="index-table" style="border-spacing: 0px;">
				<tr class="index-tr index-title">
					<th>Permissions</th>
					<th>Select All</th>
					<th>Create</th>
					<th>Read</th>
					<th>Update</th>
					<th>Delete</th>
					<th></th>
				</tr>
				<tr class='index-tr'>
					<td>User Group</td>
					<td>
						{{Form::label('selectall', 'Check All', array('class'=>'question-label checkAll'))}} / {{Form::label('selectAll', 'Uncheck All', array('class'=>'question-label uncheckAll'))}}
					</td>
					<td>
						{{Form::checkbox('admingroup_c', true, false, array('class'=>'childAll'))}}
						<div class="checkClose"></div>
					</td>
					<td>
						{{Form::checkbox('admingroup_r', true, false, array('class'=>'childAll childTrigger'))}}
						<div class="checkClose"></div>
					</td>
					<td>
						{{Form::checkbox('admingroup_u', true, false, array('class'=>'childAll childTriggered'))}}
						<div class="checkClose"></div>
					</td>
					<td>
						{{Form::checkbox('admingroup_d', true, false, array('class'=>'childAll childTriggered'))}}
						<div class="checkClose"></div>
					</td>
				</tr>
				<tr class='index-tr'>
					<td>Setting</td>
					<td>
						{{Form::label('selectall', 'Check All', array('class'=>'question-label checkAll'))}} / {{Form::label('selectAll', 'Uncheck All', array('class'=>'question-label uncheckAll'))}}
					</td>
					<td>

					</td>
					<td>

					</td>
					<td>
						{{Form::checkbox('setting_u', true, false, array('class'=>'childAll childTriggered'))}}
						<div class="checkClose"></div>
					</td>
					<td>

					</td>
				</tr>
				<tr class='index-tr'>
					<td>User</td>
					<td>
						{{Form::label('selectall', 'Check All', array('class'=>'question-label checkAll'))}} / {{Form::label('selectAll', 'Uncheck All', array('class'=>'question-label uncheckAll'))}}
					</td>
					<td>
						{{Form::checkbox('user_c', true, false, array('class'=>'childAll'))}}
						<div class="checkClose"></div>
					</td>
					<td>
						{{Form::checkbox('user_r', true, false, array('class'=>'childAll childTrigger'))}}
						<div class="checkClose"></div>
					</td>
					<td>
						{{Form::checkbox('user_u', true, false, array('class'=>'childAll childTriggered'))}}
						<div class="checkClose"></div>
					</td>
					<td>
						{{Form::checkbox('user_d', true, false, array('class'=>'childAll childTriggered'))}}
						<div class="checkClose"></div>
					</td>
				</tr>
				<tr class='index-tr'>
					<td>Customer</td>
					<td>
						{{Form::label('selectall', 'Check All', array('class'=>'question-label checkAll'))}} / {{Form::label('selectAll', 'Uncheck All', array('class'=>'question-label uncheckAll'))}}
					</td>
					<td>
						{{Form::checkbox('customer_c', true, false, array('class'=>'childAll'))}}
						<div class="checkClose"></div>
					</td>
					<td>
						{{Form::checkbox('customer_r', true, false, array('class'=>'childAll childTrigger'))}}
						<div class="checkClose"></div>
					</td>
					<td>
						{{Form::checkbox('customer_u', true, false, array('class'=>'childAll childTriggered'))}}
						<div class="checkClose"></div>
					</td>
					<td>
						{{Form::checkbox('customer_d', true, false, array('class'=>'childAll childTriggered'))}}
						<div class="checkClose"></div>
					</td>
				</tr>
				<tr class='index-tr'>
					<td>Branch</td>
					<td>
						{{Form::label('selectall', 'Check All', array('class'=>'question-label checkAll'))}} / {{Form::label('selectAll', 'Uncheck All', array('class'=>'question-label uncheckAll'))}}
					</td>
					<td>
						{{Form::checkbox('branch_c', true, false, array('class'=>'childAll'))}}
						<div class="checkClose"></div>
					</td>
					<td>
						{{Form::checkbox('branch_r', true, false, array('class'=>'childAll childTrigger'))}}
						<div class="checkClose"></div>
					</td>
					<td>
						{{Form::checkbox('branch_u', true, false, array('class'=>'childAll childTriggered'))}}
						<div class="checkClose"></div>
					</td>
					<td>
						{{Form::checkbox('branch_d', true, false, array('class'=>'childAll childTriggered'))}}
						<div class="checkClose"></div>
					</td>
				</tr>
				<tr class='index-tr'>
					<td>Salesman</td>
					<td>
						{{Form::label('selectall', 'Check All', array('class'=>'question-label checkAll'))}} / {{Form::label('selectAll', 'Uncheck All', array('class'=>'question-label uncheckAll'))}}
					</td>
					<td>
						{{Form::checkbox('salesman_c', true, false, array('class'=>'childAll'))}}
						<div class="checkClose"></div>
					</td>
					<td>
						{{Form::checkbox('salesman_r', true, false, array('class'=>'childAll childTrigger'))}}
						<div class="checkClose"></div>
					</td>
					<td>
						{{Form::checkbox('salesman_u', true, false, array('class'=>'childAll childTriggered'))}}
						<div class="checkClose"></div>
					</td>
					<td>
						{{Form::checkbox('salesman_d', true, false, array('class'=>'childAll childTriggered'))}}
						<div class="checkClose"></div>
					</td>
				</tr>
				<tr class='index-tr'>
					<td>Product</td>
					<td>
						{{Form::label('selectall', 'Check All', array('class'=>'question-label checkAll'))}} / {{Form::label('selectAll', 'Uncheck All', array('class'=>'question-label uncheckAll'))}}
					</td>
					<td>
						{{Form::checkbox('product_c', true, false, array('class'=>'childAll'))}}
						<div class="checkClose"></div>
					</td>
					<td>
						{{Form::checkbox('product_r', true, false, array('class'=>'childAll childTrigger'))}}
						<div class="checkClose"></div>
					</td>
					<td>
						{{Form::checkbox('product_u', true, false, array('class'=>'childAll childTriggered'))}}
						<div class="checkClose"></div>
					</td>
					<td>
						{{Form::checkbox('product_d', true, false, array('class'=>'childAll childTriggered'))}}
						<div class="checkClose"></div>
					</td>
				</tr>
				<tr class='index-tr'>
					<td>Sales</td>
					<td>
						{{Form::label('selectall', 'Check All', array('class'=>'question-label checkAll'))}} / {{Form::label('selectAll', 'Uncheck All', array('class'=>'question-label uncheckAll'))}}
					</td>
					<td>
						{{Form::checkbox('sales_c', true, false, array('class'=>'childAll'))}}
						<div class="checkClose"></div>
					</td>
					<td>
						{{Form::checkbox('sales_r', true, false, array('class'=>'childAll childTrigger'))}}
						<div class="checkClose"></div>
					</td>
					<td>
						{{Form::checkbox('sales_u', true, false, array('class'=>'childAll childTriggered'))}}
						<div class="checkClose"></div>
					</td>
					<td>
						{{Form::checkbox('sales_d', true, false, array('class'=>'childAll childTriggered'))}}
						<div class="checkClose"></div>
					</td>
				</tr>
				<tr class='index-tr'>
					<td>Sales Return</td>
					<td>
						{{Form::label('selectall', 'Check All', array('class'=>'question-label checkAll'))}} / {{Form::label('selectAll', 'Uncheck All', array('class'=>'question-label uncheckAll'))}}
					</td>
					<td>
						{{Form::checkbox('salesreturn_c', true, false, array('class'=>'childAll'))}}
						<div class="checkClose"></div>
					</td>
					<td>
						{{Form::checkbox('salesreturn_r', true, false, array('class'=>'childAll childTrigger'))}}
						<div class="checkClose"></div>
					</td>
					<td>
						{{Form::checkbox('salesreturn_u', true, false, array('class'=>'childAll childTriggered'))}}
						<div class="checkClose"></div>
					</td>
					<td>
						{{Form::checkbox('salesreturn_d', true, false, array('class'=>'childAll childTriggered'))}}
						<div class="checkClose"></div>
					</td>
				</tr>
				<tr class='index-tr'>
					<td>Payment</td>
					<td>
						{{Form::label('selectall', 'Check All', array('class'=>'question-label checkAll'))}} / {{Form::label('selectAll', 'Uncheck All', array('class'=>'question-label uncheckAll'))}}
					</td>
					<td>
						{{Form::checkbox('payment_c', true, false, array('class'=>'childAll'))}}
						<div class="checkClose"></div>
					</td>
					<td>
						{{Form::checkbox('payment_r', true, false, array('class'=>'childAll childTrigger'))}}
						<div class="checkClose"></div>
					</td>
					<td>
						{{Form::checkbox('payment_u', true, false, array('class'=>'childAll childTriggered'))}}
						<div class="checkClose"></div>
					</td>
					<td>
						{{Form::checkbox('payment_d', true, false, array('class'=>'childAll childTriggered'))}}
						<div class="checkClose"></div>
					</td>
				</tr>
			</table>
			<br/><br/>
			<div class="edit-group">
				<div class="edit-left">
					{{Form::label('is_active', 'Active')}}
				</div><!--
				--><div class="edit-right">
					{{Form::radio('is_active', 1, 1, array('class'=>'quiz-radio', 'id'=>'true'))}} {{Form::label('true', 'Active', array('class'=>'question-label'))}}<br>
					{{Form::radio('is_active', 0, 0, array('class'=>'quiz-radio', 'id'=>'false'))}} {{Form::label('false', 'Not Active', array('class'=>'question-label'))}}
				</div>
			</div>
			<div class="edit-group">
				<div class="edit-left">
				</div><!--
				--><div class="edit-right">
					{{Form::submit('Save', array('class'=>'edit-submit margin'))}}
					{{Form::button('Reset', array('class'=>'edit-submit', 'id'=>'reset'))}}
					<section id="blur">
						<div id="blur-question">
							<span id="blur-text">Do you really want to reset this form?</span>
							{{Form::reset('Yes', array('class'=>'blur-submit blur-left'))}}
							{{Form::button('Cancel', array('class'=>'blur-submit cancel'))}}
						</div>
					</section>
				</div>
			</div>
		</section>
	{{Form::close()}}
@stop