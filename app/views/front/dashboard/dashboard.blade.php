@extends('front.template.master')

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
					Navigation
				</div>
				<div class="dashboard-header-desc">
					Navigation lets you manage, add, and edit the pages on your site
				</div>
			</header>
			<a href="{{URL::to('customer')}}" style="display: inline-block; vertical-align-top;">
				<section class="dashboard-icon-group" id="0">
					<div class="dashboard-icon-image">
						{{HTML::image('img/admin/budi_jaya_costumer.png', '', array('class'=>'dash-icon'))}}
					</div>
					<div class="dashboard-icon-title">
						Customer
					</div>
				</section>
			</a><!--
			--><a href="{{URL::to('salesman')}}" style="display: inline-block; vertical-align-top;">
				<section class="dashboard-icon-group" id="0">
					<div class="dashboard-icon-image">
						{{HTML::image('img/admin/budi_jaya_salesman.png', '', array('class'=>'dash-icon'))}}
					</div>
					<div class="dashboard-icon-title">
						Salesman
					</div>
				</section>
			</a><!--
			--><a href="{{URL::to('sales')}}" style="display: inline-block; vertical-align-top;">
				<section class="dashboard-icon-group" id="0">
					<div class="dashboard-icon-image">
						{{HTML::image('img/admin/budi_jaya_sales.png', '', array('class'=>'dash-icon'))}}
					</div>
					<div class="dashboard-icon-title">
						Nota
					</div>
				</section>
			</a><!--
			--><a href="{{URL::to('import-second-product')}}" style="display: inline-block; vertical-align-top;">
				<section class="dashboard-icon-group" id="0">
					<div class="dashboard-icon-image">
						{{HTML::image('img/admin/budi_jaya_recycle.png', '', array('class'=>'dash-icon'))}}
					</div>
					<div class="dashboard-icon-title">
						Accu Mati
					</div>
				</section>
			</a><!--
			--><a href="{{URL::to('product-repair')}}" style="display: inline-block; vertical-align-top;">
				<section class="dashboard-icon-group" id="0">
					<div class="dashboard-icon-image">
						{{HTML::image('img/admin/budi_jaya_repair.png', '', array('class'=>'dash-icon'))}}
					</div>
					<div class="dashboard-icon-title">
						Klaim
					</div>
				</section>
			</a><!--
			--><a href="{{URL::to('payment')}}" style="display: inline-block; vertical-align-top;">
				<section class="dashboard-icon-group" id="0">
					<div class="dashboard-icon-image">
						{{HTML::image('img/admin/budi_jaya_payment.png', '', array('class'=>'dash-icon'))}}
					</div>
					<div class="dashboard-icon-title">
						Penerimaan Piutang
					</div>
				</section>
			</a>
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
			<a href="{{URL::to('logout')}}" style="display: inline-block; vertical-align-top;">
				<section class="dashboard-icon-group" id="2">
					<div class="dashboard-icon-image">
						{{HTML::image('img/admin/budi_jaya_logout.png', '', array('class'=>'dash-icon'))}}
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