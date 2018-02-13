<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Laporan Penjualan</title>

	<style type="text/css">
		html{
			margin: 0;
			padding: 0;
		}

		body {
			width: 194mm;
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
			width: 60px;
		}

		table {
			width: 100%;
			font-size: 14px;
			margin-top: 10px;
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
			/*background: rgba(0,0,0,0.2);*/
			font-weight: bold;
			line-height: 18px;
			font-size: 16px;
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

		.summary {
		    margin-top: 25px;
		    margin-bottom: 20px;
		    width: 320px;
		    border: solid 1px #000;
		    padding: 10px;
		    font-size: 18px;
		    line-height: 24px;
		    position: relative;
		}

		.summary strong {
		    background: #fff;
		    font-size: 20px;
		    margin-bottom: 7px;
		    display: block;
		}
		.summary div span:first-child {
		    width: 115px;
		    display: inline-block;
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
		<h1>LAPORAN PENJUALAN</h1>
		<div class="report-desc">
			<span>Group by</span>
			<span>: Customer</span>
		</div>
		<div class="report-desc">
			<span>Date</span>
			<span>: {{date('d/m/Y', strtotime($start_date)) . ' - ' . date('d/m/Y', strtotime($end_date))}}</span>
		</div>
		<table border="0" style="border-spacing: 0;">
			<?php
				$product_terjual = 0;
				$total_penjualan = 0;
			?>
			@foreach($customers as $customer)
				<?php
					$sales = Sale::where('customer_id', '=', $customer->id)->whereBetween('date', array($start_date, $end_date))->where('status', '!=', 'Canceled')->orderBy('id', 'asc')->get();
					$total_qty = 0;
					$total = 0;
				?>
				@if(count($sales) != 0)
					<tr class="item-title">
						<td colspan="5" style="border-right: solid 1px #000;">
							{{$customer->name}}<br>
							<span style="font-size: 13px;">{{$customer->address}}, {{$customer->no_telp}}</span>
						</td>
					</tr>
					<tr class="item-product">
						<td>#</td>
						<td>No. Nota</td>
						<td>Tgl.</td>
						<td>Total (item)</td>
						<td style="border-right: solid 1px #000;">Total Pembayaran</td>
					</tr>
					<?php $no = 1 ;?>
					@foreach($sales as $sale)
						<?php
							$qty = 0;
							$saledetails = Salesdetail::where('sale_id', '=', $sale->id)->where('type', '=', 'Sales')->get();
							foreach ($saledetails as $saledetail) 
							{
								$qty = $qty + $saledetail->qty;
							}
							$total_qty = $total_qty + $qty; 
							$total = $total + $sale->paid; 
						?>
						<tr class="item">
							<td>{{$no++}}</td>
							<td>{{$sale->no_invoice}}</td>
							<td>{{date('d/m/Y', strtotime($sale->date))}}</td>
							<td>{{number_format((float)$qty, 0,",",".")}}{{number_format((float)$qty, 0,",",".")}}</td>
							<td style="border-right: solid 1px #000;">Rp. {{digitGroup($sale->paid)}}</td>
						</tr>
					@endforeach
					<tr class="item-product">
						<td colspan="3" style="text-align: right; border-bottom: solid 1px #000;">Total</td>
						<td style="border-bottom: solid 1px #000;">{{number_format((float)$total_qty, 0,",",".")}}</td>
						<td style="border-right: solid 1px #000; border-bottom: solid 1px #000;">Rp. {{digitGroup($total)}}</td>
					</tr>
					<?php
						$product_terjual = $product_terjual + $total_qty;
						$total_penjualan = $total_penjualan + $total;
					?>
				@endif
			@endforeach
		</table>

		<div class="summary">
			<strong>Summary</strong>
			<div>
				<span>Produk Terjual</span>
				<span>: {{digitGroup($product_terjual)}} pcs</span>
			</div>
			<div>
				<span>Total Penjualan</span>
				<span>: Rp. {{digitGroup($total_penjualan)}}</span>
			</div>
		</div>
	</div>
</body>
</html>