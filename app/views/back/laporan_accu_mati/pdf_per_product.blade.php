<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>LAPORAN PENJUALAN ACCU MATI</title>

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
			font-weight: bold;
			/*background: rgba(0,0,0,0.1);*/
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
		    padding: 10px 15px;
		    font-size: 18px;
		    line-height: 24px;
		    position: relative;
		}

		.summary strong {
		    background: #fff;
		    left: 0;
		    font-size: 20px;
		    margin-bottom: 7px;
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
		<h1>LAPORAN PENJUALAN ACCU MATI</h1>
		<div class="report-desc">
			<span>Group by</span>
			<span>: Per Produk</span>
		</div>
		<div class="report-desc">
			<span>Tanggal</span>
			<span>: {{date('d/m/Y', strtotime($start_date)) . ' - ' . date('d/m/Y', strtotime($end_date))}}</span>
		</div>
		<table border="0" style="border-spacing: 0;">
			<?php
				$product_terjual = 0;
				$total_penjualan = 0;
			?>
			@foreach($products as $product)
				<?php
					$sales = Sale::where('branch_id', '=', $branch->id)->whereBetween('date', array($start_date, $end_date))->where('status', '!=', 'Canceled')->orderBy('id', 'asc')->get();
					$total = 0;
					$price_total = 0;
				?>
				@if(count($sales) != 0)
					<tr class="item-product">
						<td colspan="7" style="border-right: solid 1px #000;">{{$product->name}}</td>
					</tr>
					<tr class="item-title">
						<td>#</td>
						<td>No. Nota</td>
						<td>Tgl</td>
						<td>Customer</td>
						<td>Harga</td>
						<td style="text-align: center;">Qty</td>
						<td style="border-right: solid 1px #000;">Total</td>
					</tr>
					<?php $no = 1 ;?>
					@foreach($sales as $sale)
						<?php
							$saledetails = Salesdetail::where('sale_id', '=', $sale->id)->where('product_id', '=', $product->id)->get();
						?>
						@foreach($saledetails as $saledetail)
							@if($saledetail->type == 'Sales')
								<tr class="item">
									<td>{{$no++}}</td>
									<td>{{$saledetail->sale->no_invoice}}</td>
									<td>{{date('d/m/Y', strtotime($saledetail->sale->date))}}</td>
									<td>{{$saledetail->sale->customer->name}}</td>
									<td>Rp. {{digitGroup($saledetail->price)}}</td>
									<td style="text-align: center;">{{$saledetail->qty}}</td>
									<td style="border-right: solid 1px #000;">Rp. {{digitGroup($saledetail->subtotal)}}</td>
									<?php 
										$total = $total + $saledetail->qty; 
										$price_total = $price_total + $saledetail->subtotal; 
									?>
								</tr>
							@endif
						@endforeach
					@endforeach
					<tr class="item-product">
						<td colspan="5" style="text-align: right; border-bottom: solid 1px #000;">Total Semua Penjualan</td>
						<td style="text-align: center; border-bottom: solid 1px #000;">{{digitGroup($total)}}</td>
						<td style="border-right: solid 1px #000; border-bottom: solid 1px #000;">Rp. {{digitGroup($price_total)}}</td>
					</tr>
					<?php 
						$product_terjual = $product_terjual + $total;
						$total_penjualan = $total_penjualan + $price_total; 
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