<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Laporan Kartu Stock</title>

	<style type="text/css">
		html{
			margin: 0;
			padding: 0;
		}

		body {
			width: 210mm;
			min-height: 297mm;
			border: solid 1px #000;
			border-bottom: none;
			padding: 8mm;
			margin: 0;
		}

		h1 {
			text-align: center;
			font-size: 26px;
		}

		.report-desc {
			font-size: 14px;
			line-height: 20px;
		}

		.report-desc span:first-child{
			display: inline-block;
			width: 90px;
		}

		table {
			width: 100%;
			font-size: 14px;
			margin-top: 5px;
		    border-right: solid 1px #000;
		    border-bottom: solid 1px #000;
		    margin-bottom: 20px;
		}

		table tr td {
			padding: 5px;
			border-left: solid 1px #000;
			border-top: solid 1px #000;
		}

		table tr.item-product {
			font-weight: bold;
		}

		table tr.item-title {
			background: rgba(0,0,0,0.1);
		}

		.button-print {
		    width: 46px;
		    height: 46px;
		    font-size: 14px;
		    border: 0;
		    background: #d71d21;
		    color: #fff;
		    border-radius: 23px;
		    position: fixed;
		    top: 1cm;
		    left: 23cm;
		    cursor: pointer;
		    opacity: 0.7;
		}

		.button-print.excel {
		    top: 2.5cm;
		}

		.button-print:hover {
		    opacity: 1;
		}
	</style>
</head>
<body>
	<header>
		<div style="text-align: center;">
			{{-- {{HTML::image('img/logo.png', '', array('id'=>'header-img', 'style'=>'width: 130px;'))}} --}}
			<h1 style="display: inline-block;font-size: 22px;vertical-align: middle;margin: 0;margin-top: -13px;margin-left: 20px;font-family: monospace; text-transform: uppercase">
				{{$setting->name . ' - ' . $branch->name}}
			</h1>
		</div>
		<h3 style="margin: 0;font-size: 13px;font-weight: 100;margin-top: 6px;text-align: center;border-bottom: solid 1px #000;padding-bottom: 10px;">
			{{$branch->address}}, {{'Phone: ' . $branch->phone . ', Email: ' . $branch->email}}
		</h3>
	</header>
	<div class='content'>
		<h1>Laporan Kartu Stock</h1>

		<div class="report-desc">
			<span>Tanggal</span>
			<span>: {{date('d/m/Y', strtotime($start_date)) . ' - ' . date('d/m/Y', strtotime($end_date))}}</span>
		</div>
		@foreach($products as $product)
			<div class="report-desc">
				<span>Nama Produk</span>
				<span>: {{$product->name}}</span>
			</div>
			<table border="0" style="border-spacing: 0;">
				<?php
					$inventorys = Inventorygood::where('branch_id', '=', $branch->id)->where('product_id', '=', $product->id)->whereBetween('date', array($start_date, $end_date))->orderBy('date', 'asc')->orderBy('id', 'asc')->get();

					$inventory_first = Inventorygood::where('branch_id', '=', $branch->id)->where('product_id', '=', $product->id)->where('date', '<', $start_date)->orderBy('date', 'desc')->orderBy('id', 'desc')->first();
					$total = 0;
				?>
				<tr class="item-title">
					<td>#</td>
					<td>Tgl.</td>
					{{-- <td>No. Invoice</td> --}}
					<td>Transaksi</td>
					<td>Stock Awal</td>
					<td>Masuk/Keluar</td>
					<td>Stock Akhir</td>
					<td>Catatan</td>
				</tr>
				@if($inventory_first != null)
					<tr class="item">
						<td>1</td>
						<td>{{date('d/m/Y', strtotime($start_date))}}</td>
						{{-- <td>No. Invoice</td> --}}
						<td>Posisi stock akhir</td>
						<td>{{$inventory_first->final_stock}}</td>
						<td>0</td>
						<td>{{$inventory_first->final_stock}}</td>
						<td>-</td>
					</tr>

					<?php $no = 2 ;?>
				@else
					<?php $no = 1 ;?>
				@endif
				@if(count($inventorys) != 0)
					@foreach($inventorys as $inventory)
						<tr class="item">
							<td>{{$no++}}</td>
							<td>{{date('d/m/Y', strtotime($inventory->date))}}</td>
							<td>
								@if($inventory->status == 'Sale')
									<?php $saledetail = Salesdetail::find($inventory->trans_id);  ?>
									@if($saledetail != null)
										<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales/view/' . $saledetail->sale->id)}}" target="_blank">
										Penjualan ({{$saledetail->sale->no_invoice}})
										</a>
									@else
										Penjualan
									@endif
								@elseif($inventory->status == 'Cancel')
									<?php $saledetail = Salesdetail::find($inventory->trans_id);  ?>
									@if($saledetail != null)
										<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/sales/view/' . $saledetail->sale->id)}}" target="_blank">
											Pembatalan Penjualan ({{$saledetail->sale->no_invoice}})
										</a>
									@else
										Pembatalan Penjualan
									@endif
								@elseif($inventory->status == 'Pembelian')
									<?php $purchasedetail = Purchasedetail::find($inventory->trans_id);  ?>
									<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/pembelian/view/' . $purchasedetail->purchase->id)}}" target="_blank">
									{{$inventory->status}} ({{$purchasedetail->purchase->no_invoice}})
									</a>
								@elseif(($inventory->status == 'Stock In') OR ($inventory->status == 'Stock Out'))
									<?php $stockgooddetail = Stockgooddetail::find($inventory->trans_id);  ?>
									<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/inventory-update/view/' . $stockgooddetail->stockgood->id)}}" target="_blank">
										{{$inventory->status}}
									</a>
								@else
									{{$inventory->status}}
								@endif
							</td>
							<td>{{digitGroup($inventory->last_stock)}}</td>
							<td>
								@if(($inventory->status == "Stock In") OR ($inventory->status == "Sale Return") OR ($inventory->status == "Cancel") OR ($inventory->status == "Pembelian"))
									+
								@else
									-
								@endif
								{{digitGroup($inventory->amount)}}
							</td>
							<td>{{digitGroup($inventory->final_stock)}}</td>
							<td>{{$inventory->note}}</td>
						</tr>
					@endforeach
				@endif
			</table>
		@endforeach
		@if($type == 'specific')
			<a target="_blank" href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/stock-cart/pdf/' . $branch->id . '/' . $start_date . '/' . $end_date . '/' . $product->id . '/' . $type)}}">
				{{Form::button('Print', array('class'=>'button-print print'))}}
			</a>
			<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/stock-cart/excel/' . $branch->id . '/' . $start_date . '/' . $end_date . '/' . $product->id . '/' . $type)}}">
				{{Form::button('Excel', array('class'=>'button-print excel'))}}</a>
		@elseif($type == 'all')
			<a target="_blank" href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/stock-cart/pdf/' . $branch->id . '/' . $start_date . '/' . $end_date . '/0/' . $type)}}">
				{{Form::button('Print', array('class'=>'button-print print'))}}
			</a>
			<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/stock-cart/excel/' . $branch->id . '/' . $start_date . '/' . $end_date . '/0/' . $type)}}">
				{{Form::button('Excel', array('class'=>'button-print excel'))}}</a>
		@else
			<a target="_blank" href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/stock-cart/pdf/' . $branch->id . '/' . $start_date . '/' . $end_date . '/' . $product_name . '/' . $type)}}">
				{{Form::button('Print', array('class'=>'button-print print'))}}
			</a>
			<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/stock-cart/excel/' . $branch->id . '/' . $start_date . '/' . $end_date . '/' . $product_name . '/' . $type)}}">
				{{Form::button('Excel', array('class'=>'button-print excel'))}}</a>
		@endif
	</div>
</body>
</html>