@extends('back.template.master')

@section('title')
	Dashboard Admin
@stop

@section('head_additional')
	<script>
		$(document).ready(function(){
			$('#page-content').css({'padding': '0px', 'border-bottom': '0px'});
			$('#message').css({'margin': '0px'});
		});
	</script>
@stop

@section('page_title')
	Dashboard
@stop

@section('help')
	<ul style="padding-left: 18px;">
		<li>Klik Shortcut yang ada di halaman ini untuk mempercepat menuju halaman yang dituju.</li>
		<li>Gunakan tombol Sign Out untuk Sign Out dan keluar dari halaman Admin.</li>
	</ul>
@stop

@section('content')
	<section id="dashboard-content">
		<article class="dashboard-group">
				<header class="dashboard-header-group">
					<div class="dashboard-header-title">
						Notification
					</div>
					<div class="dashboard-header-desc">
						<!-- Description Notification -->
					</div>
				</header>
				<div class="dashboard-notiv">
					<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/request-update-sales?src_name=&src_status=Waiting+Cancelation&order_by=id&order_method=asc')}}" target="_blank" style="text-decoration: none;">
						<div class="dashboard-notiv-item">
							<?php $updatesales = Updatesale::where('status', '=', 'Waiting Cancelation')->get(); ?>
							Terdapat {{count($updatesales)}} Request Pembatalan Nota yang belum terbatalkan
						</div>
					</a>
				
					<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/request-update-sales?src_name=&src_status=Waiting+Confirmation+for+Admin&order_by=id&order_method=asc')}}" target="_blank" style="text-decoration: none;">
						<div class="dashboard-notiv-item">
							<?php $updatesales = Updatesale::where('status', '=', 'Waiting Confirmation for Admin')->get(); ?>
							Terdapat {{count($updatesales)}} Request Perubahan Nota yang belum di ijinkan
						</div>
					</a>
				</div>
			</article>
		<article class="dashboard-group">
			<header class="dashboard-header-group">
				<div class="dashboard-header-title">
					Navigation
				</div>
				<div class="dashboard-header-desc">
					Navigation lets you manage, add, and edit the pages on your site
				</div>
			</header>
			<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/branch')}}" style="display: inline-block; vertical-align-top;">
				<section class="dashboard-icon-group" id="0">
					<div class="dashboard-icon-image">
						{{HTML::image('img/admin/branch.png', '', array('class'=>'dash-icon'))}}
					</div>
					<div class="dashboard-icon-title">
						Branch
					</div>
				</section>
			</a><!--
			--><a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/user')}}" style="display: inline-block; vertical-align-top;">
				<section class="dashboard-icon-group" id="0">
					<div class="dashboard-icon-image">
						{{HTML::image('img/admin/user.png', '', array('class'=>'dash-icon'))}}
					</div>
					<div class="dashboard-icon-title">
						User
					</div>
				</section>
			</a><!--
			--><a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/salesman')}}" style="display: inline-block; vertical-align-top;">
				<section class="dashboard-icon-group" id="0">
					<div class="dashboard-icon-image">
						{{HTML::image('img/admin/salesman.png', '', array('class'=>'dash-icon'))}}
					</div>
					<div class="dashboard-icon-title">
						Salesman
					</div>
				</section>
			</a><!--
			--><a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/customer')}}" style="display: inline-block; vertical-align-top;">
				<section class="dashboard-icon-group" id="0">
					<div class="dashboard-icon-image">
						{{HTML::image('img/admin/customer.png', '', array('class'=>'dash-icon'))}}
					</div>
					<div class="dashboard-icon-title">
						Customer
					</div>
				</section>
			</a><!--
			--><a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/product')}}" style="display: inline-block; vertical-align-top;">
				<section class="dashboard-icon-group" id="0">
					<div class="dashboard-icon-image">
						{{HTML::image('img/admin/product.png', '', array('class'=>'dash-icon'))}}
					</div>
					<div class="dashboard-icon-title">
						Product
					</div>
				</section>
			</a><!--
			-->
			<?php $branch = Branch::first(); ?>
			@if($branch != null)
				<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales/index/' . $branch->id)}}" style="display: inline-block; vertical-align-top;">
					<section class="dashboard-icon-group" id="0">
						<div class="dashboard-icon-image">
							{{HTML::image('img/admin/sales.png', '', array('class'=>'dash-icon'))}}
						</div>
						<div class="dashboard-icon-title">
							Nota
						</div>
					</section>
				</a>
			@endif
			<!--
			-->
			@if($branch != null)
				<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/payment/index/' . $branch->id)}}" style="display: inline-block; vertical-align-top;">
					<section class="dashboard-icon-group" id="0">
						<div class="dashboard-icon-image">
							{{HTML::image('img/admin/payment.png', '', array('class'=>'dash-icon'))}}
						</div>
						<div class="dashboard-icon-title">
							Penerimaan Piutang
						</div>
					</section>
				</a>
			@endif
			<div class="dashboard-icon-hover"></div>
		</article>
		<article class="dashboard-group">
			<header class="dashboard-header-group">
				<div class="dashboard-header-title">
					Master
				</div>
				<div class="dashboard-header-desc">
					Master lets you manage, add, and edit the pages on your site
				</div>
			</header>
			<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/user/edit-profile')}}" style="display: inline-block; vertical-align-top;">
				<section class="dashboard-icon-group" id="0">
					<div class="dashboard-icon-image">
						{{HTML::image('img/admin/edit_profile.png', '', array('class'=>'dash-icon'))}}
					</div>
					<div class="dashboard-icon-title">
						Edit Profile
					</div>
				</section>
			</a><!--
			--><a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/setting/edit')}}" style="display: inline-block; vertical-align-top;">
				<section class="dashboard-icon-group" id="1">
					<div class="dashboard-icon-image">
						{{HTML::image('img/admin/setting.png', '', array('class'=>'dash-icon'))}}
					</div>
					<div class="dashboard-icon-title">
						Setting
					</div>
				</section>
			</a><!--
			--><a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/logout')}}" style="display: inline-block; vertical-align-top;">
				<section class="dashboard-icon-group" id="2">
					<div class="dashboard-icon-image">
						{{HTML::image('img/admin/logout.png', '', array('class'=>'dash-icon'))}}
					</div>
					<div class="dashboard-icon-title">
						Log Out
					</div>
				</section>
			</a>
			<div class="dashboard-icon-hover"></div>
		</article>
	</section>
@stop