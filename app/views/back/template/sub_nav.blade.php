<?php
	$setting = Setting::first();
?>
<section class="nav-section not-margin-top">
	<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/dashboard')}}"><div class="nav-title nav-title-link" title="Dashboard">
		Dashboard
	</div></a>
	<div class="separator"></div>
</section>
<section class="nav-section">
	<div class="nav-title">
		Navigation
	</div>
	<div class="nav-group">
		<div class="nav-item-group">
			<nav class="nav-item">
				<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/branch')}}" class="nav-link" title="Branch Management">
					Branch
				</a>
				<div class="nav-icon-group">
					<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/branch/create')}}">{{HTML::image('img/admin/icon_add.png', '', array('class'=>'nav-icon', 'title'=>'Add New Branch'))}}</a>
				</div>
			</nav>
		
			<nav class="nav-item">
				<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/user')}}" class="nav-link" title="User Management">
					User
				</a>
				<div class="nav-icon-group">
					<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/user/create')}}">{{HTML::image('img/admin/icon_add.png', '', array('class'=>'nav-icon', 'title'=>'Add New User'))}}</a>
				</div>
			</nav>
		
			<nav class="nav-item">
				<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/salesman')}}" class="nav-link" title="Salesman Management">
					Salesman
				</a>
				<div class="nav-icon-group">
					<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/salesman/create')}}">{{HTML::image('img/admin/icon_add.png', '', array('class'=>'nav-icon', 'title'=>'Add New Salesman'))}}</a>
				</div>
			</nav>
		
			<nav class="nav-item">
				<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/customer')}}" class="nav-link" title="Customer Management">
					Customer
				</a>
				<div class="nav-icon-group">
					<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/customer/create')}}">{{HTML::image('img/admin/icon_add.png', '', array('class'=>'nav-icon', 'title'=>'Add New Customer'))}}</a>
				</div>
			</nav>
	
			<nav class="nav-item">
				<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/product')}}" class="nav-link" title="Product Management">
					Product
				</a>
				<div class="nav-icon-group">
					<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/product/create')}}">{{HTML::image('img/admin/icon_add.png', '', array('class'=>'nav-icon', 'title'=>'Add New Product'))}}</a>
				</div>
			</nav>

			<?php $branch = Branch::first(); ?>
			@if($branch != null)
				<nav class="nav-item">
					<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/inventory-update/index/' . $branch->id)}}" class="nav-link" title="Penyesuaian Stock Management">
						Penyesuaian Stock
					</a>
				</nav>
			@endif

			@if($branch != null)
				<nav class="nav-item">
					<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/inventory-product/index/' . $branch->id)}}" class="nav-link" title="Posisi Stock Akhir Management">
						Posisi Stock Akhir
					</a>
				</nav>
			@endif

			<nav class="nav-item">
				<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/pembelian')}}" class="nav-link" title="Pembelian Management">
					Pembelian
				</a>
				<div class="nav-icon-group">
					<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/pembelian/create')}}">{{HTML::image('img/admin/icon_add.png', '', array('class'=>'nav-icon', 'title'=>'Add New Pembelian'))}}</a>
				</div>
			</nav>

			<nav class="nav-master">
				<nav class="nav-sub toggle">
					Nota
					<div class="dropdown1"></div>
				</nav>
				<nav class="sub">
					@if($branch != null)
						<nav class="nav-item">
							<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales/index/' . $branch->id)}}" class="nav-link" title="Nota Management">
								{{HTML::image('img/admin/sub_navigation.png', '', array('class'=>'sub-nav'))}} 
								Nota
							</a>
							<div class="nav-icon-group">
								<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales/create')}}">{{HTML::image('img/admin/icon_add.png', '', array('class'=>'nav-icon', 'title'=>'Add New Nota'))}}</a>
							</div>
						</nav>
					@endif
				
					<nav class="nav-item">
						<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/request-update-sales')}}" class="nav-link" title="Status Perubahan Nota  Management">
							{{HTML::image('img/admin/sub_navigation.png', '', array('class'=>'sub-nav'))}} 
							Status Perubahan Nota 
						</a>
					</nav>

					<nav class="nav-item">
						<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/pembulatan-nota')}}" class="nav-link" title="Pembulatan Nota Management">
							{{HTML::image('img/admin/sub_navigation.png', '', array('class'=>'sub-nav'))}} 
							Pembulatan Nota 
						</a>
					</nav>
					
				</nav>
			</nav>
	
			{{-- <nav class="nav-item">
				<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales-return')}}" class="nav-link" title="Sales Return Management">
					Sales Return
				</a>
				<div class="nav-icon-group">
					<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales-return/create')}}">{{HTML::image('img/admin/icon_add.png', '', array('class'=>'nav-icon', 'title'=>'Add New Sales Return'))}}</a>
				</div>
			</nav> --}}
			
			@if($branch != null)
				<nav class="nav-item">
					<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/payment/index/' . $branch->id)}}" class="nav-link" title="Penerimaan Piutang Management">
						Penerimaan Piutang
					</a>
					<div class="nav-icon-group">
						<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/payment/create')}}">{{HTML::image('img/admin/icon_add.png', '', array('class'=>'nav-icon', 'title'=>'Add New Penerimaan Piutang'))}}</a>
					</div>
				</nav>
			@endif

			{{-- <nav class="nav-item">
				<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales-due-date')}}" class="nav-link" title="Overdue Receivable Management">
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
						<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/second-product')}}" class="nav-link" title="Accu Mati Items Management">
							{{HTML::image('img/admin/sub_navigation.png', '', array('class'=>'sub-nav'))}} 
							Accu Mati Items
						</a>
						<div class="nav-icon-group">
							<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/second-product/create')}}">{{HTML::image('img/admin/icon_add.png', '', array('class'=>'nav-icon', 'title'=>'Add New Accu Mati Items'))}}</a>
						</div>
					</nav>

					@if($branch != null)
						<nav class="nav-item">
							<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/import-second-product/index/' . $branch->id)}}" class="nav-link" title="Accu Mati Purchase Management">
								{{HTML::image('img/admin/sub_navigation.png', '', array('class'=>'sub-nav'))}} 
								Accu Mati Purchase
							</a>
							<div class="nav-icon-group">
								<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/import-second-product/create')}}">{{HTML::image('img/admin/icon_add.png', '', array('class'=>'nav-icon', 'title'=>'Add New Accu Mati Purchase'))}}</a>
							</div>
						</nav>
					@endif

					<?php $branch = Branch::first(); ?>
					@if($branch != null)
						<nav class="nav-item">
							<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/inventory-recycle/index/' . $branch->id)}}" class="nav-link" title="Inventory Accu Mati Management">
							{{HTML::image('img/admin/sub_navigation.png', '', array('class'=>'sub-nav'))}} 
								Inventory Item Accu Mati
							</a>
						</nav>
					@endif
				</nav>
			</nav>
			
			<nav class="nav-master">
				<nav class="nav-sub toggle">
					Klaim
					<div class="dropdown1"></div>
				</nav>
				<nav class="sub">
					@if($branch != null)
						<nav class="nav-item">
							<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/product-repair/index/' . $branch->id)}}" class="nav-link" title="Klaim Items Management">
								{{HTML::image('img/admin/sub_navigation.png', '', array('class'=>'sub-nav'))}} 
								Klaim Items
							</a>
							<div class="nav-icon-group">
								<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/product-repair/create')}}">{{HTML::image('img/admin/icon_add.png', '', array('class'=>'nav-icon', 'title'=>'Add New Klaim Items'))}}</a>
							</div>
						</nav>
					@endif
					
					<?php $branch = Branch::first(); ?>
					@if($branch != null)
						<nav class="nav-item">
							<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/inventory-repair/index/' . $branch->id)}}" class="nav-link" title="Inventory Klaim Management">
							{{HTML::image('img/admin/sub_navigation.png', '', array('class'=>'sub-nav'))}} 
								Inventory Item Klaim
							</a>
						</nav>
					@endif
				</nav>
			</nav>

			@if($branch != null)
				<nav class="nav-item">
					<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/account-receivable/index/' . $branch->id)}}" class="nav-link" title="Laporan Piutang Management">
						Laporan Piutang
					</a>
				</nav>
			@endif

			<nav class="nav-item">
				<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/laporan-penerimaan-piutang')}}" class="nav-link" title="Laporan Penerimaan Piutang Management">
					Laporan Penerimaan Piutang
				</a>
			</nav>

			<nav class="nav-item">
				<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales-report')}}" class="nav-link" title="Laporan Penjualan Management">
					Laporan Penjualan
				</a>
				<div class="nav-icon-group">
					<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales-report/create')}}">{{HTML::image('img/admin/icon_add.png', '', array('class'=>'nav-icon', 'title'=>'Add New Laporan Penjualan'))}}</a>
				</div>
			</nav>

			<nav class="nav-item">
				<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/laporan-accu-mati')}}" class="nav-link" title="Laporan Penjualan Accu Mati Management">
					Laporan Penjualan Accu Mati
				</a>
				<div class="nav-icon-group">
					<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/laporan-accu-mati/create')}}">{{HTML::image('img/admin/icon_add.png', '', array('class'=>'nav-icon', 'title'=>'Add New Laporan Penjualan Accu Mati'))}}</a>
				</div>
			</nav>

			<nav class="nav-item">
				<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/stock-cart')}}" class="nav-link" title="Laporan Kartu Stock Management">
					Laporan Kartu Stock
				</a>
			</nav>

			<nav class="nav-item">
				<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/commission-report')}}" class="nav-link" title="Laporan Komisi Sales Management">
					Laporan Komisi Sales
				</a>
			</nav>
		</div>
	</div>
	<div class="separator"></div>
</section>
<section class="nav-section">
	<div class="nav-title">
		Master
	</div>
	<div class="nav-group">
		<nav class="nav-master">
			<nav class="nav-sub toggle">
				Admin
				<div class="dropdown1"></div>
			</nav>
			<nav class="sub">
				<nav class="nav-master">
					<nav class="nav-sub-link">
						<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/admingroup')}}" class="nav-link" title="User Group Management">
							{{HTML::image('img/admin/sub_navigation.png', '', array('class'=>'sub-nav'))}} 
							Admin Group
						</a>
						<div class="nav-icon-group">
							<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/admingroup/create')}}">{{HTML::image('img/admin/icon_add.png', '', array('class'=>'nav-icon', 'title'=>'Add New Admin Group'))}}</a>
						</div>
					</nav>
				</nav>
				<nav class="nav-master">
					<nav class="nav-sub-link">
						<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/admin')}}" class="nav-link" title="Admin Management">
							{{HTML::image('img/admin/sub_navigation.png', '', array('class'=>'sub-nav'))}} 
							Admin Management
						</a>
						<div class="nav-icon-group">
							<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/admin/create')}}">{{HTML::image('img/admin/icon_add.png', '', array('class'=>'nav-icon', 'title'=>'Add New Admin'))}}</a>
						</div>
					</nav>
				</nav>
				<nav class="nav-master">
					<nav class="nav-sub-link">
						<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/admin/edit-profile')}}" class="nav-link" title="Edit Profile">
							{{HTML::image('img/admin/sub_navigation.png', '', array('class'=>'sub-nav'))}} 
							Edit Profile
						</a>
					</nav>
				</nav>
			</nav>
		</nav>
		<div class="nav-item-group">
			<nav class="nav-item">
				<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/setting/edit')}}" class="nav-link" title="Edit Setting">
					Setting
				</a>
			</nav>
		</div>
	</div>
	<div class="separator"></div>
</section>
<section class="nav-section no-margin-bottom">
	<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/logout')}}"><div class="nav-title nav-title-link" title="Log Out">
		Log Out
	</div></a>
</section>