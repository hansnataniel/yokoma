<?php
	$setting = Setting::first();
?>
<section class="nav-section not-margin-top">
	<a href="{{URL::to('dashboard')}}"><div class="nav-title nav-title-link" title="Dashboard">
		Dashboard
	</div></a>
	<div class="separator"></div>
</section>
<section class="nav-section">
	<div class="nav-title">
		Menu
	</div>
	<div class="nav-group">
		<div class="nav-item-group">
			<nav class="nav-item">
				<a href="{{URL::to('salesman')}}" class="nav-link" title="Salesman Management">
					Salesman
				</a>
				<div class="nav-icon-group">
					<a href="{{URL::to('salesman/create')}}">{{HTML::image('img/admin/icon_add.png', '', array('class'=>'nav-icon', 'title'=>'Add New Salesman'))}}</a>
				</div>
			</nav>
		
			<nav class="nav-item">
				<a href="{{URL::to('customer')}}" class="nav-link" title="Customer Management">
					Customer
				</a>
				<div class="nav-icon-group">
					<a href="{{URL::to('customer/create')}}">{{HTML::image('img/admin/icon_add.png', '', array('class'=>'nav-icon', 'title'=>'Add New Customer'))}}</a>
				</div>
			</nav>
		
			<nav class="nav-item">
				<a href="{{URL::to('inventory-update/')}}" class="nav-link" title="Penyesuain Stock Management">
					Penyesuain Stock
				</a>
			</nav>
		
			<nav class="nav-item">
				<a href="{{URL::to('inventory-product')}}" class="nav-link" title="Posisi Stock Akhir Management">
					Posisi Stock Akhir
				</a>
			</nav>

			<nav class="nav-item">
				<a href="{{URL::to('pembelian')}}" class="nav-link" title="Pembelian Management">
					Pembelian
				</a>
				<div class="nav-icon-group">
					<a href="{{URL::to('pembelian/create')}}">{{HTML::image('img/admin/icon_add.png', '', array('class'=>'nav-icon', 'title'=>'Add New Pembelian'))}}</a>
				</div>
			</nav>

			<nav class="nav-master">
				<nav class="nav-sub toggle">
					Nota
					<div class="dropdown1"></div>
				</nav>
				<nav class="sub">
					<nav class="nav-item">
						<a href="{{URL::to('sales')}}" class="nav-link" title="Nota Management">
							{{HTML::image('img/admin/sub_navigation.png', '', array('class'=>'sub-nav'))}} 
							Nota
						</a>
						<div class="nav-icon-group">
							<a href="{{URL::to('sales/create')}}">{{HTML::image('img/admin/icon_add.png', '', array('class'=>'nav-icon', 'title'=>'Add New Nota'))}}</a>
						</div>
					</nav>
				
					<nav class="nav-item">
						<a href="{{URL::to('request-update-sales')}}" class="nav-link" title="Status Perubahan Nota Management">
							{{HTML::image('img/admin/sub_navigation.png', '', array('class'=>'sub-nav'))}} 
							Status Perubahan Nota
						</a>
					</nav>

					<nav class="nav-item">
						<a href="{{URL::to('pembulatan-nota')}}" class="nav-link" title="Pembulatan Nota Management">
							{{HTML::image('img/admin/sub_navigation.png', '', array('class'=>'sub-nav'))}} 
							Pembulatan Nota 
						</a>
					</nav>
				</nav>
			</nav>
		
			{{-- <nav class="nav-item">
				<a href="{{URL::to('sales-return')}}" class="nav-link" title="Sales Return Management">
					Sales Return
				</a>
				<div class="nav-icon-group">
					<a href="{{URL::to('sales-return/create')}}">{{HTML::image('img/admin/icon_add.png', '', array('class'=>'nav-icon', 'title'=>'Add New Sales Return'))}}</a>
				</div>
			</nav> --}}

			<nav class="nav-item">
				<a href="{{URL::to('payment')}}" class="nav-link" title="Penerimaan Piutang">
					Penerimaan Piutang
				</a>
				<div class="nav-icon-group">
					<a href="{{URL::to('payment/create')}}">{{HTML::image('img/admin/icon_add.png', '', array('class'=>'nav-icon', 'title'=>'Add New Penerimaan Piutang'))}}</a>
				</div>
			</nav>

			{{-- <nav class="nav-item">
				<a href="{{URL::to('sales-due-date')}}" class="nav-link" title="Sales Due Date Management">
					Overdue Receivable
				</a>
			</nav> --}}

			<nav class="nav-master">
				<nav class="nav-sub toggle">
					Accu Mati
					<div class="dropdown1"></div>
				</nav>
				<nav class="sub">
					<nav class="nav-item">
						<a href="{{URL::to('import-second-product')}}" class="nav-link" title="Accu Mati Purchase">
							{{HTML::image('img/admin/sub_navigation.png', '', array('class'=>'sub-nav'))}} 
							Pembelian Accu Mati
						</a>
						<div class="nav-icon-group">
							<a href="{{URL::to('import-second-product/create')}}">{{HTML::image('img/admin/icon_add.png', '', array('class'=>'nav-icon', 'title'=>'Add New Accu Mati Purchase'))}}</a>
						</div>
					</nav>

					<nav class="nav-item">
						<a href="{{URL::to('inventory-recycle')}}" class="nav-link" title="Accu Mati Item Inventory Management">
							{{HTML::image('img/admin/sub_navigation.png', '', array('class'=>'sub-nav'))}} 
							Persediaan Accu Mati
						</a>
					</nav>
				</nav>
			</nav>
		
			<nav class="nav-master">
				<nav class="nav-sub toggle">
					Klaim
					<div class="dropdown1"></div>
				</nav>
				<nav class="sub">
					<nav class="nav-item">
						<a href="{{URL::to('product-repair')}}" class="nav-link" title="Product Klaim Management">
							{{HTML::image('img/admin/sub_navigation.png', '', array('class'=>'sub-nav'))}} 
							Klaim Item
						</a>
						<div class="nav-icon-group">
							<a href="{{URL::to('product-repair/create')}}">{{HTML::image('img/admin/icon_add.png', '', array('class'=>'nav-icon', 'title'=>'Add New Klaim Item'))}}</a>
						</div>
					</nav>

					<nav class="nav-item">
						<a href="{{URL::to('inventory-repair')}}" class="nav-link" title="Klaim Item Inventory Management">
							{{HTML::image('img/admin/sub_navigation.png', '', array('class'=>'sub-nav'))}} 
							Klaim Item Inventory
						</a>
					</nav>
				</nav>
			</nav>

			<nav class="nav-item">
				<a href="{{URL::to('account-receivable')}}" class="nav-link" title="Laporan Piutang Management">
					Laporan Piutang
				</a>
			</nav>

			<nav class="nav-item">
				<a href="{{URL::to('laporan-penerimaan-piutang')}}" class="nav-link" title="Laporan Penerimaan Piutang Management">
					Laporan Penerimaan Piutang
				</a>
			</nav>
		
			<nav class="nav-item">
				<a href="{{URL::to('sales-report')}}" class="nav-link" title="Sales Report Management">
					Laporan Penjualan
				</a>
			</nav>

			<nav class="nav-item">
				<a href="{{URL::to('laporan-accu-mati')}}" class="nav-link" title="Laporan Penjualan Accu Mati Management">
					Laporan Penjualan Accu Mati
				</a>
			</nav>

			<nav class="nav-item">
				<a href="{{URL::to('stock-cart')}}" class="nav-link" title="Laporan Kartu Stock">
					Laporan Kartu Stock
				</a>
			</nav>

			<nav class="nav-item">
				<a href="{{URL::to('commission-report')}}" class="nav-link" title="Laporan Komisi Sales Management">
					Laporan Komisi Sales
				</a>
			</nav>
			<nav class="nav-item">
				<a href="{{URL::to('user/edit-profile')}}" class="nav-link" title="Profile">
					Profile
				</a>
			</nav>
		</div>
	</div>
	<div class="separator"></div>
</section>
<section class="nav-section no-margin-bottom">
	<a href="{{URL::to('logout')}}"><div class="nav-title nav-title-link" title="Log Out">
		Log Out
	</div></a>
</section>